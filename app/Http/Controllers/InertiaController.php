<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class InertiaController extends Controller
{
    public function app(): Response
    {
        return Inertia::render('App');
    }
}
