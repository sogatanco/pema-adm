<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostResource;
use App\Models\Signer;
use App\Models\Manager;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class SignerController extends Controller
{
    function index()
    {
        $data=Signer::all();

        return new PostResource(true, 'List Data Signer', $data);
    }

    function show($id_divisi)
    {
        $data=Signer::where('id_divisi', 0)->orWhere('id_divisi', $id_divisi)->get();
        return new PostResource(true, 'List Data Signer', $data);
    }

    function getDireksi()
    {
        $data=Signer::where('id_divisi', 0)->get();
        return new PostResource(true, 'List Direksi', $data);
    }

    function getManager()
    {
        $data=Manager::all();
        return new PostResource(true, 'List Data Manager', $data);
    }
}
