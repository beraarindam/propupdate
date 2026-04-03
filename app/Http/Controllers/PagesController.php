<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    public function about(): View
    {
        return view('frontend.about-us');
    }
}
