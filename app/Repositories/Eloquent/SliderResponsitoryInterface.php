<?php

namespace App\Repositories\Eloquent;
/**
 * Interface SliderResponsitoryInterface
 *
 * @package App\Repositories\Eloquent
 */
interface SliderResponsitoryInterface
{
    /**
     * @return mixed
     */
    public function findOnlyPublishedByGroup($group_id);

    /**
     * @return mixed
     */
    public function getImageFolder();
}

?>