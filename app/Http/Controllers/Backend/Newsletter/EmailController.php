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
            alert()->warning('Cet email est déjà dans la liste');
            return redirect()->back();
        }

        $email = $this->emails->create($request->all());

        alert()->success('Email ajouté');

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

        alert()->success('Email mis à jour');

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

        alert()->success('Email supprimée de la liste');

        return redirect()->back();
    }
}
