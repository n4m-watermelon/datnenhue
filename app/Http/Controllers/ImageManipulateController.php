<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Image;

/**
 * Class ImageManipulateController
 * @package App\Http\Controllers
 */
class ImageManipulateController extends Controller
{
    /**
     *
     * @param Request $request
     * @param $width
     * @param $height
     * @param $path
     * @return mixed
     */
    public function manipulate(Request $request, $width, $height, $path, $watermark=1)
    {
        $fit_position       = $request->get('position', 'center');
        $include_watermark  = 0;
        if($watermark) {
            $include_watermark = Setting::getSetting('site')->use_image_watermark;
        }
        $watermark_position = Setting::getSetting('site')->watermark_position;
        if(strpos($path, 'upload/') !== false){
            $path = str_replace('upload/', '', $path);
        }

        if (!file_exists(public_path('/upload/' . $path))) {
            $path = 'settings/mua-ban-dat-nen-hue-uy-tin-nhanh-gon-0793647929-logo.gif';
        }

        if ($height == 0) {
            $img = Image::make(public_path('upload/' . $path))->widen($width, function ($constraint) {
                $constraint->upsize();
            });
        } elseif ($width == 0) {
            $img = Image::make(public_path('upload/' . $path))->heighten($height, function ($constraint) {
                $constraint->upsize();
            });
        } else {
            $img = Image::make(public_path('upload/' . $path))->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            }, $fit_position);
        }
        if (file_exists(public_path('upload/settings/' . Setting::getSetting('site')->watermark)) && $include_watermark == 1 && $img->width() >= 100) {
            $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen
            (floor($img->width() /3), function ($constraint) {
                $constraint->upsize();
            });
            $img->insert($watermark, $watermark_position, floor($img->width() / 100), floor($img->width() / 100));
        }
        return $img->response();
    }


    public function manipulateOrigin($path)
    {
        $include_watermark = Setting::getSetting('site')->use_image_watermark;
        $watermark_position = Setting::getSetting('site')->watermark_position;
        $img = Image::make(public_path('upload/' . $path));
        if (file_exists(public_path('upload/settings/' . Setting::getSetting('site')->watermark)) && $include_watermark == 1 && $img->width() >= 100) {
            $watermark = Image::make(public_path('upload/settings/' . Setting::getSetting('site')->watermark))->widen
            (floor($img->width() / 3), function ($constraint) {
                $constraint->upsize();
            });
            $img->insert($watermark, $watermark_position, floor($img->width() / 100), floor($img->width() / 100));
        }
        return $img->response();
    }
}
