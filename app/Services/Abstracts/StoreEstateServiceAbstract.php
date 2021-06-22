<?php

namespace App\Services\Abstracts;

use App\Models\Estate;
use App\Repositories\Estate\Interfaces\EstateInterface;
use Illuminate\Http\Request;

abstract class StoreEstateServiceAbstract
{
    /**
     * @var EstateInterface
     */
    protected $estateRepository;

    /**
     * StoreCategoryServiceAbstract constructor.
     * @param EstateInterface $estateRepository
     *
     */
    public function __construct(EstateInterface $estateRepository)
    {
        $this->estateRepository = $estateRepository;
    }

    /**
     * @param Request $request
     * @param Estate $estate
     * @return mixed
     *
     */
    abstract public function execute(Request $request, Estate $estate);


}
