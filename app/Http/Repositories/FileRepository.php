<?php


namespace App\Http\Repositories;

use App\Http\TraitHelper\CustomException;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
    /**
     * 删除单个文件
     * @throws CustomException
     */
    public function deleteSingle()
    {
        // 校验参数
        $this->_validateParamsForDeleteSingle();

        // 删除文件
        $this->_deleteSingleDo();
    }

    /**
     * 删除单个文件
     * @throws CustomException
     */
    private function _deleteSingleDo()
    {
        $file_path = request()->post('file_path');
        $is_deleted = Storage::disk('qiniu')->delete($file_path);
        if (!$is_deleted) {
            throw new CustomException('删除文件失败');
        }
    }

    /**
     * 校验参数
     * @throws CustomException
     */
    private function _validateParamsForDeleteSingle()
    {
        if (!request()->post('file_path')) {
            throw new CustomException('请输入要删除文件得路径');
        }
    }

    /**
     * 上传单个文件
     * @return array
     * @throws CustomException
     */
    public function uploadSingle(): array
    {
        if (!request()->hasFile('file')) {
            throw new CustomException('缺少上传的文件');
        }

        // 文件的名字
        $dir = '/' . (request()->post('dir_name') ?? '');
        $file_name = $dir . md5(time() . user('api')->id) . '.' . request()->file->extension();

        // 存储到七牛云
        $response_upload = Storage::disk('qiniu')->put($file_name, fopen(request()->file, 'r'));
        if ($response_upload === false) {
            throw new CustomException('上传到七牛云失败');
        }

        // 返回新增文件的信息
        $file_qiniu = 'http://' . env('QINIU_DOMAIN') . $file_name;
        return compact('file_qiniu', 'file_name');
    }
}