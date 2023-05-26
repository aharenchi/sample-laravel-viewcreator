<?php

namespace App\Http\Controllers\Root;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

final class RootIndexGetController extends Controller
{
    public function __construct()
    {
    }

    public function __invoke(Request $request): View
    {
        return view('pages.index');
    }
}
