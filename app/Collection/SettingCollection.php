<?php

namespace App\Collection;

use App\User;
use Illuminate\Support\Collection;

class SettingCollection extends Collection
{
    protected $user;

    /*
     * 允许更新的设置字段
     * */
    protected $allowed_attributes = ['bio', 'area'];

    /**
     * SettingCollection constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * 更新用户设置
     * @param array $attributes
     * @return bool
     */
    public function update($attributes)
    {
        $settings = array_merge($this->user->settings, array_only($attributes, $this->allowed_attributes));
        return $this->user->update(compact('settings'));
    }
}