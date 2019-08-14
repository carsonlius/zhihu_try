<?php

namespace App\Http\Controllers;

use App\BookClosureClassification;
use App\BookClosureRelationship;
use App\PathTree;

class TestController extends Controller
{
    /**
     * 闭包表
     * 优点  物理路径的进化版
     * 缺点  空间换性能
     */
    public function closureTree()
    {
        // 北京下面的所有的后代节点
        $list_chirdren = $this->_getSonClassification('北京');

        // 所有后代节点的子节点
        $list_descendant_ids = collect($list_chirdren)->pluck('descendant_id')->toArray();
        $list_all_trees = $this->_getOneSonForClassification($list_descendant_ids);

        // 整理
        $list_tidy = $this->_tidyClosureTree($list_all_trees, $list_chirdren, 2);

        return compact('list_all_trees', 'list_chirdren', 'list_tidy');
    }

    /**
     *
     * @param array $list_all_trees
     * @param array $children
     * @param $base_id
     * @return array
     */
    private function _tidyClosureTree(array $list_all_trees, array $children, $base_id): array
    {
        $list_all_trees = array_column($list_all_trees, null, 'descendant_id');
        $children = array_column($children, null, 'descendant_id');

        // 过滤
        $list_container = [];
        foreach ($list_all_trees as $descendant_id => $item) {
            $ancestor_id = $item['ancestor_id'];
            $list_all_trees[$descendant_id]['name'] = $children[$descendant_id]['name'] ?? 'none';

            if ($ancestor_id == $base_id) {
                $list_container = &$list_all_trees[$descendant_id];
                continue;
            }

            $list_all_trees[$ancestor_id]['sons'][] = &$list_all_trees[$descendant_id];
        }

        return $list_container;
    }

    /**
     * 获取后台的相关信息
     * @param array $list_descendant_ids
     * @return array
     */
    private function _getOneSonForClassification(array $list_descendant_ids): array
    {
        return BookClosureRelationship::whereIn('ancestor_id', $list_descendant_ids)
            ->where('distance', 1)
            ->select('ancestor_id', 'descendant_id', 'distance')
            ->get()
            ->toArray();
    }

    /**
     * 获取后代分类
     * @param string $name
     * @return array
     */
    private function _getSonClassification(string $name): array
    {
        $sql = <<<EOF
SELECT
	classification_2.name, classification_2.id as descendant_id, relationship_1.distance
FROM
	book_closure_classification classification
INNER JOIN book_closure_relationship relationship_1 ON classification.id = relationship_1.ancestor_id
INNER JOIN book_closure_classification classification_2 ON classification_2.id = relationship_1.descendant_id
WHERE
	classification.name = "$name"
EOF;
        $list = \DB::select($sql);
        return array_map(function ($item) {
            return (array)$item;
        }, $list);
    }

    /**
     * 书本分类
     * @param string $name
     * @return BookClosureClassification
     */
    private function _getBookClassification(string $name): BookClosureClassification
    {
        return BookClosureClassification::getOneItem(compact('name'));
    }

