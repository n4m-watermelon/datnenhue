<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CacheTool;
use Artisan;

class CacheToolController extends Controller
{
    /**
     * Clear view and cache folder
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearAll()
    {
        Artisan::call('cache:clear');
        CacheTool::clearAll();
        return redirect()->back()->with('status', 'Đã xóa tất cả bộ nhớ đệm ứng dụng và giao diện thành công !');
    }

    /**
     * Clear cache folder
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearApp()
    {
        Artisan::call('cache:clear');
        CacheTool::clearApp();
        return redirect()->back()->with('status', 'Đã xóa bộ nhớ đệm ứng dụng thành công !');
    }

    /**
     * Clear view folder
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearView()
    {
        Artisan::call('cache:clear');
        CacheTool::clearView();
        return redirect()->back()->with('status', 'Đã xóa bộ nhớ đệm giao diện thành công !');
    }
}
