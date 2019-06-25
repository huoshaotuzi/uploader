<?php

namespace Huoshaotuzi\Uploader\Validate;


class FileValidate
{
    public $maxSize = [
        'value' => 0,
        'error_message' => 'file size error',
    ];

    public $extensions = [
        'value' => [],
        'error_message' => 'file type error',
    ];

    public $limitRate = [
        'value' => 0,
        'error_message' => 'limit rate error',
    ];

    public function __construct($config = [])
    {
        if(is_array($config)) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * 文件校验
     * @param $file
     * @throws \Exception
     */
    public function validate($file)
    {
        $value = $this->extendsions['value'];
        $message = $this->extendsions['error_message'];
        if(!in_array($file['type'], $value)) {
            throw new \Exception($message);
        }

        $value = $this->maxSize['value'];
        $message = $this->maxSize['error_message'];
        // 0 允许上传任意大小
        if ($value != 0 && $file['size'] > $value) {
            throw new \Exception($message);
        }

        $value = $this->limitRate['value'];
        $message = $this->limitRate['error_message'];
        if(!$this->getCurrentRate($value)) {
            throw new \Exception($message);
        }
    }

    /**
     * 判断频率
     * @param $value
     * @return bool
     */
    protected function getCurrentRate($value)
    {
        if($value == 0) {
            return true;
        }
        return true;
    }
}