<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeeperController extends Controller
{

    public function index()
    {
        return view('keepers.index');
    }
}
