<?php

namespace App\Http\Controllers;

use App\Repositories\Unit\Interfaces\EstateUnitInterface;
use App\Repositories\Utility\Interfaces\UtilityInterface;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @var EstateUnitInterface
     */
    protected $estateUnitRepository;
    /**
     * @var UtilityInterface
     */
    protected $utilityRepository;

    //

    /**
     * ApiController constructor.
     * @param UtilityInterface $utilityRepository
     * @param EstateUnitInterface $estateUnitRepository
     */
    public function __construct(
        UtilityInterface $utilityRepository,
        EstateUnitInterface $estateUnitRepository
    )
    {
        $this->utilityRepository = $utilityRepository;
        $this->estateUnitRepository = $estateUnitRepository;
    }

    /**
     * Get Action Change Type
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActionChangeType(Request $request)
    {
        try {
            $units   = $this->estateUnitRepository->getByType($request->input('type'));
            $utility = $this->utilityRepository->getByType($request->input('type'));
            if ($request->ajax()) {
                return response()->json([
                    'status'  => 200,
                    'units'   => $units,
                    'utility' => $utility
                ], 200);
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
