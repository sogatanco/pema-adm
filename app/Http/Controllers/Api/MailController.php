<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use App\Mail\MyTestMail;
use App\Models\Manager;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index(Request $request)
    {

        $details = [
            'title' => $request->title,
            'body' => $request->body,
            'subject' => $request->subject,
            'link' => $request->link,
        ];


        if (Mail::to($request->email)->send(new MyTestMail($details))) {
            return 'email sudah terkirim';
        } else {
            return 'pap lemumo';
        }
    }

    function getMailManager($id_divisi)
    {
        $data=Manager::where('id_divisi', $id_divisi)->first();
        return new PostResource(true, 'Email', $data);
    }
}
