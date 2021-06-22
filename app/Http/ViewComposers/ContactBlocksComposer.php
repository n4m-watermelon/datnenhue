<?php

namespace App\Http\ViewComposers;

use App\Models\Contact;
use Illuminate\View\View;

class ContactBlocksComposer
{
    /**
     * ContactBlocksComposer constructor.
     */
    public function __construct()
    {
    }

    /**
     * View method compose
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        $block = $view->getData()['block'];
        $contact = Contact::find($block->params->contact_id);
        if (!isset($contact)) {
            $contact = [];
        }
        $view->with([
            'contact' => $contact
        ]);
    }
}

?>
