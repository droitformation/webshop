<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Newsletter\Repo\NewsletterListInterface;
use App\Droit\Newsletter\Repo\NewsletterEmailInterface;
use App\Http\Requests\EmailRequest;

class EmailController extends Controller
{
    protected $list;
    protected $emails;

    public function __construct( NewsletterListInterface $list, NewsletterEmailInterface $emails)
    {
        $this->list     = $list;
        $this->emails   = $emails;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        view()->share('isNewsletter',true);
    }

    public function store(EmailRequest $request)
    {
        $exist = $this->list->emailExist($request->input('list_id'),$request->input('email'));

        if(!$exist->isEmpty()){
            flash('Cet email est déjà dans la liste')->warning();
            return redirect()->back();
        }

        $email = $this->emails->create($request->all());

        flash('Email ajouté')->success();

        return redirect('build/liste/'.$email->list_id);
    }

    /**
     * Update the specified resource in storage.
     * PUT /compte/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id,EmailRequest $request)
    {
        $email = $this->emails->update( $request->all() );

        flash('Email mis à jour')->success();

        return redirect('build/liste/'.$email->list_id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /list
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->emails->delete($id);

        flash('Email supprimée de la liste')->success();

        return redirect()->back();
    }
}
