<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Estate;
use App\Models\EstateType;

class SiteMapController extends Controller
{
    public function siteMapMaster()
    {
        return response()->view('frontend.sitemaps.sitemap_master')->header('Content-Type', 'text/xml');
    }

    public function siteMap()
    {
        $categories = Category::where('public', 1)->get();
        $products = Estate::where('public', 1)->get();
        $articles = Article::where('public', 1)->get();
        return response()->view('frontend.sitemaps.sitemap_default', compact('articles', 'categories', 'products'))->header('Content-Type', 'text/xml');
    }

    public function siteMapMobile()
    {
        $categories = Category::where('public', 1)->get();
        $products = Estate::where('public', 1)->get();
        $articles = Article::where('public', 1)->get();
        $types = EstateType::all();
        return response()->view('frontend.sitemaps.sitemap_mobile', compact('articles', 'categories', 'products', 'types'))->header('Content-Type', 'text/xml');
    }

    public function siteMapImage()
    {
        return response()->view('frontend.sitemaps.sitemap_image')->header('Content-Type', 'text/xml');
    }
}
