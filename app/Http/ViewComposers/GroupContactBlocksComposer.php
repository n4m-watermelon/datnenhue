<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\View\View;

class GroupContactBlocksComposer
{
    /**
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        if (isset($block->params->category_id))
            $category = Category::find($block->params->category_id);
        if (!isset($category)) {
            $contacts = [];
        } else {
            $contacts = Contact::whereRaw('category_id =' . $block->params->category_id . ' and public = 1')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        $view->with('contacts', $contacts);
    }
}

?>
