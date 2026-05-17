<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Auth\Services\GoogleService;

class GoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function redirect(GoogleService $service)
    {
        return $service->redirect();
    }

    public function callback(GoogleService $service)
    {
        return $service->callback();
    }
}
