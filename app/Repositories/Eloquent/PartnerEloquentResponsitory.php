<?php

namespace App\Repositories\Eloquent;

use App\Models\Partner;

class PartnerEloquentResponsitory extends EloquentBaseReponsitory implements PartnerResponsitoryInterface
{
    /**
     * @return string
     */
    function setModel()
    {
        return Partner::class;
    }

    /**
     * @return mixed
     */
    public function getImageFolder()
    {
        return $this->_model->getImageFolder();
    }

    /**
     * @return mixed
     */
    public function isPublished()
    {
        return $this->_model->where('public',1)->get();
    }
}
