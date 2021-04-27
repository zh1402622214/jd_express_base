<?php
namespace Lop\Api\Request;

abstract  class DomainFileAbstractRequest extends DomainAbstractRequest
{
    private  $fileNames = array();

    /**
     * @return array
     */
    public function getFileNames()
    {
        return $this->fileNames;
    }

    /**
     * @param array $fileNames
     */
    public function setFileNames($fileNames)
    {
        $this->fileNames = $fileNames;
    }

    /**
     * @param  $fileName
     */
    public function addFileName($fileName)
    {
        array_push($this->fileNames,$fileName);
    }


}