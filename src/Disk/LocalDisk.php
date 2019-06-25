<?php

namespace Huoshaotuzi\Uploader\Disk;

class LocalDisk extends DiskAbstract
{
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
        if (empty($fileName)) {
            $fileName = uniqid();
        }

        $extensionName = substr($file['name'], strrpos($file['name'], '.'));
        $document = $path . date('/Y/m/d/');
        $fullName = $document . $fileName . $extensionName;

        if (!$overwrite) {
            if (file_exists($fullName)) {
                throw new \Exception($fullName . ' is exists!');
            }
        }

        if(!file_exists($document)) {
            $this->createDocument($document);
        }

        move_uploaded_file($file['tmp_name'], $fullName);

        return $fullName;
    }

    /**
     * 删除指定文件
     * @param $filePath
     */
    public function delete($filePath)
    {
        if(file_exists($filePath)) {
            unlink($filePath);
        }
    }
}