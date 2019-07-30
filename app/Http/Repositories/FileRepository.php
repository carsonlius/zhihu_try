<?php


namespace App\Http\Repositories;


use App\Http\TraitHelper\CustomException;

class FileRepository
{
    /**
     * 文件上传
     * @return array
     * @throws CustomException
     */
    public function upload(): array
    {

        $url = 'http://pic25.nipic.com/20121117/9252150_165726249000_2.jpg';
        return compact('url');
    }
}