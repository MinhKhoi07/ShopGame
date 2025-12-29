<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display news/announcements
     */
    public function index()
    {
        // Sử dụng Banner làm nguồn tin tức
        $news = Banner::active()
            ->byType('news')
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('news.index', compact('news'));
    }
}
