<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMailRequest;
use App\Models\Contact;
use Mail;

class ContactsController extends Controller
{
    /**
     * ContactController@show
     *
     * @param $id
     * @return View
     */
    public function show($id)
    {
        $contact = Contact::whereRaw('id = ? and public = 1', [$id])->first();
        return view('frontend.contacts.show', compact('contact'));
    }

    /**
     * Contact Send
     *
     * @param SendMailRequest $request
     * @param $contact_alias
     * @return string
     */
    public function send(SendMailRequest $request, $contact_alias)
    {
        $contact = Contact::where('title_alias', '=', $contact_alias)->firstOrFail();
        $data = $request->all();
        // Send mail
        Mail::send('emails.contacts.contact', $data, function ($message) use ($contact) {
            $message->to($contact->email, $contact->title)->subject('Mail liên hệ gửi từ website ' . SITENAME);
        });
        return redirect()->back()->with('message', 'Cảm ơn quý khách đã liên hệ với chúng tôi. Chúng tôi sẽ hồi âm trong thời gian sớm nhất có thể');
    }
}
