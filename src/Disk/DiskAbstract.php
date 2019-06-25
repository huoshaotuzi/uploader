<?php

namespace Huoshaotuzi\Uploader\Disk;


abstract class DiskAbstract
{
    abstract public function save($file, $path, $fileName, $overwrite = false);

    abstract public function delete($filePath);

    /**
     * 文件夹不存在则创建
     * @param $fullDocument
     */
    protected function createDocument($fullDocument)
    {
        $fullDocument = str_replace('//', '/', $fullDocument);
        $documents = explode('/', $fullDocument);

        $current = '.';
        foreach ($documents as $item) {
            $current .= '/' . $item;
            echo $current . PHP_EOL;
            if(!file_exists($current)) {
                mkdir($current, 0766);
            }
        }
    }
}