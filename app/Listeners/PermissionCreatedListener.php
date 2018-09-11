<?php

namespace App\Listeners;

use App\Events\PermissionCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Ultraware\Roles\Models\Role;

class PermissionCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PermissionCreatedEvent  $event
     * @return void
     */
    public function handle(PermissionCreatedEvent $event)
    {
        // 系统管理员添加上这个权限id
        $slug = 'admin';
        $role_admin = Role::where(compact($slug))->first();
        $role_admin->attachPermission($event->permission);
    }
}
