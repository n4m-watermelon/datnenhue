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
            'value.sitename.required'         => 'Vui lòng nhập tên site',
            'value.record_per_page.required'  => 'Vui lòng nhập số dữ liệu mỗi trang',
            'value.record_per_page.numeric'   => 'Số dữ liệu mỗi trang phải là kiểu số',
            'value.record_per_page.min'       => 'Số dữ liệu mỗi trang tối thiểu là :min',
            'value.page_per_segment.required' => 'Vui lòng nhập số trang mỗi phân đoạn',
            'value.page_per_segment.numeric'  => 'Số trang mỗi phân đoạn phải là kiểu số',
            'value.page_per_segment.min'      => 'Số trang mỗi phân đoạn tối thiểu là :min',
            /*
            'value.mail_address.required'           => 'Vui lòng nhập địa chỉ email gửi đi',
            'value.mail_address.email'              => 'Địa chỉ email không hợp lệ',
            'value.mail_host.required_if'           => 'Vui lòng nhập SMTP host',
            'value.mail_port.required_if'           => 'Vui lòng nhập SMTP port',
            'value.mail_auth_username.required_if'  => 'Vui lòng nhập SMTP username',
            */
        ],
        'articles'   => [
            'value.thumbnail_width.required'  => 'Vui lòng nhập chiều rộng của ảnh minh họa',
            'value.thumbnail_width.numeric'   => 'Chiều rộng của ảnh phải là kiểu số',
            'value.thumbnail_width.min'       => 'Chiều rộng của ảnh tối thiểu là :min',
            'value.thumbnail_height.required' => 'Vui lòng nhập chiều cao của ảnh minh họa',
            'value.thumbnail_height.numeric'  => 'Chiều cao của ảnh phải là kiểu số',
            'value.thumbnail_height.min'      => 'Chiều cao của ảnh tối thiểu là :min',
            'value.record_per_page.required'  => 'Vui lòng nhập số dữ liệu mỗi trang',
            'value.record_per_page.numeric'   => 'Số dữ liệu mỗi trang phải là kiểu số',
            'value.record_per_page.min'       => 'Số dữ liệu mỗi trang tối thiểu là :min',
            'value.page_per_segment.required' => 'Vui lòng nhập số trang mỗi phân đoạn',
            'value.page_per_segment.numeric'  => 'Số trang mỗi phân đoạn phải là kiểu số',
            'value.page_per_segment.min'      => 'Số trang mỗi phân đoạn tối thiểu là :min',
        ],
        'projects'   => [
            'value.thumbnail_width.required'  => 'Vui lòng nhập chiều rộng của ảnh minh họa',
            'value.thumbnail_width.numeric'   => 'Chiều rộng của ảnh phải là kiểu số',
            'value.thumbnail_width.min'       => 'Chiều rộng của ảnh tối thiểu là :min',
            'value.thumbnail_height.required' => 'Vui lòng nhập chiều cao của ảnh minh họa',
            'value.thumbnail_height.numeric'  => 'Chiều cao của ảnh phải là kiểu số',
            'value.thumbnail_height.min'      => 'Chiều cao của ảnh tối thiểu là :min',
            'value.record_per_page.required'  => 'Vui lòng nhập số dữ liệu mỗi trang',
            'value.record_per_page.numeric'   => 'Số dữ liệu mỗi trang phải là kiểu số',
            'value.record_per_page.min'       => 'Số dữ liệu mỗi trang tối thiểu là :min',
            'value.page_per_segment.required' => 'Vui lòng nhập số trang mỗi phân đoạn',
            'value.page_per_segment.numeric'  => 'Số trang mỗi phân đoạn phải là kiểu số',
            'value.page_per_segment.min'      => 'Số trang mỗi phân đoạn tối thiểu là :min',
        ],
        'training'   => [
            'value.thumbnail_width.required'  => 'Vui lòng nhập chiều rộng của ảnh minh họa',
            'value.thumbnail_width.numeric'   => 'Chiều rộng của ảnh minh họa phải là kiểu số',
            'value.thumbnail_width.min'       => 'Chiều rộng của ảnh minh họa tối thiểu là :min',
            'value.thumbnail_height.required' => 'Vui lòng nhập chiều cao của ảnh minh họa',
            'value.thumbnail_height.numeric'  => 'Chiều cao của ảnh minh họa phải là kiểu số',
            'value.thumbnail_height.min'      => 'Chiều cao của ảnh minh họa tối thiểu là :min',
            'value.avatar_width.required'     => 'Vui lòng nhập chiều rộng của ảnh avatar',
            'value.avatar_width.numeric'      => 'Chiều rộng của ảnh vatar phải là kiểu số',
            'value.avatar_width.min'          => 'Chiều rộng của ảnh avatar tối thiểu là :min',
            'value.record_per_page.required'  => 'Vui lòng nhập số dữ liệu mỗi trang',
            'value.record_per_page.numeric'   => 'Số dữ liệu mỗi trang phải là kiểu số',
            'value.record_per_page.min'       => 'Số dữ liệu mỗi trang tối thiểu là :min',
            'value.page_per_segment.required' => 'Vui lòng nhập số trang mỗi phân đoạn',
            'value.page_per_segment.numeric'  => 'Số trang mỗi phân đoạn phải là kiểu số',
            'value.page_per_segment.min'      => 'Số trang mỗi phân đoạn tối thiểu là :min',
        ],
        'users'      => [
            'value.username_min_length.required' => 'Vui lòng nhập độ dài username tối thiểu',
            'value.username_min_length.numeric'  => 'Độ dài username phải là kiểu số',
            'value.username_min_length.min'      => 'Độ dài username tối thiểu là :min ký tự',
            'value.password_min_length.required' => 'Vui lòng nhập độ dài password tối thiểu',
            'value.password_min_length.numeric'  => 'Độ dài password phải là kiểu số',
            'value.password_min_length.min'      => 'Độ dài password tối thiểu là :min ký tự',
        ],
        'third_apps' => [
            'value.fb_app_id.required' => 'Vui lòng nhập Facebook App ID.'
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
