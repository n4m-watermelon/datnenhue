<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagsRequest;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\Tagged;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    protected $_tag;
    protected $_tagged;

    public function __construct(Tag $tag, Tagged $tagged)
    {
        $this->_tag = $tag;
        $this->_tagged = $tagged;
    }

    public function index()
    {
        $tags = $this->_tag->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        $tag = new $this->_tag;
        return view('admin.tags.create', compact('tag'));
    }

    public function store(StoreTagsRequest $request)
    {
        $data = $request->all();
        $data['slug'] = str_slug($data['name']);
        $tag = $this->_tag->create($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::tags.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::tags.edit', $tag->id)->with('status', trans('notices.create_success_message'));
        }
    }

    public function show($id)
    {
        $tag = $this->_tag->findOrFail($id);
        $tagged_items = $this->_tagged->where('tag_name', '=', $tag->name)->get();
        $taggables = array();
        foreach ($tagged_items as $tagged_item) {
            $taggables[] = call_user_func($tagged_item->taggable_type . '::find', $tagged_item->taggable_id);
        }
        return view('admin.tags.show', compact('tag', 'taggables'));
    }

    public function edit($id)
    {
        $tag = $this->_tag->findOrFail($id);
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(StoreTagsRequest $request, $id)
    {
        $tag = $this->_tag->findOrFail($id);
        $data = $request->all();
        $tag->update($data);
        $tagged_items = $this->_tagged->where('tag_name', '=', $tag->name)->get();
        foreach ($tagged_items as $tagged_item) {
            $tagged_item->update([
                'tag_name' => $tag->name,
                'tag_slug' => str_slug($tag->name)
            ]);
        }
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::tags.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::tags.edit', $id)->with('status', trans('notices.update_success_message'));
        }
    }


    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $tag = $this->_tag->findOrFail($id);
            $this->_tagged->where('tag_name', '=', $tag->name)->delete();
            $tag->delete();
            return response()->json([
                'msg' => trans('notices.delete_success_message'),
                'status' => 200
            ], 200);
        }
    }

    public function detachAll(Request $request, $id)
    {
        if ($request->ajax()) {
            $tag = $this->_tag->findOrFail($id);
            $this->_tagged->where('tag_name', '=', $tag->name)->delete();
            $tag->count = 0;
            $tag->save();
            return response()->json([
                'msg' => 'Gỡ bỏ nội dung thành công.',
                'status' => 200
            ], 200);
        }
    }
}
