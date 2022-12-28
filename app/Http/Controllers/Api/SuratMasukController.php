<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostResource;
use App\Models\SuratMasuk;
use App\Models\SMasuk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class SuratMasukController extends Controller
{
    function index()
    {
        $data=SuratMasuk::latest()->paginate(10);

        return new PostResource(true, 'List Data Surat Masuk', $data);
    }

    function store(Request $request)
    {
        $name='suratmasuk/'.date_timestamp_get(date_create()).'.pdf';
        $surat =base64_decode($request->base64file, true);
        if (Storage::disk('public_uploads')->put($name , $surat)){
            $suratmasuk=new SMasuk();
            $suratmasuk->nomor=$request->nomor;
            $suratmasuk->pengirim=$request->pengirim;
            $suratmasuk->perihal=$request->perihal;
            $suratmasuk->id_direksi=$request->direksi;
            $suratmasuk->file=$name;
            $suratmasuk->via=$request->via;
            $suratmasuk->tgl_surat=$request->tanggal;
            
            if($suratmasuk->save()){
                return new PostResource(true, $suratmasuk->id, []);
            }else{
                return new PostResource(false, 'Data gagal ditambahkan', []);
            }
            
        }else{
            return new PostResource(false, 'Tolong periksa kembali file suratnya !', []);
        }
    }

    function show($request)
    {
        $data=SuratMasuk::query()
             ->where('nomor', 'LIKE', "%{$request}%")
            ->orWhere('pengirim', 'LIKE', "%{$request}%")
            ->orWhere('perihal', 'LIKE', "%{$request}%")
            ->latest()
            ->paginate(10);
            return new PostResource(true, 'Hasil Pencarian dari '.$request, $data);
    }

    function getDireksi($id_direksi){
        $data=SuratMasuk::where('id_direksi', $id_direksi)->whereNull('disposisi')->latest()->get();
        return new PostResource(true, 'Surat Masuk', $data);
    }

    function getManager($id_manager){
        $data=SuratMasuk::where('disposisi', $id_manager)->latest()->get();
        return new PostResource(true, 'Surat Masuk', $data);
    }

    function disposisi(Request $request){
        $update=DB::table('surat_masuks')->where('id', $request->id)->update([
            'disposisi'=>$request->disposisi,
            'tickler'=>$request->tickler,
            'catatan'=>$request->catatan
        ]);
        if($update){
            return new PostResource(true, 'Data inserted', []);
        }else{
            return new PostResource(true, 'Gagal', []);
        }
    }
}
