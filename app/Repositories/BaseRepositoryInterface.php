<?php

namespace App\Repositories;
/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories
 */
interface BaseRepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $id
     * @return mixed
     *
     */
    public function find($id);

    /**
     * @param array $attribute
     * @return mixed
     */
    public function create(array $attribute);

    /**
     * @param $id
     * @param array $attribute
     * @return mixed
     */
    public function update($id, array $attribute);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
