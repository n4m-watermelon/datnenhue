<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * MessageController constructor.
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSend(Request $request)
    {
        try{
            if ($request->ajax()) {
                $data = $request->all();
                $messaged = $this->message->create([
                    'name'           => $data['your_name'],
                    'email'          => $data['your_email'],
                    'phone'          => $data['your_phone'],
                    'subject'        => isset($data['your_subject']) ? $data['your_subject'] : null,
                    'content'        => !empty($data['your_content']) ? $data['your_content'] : null,
                    'reference_id'   => isset($data['reference_id']) ? $data['reference_id'] : null,
                    'reference_type' => isset($data['reference_type']) ? $data['reference_type'] : null
                ]);
                $model = 'App\\' . trim($data['reference_type']);
                $estate = $model::find($data['reference_id']);
                $to = null;
                if ($estate) {
                    if (!is_null($estate->contact)) {
                        $to = $estate->contact->email;
                    } else {
                        $contact = Contact::find($estate->getSetting('contact_id'));
                        if ($contact)
                            $to = $contact->email;
                    }
                } else {
                    $to = null;
                }
                if (!is_null($to)) {
                    \Mail::send('emails.messages.send', $data, function ($message) use ($messaged, $to) {
                        $message->to($to)->subject('Mail li??n h??? g???i t??? website ' . SITENAME);
                    });
                }
                //dispatch(new SendMessageEmail($message));
                return response()->json([
                    'data' => [
                        'msg'  => 'C???m ??n b???n ???? li??n h??? v???i ch??ng t??i!!',
                        'item' => $messaged
                    ],
                    'status' => 200
                ], 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'msg' => $exception->getMessage()
            ]);
        }

    }

    public function postQuote(Request $request)
    {
        if ($request->ajax()) {
            try{
                $data = $request->all();
                $messaged = $this->message->create([
                    'name'           => $data['footer_name'],
                    'email'          => $data['footer_email'],
                    'phone'          => $data['footer_phone'],
                    'subject'        => isset($data['your_subject']) ? $data['your_subject'] : null,
                    'content'        => !empty($data['footer_note']) ? $data['footer_note'] : null,
                    'reference_id'   => isset($data['reference_id']) ? $data['reference_id'] : null,
                    'reference_type' => isset($data['reference_type']) ? $data['reference_type'] : null
                ]);
                $contact = Contact::find(Setting::getSetting('estates')->contact_id);
                $to = null;
                if ($contact){
                    $to = $contact->email;
                }else{
                    $to = 'quochieuhcm@gmail.com';
                }
                if (!is_null($to)){
                    \Mail::send('emails.messages.send', $data, function ($message) use ($messaged, $to) {
                        $message->to($to)->subject('Mail ????ng k?? t?? v??n g???i t??? website ' . SITENAME);
                    });
                }

                return response()->json([
                    'data' => [
                        'msg'  => 'C???m ??n b???n ???? li??n h??? v???i ch??ng t??i!!',
                        'item' => $messaged
                    ],
                    'status' => 200
                ], 200);
            }catch (\Exception $exception){
                return response()->json([
                    'msg' => $exception->getMessage()
                ],403);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSchedule(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'data' => [
                    'msg'    => '?????t l???ch h???n th??nh c??ng! Ch??ng t??i s???m li??n h??? v???i b???n.',
                    'item'   => '',
                    'code'   => '',
                    'status' => 200
                ]
            ], 200);
        }
    }
}
