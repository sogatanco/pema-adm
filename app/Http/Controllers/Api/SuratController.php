<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostResource;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    function index()
    {
        // 
    }

    function show(Surat $surat)
    {
        return new PostResource(true, 'Dat Surat', $surat);
    }

    function getLast()
    {
        $data = Surat::latest()->first()->id;

        return new PostResource(true, 'List Data Signer', $data);
    }

    function store(Request $request)
    {
        // return new PostResource(true, 'List Data Signer', $request->all());

        $surat = base64_decode($request->file_surat, true);

        if (Storage::disk('public_uploads')->put($request->id_user . '-surat.pdf', $surat)) {
            $lampiran = base64_decode($request->file_lampiran, true);
            $filename = 'surats/' . $request->id . '.pdf';
            $pdf = new \Clegginabox\PDFMerger\PDFMerger;
            if ($lampiran) {
                Storage::disk('public_uploads')->put('lampiran/' . $request->id . '.pdf', $lampiran);
                $pdf->addPDF('uploads/' . $request->id_user . '-surat.pdf', 'all', 'P');
                $pdf->addPDF('uploads/lampiran/' . $request->id . '.pdf', 'all', 'P');
            } else {
                $pdf->addPDF('uploads/' . $request->id_user . '-surat.pdf', 'all', 'P');
            }
            if ($pdf->merge('file', $filename, 'P')) {
                $dbsurat = new Surat();

                $dbsurat->id = $request->id;
                $dbsurat->nomor_surat = $request->nomorSurat;
                $dbsurat->kepada = $request->kepada;
                $dbsurat->perihal = $request->perihal;
                $dbsurat->tembusan = $request->tembusan;
                $dbsurat->body_surat = $request->body;
                $dbsurat->url_surat = $filename;
                $dbsurat->id_user = $request->id_user;
                $dbsurat->id_divisi = $request->id_divisi;
                $dbsurat->status = 'submit';
                $dbsurat->ditandatangani_oleh = $request->ditandatanganiOleh;
                $dbsurat->jml_lampiran = $request->jml_lampiran;

                // return new PostResource(true, 'Data inserted',$dbsurat);  
                if ($dbsurat->save()) {
                    return new PostResource(true, 'Data inserted', $request->all());
                }
            }
        }
    }


    function getSubmitted($id_user)
    {
        $data = Surat::where('id_user', $id_user)->where('status','!=', 'signed')->latest()->paginate(10);
        return new PostResource(true, 'Submitted Surat', $data);
    }

    function edit(Request $request, Surat $surat)
    {
        $surat = base64_decode($request->file_surat, true);
        if (Storage::disk('public_uploads')->put($request->id_user . '-surat.pdf', $surat)) {
            $filename = 'surats/' . $request->id . '.pdf';
            $pdf = new \Clegginabox\PDFMerger\PDFMerger;
            $pdf->addPDF('uploads/' . $request->id_user . '-surat.pdf', 'all', 'P');
            
            if ($request->jml_lampiran > 0) {  
                if($request->file_lampiran){
                    $lampiran = base64_decode($request->file_lampiran, true);
                    Storage::disk('public_uploads')->put('lampiran/' . $request->id . '.pdf', $lampiran);
                    $pdf->addPDF('uploads/lampiran/' . $request->id . '.pdf', 'all', 'P');
                }else{
                    $pdf->addPDF('uploads/lampiran/' . $request->id . '.pdf', 'all', 'P');
                }    
            }

            if ($pdf->merge('file', $filename, 'P')){

                // return new PostResource(true, 'Data inserted',  $request->nomorSurat);

                $update=DB::table('surats')->where('id', $request->id)->update([
                    'nomor_surat'=>$request->nomorSurat,
                    'kepada'=>$request->kepada,
                    'perihal'=>$request->perihal,
                    'tembusan'=>$request->tembusan,
                    'body_surat'=>$request->body,
                    'url_surat'=>$filename,
                    'id_user'=>$request->id_user,
                    'id_divisi'=>$request->id_divisi,
                    'status'=>$request->status,
                    'ditandatangani_oleh'=>$request->ditandatanganiOleh,
                    'jml_lampiran'=>$request->jml_lampiran,
                ]);

                if($update){
                    return new PostResource(true, 'Data inserted', []);
                }else{
                    return new PostResource(true, 'Gagal', []);
                }

            }

        }
    }

    function getSuratDetail($id_surat)
    {
        $data=Surat::where('id', $id_surat)->first();
        return new PostResource(true, 'Detail', $data);
    }

    // manager
    function getReview($id_divisi)
    {
        $data = Surat::where('id_divisi', $id_divisi)->where('status','=', 'submit')
        ->where('ditandatangani_oleh', '<', 4)
        ->get();
        return new PostResource(true, 'Must Be Review', $data);
    }

    function updateStatus(Request $request){
        $update=DB::table('surats')->where('id', $request->id_surat)->update([
            'status'=>$request->status
        ]);

        if($update){
            return new PostResource(true, 'Data inserted', []);
        }else{
            return new PostResource(true, 'Gagal', []);
        }

    }

    function approveByManager($id_divisi){
        $data=Surat::where('id_divisi', $id_divisi)
        ->where('status', '=', 'approve_by_manager')
        ->orWhere('status', '=', 'reject_by_direksi')
        ->orWhere('status', '=', 'reject_by_direksi_terkait')
        ->orWhere('status', '=', 'approve_by_direksi_terkait')
        ->orWhere('status', '=', 'approve_by_direksi')
        ->orWhere('status', '=', 'reject_by_dirut')
        ->latest()
        ->paginate(10);

        return new PostResource(true, 'Surat yang sudah di approve manager', $data);

    }

   
    function sign(Request $request){
        $update=DB::table('surats')->where('id', $request->id_surat)->update([
            'status'=>$request->status,
            'ditandatangani'=>Carbon::now() 
        ]);

        if($update){
            return new PostResource(true, 'Data inserted', []);
        }else{
            return new PostResource(true, 'Gagal', []);
        }
        
    }

    




}
