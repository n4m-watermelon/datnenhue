<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUtilityRequest;
use App\Repositories\Utility\Interfaces\UtilityInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UtilitiesController extends Controller
{
    protected $utilityRepository;

    public function __construct(UtilityInterface $utilityRepository)
    {
        $this->utilityRepository = $utilityRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilities = $this->utilityRepository->all();
        return view('admin.utilities.index',compact('utilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $utility = $this->utilityRepository;
        return view('admin.utilities.create',compact('utility'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUtilityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUtilityRequest $request)
    {
        $utility = $this->utilityRepository->createOrUpdate(array_merge($request->input(), [
            'title_alias' => Str::slug($request->input('title'))
        ]));
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::utilities.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::utilities.edit', $utility->id)->with('status', trans('notices.create_success_message'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $utility = $this->utilityRepository->findById($id);
        return view('admin.utilities.edit', compact('utility'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreUtilityRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUtilityRequest $request, $id)
    {
        $utility = $this->utilityRepository->findOrFail($id);
        $utility->fill(array_merge($request->input(), [
            'title_alias' => Str::slug($request->input('title'))
        ]));
        $this->utilityRepository->createOrUpdate($utility);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::utilities.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::utilities.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $utility = $this->utilityRepository->findOrFail($id);
            $this->utilityRepository->delete($utility);

            return response()->json([
                'status' => 200,
                'msg' => trans('notices.delete_success_message')
            ], 200);

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
