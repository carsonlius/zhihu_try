<?php

namespace App\TransForm;

class PeriodicalTransFormer extends TransFormer
{
    /**
     * 格式化单元
     * @param array $item
     * @return array
     */
    public static function transForm(array $item): array
    {
        $data = json_decode($item['data'], true);
        return [
            'id' => $item['id'] ?? '',
            'periodical_index' => $item['periodical_index'] ?? '',
            'des' => $item['des'], // 描述
            'fav_nums' => $item['fav_nums'] ?? '', // 点赞的数量
            'type' => $item['type'] ?? '', // 类型 text music movie
            'title' => $item['title'],
            'img' => $item['img'], // 封面
            'month' => self::_formatMonth($item['month'] ?? ''), // 创建的月份
            'year' => substr($item['month'], 0, 4),  // 创建的年份
            'author' => $data['author'] ?? '', // 作者  text music
            'content' => $data['content'] ?? '', // 内容 text
            'music_url' => $data['url'] ?? '', // 音乐地址 music
            'name' => $data['name'] ?? '', // 名字 music movie
        ];
    }

    /**
     * 格式化月份
     * @param string $month
     * @return string
     */
    private static function _formatMonth(string $month)
    {
        $month = substr($month, 4, 2);
        $list_mapping_months = [
            '01' => '一月',
            '02' => '二月',
            '03' => '三月',
            '04' => '四月',
            '05' => '五月',
            '06' => '六月',
            '07' => '七月',
            '08' => '八月',
            '09' => '九月',
            '10' => '十月',
            '11' => '十一月',
            '12' => '十二月',
        ];

        return $list_mapping_months[$month] ?? '异常';
    }
}
