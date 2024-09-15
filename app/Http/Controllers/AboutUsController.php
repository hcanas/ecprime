<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AboutUsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('AboutUs');
    }
}
