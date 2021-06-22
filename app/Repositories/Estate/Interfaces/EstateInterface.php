<?php

namespace App\Repositories\Estate\Interfaces;

use App\Supports\Repositories\Interfaces\RepositoryInterface;

interface EstateInterface extends RepositoryInterface
{
    public function getEstateByUser();
    /**
     * @param $product_id
     * @param string $size
     * @return mixed
     */
    public function getImageObject($product_id, $size);

    /**
     * @param $categories
     * @param $filter
     * @param $block
     * @return mixed
     */
    public function getEstateBlock($categories, $filter, $block);

    /**
     * @param $district_id
     * @return mixed
     */
    public function countByDistrict($district_id);

    /**
     * @param array $category_id
     * @param $district_id
     * @param int $paginate
     * @param int $limit
     * @return mixed
     */
    public function getByCategoryAndDistrictId(array $category_id, $district_id, $paginate = 12, $limit = 0);
    public function getByDistrictId($district_id, $paginate = 12, $limit = 0);

    /**
     * @param $utility
     * @param $district_id
     * @param $filter_acreage
     * @param $filter_unit
     * @param $filter_price
     * @param int $paginate
     * @param int $limit
     * @return mixed
     */
    public function getSearchByOption($utility, $district_id, $filter_price, $filter_unit, $filter_acreage, $paginate = 12, $limit = 0);

    /**
     * @param $utility_id
     * @param int $paginate
     * @param int $limit
     * @return mixed
     */
    public function getByUtility($utility_id, $paginate = 12, $limit = 0);
}
