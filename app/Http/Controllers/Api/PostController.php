<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Resources\PostResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        //get posts
        $posts = Post::latest()->paginate(5);

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Posts', $posts);
    }

}