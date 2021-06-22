<?php

namespace App\Repositories\Utility\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface UtilityInterface extends RepositoryInterface
{
    public function getList(array $prependList = [], array $appendList = []);

    public function getByType($type);

    public function getListing($type = 2);

    public function getByTitleAlias($title_alias);
}
