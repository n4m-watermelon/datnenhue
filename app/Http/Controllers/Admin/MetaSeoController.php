<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\MetaSeo\Interfaces\MetaSeoInterface;
use Illuminate\Http\Request;

class MetaSeoController extends Controller
{
    /**
     * @var MetaSeoInterface
     */
    protected $metaSeoRepository;

    /**
     * MetaSeoController constructor.
     * @param MetaSeoInterface $metaSeoRepository
     */
    public function __construct(MetaSeoInterface $metaSeoRepository)
    {
        $this->metaSeoRepository = $metaSeoRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $metaSeo = $this->metaSeoRepository->all();
        return view('admin.meta_seo.index', compact('metaSeo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $metaSeo = $this->metaSeoRepository;
        return view('admin.meta_seo.create', compact('metaSeo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

        } catch (\Exception $ex) {
            echo 'Save:' . $ex->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $metaSeo = $this->metaSeoRepository->findOrFail($id);
        return view('admin.meta_seo.show', compact('metaSeo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $metaSeo = $this->metaSeoRepository->findOrFail($id);
        return view('admin.meta_seo.edit', compact('metaSeo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

        } catch (\Exception $ex) {
            echo 'Save:' . $ex->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {

        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
