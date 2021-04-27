<?php

/**
 * 利用PHP访问websocket
 */

namespace Lop\WebSocket\Sync\Request;

class Client extends Base
{
    protected $socket_uri;
    protected $message = null;
    protected $pong = null;
    protected $disconnected = null;
    protected $options = null;
    private $serverUrl = null;
    /**
     * @param array $options
     *   Associative array containing:
     *   - context:
     *   - timeout:      Default: 5
     *   - headers:
     */
    public function __construct($options = array())
    {
        $this->options = $options;
        if (!array_key_exists('timeout', $this->options)) $this->options['timeout'] = 5;

        // the fragment size
        if (!array_key_exists('fragment_size', $this->options)) $this->options['fragment_size'] = 4096;
    }

    public function __destruct()
    {
        if ($this->socket) {
            if (get_resource_type($this->socket) === 'stream') fclose($this->socket);
            $this->socket = null;
        }
    }

    /**
     * @param string $uri A ws/wss-URI
     * 握手
     */
    public function connect($uri)
    {
        $this->socket_uri = $uri;
        //echo "uri: " . $uri . "\n";
        $url_parts = parse_url($this->socket_uri);
        $scheme = $url_parts['scheme'];
        $host = $url_parts['host'];
        $user = isset($url_parts['user']) ? $url_parts['user'] : '';
        $pass = isset($url_parts['pass']) ? $url_parts['pass'] : '';
        $default_port = isset($url_parts['port']) ? false : true;
        $port = isset($url_parts['port']) ? $url_parts['port'] : ($scheme === 'wss' ? 443 : 80);
        $path = isset($url_parts['path']) ? $url_parts['path'] : '/';
        $query = isset($url_parts['query']) ? $url_parts['query'] : '';
        $fragment = isset($url_parts['fragment']) ? $url_parts['fragment'] : '';

        $path_with_query = $path;
        if (!empty($query)) $path_with_query .= '?' . $query;
        if (!empty($fragment)) $path_with_query .= '#' . $fragment;
        if (!in_array($scheme, array('ws', 'wss'))) {
            throw new BadUriException(
                "Url should have scheme ws or wss, not '$scheme' from URI '$this->socket_uri' ."
            );
        }

        $host_uri = ($scheme === 'wss' ? 'ssl' : 'tcp') . '://' . $host;

        // Set the stream context options if they're already set in the config
        if (isset($this->options['context'])) {
            // Suppress the error since we'll catch it below
            if (@get_resource_type($this->options['context']) === 'stream-context') {
                $context = $this->options['context'];
            } else {
                throw new \InvalidArgumentException(
                    "Stream context in \$options['context'] isn't a valid context"
                );
            }
        } else {
            $context = stream_context_create();
        }

        // Open the socket.  @ is there to supress warning that we will catch in check below instead.
        $this->socket = @stream_socket_client(
            $host_uri . ':' . $port,
            $errno,
            $errstr,
            $this->options['timeout'],
            STREAM_CLIENT_CONNECT,
            $context
        );

        if ($this->socket === false) {
            throw new ConnectionException(
                "Could not open socket to \"$host:$port\": $errstr ($errno)."
            );
        }

        // Set timeout on the stream as well.
        stream_set_timeout($this->socket, $this->options['timeout']);

        // Generate the WebSocket key.
        $key = self::generateKey();

        // Default headers (using lowercase for simpler array_merge below).
        if ($default_port == true) {
            $host_header = $host;
        } else {
            $host_header = $host . ":" . $port;
        }
        $headers = array(
            'host' => $host_header,
            'user-agent' => 'websocket-client-php',
            'connection' => 'Upgrade',
            'upgrade' => 'websocket',
            'sec-websocket-key' => $key,
            'sec-websocket-version' => '13',
        );

        // Handle basic authentication.
        if ($user || $pass) {
            $headers['authorization'] = 'Basic ' . base64_encode($user . ':' . $pass) . "\r\n";
        }

        // Deprecated way of adding origin (use headers instead).
        if (isset($this->options['origin'])) $headers['origin'] = $this->options['origin'];

        // Add and override with headers from options.
        if (isset($this->options['headers'])) {
            $headers = array_merge($headers, array_change_key_case($this->options['headers']));
        }

        $header =
            "GET " . $path_with_query . " HTTP/1.1\r\n"
            . implode(
                "\r\n", array_map(
                    function ($key, $value) {
                        return "$key: $value";
                    }, array_keys($headers), $headers
                )
            )
            . "\r\n\r\n";

       // echo "request header: ".$header."\n";
        // Send headers.
        $this->write($header);

        // Get server response header (terminated with double CR+LF).
        $response = stream_get_line($this->socket, 1024, "\r\n\r\n");

        /// @todo Handle version switching
        //echo "response: ".$response."\n";
        // Validate response.
        if (!preg_match('#Sec-WebSocket-Accept:\s(.*)$#mUi', $response, $matches)) {
            $address = $scheme . '://' . $host . $path_with_query;
            throw new ConnectionException(
                "Connection to '{$address}' failed: Server sent invalid upgrade response:\n"
                . $response
            );
        }

        $keyAccept = trim($matches[1]);
        $expectedResonse
            = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        if ($keyAccept !== $expectedResonse) {
            throw new ConnectionException('Server sent bad upgrade response.');
        }

        $this->is_connected = true;
        //echo "connected\n";
        $this->send("1","ping");
    }

    /**
     * 生成随机串 for WebSocket key.
     * @return string Random string
     */
    protected static function generateKey()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"$&/()=[]{}0123456789';
        $key = '';
        $chars_length = strlen($chars);
        for ($i = 0; $i < 16; $i++) $key .= $chars[mt_rand(0, $chars_length - 1)];
        return base64_encode($key);
    }

    /**
     * Set a callback to execute when a message arrives.
     *
     * The callable will receive the message string and the server instance.
     * @param  callable $callback the callback
     * @return self
     */
    public function onMessage(callable $callback)
    {
        $this->message = $callback;
        return $this;
    }





    public function onPong(callable $callback)
    {
        $this->pong = $callback;
        return $this;
    }

    public function onDisconnected(callable $callback)
    {
        $this->disconnected = $callback;
        return $this;
    }
    private function get_total_millisecond()
    {
        $time = explode (" ", microtime () );
        $time = $time [1] . ($time [0] * 1000);
        $time2 = explode ( ".", $time );
        $time = $time2 [0];
        return $time;
    }

    public function setServerUrl($serverUrl){
        $this->serverUrl=$serverUrl;
    }
    /**
     * Start listening.
     */
    public function run()
    {
        try{
            while (true) {
                if (!$this->isConnected()) {
                    break;
                }
                $changed = [$this->socket];
                if (@stream_select($changed, $write = null, $except = null, 30) > 0) {

                    $message = $this->receive();
                    if (!empty($message) && $message['type']=='text' && isset($this->message)) {
                        call_user_func($this->message, $message['payload'], $this);
                    }
                    if (!empty($message) && $message['type']=='pong' && isset($this->pong)) {
                        call_user_func($this->pong, $message['payload'], $this);
                    }
                    if (!empty($message) && $message['type']=='close') {
                        $this->connect($this->serverUrl);
                    }
                }else{
                    $this->send($this->get_total_millisecond(),"ping");
                }
                usleep(10000*1000);
            }
        }catch (ConnectionException $conne){
            error_log(  "连接服务端异常:".$conne);
            $this->is_connected=false;
            call_user_func($this->disconnected,"",$this);
        }
    }
}
