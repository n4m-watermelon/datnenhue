<?php

namespace App\Repositories\Acl\Eloquent;


use App\Repositories\Acl\Interfaces\UserInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class UserRepository extends RepositoriesAbstract implements UserInterface
{
    /**
     * @param array $prependList
     * @param array $appendList
     * @return array
     */
    public function getList(array $prependList = [], array $appendList = [])
    {
        $all = $this->model->select(\DB::raw("CONCAT(first_name,' ',last_name) AS name"), 'id')->get()->toArray();
        $list = array_column($all, 'name', 'id');
        foreach ($list as $key => $title) {
            $prependList[$key] = $title;
        }
        foreach ($appendList as $key => $title) {
            $prependList[$key] = $title;
        }
        return $prependList;
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     */
    public function getUsersList()
    {
        $query = $this->model->leftJoin('role_users', 'users.id', '=', 'role_users.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_users.role_id')
            ->select([
                'users.id',
                'users.username',
                'users.email',
                'roles.name as role_name',
                'roles.id as role_id',
                'users.updated_at',
                'users.created_at',
                'users.super_user',
            ]);

        return $this->applyBeforeExecuteQuery($query)->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getUniqueUsernameFromEmail($email)
    {
        $emailPrefix = substr($email, 0, strpos($email, '@'));
        $username = $emailPrefix;
        $offset = 1;
        while ($this->getFirstBy(['username' => $username])) {
            $username = $emailPrefix . $offset;
            $offset++;
        }

        $this->resetModel();

        return $username;
    }

    public function getUserSupper()
    {
        $query = $this->model->where([
            'supper_user' => 1
        ]);
        return $this->applyBeforeExecuteQuery($query)->get();
    }
}
