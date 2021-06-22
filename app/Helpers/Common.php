<?php

use App\Repositories\Article\Interfaces\ArticleInterface;
use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\ContentBlock\Interfaces\ContentBlockInterface;
use App\Repositories\District\Interfaces\DistrictInterface;
use App\Repositories\Estate\Interfaces\EstateInterface;
use App\Repositories\EstateGallery\Interfaces\EstateGalleryInterface;
use App\Repositories\Setting\Interfaces\SettingInterface;
use App\Repositories\Utility\Interfaces\UtilityInterface;
use Carbon\Carbon;

if (!function_exists('getClassBody')) {
    /**
     * Get Class Body
     * @return string
     */
    function getClassBody()
    {
        $class = '';
        $routeName = request()->route()->getName();
        if ($routeName == 'home.index') {
            $class .= 'homepage';
        } elseif ($routeName == 'categories.show') {
            $class .= 'archive';
        } elseif ($routeName == 'paths.parse') {
            $class .= 'single-paged';
        } elseif ($routeName == 'search.index') {
            $class .= 'search';
        }
        return $class;
    }
}
if (!function_exists('human_file_size')) {
    /**
     * @param $bytes
     * @param int $precision
     * @return string
     * @author Sang Nguyen
     */
    function human_file_size($bytes, $precision = 2)
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }
}
if (!function_exists('format_time')) {
    /**
     * @param Carbon $timestamp
     * @param $format
     * @return mixed
     * @author Sang Nguyen
     */
    function format_time(Carbon $timestamp, $format = 'j M Y H:i')
    {
        $first = Carbon::create(0000, 0, 0, 00, 00, 00);
        if ($timestamp->lte($first)) {
            return '';
        }

        return $timestamp->format($format);
    }
}
if (!function_exists('is_image')) {
    /**
     * Is the mime type an image
     *
     * @param $mimeType
     * @return bool
     * @author Sang Nguyen
     */
    function is_image($mimeType)
    {
        return starts_with($mimeType, 'image/');
    }
}
if (!function_exists('getClassBody')) {
    function getClassBody()
    {
        $class = '';
        $routeName = request()->route()->getName();
        if ($routeName == 'home.index') {
            $class .= 'homepage';
        } else if ($routeName == 'page.contestants') {
            $class .= 'pape-contestants';
        } else {
            $class .= 'PageInSide';
        }
        return $class;
    }
}

if (!function_exists('scan_folder')) {
    function scan_folder($path, $ignore_files = [])
    {
        try {
            if (is_dir($path)) {
                $data = array_diff(scandir($path), array_merge(['.', '..'], $ignore_files));
                natsort($data);
                return $data;
            }
            return [];
        } catch (Exception $ex) {
            return [];
        }
    }
}

if (!function_exists('cut_string')) {
    function cut_string($string, $length, $string_end = '...')
    {
        if (strlen($string) <= $length) {
            return $string;
        } else {
            if (strpos($string, " ", $length) > $length) {
                $newLenght = strpos($string, " ", $length);
                $new_string = substr($string, 0, $newLenght) . $string_end;
                return $new_string;
            }
            $new_string = substr($string, 0, $length) . $string_end;
            return $new_string;
        }
    }
}

if (!function_exists('date_from_database')) {
    function date_from_database($time, $format = 'Y-m-d')
    {
        if (empty($time)) {
            return $time;
        }
        return format_time(Carbon::parse($time), $format);
    }
}


if (!function_exists('get_image_url')) {
    /**
     * @param $url
     * @param $size
     * @param bool $relative_path
     * @param null $default
     * @return mixed
     * @author Sang Nguyen
     */
    function get_image_url($url, $size = null, $relative_path = false, $default = null)
    {
        if (empty($url)) {
            return $default;
        }

        if (array_key_exists($size, config('media.sizes'))) {
            $url = str_replace(File::name($url) . '.' . File::extension($url), File::name($url) . '-' . config('media.sizes.' . $size) . '.' . File::extension($url), $url);
        }

        if ($relative_path) {
            return $url;
        }

        if ($url == '__image__') {
            return url($default);
        }

        return url($url);
    }
}


