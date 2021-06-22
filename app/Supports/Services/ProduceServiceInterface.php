<?php

namespace App\Supports\Services;

use Illuminate\Http\Request;

interface ProduceServiceInterface
{
    /**
     * Execute produce an entity
     *
     * @param Request $request
     * @return mixed
     */
    public function execute(Request $request);
}
