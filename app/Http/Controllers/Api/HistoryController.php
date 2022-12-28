<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\revisi;
use Illuminate\Http\Request;


class HistoryController extends Controller
{
    function index(){
        // 
    }

    function store(){
        
    }

    function revisi(Request $request){
        $dbRevisi=new revisi();

        $dbRevisi->id_surat=$request->id_surat;
        $dbRevisi->id_user=$request->id_user;
        $dbRevisi->status=$request->status;
        $dbRevisi->catatan=$request->catatan;

        if($dbRevisi->save()){
            return new PostResource(true, 'Data inserted',[]);
        }   
    }

    function getHistory($id_surat){
        $data=revisi::where('id_surat', $id_surat)->get();
        return new PostResource(true, 'Data Revisi surat '.$id_surat, $data);
    }
}
