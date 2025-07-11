<?php

namespace App\Http\Controllers\kepala;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KepalaController extends Controller
{
    public function index()
    {
        return Inertia::render('kepala/Index');
    }
}
