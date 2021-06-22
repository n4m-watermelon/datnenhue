<?php

namespace App\Repositories\Eloquent;

use App\Models\Slider;

class SliderEloquentResponsitory extends EloquentBaseReponsitory implements SliderResponsitoryInterface
{
    /**
     * @return string
     */
    public function setModel()
    {
        return Slider::class;
    }

    /**
     * @return mixed
     */
    public function getImageFolder()
    {
        return $this->_model->getImageFolder();
    }

    /**
     * @param $group_id
     * @return mixed
     */
    public function findOnlyPublishedByGroup($group_id)
    {
        return $this->_model->where('public', 1)
            ->where('group_id', $group_id)
            ->orderBy('ordering', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }
}
