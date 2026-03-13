<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    public function index(string $locale = 'en')
    {
        return view('landing');
    }
}
