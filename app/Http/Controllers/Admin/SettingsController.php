<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        return redirect()->route('admin::settings.edit', ['site']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return redirect()->route('admin::settings.edit', ['site']);
    }

    public function store(Request $request)
    {
        return redirect()->route('admin::settings.edit', ['site']);
    }


    public function show($name)
    {
        return redirect()->route('admin::settings.edit', [$name]);
    }


    public function edit($param)
    {
        $view_folder = snake_case($param);
        $setting = Setting::where('name', '=', $param)->first();
        if ($setting) {
            $setting->value = (empty($setting->value)) ? json_decode($setting->default, false) : json_decode($setting->value, false);
            return view('admin.settings.' . $view_folder . '.edit', compact('setting'));
        } else {
            return response()->make('Page not found', '404');
        }
    }


    public function update(Request $request, $name)
    {
        $setting = Setting::where('name', '=', $name)->first();
        if (!$setting)
            return redirect()->route('admin::settings.edit', ['site'])->withErrors(['Không tồn tại đối tượng này, vui lòng thử lại lại sau !']);
        $data = $request->all();
        if ($request->hasFile('logo')) {
            Setting::$rules['site']['logo'] = 'image';
            Setting::$messages['site']['logo.image'] = 'Ảnh minh họa không hợp lệ. Chỉ chấp nhận tập tin png, jpeg, bmp hoặc gif';
        }
        if (isset(Setting::$rules[$name])) {
            $validator = Validator::make($data = $request->all(), Setting::$rules[$name], Setting::$messages[$name]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
        }
        // Setting logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            if ($logo->isValid()) {
                if (isset(Setting::getSetting($name)->logo) && \File::exists('upload/settings/' . Setting::getSetting($name)->logo)) {
                    // Delete old image
                    \File::delete('upload/settings/' . Setting::getSetting($name)->logo);
                }
                $ext = $logo->getClientOriginalExtension();
                $logo_name = str_slug($request['value']['sitename']) . '-logo' . '.' . $ext;
                $logo->move('upload/settings/', $logo_name);

                $data['value']['logo'] = $logo_name;

            }
        } else {
            if (isset(Setting::getSetting($name)->logo)) {
                $data['value']['logo'] = Setting::getSetting($name)->logo;
            }
        }

        // Watermark
        if ($request->hasFile('watermark')) {
            $watermark = $request->file('watermark');
            if ($watermark->isValid()) {
                if (isset(Setting::getSetting($name)->watermark) && \File::exists('upload/settings/' . Setting::getSetting($name)->watermark)) {
                    \File::delete('upload/settings/' . Setting::getSetting($name)->watermark);
                }
                $extFile = $watermark->getClientOriginalExtension();
                $watermark_name = str_slug($request['value']['sitename']) . '-watermark' . '.' . $extFile;
                $watermark->move('upload/settings/', $watermark_name);
                $data['value']['watermark'] = $watermark_name;
            }
        } else {
            if (isset(Setting::getSetting($name)->watermark)) {
                $data['value']['watermark'] = Setting::getSetting($name)->watermark;
            }
        }
        $setting->value = json_encode($data['value'], JSON_UNESCAPED_UNICODE);
        // Update
        $setting->update();
        return redirect()->route('admin::settings.edit', $name)->with('status', 'Cập nhật thông tin thành công!');
    }

    public function destroy($param)
    {
        //
    }

    // reset setting
    public function reset($param)
    {

    }
}
