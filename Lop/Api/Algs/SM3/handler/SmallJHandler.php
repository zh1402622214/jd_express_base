<?php
/**
 * SmallJHandler @ SM3-PHP
 *
 * Code BY ch4o5
 * 10月. 14日 2019年
 * Powered by PhpStorm
 */

namespace Lop\Api\Algs\SM3\handler;

use Lop\Api\Algs\SM3\libs\WordConversion;

/**
 * 小j处理类
 * Class SmallJHandler
 *
 * @package SM3\handler
 */
class SmallJHandler extends JHandler
{
    /** @var int j的最大可用值 */
    const SMALLEST_J = 0;
    /** @var int j的最小可用值 */
    const BIGGEST_J = 15;
    /** @var string T常量 */
    const T = '79cc4519';
    
    /**
     * 补充父类
     * SmallJHandler constructor.
     */
    public function __construct()
    {
        parent::__construct(self::T, self::SMALLEST_J, self::BIGGEST_J);
    }
    
    /**
     * 布尔函数
     *
     * @param $X  
     * @param $Y  
     * @param $Z  
     *
     * @return 
     */
    public function FF($X, $Y, $Z)
    {
        return self::boolFunction($X, $Y, $Z);
    }
    
    /**
     * 小j值的布尔函数公共方法
     *
     * @param $X 
     * @param $Y 
     * @param $Z 
     *
     * @return 
     */
    private static function boolFunction($X, $Y, $Z)
    {
        return WordConversion::xorConversion(
            array(
                $X,
                $Y,
                $Z
            )
        );
    }
    
    /**
     * 布尔函数
     *
     */
    public function GG($X, $Y, $Z)
    {
        return self::boolFunction($X, $Y, $Z);
    }
}