    /**
     * 物化路径模式
     * 缺点 占用的空间比较大, mysql path字段可能被填满， 并非是真的无限分类, && 确保路径是唯一的
     * 优点 空间换性能
     * 总结:
     *    适用于生物科目分类 行政区划(他们的层级是确定的)
     *    可以添加level
     *
     * 操作:
     *     更新 需要更新本节点 && 子节点 （列如修改了某个节点的路径的时候，除了修改本身之外还要修改所有的子产品）
     *     删除  删除一个节点 理论上也应该删除所有的子节点
     *     查询一个节点的子节点是很简单, 不需要递归查询  path like "{{ $path}}%"
     *     插入  父节点的路径加上新增ID（获取其他的唯一标识）
     */
    public function pathTree()
    {
        // 中国下面的所有节点
        $node_china = PathTree::getOneItem(['code' => 1], ['path']);
        $sql_sub = 'select * from path_tree where path like "' . $node_china->path . '/%"';
        $list_sub_of_china = \DB::select($sql_sub);

        // 直接属于中国的节点
        $sql_sub_d = 'select * from path_tree where path regexp "' . $node_china->path . '/[0-9]*$"';
        $list_sub_d_of_china = \DB::select($sql_sub_d);

        // 东城区的所有上级
        $node_dongcheng = PathTree::getOneItem(['code' => '110101'], ['path']);
//        $sql_parent = 'select * from path_tree where locate(path, "' . $node_dongcheng->path . '")  and path <> "' . $node_dongcheng->path . '"';
        $sql_parent = 'select * from path_tree where locate(path, "' . $node_dongcheng->path . '")  and path <> "' . $node_dongcheng->path . '"';
        $list_parent_of_dongcheng = \DB::select($sql_parent);

//        return compact('sql_parent', 'list_parent_of_dongcheng');

        return compact('sql_sub', 'sql_sub_d', 'sql_parent');
        // 如果是要获得完整的树状结构
        $list_all = PathTree::getItems([], ['path', 'name'])->toArray();
        return $this->_tidyWholeTreeForPath($list_all);
    }

    /**
     * 如果是要获得完整的树状结构
     * @param $list_sub_of_china
     * @return array
     */
    private function _tidyWholeTreeForPath(array $list_sub_of_china): array
    {
        // 格式转化
        $list_sub_of_china = array_column($list_sub_of_china, null, 'path');
        foreach ($list_sub_of_china as $key => $node_item) {

            list($path_parent, $path) = [
                $this->_getParentPath($node_item['path']),
                $node_item['path']
            ];

            // 如果是最顶级的则不需要操作
            if ($path == '/1') {
                continue;
            }

            $list_sub_of_china[$path_parent]['sons'][] = &$list_sub_of_china[$key];
        }

        // 过滤掉非顶级的路径
        return array_filter($list_sub_of_china, function ($item) {
            return $item['path'] == '/1';
        });
    }

    /**
     * 格式转换
     * @param array $list_sub_of_china
     * @return array
     */
    private function _formatPathArr(array $list_sub_of_china): array
    {
        $list_sub_of_china = array_map(function ($item) {
            return (array)$item;
        }, $list_sub_of_china);

        return array_column($list_sub_of_china, null, 'path');
    }

    /**
     * 格式
     * @param string $path
     * @return string
     */
    private function _getParentPath(string $path): string
    {
        if ($path == '/1') {
            return '';
        }

        $path_arr = explode('/', $path);
        array_pop($path_arr);

        return implode('/', $path_arr);
    }

    /**
     * 邻接列表模式
     * 优点 结构简单,增删改很简单
     * 缺点 读很麻烦 需要不断的递归, 对数据库来说有很大的开销, 只适用于层级比较少的时候,4层以内
     * 开进版 平面结构， 增加level的概念 应用与评论表
     */
    public function common()
    {
        $list_permissions = \Ultraware\Roles\Models\Permission::all()->toArray();
        $list_permissions = array_column($list_permissions, null, 'id');

        $list_container = [];
        foreach ($list_permissions as $key => $permission) {
            $parent_id = $permission['parent_id'];

            // 如果是顶级的话  则不需要寻找父级
            if (!$parent_id) {
                $list_container[] = &$list_permissions[$key];
                continue;
            }

            // 给对应的父生成子级
            $list_permissions[$parent_id]['sons'][] = &$list_permissions[$key];
        }
        return $list_container;


//        // 让各个节点生成树状结构
//        foreach ($list_permissions as $key => $permission) {
//            $parent_id = $permission['parent_id'];
//            // 如果是顶级的话  则不需要寻找父级
//            if (!$parent_id) {
//                continue;
//            }
//            $list_permissions[$parent_id]['sons'][] = &$list_permissions[$key];
//        }
//
//        // 只保留顶级树状结构
//        return array_filter($list_permissions, function ($permission) {
//            return !$permission['parent_id'];
//        });

    }
}
