<?php

namespace App\Models;

class Setting extends BaseModel
{
    /**
     * Return all Rules For Validate.
     *
     * @return Array
     */
    public static $rules = [
        'site'       => [
            'value.sitename'         => 'required',
            'value.record_per_page'  => 'required|numeric|min:3',
            'value.page_per_segment' => 'required|numeric|min:3',
            /*
            'value.mail_address'        => 'required|email',
            'value.mail_host'           => 'required_if:value.mail_driver,smtp',
            'value.mail_port'           => 'required_if:value.mail_driver,smtp',
            'value.mail_auth_username'  => 'required_if:value.mail_driver,smtp',
            */
        ],
        'articles'   => [
            'value.thumbnail_width'  => 'required|numeric|min:200',
            'value.thumbnail_height' => 'required|numeric|min:175',
            'value.record_per_page'  => 'required|numeric|min:3',
            'value.page_per_segment' => 'required|numeric|min:3',
        ],
        'projects'   => [
            'value.thumbnail_width'  => 'required|numeric|min:200',
            'value.thumbnail_height' => 'required|numeric|min:175',
            'value.record_per_page'  => 'required|numeric|min:3',
            'value.page_per_segment' => 'required|numeric|min:3',
        ],
        'training'   => [
            'value.thumbnail_width'  => 'required|numeric|min:50',
            'value.thumbnail_height' => 'required|numeric|min:50',
            'value.avatar_width'     => 'required|numeric|min:50',
            'value.record_per_page'  => 'required|numeric|min:3',
            'value.page_per_segment' => 'required|numeric|min:3',
        ],
        'users'      => [
            'value.username_min_length' => 'required|numeric|min:3',
            'value.password_min_length' => 'required|numeric|min:3',
        ],
        'third_apps' => [
            'value.fb_app_id' => 'required'
        ],
    ];
    //
    /**
     * Return all messages.
     *
     * @return Array
     */
    public static $messages = [
        'site'       => [
            'value.sitename.required'         => 'Vui l??ng nh???p t??n site',
            'value.record_per_page.required'  => 'Vui l??ng nh???p s??? d??? li???u m???i trang',
            'value.record_per_page.numeric'   => 'S??? d??? li???u m???i trang ph???i l?? ki???u s???',
            'value.record_per_page.min'       => 'S??? d??? li???u m???i trang t???i thi???u l?? :min',
            'value.page_per_segment.required' => 'Vui l??ng nh???p s??? trang m???i ph??n ??o???n',
            'value.page_per_segment.numeric'  => 'S??? trang m???i ph??n ??o???n ph???i l?? ki???u s???',
            'value.page_per_segment.min'      => 'S??? trang m???i ph??n ??o???n t???i thi???u l?? :min',
            /*
            'value.mail_address.required'           => 'Vui l??ng nh???p ?????a ch??? email g???i ??i',
            'value.mail_address.email'              => '?????a ch??? email kh??ng h???p l???',
            'value.mail_host.required_if'           => 'Vui l??ng nh???p SMTP host',
            'value.mail_port.required_if'           => 'Vui l??ng nh???p SMTP port',
            'value.mail_auth_username.required_if'  => 'Vui l??ng nh???p SMTP username',
            */
        ],
        'articles'   => [
            'value.thumbnail_width.required'  => 'Vui l??ng nh???p chi???u r???ng c???a ???nh minh h???a',
            'value.thumbnail_width.numeric'   => 'Chi???u r???ng c???a ???nh ph???i l?? ki???u s???',
            'value.thumbnail_width.min'       => 'Chi???u r???ng c???a ???nh t???i thi???u l?? :min',
            'value.thumbnail_height.required' => 'Vui l??ng nh???p chi???u cao c???a ???nh minh h???a',
            'value.thumbnail_height.numeric'  => 'Chi???u cao c???a ???nh ph???i l?? ki???u s???',
            'value.thumbnail_height.min'      => 'Chi???u cao c???a ???nh t???i thi???u l?? :min',
            'value.record_per_page.required'  => 'Vui l??ng nh???p s??? d??? li???u m???i trang',
            'value.record_per_page.numeric'   => 'S??? d??? li???u m???i trang ph???i l?? ki???u s???',
            'value.record_per_page.min'       => 'S??? d??? li???u m???i trang t???i thi???u l?? :min',
            'value.page_per_segment.required' => 'Vui l??ng nh???p s??? trang m???i ph??n ??o???n',
            'value.page_per_segment.numeric'  => 'S??? trang m???i ph??n ??o???n ph???i l?? ki???u s???',
            'value.page_per_segment.min'      => 'S??? trang m???i ph??n ??o???n t???i thi???u l?? :min',
        ],
        'projects'   => [
            'value.thumbnail_width.required'  => 'Vui l??ng nh???p chi???u r???ng c???a ???nh minh h???a',
            'value.thumbnail_width.numeric'   => 'Chi???u r???ng c???a ???nh ph???i l?? ki???u s???',
            'value.thumbnail_width.min'       => 'Chi???u r???ng c???a ???nh t???i thi???u l?? :min',
            'value.thumbnail_height.required' => 'Vui l??ng nh???p chi???u cao c???a ???nh minh h???a',
            'value.thumbnail_height.numeric'  => 'Chi???u cao c???a ???nh ph???i l?? ki???u s???',
            'value.thumbnail_height.min'      => 'Chi???u cao c???a ???nh t???i thi???u l?? :min',
            'value.record_per_page.required'  => 'Vui l??ng nh???p s??? d??? li???u m???i trang',
            'value.record_per_page.numeric'   => 'S??? d??? li???u m???i trang ph???i l?? ki???u s???',
            'value.record_per_page.min'       => 'S??? d??? li???u m???i trang t???i thi???u l?? :min',
            'value.page_per_segment.required' => 'Vui l??ng nh???p s??? trang m???i ph??n ??o???n',
            'value.page_per_segment.numeric'  => 'S??? trang m???i ph??n ??o???n ph???i l?? ki???u s???',
            'value.page_per_segment.min'      => 'S??? trang m???i ph??n ??o???n t???i thi???u l?? :min',
        ],
        'training'   => [
            'value.thumbnail_width.required'  => 'Vui l??ng nh???p chi???u r???ng c???a ???nh minh h???a',
            'value.thumbnail_width.numeric'   => 'Chi???u r???ng c???a ???nh minh h???a ph???i l?? ki???u s???',
            'value.thumbnail_width.min'       => 'Chi???u r???ng c???a ???nh minh h???a t???i thi???u l?? :min',
            'value.thumbnail_height.required' => 'Vui l??ng nh???p chi???u cao c???a ???nh minh h???a',
            'value.thumbnail_height.numeric'  => 'Chi???u cao c???a ???nh minh h???a ph???i l?? ki???u s???',
            'value.thumbnail_height.min'      => 'Chi???u cao c???a ???nh minh h???a t???i thi???u l?? :min',
            'value.avatar_width.required'     => 'Vui l??ng nh???p chi???u r???ng c???a ???nh avatar',
            'value.avatar_width.numeric'      => 'Chi???u r???ng c???a ???nh vatar ph???i l?? ki???u s???',
            'value.avatar_width.min'          => 'Chi???u r???ng c???a ???nh avatar t???i thi???u l?? :min',
            'value.record_per_page.required'  => 'Vui l??ng nh???p s??? d??? li???u m???i trang',
            'value.record_per_page.numeric'   => 'S??? d??? li???u m???i trang ph???i l?? ki???u s???',
            'value.record_per_page.min'       => 'S??? d??? li???u m???i trang t???i thi???u l?? :min',
            'value.page_per_segment.required' => 'Vui l??ng nh???p s??? trang m???i ph??n ??o???n',
            'value.page_per_segment.numeric'  => 'S??? trang m???i ph??n ??o???n ph???i l?? ki???u s???',
            'value.page_per_segment.min'      => 'S??? trang m???i ph??n ??o???n t???i thi???u l?? :min',
        ],
        'users'      => [
            'value.username_min_length.required' => 'Vui l??ng nh???p ????? d??i username t???i thi???u',
            'value.username_min_length.numeric'  => '????? d??i username ph???i l?? ki???u s???',
            'value.username_min_length.min'      => '????? d??i username t???i thi???u l?? :min k?? t???',
            'value.password_min_length.required' => 'Vui l??ng nh???p ????? d??i password t???i thi???u',
            'value.password_min_length.numeric'  => '????? d??i password ph???i l?? ki???u s???',
            'value.password_min_length.min'      => '????? d??i password t???i thi???u l?? :min k?? t???',
        ],
        'third_apps' => [
            'value.fb_app_id.required' => 'Vui l??ng nh???p Facebook App ID.'
        ]
    ];
    public $timestamps = false;
    protected $fillable = [
        'name',
        'value',
        'default'
    ];

    public static function boot()
    {
        parent::boot();
    }

    /**
     * Return all value of specified setting.
     *
     * @param  string $name
     * @return Mixed
     */
    public static function getSetting($name)
    {
        //$instance = new static;
        $setting = self::where('name', '=', $name)->first();
        if (!$setting) {
            return false;
        }
        $setting->value = (empty($setting->value)) ?
            json_decode($setting->default, false) :
            json_decode($setting->value, false);
        return $setting->value;
    }
}
