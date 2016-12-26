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
        $data = $request->all();

        $period['start'] = (isset($data['start']) ? \Carbon\Carbon::parse($data['start']) : null);
        $period['end']   = (isset($data['end'])   ? \Carbon\Carbon::parse($data['end'])   : null);
        
        $emails = $this->email->getAll($period);

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
