<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function index()
    {
        // 'home' değil, 'welcome' dosyasını göster
        return view('welcome');
    }
}

