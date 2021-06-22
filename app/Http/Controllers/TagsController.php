<?php

namespace App\Http\Controllers;

class TagsController extends Controller
{
    /**
     * Display the specified tag.
     * @param $tag_alias
     * @return mixed
     */
    public function show($tag_alias)
    {
        $related = \Conner\Tagging\Model\Tagged::where('tag_slug', '=', $tag_alias)->orderBy('id', 'desc')->get();
        $relatedSet = array();
        foreach ($related as $related_item) {
            $model = $related_item->taggable_type;
            $item = $model::whereRaw('id = ? and public = 1', array($related_item->taggable_id))
                ->whereHas('category', function ($query) {
                    $query->where('public', '=', 1);
                })
                ->first();
            if (!empty($item)) {
                if ($item->category->ancestors()->where('public', '!=', 1)->count() == 0) {
                    $relatedSet[] = $item;
                }
            }
        }
        return view('frontend.tags.show', compact('relatedSet', 'tag_alias', 'related', 'model'));
    }

}
