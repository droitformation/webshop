<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Site\Repo\SiteInterface;
use App\Http\Requests\SendMessage;

class ContactController extends Controller
{

    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
        $this->middleware('honeypot');
    }

    /**
     * Send contact message
     *
     * @return Response
     */
    public function sendMessage(SendMessage $request)
    {
        $site = $this->site->find($request->input('site'));

        $data = [
            'email'    => $request->input('email'),
            'name'     => $request->input('name'),
            'remarque' => $request->input('remarque'),
            'site'     => $site
        ];

        \Mail::send('emails.contact', $data, function ($message) use ($data,$site) {

            $message->from($data['email'], $data['name']);
            $message->to('droit.formation@unine.ch')->subject('Message depuis le site '.$site->nom.'');
        });

        $request->session()->flash('ContactConfirmation', 'Ok');

        return redirect($site->slug);

    }
}
