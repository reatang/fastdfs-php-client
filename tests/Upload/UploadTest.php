<?php

namespace FastDFS\Upload;

use FastDFS\TestCase;

use FastDFS\FileSystem;
use FastDFS\Connection;
class UploadTest extends TestCase
{
    public function testPut()
    {
        $system = new FileSystem;

        $file_info = $system->put(__DIR__ . '/test.txt');

        if ($file_info == false) {
            echo $system->errorNo() . PHP_EOL;
            echo $system->errorMsg() . PHP_EOL;
        }
        var_dump($file_info);
    }
}