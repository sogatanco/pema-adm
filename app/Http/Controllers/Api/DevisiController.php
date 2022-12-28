<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Divisi;
use App\Http\Resources\PostResource;

class DevisiController extends Controller
{
    function index()
    {
        $data= Divisi::all();
        return new PostResource(true, 'sdgdsg', $data);
    }
}