if (!function_exists('get_file_data')) {
    /**
     * @param $file
     * @param $convert_to_array
     * @return bool|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function get_file_data($file, $convert_to_array = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($convert_to_array) {
                return json_decode($file, true);
            } else {
                return $file;
            }
        }
        if (!$convert_to_array) {
            return null;
        }
        return [];
    }
}
if (!function_exists('json_encode_prettify')) {
    /**
     * @param $data
     * @return string
     */
    function json_encode_prettify($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
if (!function_exists('save_file_data')) {
    /**
     * @param $path
     * @param $data
     * @param $json
     * @return bool|mixed
     */
    function save_file_data($path, $data, $json = true)
    {
        try {
            if ($json) {
                $data = json_encode_prettify($data);
            }
            if (!File::isDirectory(dirname($path))) {
                File::makeDirectory(dirname($path), 493, true);
            }
            File::put($path, $data);

            return true;
        } catch (Exception $ex) {
            info($ex->getMessage());
            return false;
        }
    }
}


if (!function_exists('get_setting_email_template_content')) {
    /**
     * Get content of email template if module need to config email template
     * @param $template string type of module is system or plugins
     * @param $email_template_key string key is config in config.email.templates.$key
     * @return bool|mixed|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    function get_setting_email_template_content($template, $email_template_key)
    {
        $default_path = base_path('public/emails/' . $template . '/' . $email_template_key . '.tpl');
        $storage_path = get_setting_email_template_path($template, $email_template_key);

        if ($storage_path != null && File::exists($storage_path)) {
            return get_file_data($storage_path, false);
        }

        return File::exists($default_path) ? get_file_data($default_path, false) : '';
    }
}

if (!function_exists('get_setting_email_template_path')) {
    /**
     * Get user email template path in storage file
     * @param $template string
     * @param $email_template_key string key is config in config.email.templates.$key
     * @return string
     */
    function get_setting_email_template_path($template, $email_template_key)
    {
        return storage_path('app/emails/' . $template . '/' . $email_template_key . '.tpl');
    }
}

if (!function_exists('get_image_object')) {
    /**
     * @param $image
     * @param null $size
     * @param bool $relative_path
     * @return \Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    function get_image_object($image, $size = null, $relative_path = false)
    {
        if (!empty($image)) {
            if (empty($size) || $image == '__value__') {
                if ($relative_path) {
                    return $image;
                }
                return url($image);
            }
            return get_image_url($image, $size, $relative_path);
        }

        return get_image_url(config('media.default-img'), null, $relative_path);
    }
}

if (!function_exists('get_estate_gallery')) {
    /**
     * @param $estate_id
     * @param $id
     * @param string $size
     * @return mixed
     */
    function get_estate_gallery($estate_id, $id, $size = 'medium')
    {
        return app(EstateGalleryInterface::class)->getImageById($estate_id, $id, $size);
    }
}

if (!function_exists('get_image_post_object')) {
    /**
     * @param $id
     * @param string $size
     * @return mixed
     */
    function get_image_post_object($id, $size = 'medium')
    {
        return app(ArticleInterface::class)->getImageObject($id, $size);
    }
}


if (!function_exists('count_content_blocks')) {
    /**
     * @param $positions
     * @return mixed
     */
    function count_content_blocks($positions)
    {
        return app(ContentBlockInterface::class)->getModel()->countBlocks($positions);
    }
}

if (!function_exists('load_content_blocks')) {
    /**
     * @param $positions
     * @return mixed
     */
    function load_content_blocks($positions)
    {
        return app(ContentBlockInterface::class)->getModel()->loadBlocks($positions);
    }
}

if (!function_exists('get_featured_posts')) {
    /**
     * @param $limit
     * @return mixed
     *
     */
    function get_featured_posts($limit)
    {
        return app(ArticleInterface::class)->getFeatured($limit);
    }
}

if (!function_exists('get_category_children_by_root')) {
    function get_category_children_by_root($root)
    {
        return app(CategoryInterface::class)->getChildrenByRoot($root);
    }
}


if (!function_exists('get_districts_by_province')) {
    /**
     * @param $province_id
     * @return mixed
     */
    function get_districts_by_province($province_id)
    {
        return app(DistrictInterface::class)->getByProvinceId($province_id);
    }
}

if (!function_exists('count_has_district')) {
    /**
     * @param $district_id
     * @return mixed
     */
    function count_has_district($district_id)
    {
        return app(EstateInterface::class)->countByDistrict($district_id);
    }
}

if (!function_exists('box_tree_sidebar_category')) {
    function box_tree_sidebar_category($category_id)
    {
        $html = '<ul class="list-cate">';
        $category = app(CategoryInterface::class)->getSingleCategory($category_id);
        if ($category) {
            $districts = app(DistrictInterface::class)->getByProvinceId(1);
            if ($districts->count() > 0) {
                foreach ($districts as $district) {
                    if (count_has_district($district->id)) {
                        $html .= '<li>';
                        $html .= '<a href="' . route('category.district', [$category->title_alias, $district->slug_name,
                                $district->id]) . '" title="'
                            . $district->getFullName() . '">';
                        $html .= $category->title . ' ' . $district->getFullName();
                        $html .= '</a>';
                        $html .= '</li>';
                    }
                }
            }
        }
        $html .= '</ul>';
        return $html;
    }
}

if (!function_exists('get_utility_select')) {
    function get_utility_select()
    {
        return app(\App\Repositories\Utility\Interfaces\UtilityInterface::class)->getList([
            '' => 'Danh mục'
        ], []);
    }
}

if (!function_exists('get_utility_listing')) {
    function get_utility_listing()
    {
        return app(UtilityInterface::class)->getListing();
    }
}

if (!function_exists('get_setting_site')) {
    function get_setting_site()
    {
        return app(SettingInterface::class)->getSettingByName('site');
    }
}
if (!function_exists('preg_trim')){
    function preg_trim($subject)
    {
        $regex = "/\s*(\.*)\s*/s";
        if (preg_match($regex, $subject, $matches)) {
            $subject = $matches[1];
        }
        return $subject;
    }
}

if (!function_exists('formatPrice')){
    function formatPrice($price)
    {
        if (is_numeric( $price ) && floor( $price ) != $price) {
            return number_format($price, 2, ',', '.');
        }

        return number_format($price, 0, ',', '.');

    }
}
