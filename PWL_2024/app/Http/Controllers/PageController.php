<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        return 'Selamat Datang';
    }

    public function about() {
        return 'Nim : 2341720184 <br> Nama : Gabriel Batavia Xaverius';
    }

    public function articles($id) {
        return 'Halaman Artikel dengan ID '.$id;
    }
}
