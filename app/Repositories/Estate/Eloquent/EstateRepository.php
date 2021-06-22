<?php

namespace App\Repositories\Estate\Eloquent;

use App\Models\RangeAcreage;
use App\Models\RangePrice;
use App\Repositories\Estate\Interfaces\EstateInterface;
use App\Supports\Repositories\Eloquent\RepositoriesAbstract;

class EstateRepository extends RepositoriesAbstract implements EstateInterface
{

    public function getEstateByUser()
    {
        $estate = $this->model;
        if (!auth()->user()->isSuperUser()){
            $estate = $this->model->where('created_by', auth()->user()->id);
        }
        return $this->applyBeforeExecuteQuery($estate)->get();

    }

    /**
     * @param $product_id
     * @param $size
     * @return string
     */
    public function getImageObject($product_id, $size)
    {
        $product = $this->findById($product_id);
        $sizes = explode('x', config('media.sizes.' . $size));
        if ($product && count($sizes) > 0) {
            if ($product->image) {
                return route('image.manipulate', [$sizes[0], $sizes[1], 'estate-images/' . $product->image]);
            }
        }
        return asset('templates/frontend/assets/img/detail_tour/1.png');
    }

    /**
     * @param $categories
     * @param $filter
     * @param $block
     * @return \Illuminate\Support\Collection
     */
    public function getEstateBlock($categories, $filter, $block)
    {
        $data = $this->model->getModel()->whereRaw('category_id in (' . $categories . ') and public = 1' .
            $filter)->orderBy($block->params->orderBy, $block->params->direction)
            ->take($block->params->amount_of_data);
        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * @param $district_id
     * @return bool|mixed
     */
    public function countByDistrict($district_id)
    {
        $query = $this->model->select('estates.id')->where([
            'estates.district_id' => $district_id,
            'estates.public' => 1
        ]);
        $count_row = $this->applyBeforeExecuteQuery($query)->count();
        if ($count_row > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param array $category_id
     * @param $district_id
     * @param int $paginate
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     * @author JackiePham
     */
    public function getByCategoryAndDistrictId(array $category_id, $district_id, $paginate = 12, $limit = 0)
    {
        $data = $this->model
            ->where('estates.public', 1)
            ->whereIn('estates.category_id', $category_id)
            ->where('estates.district_id', $district_id)
            ->select('estates.*')
            ->distinct()
            ->orderBy('estates.created_at', 'desc');

        if ($paginate != 0) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data)->limit($limit)->get();
    }
    public function getByDistrictId($district_id, $paginate = 12, $limit = 0)
    {
        $data = $this->model
            ->where('estates.public', 1)
            ->where('estates.district_id', $district_id)
            ->select('estates.*')
            ->distinct()
            ->orderBy('estates.created_at', 'desc');

        if ($paginate != 0) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data)->limit($limit)->get();
    }

    /**
     * @param $utility
     * @param $district_id
     * @param $filter_acreage
     * @param $filter_price
     * @param $filter_unit
     * @param int $paginate
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function getSearchByOption($utility, $district_id, $filter_price, $filter_unit, $filter_acreage, $paginate = 12, $limit = 0)
    {
        $query = $this->model->where('public', 1);
        if ($utility > 0) {
            $query->whereHas('utilities', function ($query) use ($utility) {
                $query->where('utility_id', $utility);
            });
        }
        if ($district_id > 0) {
            $query->where('district_id', $district_id);
        }
        if ($filter_price > 0) {
            $range_price = RangePrice::where('id', $filter_price)->first();
            if ($range_price) {
                $min_price = (int)$range_price->min;
                $max_price = (int)$range_price->max;
                if ($min_price > 0 && $max_price > 0) {
                    $query->whereBetween('price', [$min_price, $max_price]);
                }
                if ($min_price > 0 && $max_price == 0) {
                    $query->where('price', '>=', $min_price);
                }
            }
        }
        if ($filter_unit){
            $query->where('unit_id', $filter_unit);
        }

        if ($filter_acreage > 0) {
            $range_acreage = RangeAcreage::where('id', $filter_acreage)->first();
            if ($range_acreage) {
                $min = (int)$range_acreage->min;
                $max = (int)$range_acreage->max;
                if ($min > 0 && $max > 0) {
                    $query->whereBetween('area', [$min, $max]);
                }
                if ($min > 0 && $max == 0) {
                    $query->where('area', '>=', $min);
                }
            }
        }
        $query->orderBy('created_at', 'desc');

        if ($paginate != 0) {
            return $this->applyBeforeExecuteQuery($query)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($query)->limit($limit)->get();
    }

    /**
     * @param $utility_id
     * @param int $paginate
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection|mixed
     */
    public function getByUtility($utility_id, $paginate = 12, $limit = 0)
    {
        $data = $this->model
            ->whereHas('utilities', function ($query) use ($utility_id) {
                $query->where('utility_id', $utility_id);
            })
            ->where('estates.public', 1)
            ->select('estates.*')
            ->distinct()
            ->orderBy('estates.created_at', 'desc');

        if ($paginate != 0) {
            return $this->applyBeforeExecuteQuery($data)->paginate($paginate);
        }
        return $this->applyBeforeExecuteQuery($data)->limit($limit)->get();
    }
}
