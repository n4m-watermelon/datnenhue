<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactsResquest;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;

/**
 * ContactsController
 *
 * @package WCSEO CMS
 * @author ....
 * @author Pham Quoc Hieu <quochieuhcm@gmail.com>
 * @copyright 2017
 * @version $Id$
 * @access public
 */
class ContactController extends Controller
{

    protected $contact;

    /**
     * ContactController constructor.
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * Display a listing of the contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::all();
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Category::whereComponent('Contact')->count() === 0)
            return redirect()->route('admin::categories.indexSpec', ['Contact'])->withErrors(['Bạn chưa tạo nhóm liên hệ, vui lòng tạo ít nhất một nhóm.']);
        $contact = new Contact;
        return view('admin.contacts.create', compact('contact'));
    }

    public function store(StoreContactsResquest $request)
    {
        $data = $request->all();
        // Insert contact to database
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid())
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($request->get('title_alias')) . '.' . $ext;
            $location = public_path('upload/' . $this->contact->getImageFolder() . '/');
            $upload->move($location, $file_name);
            $data['image'] = $file_name;
        }
        $contact = $this->contact->create($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::contacts.index')->with('status', trans('notices.create_success_message'));
        } else {
            return redirect()->route('admin::contacts.edit', $contact->id)->with('status', trans('notices.create_success_message'));
        }
    }

    public function show($id)
    {
        $contact = $this->contact->find($id);
        return view('admin.contacts.show', compact('contact'));
    }

    public function edit($id)
    {
        $contact = Contact::find($id);
        return view('admin.contacts.edit', compact('contact'));
    }

    public function update(StoreContactsResquest $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $upload = $request->file('image');
            if (!$upload->isValid()) {
                return redirect()->back()->withErrors(['Việc upload ảnh đại diện thất bại, vui lòng thử lại sau.'])->withInput();
            }
            $ext = $upload->getClientOriginalExtension();
            $file_name = time() . '-' . str_slug($request->get('title_alias')) . '.' . $ext;
            if (!is_null($contact->image) && \File::exists('upload/' . $contact->getImagePath())) {
                \File::delete('upload/' . $contact->getImagePath());
            }
            $location = public_path('upload/' . $contact->getImageFolder());
            $upload->move($location, $file_name);
            $data['image'] = $file_name;
        }
        $contact->update($data);
        if ($request->get('submit') == 'save') {
            return redirect()->route('admin::contacts.index')->with('status', trans('notices.update_success_message'));
        } else {
            return redirect()->route('admin::contacts.edit', $contact->id)->with('status', trans('notices.update_success_message'));
        }
    }


    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $contact = Contact::find($id);
            $contact->delete($request->all());
            return response()->json(['msg' => 'Xóa danh bạ thành công!', 'status' => '200']);
        }
        return redirect()->route('admin::projects.index')->with('error', 'Đã gặp phải lỗi trong quá trình thao tác, vui lòng thử lại sau.');
    }
}
