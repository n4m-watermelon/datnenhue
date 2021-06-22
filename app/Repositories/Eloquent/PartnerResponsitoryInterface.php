<?php

namespace App\Repositories\Eloquent;

interface PartnerResponsitoryInterface
{
    /**
     * @return mixed
     */
    public function getImageFolder();

    public function isPublished();
}