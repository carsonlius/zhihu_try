<?php


namespace App\Http\Repositories;


use App\Http\TraitHelper\CustomException;
use App\Periodical;

class PeriodicalRepository
{
    /**
     * 更新期刊
     * @throws CustomException
     * @return array
     */
    public function update(): array
    {
        // 校验参数
        $this->_validateParamsForUpdate();

        // 更新
        return $this->_updateDo();
    }

    /**
     * 更新
     * @return array
     * @throws CustomException
     */
    private function _updateDo(): array
    {
        list($where, $params) = $this->_genParamsForUpdate();
        Periodical::where($where)
            ->update($params);

        $periodical = Periodical::getOneItem($where);
        return $periodical ? $periodical->toArray() : [];
    }

    /**
     * 生成参数
     * @return array
     * @throws CustomException
     */
    private function _genParamsForUpdate(): array
    {
        $params = $this->_genParamsForCreateAndUpdate();

//        $params['']

        return [
            ['id' => request()->post('id')],
            $this->_genParamsForCreateAndUpdate()
        ];
    }


    /**
     * 校验参数
     * @throws CustomException
     */
    private function _validateParamsForUpdate()
    {
        $type = request()->post('type');
        !request()->post('id') && $this->_errorShow('请传递期刊ID');

        !$type && $this->_errorShow('请选择期刊类型');
        !request()->post('des') && $this->_errorShow('请输入描述');
        !request()->post('title') && $this->_errorShow('请输入标题');
        !request()->post('img') && $this->_errorShow('请上传封面');
        !request()->post('published') && $this->_errorShow('请选择发布的状态');
        $type == 'text' && !request()->post('content') && $this->_errorShow('请输入内容');
        $type != 'text' && !request()->post('name') && $this->_errorShow('请输入名字');
        $type != 'movie' && !request()->post('author') && $this->_errorShow('请输入作者');
        $type == 'movie' && !request()->post('actor') && $this->_errorShow('请输入演员');
    }


    /**
     * 期刊列表
     * @return array
     */
    public function lists(): array
    {
        // 生成参数
        $where = $this->_genParamsForLists();

        // 获取列表
        return $this->_getLists($where);
    }

    /**
     * 获取列表
     * @param array $where
     * @return array
     */
    private function _getLists(array $where): array
    {
        $list = Periodical::getItems($where, [], ['id', 'desc']);
        return $list ? $list->all() : [];
    }

    /**
     * 生成参数
     * @return array
     */
    private function _genParamsForLists(): array
    {
        list($periodical_index, $month, $published, $type) = [
            request()->get('periodical_index'),
            request()->get('month'),
            request()->get('published'),
            request()->get('type'),
        ];

        $where = compact('periodical_index', 'month', 'published', 'type');
        return array_filter($where, function ($item) {
            return $item;
        });
    }

    /**
     * 校验参数
     * @throws CustomException
     */
    public function createDo(): array
    {
        // 校验参数
        $this->_validateParamsForCreate();

        // 生成期刊
        return $this->_createPeriodical();
    }

    /**
     * 生成期刊
     * @return array
     * @throws CustomException
     */
    private function _createPeriodical(): array
    {
        // 参数
        $params = $this->_genParamsForCreateAndUpdate();

        // do
        return Periodical::create($params)->toArray();
    }

    /**
     * 参数
     * @return array
     * @throws CustomException
     */
    private function _genParamsForCreateAndUpdate(): array
    {
        list($type, $des, $title, $img, $published,
            $content, $author, $actor, $name, $month, $periodical_index) = [
            request()->post('type'),
            request()->post('des'),
            request()->post('title'),
            request()->post('img'),
            (string)request()->post('published'),
            request()->post('content'), // text内容
            request()->post('author'), // text music 作者
            request()->post('actor'), // movie 演员
            request()->post('name'), // movie music 名字
            date('Ym'),
            (int)request()->post('periodical_index'),
        ];

        // 如果当前的期刊是已经发布的状态  && 而且期数是null  则自动填充期刊
        $periodical_index = $this->_getPeriodicalIndex($published, $periodical_index);

        switch ($type) {
            case 'text' :
                $data = json_encode(compact('content', 'author'), JSON_UNESCAPED_UNICODE);
                break;
            case 'movie' :
                $data = json_encode(compact('actor', 'name'), JSON_UNESCAPED_UNICODE);
                break;
            case 'music' :
                $data = json_encode(compact('author', 'name'), JSON_UNESCAPED_UNICODE);
                break;
            default:
                throw new CustomException('传递的type异常');
        }

        return compact('month', 'type', 'des', 'title', 'img', 'published',
            'periodical_index','data');
    }

    /**
     * 生成期刊数
     * @param int $published
     * @param int $periodical_index
     * @return null
     */
    private function _getPeriodicalIndex(int $published, int $periodical_index)
    {
        // 如果是未发布的状态
        if ($published != 2) {
            return null;
        }

        // 如果此时已经有了期刊数
        if ($periodical_index) {
            return $periodical_index;
        }

        //  如果此时还没有期刊数
        $periodical = Periodical::getOneItem(compact('published'), ['periodical_index'], ['periodical_index', 'desc']);
        return $periodical ? $periodical->periodical_index + 1  : 1;
    }

    /**
     * 校验参数
     * @throws CustomException
     */
    private function _validateParamsForCreate()
    {
        list($type) = [
            request()->post('type'),
        ];

        !$type && $this->_errorShow('请选择期刊类型');
        !request()->post('des') && $this->_errorShow('请输入描述');
        !request()->post('title') && $this->_errorShow('请输入标题');
        !request()->post('img') && $this->_errorShow('请上传封面');
        !request()->post('published') && $this->_errorShow('请选择发布的状态');
        $type == 'text' && !request()->post('content') && $this->_errorShow('请输入内容');
        $type != 'text' && !request()->post('name') && $this->_errorShow('请输入名字');
        $type != 'movie' && !request()->post('author') && $this->_errorShow('请输入作者');
        $type == 'movie' && !request()->post('actor') && $this->_errorShow('请输入演员');
    }

    /**
     * 异常处理
     * @param string $msg
     * @throws CustomException
     */
    private function _errorShow(string $msg)
    {
        throw new CustomException($msg);
    }

}