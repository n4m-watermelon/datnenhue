<?php

namespace App\Repositories\Acl\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface RoleInterface extends RepositoryInterface
{
    /**
     * @param string $name
     * @param int|null $id
     * @return string
     */
    public function createSlug($name, $id);

    public function getList(array $prependList = [], array $appendList = []);
}
