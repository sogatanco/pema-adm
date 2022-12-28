<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ViewSurat;
use App\Models\SignedLetter;
use App\Http\Resources\PostResource;

class ViewSuratController extends Controller
{
    function getReviewDireksi($id_direksi)
    {
        $data = ViewSurat::where('id_direksi', $id_direksi)
            ->where('ditandatangani_oleh', 1)
            ->where('status', 'approve_by_manager')
            ->get();
        return new PostResource(true, 'Surat Yang Harus Direview Direksi', $data);
    }

    function getOtherDirektorat($id_direksi)
    {
        $data = ViewSurat::where('id_direksi', '!=', $id_direksi)
            ->where('ditandatangani_oleh', 1)
            ->where('status', 'approve_by_direksi_terkait')
            ->get();
        return new PostResource(true, 'Surat dari direktorat lain', $data);
    }

    function getMustSignbyDireksi($id_direksi)
    {
        $data = ViewSurat::where('id_user_direksi', $id_direksi)
            ->where('status', 'approve_by_manager')
            ->get();
        return new PostResource(true, 'Surat yang harus ditandatangani', $data);
    }

    function getMustSignbyDirut($id_dirut)
    {
        $data = ViewSurat::where('id_user_direksi', $id_dirut)
            ->where('status', 'approve_by_direksi')
            ->get();
        return new PostResource(true, 'Surat yang harus ditandatangani', $data);
    }

    function getMustSignbyManager($id_manager)
    {
        $data = ViewSurat::where('id_user_direksi', $id_manager)
            ->where('status', 'submit')
            ->get();
        return new PostResource(true, 'Surat yang harus ditandatangani', $data);
    }

    function getSigned()
    {
        $data = SignedLetter::latest()->paginate(10);
        return new PostResource(true, 'Data surat yang sudah ditandatangani',  $data);
    }

    function getSignedByDivisi($id_divisi)
    {
        $data = SignedLetter::where('id_divisi', $id_divisi)
            ->latest()->get();
        return new PostResource(true, 'Data surat yang sudah ditandatangani',  $data);
    }

    function cari($request)
    {
        $data = SignedLetter::query()
            ->where('nomor_surat', 'LIKE', "%{$request}%")
            ->orWhere('kepada', 'LIKE', "%{$request}%")
            ->orWhere('perihal', 'LIKE', "%{$request}%")
            ->latest()
            ->paginate(10);
        return new PostResource(true, 'Hasil Pencarian dari ' . $request, $data);
    }

    function getSuratByDirektorat($id_direktorat){
        $data = SignedLetter::where('id_direktorat', $id_direktorat)
            ->latest()->get();

         return new PostResource(true, 'direktorat', $data);
    }
}
