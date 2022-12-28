<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\DetailUser;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
   
    function index()
    {
        // $posts = DetailUser::latest()->paginate(5);

        // //return collection of posts as a resource
        // return new PostResource(true, 'List Data Posts', $posts);
    }

    function show($request)
    {
        $detail=DetailUser::where('id_user',$request)->get();
        return new PostResource(true, 'berhasil', $detail );
    }

    function me(Request $request)
    {     
        return new PostResource(true, 'success',  [$request->user()]);       
    }


// function login
    function store(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $user=User::where('email', $request->email)->first();
        $ip = $_SERVER['REMOTE_ADDR'];

        if(!$user || !Hash::check($request->password, $user->password)){
            return new PostResource(false, 'failed', []);
        }

        return new PostResource(true, 'success', ['token'=>$user->createToken($ip,  [$user->level])->plainTextToken, 'level'=>$user->level]);      
    }


    // logout
    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return new PostResource(true, 'success', []);
    }

    function changePass(Request $request)
    {
        $update=User::whereId(auth()->user()->id)->update([
            'password'=>Hash::make($request->new_pass)
        ]);

        if($update){
            return new PostResource(true, 'success', []); 
        }else{
            return new PostResource(false, 'Failde', []); 
        }
    }

    
}
