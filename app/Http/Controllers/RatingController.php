<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    //
    public function postRating(Request $request)
    {
        if ($request->ajax()) {
            $controller = $request->get('controller');
            $data_id = $request->get('data_id');
            $score = $request->get('score');
            switch ($controller) {
                case 'Category' :
                    $category =  Category::find($data_id);
                    if ($category){
                        $rating = new Rating;
                        $rating->rating  = $score;
                        $category->ratings()->save($rating);
                        return response()->json([
                            'msg'    => 'Cảm ơn bạn đã bình chọn!',
                            'status' => 200
                        ],200);
                    }else{
                        return response()->json([
                            'data'   => '',
                            'status' => 400,
                            'msg'    => 'Đã có lỗi xảy ra, vui lòng thử lại sau'
                        ],400);
                    }
                    break;
                case 'Article':

                    break;
                case 'Estate':

                    break;
                default:
                    break;
            }
        }
    }
}
