<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    /**
     * UserRepository::groupList()
     *
     * @return array
     */
    public function groupList()
    {
        $group = [];
        if (Auth::user()->isSuper()) {
            $group['Nhóm quyền quản lý chính'] = Role::getList(1);
        }
        $group['Nhóm quyền điều hành viên'] = Role::getList(2);
        $group['Nhóm quyền thành viên'] = Role::getList(3);
        return $group;
    }
}
