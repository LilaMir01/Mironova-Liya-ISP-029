<?php

namespace App\Http\Controllers;

use App\Models\HomeSlide;

class HomeController extends Controller
{
    public function index()
    {
        $homeSlides = HomeSlide::orderedForHome();

        return view('welcome', compact('homeSlides'));
    }
}
