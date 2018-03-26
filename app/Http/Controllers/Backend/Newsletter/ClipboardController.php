<?php

namespace App\Http\Controllers\Backend\Newsletter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Http\Requests\CopyRequest;
use App\Http\Requests\PasteRequest;
use App\Droit\Newsletter\Repo\NewsletterClipboardInterface;
use App\Droit\Newsletter\Repo\NewsletterContentInterface;

class ClipboardController extends Controller
{
    protected $clipboard;
    protected $content;

    public function __construct(NewsletterClipboardInterface $clipboard, NewsletterContentInterface $content)
    {
        $this->clipboard = $clipboard;
        $this->content   = $content;
    }

    public function copy(CopyRequest $request)
    {
        $this->clipboard->create($request->all());

        alert()->success('Contenu copié dans le presse papier');

        return redirect()->back();
    }

    public function paste(PasteRequest $request)
    {
        $copy      = $this->clipboard->find($request->input('id'));
        $content   = $this->content->find($copy->content_id);
        $replicate = $content->replicate();

        $replicate->newsletter_campagne_id = $request->input('campagne_id');
        $replicate->rang = !empty($request->input('rang')) ? $request->input('rang') + 1 : 0;
        $replicate->save();

        $copy->delete();

        alert()->success('Contenu collé dans la campagne');

        return redirect()->back();
    }
}
