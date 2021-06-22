<?php

namespace App\Repositories\Acl\Interfaces;


use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{

    /**
     * @param array $prependList
     * @param array $appendList
     * @return mixed
     */
    public function getList(array $prependList = [], array $appendList = []);

    /**
     * @return mixed
     */
    public function getUsersList();

    /**
     * Get unique username from email
     *
     * @param $email
     * @return string
     *
     */
    public function getUniqueUsernameFromEmail($email);

    public function getUserSupper();
}
