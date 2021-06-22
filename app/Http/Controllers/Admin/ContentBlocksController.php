<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContentBlock;
use App\Models\ContentBlockType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentBlocksRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ContentBlocksController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $blocks = ContentBlock::all();
        return view('admin.content_blocks.index', compact('blocks'));
    }

    public function create()
    {
        $block = new ContentBlock;
        return view(' admin.content_blocks.create', compact('block'));
    }

    public function store(StoreContentBlocksRequest $request)
    {
        $data = $request->all();
        $data['title_alias'] = str_slug($request->get('title'));
        $content_block = ContentBlock::create($data);
        $districts = $request->get('districts');
        if ($request->has('districts') && !empty($districts)) {
            $content_block->districts()->attach($districts);
        }
        if ($request->has('boxes') && !empty($request->get('boxes'))) {
            $content_block->boxes()->attach($request->get('boxes'));
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::blocks.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::blocks.edit', $content_block->id)->with('status', trans('notices.create_success_message'));
        }
    }

    public function show($id)
    {
        $block = ContentBlock::findOrFail($id);
        return view(' admin.content_blocks.show', compact('block'));
    }

    public function edit($id)
    {
        $block       = ContentBlock::findOrFail($id);
        $actionParse = explode('BlocksComposer@', $block->type->action);
        $detailForm  = Str::plural(Str::snake($actionParse[0])) . '.' . $actionParse[1];
        return view('admin.content_blocks.edit', compact('block', 'detailForm'));
    }

    public function update(Request $request, $id)
    {
        $block = ContentBlock::find($id);
        $data = $request->all();
        $data['title_alias'] = str_slug($request->get('title'));
        $block->update($data);
        $districts = $request->get('districts');
        if ($request->has('districts') && !empty($districts)) {
            $block->districts()->sync($districts);
        } else {
            $block->districts()->detach();
        }
        if ($request->has('boxes') && !empty($request->get('boxes'))) {
            $block->boxes()->sync($request->get('boxes'));
        } else {
            $block->boxes()->detach();
        }

        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::blocks.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::blocks.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }

    public function getDetailForm(Request $request)
    {
        if ($request->ajax()) {
            $actionParse = explode('BlocksComposer@', ContentBlockType::find($request->typeId)->action);
            $view        = Str::plural(Str::snake($actionParse[0])) . '.' . $actionParse[1];
            return view('admin.content_blocks.' . $view);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $block = ContentBlock::find($id);
            $block->delete($request->all());
            return response()->json([
                'msg'    => trans('notices.delete_success_message'),
                'status' => 200
            ]);
        }
        return redirect()->route('admin::blocks.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
