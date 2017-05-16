<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Email\Repo\EmailInterface;

class EmailController extends Controller
{
    protected $email;

    public function __construct(EmailInterface $email)
    {
        $this->email = $email;
    }

    public function index(Request $request)
    {
        $emails = $this->email->getAll(array_filter($request->except('_token','page')));

        return view('backend.email.index')->with(['emails' => $emails]);
    }

    public function show($id)
    {
        $email = $this->email->find($id);

        return [
            'to'       => $email->to ,
            'date'     => $email->date,
            'subject'  => $email->subject,
            'body'     => $email->body_clean
        ];
    }
}
