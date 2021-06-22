<?php

namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepositoryInterface;

abstract class EloquentBaseReponsitory implements BaseRepositoryInterface
{
    /**
     * @var
     */
    protected $_model;

    /**
     * EloquentBaseReponsitory constructor.
     */
    public function __construct()
    {
        $this->getModel();
    }

    /**
     *
     */
    private function getModel()
    {
        $this->_model = app()->make($this->setModel());
    }

    /**
     * @return mixed
     */
    abstract function setModel();

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->_model->all();
    }

    /**
     * @param array $attribute
     * @return mixed
     */
    public function create(array $attribute)
    {
        return $this->_model->create($attribute);
    }

    /**
     *
     * Method Update Item
     *
     *
     * @param $id
     * @param array $attribute
     * @return bool
     */
    public function update($id, array $attribute)
    {
        $model = $this->find($id);
        if ($model) {
            $model->update($attribute);
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->_model->find($id);
    }

    /**
     * $this->delete($id)
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->find($id);
        if ($model){
            $model->delete();
            return true;
        }
        return false;
    }
}
?>