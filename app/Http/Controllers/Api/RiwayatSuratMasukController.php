<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RiwayatSuratMasuk;
use App\Http\Resources\PostResource;



class RiwayatSuratMasukController extends Controller
{

    function show($id_surat){
        
    }
    function store(Request $request)
    {
        $dbRiwayat=new RiwayatSuratMasuk();

        $dbRiwayat->id_user=$request->id_user;
        $dbRiwayat->id_surat=$request->id_surat;
        $dbRiwayat->proses=$request->proses;

        if($dbRiwayat->save()){
            return new PostResource(true, 'Data inserted',[]);
        } 

    }
}
