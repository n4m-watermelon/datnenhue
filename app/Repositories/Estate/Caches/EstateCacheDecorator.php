<?php

namespace App\Repositories\Estate\Caches;

use App\Repositories\Estate\Interfaces\EstateInterface;
use App\Supports\Repositories\Caches\CacheAbstractDecorator;

class EstateCacheDecorator extends CacheAbstractDecorator implements EstateInterface
{
    public function getEstateByUser()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $product_id
     * @param string $size
     * @return mixed
     */
    public function getImageObject($product_id, $size)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $categories
     * @param $filter
     * @param $block
     * @return mixed
     */
    public function getEstateBlock($categories, $filter, $block)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $district_id
     * @return mixed
     */
    public function countByDistrict($district_id)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $category_id
     * @param $district_id
     * @param int $paginate
     * @param int $limit
     * @return mixed
     */
    public function getByCategoryAndDistrictId(array $category_id, $district_id, $paginate = 12, $limit = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
    public function getByDistrictId($district_id, $paginate = 12, $limit = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $utility
     * @param $district_id
     * @param $filter_acreage
     * @param $filter_price
     * @param int $paginate
     * @param int $limit
     * @return mixed
     */
    public function getSearchByOption($utility, $district_id, $filter_price, $filter_unit, $filter_acreage, $paginate = 12, $limit = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $utility_id
     * @param int $paginate
     * @param int $limit
     * @return mixed
     */
    public function getByUtility($utility_id, $paginate = 12, $limit = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
