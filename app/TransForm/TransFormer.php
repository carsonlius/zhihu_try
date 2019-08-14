<?php


namespace App\TransForm;


abstract class TransFormer
{
    public static function transForms(array $items)
    {
        return array_map([new static(), 'transForm'], $items);
    }

    public abstract static function transForm(array $item);
}