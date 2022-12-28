<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Env;
use App\Http\Resources\PostResource;

class EnvController extends Controller
{
    function index(){
        

        // return new PostResource(true,'sdgsdg', []);
    }

    function show($request)
    {
        $posts = Env::where('name',$request)->get();

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts',$posts );
    }


}
