<?php

namespace Huoshaotuzi\Uploader;


use Huoshaotuzi\Uploader\Disk\LocalDisk;
use Huoshaotuzi\Uploader\Validate\FileValidate;

class Uploader
{
    protected $validate;

    protected $disk;

    public function __construct(FileValidate $validate = null, DiskAbstract $disk = null)
    {
        $this->validate = $validate;
        // 默认使用本地存储
        if($disk) {
            $this->disk = $disk;
        } else {
            $this->disk = new LocalDisk();
        }
    }

    /**
     * 保存文件
     * @param $file
     * @param $path
     * @param string $fileName
     * @param bool $overwrite
     * @return string
     * @throws \Exception
     */
    public function save($file, $path, $fileName = '', $overwrite = false)
    {
        if($this->validate) {
            $this->validate->validate($file);
        }

        $fullName = $this->disk->save($file, $path, $fileName, $overwrite);

        return $fullName;
    }

    /**
     * 删除指定文件
     * @param $filePath
     */
    public function delete($filePath)
    {
        $this->delete($filePath);
    }
}