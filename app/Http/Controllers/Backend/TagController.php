<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Riiingme\Tag\Repo\TagInterface;
use App\Riiingme\Riiinglink\Repo\RiiinglinkInterface;
use App\Riiingme\User\Repo\UserInterface;
use Illuminate\Http\Request;

class TagController extends Controller {

	protected $tag;
    protected $user;
	protected $riiinglink;

    public function __construct(UserInterface $user, TagInterface $tag,  RiiinglinkInterface $riiinglink){

        $this->user       = $user;
		$this->tag        = $tag;
		$this->riiinglink = $riiinglink;

	}

	/**
	 *
	 * @return Response
	 */
	public function allTags()
	{
		$tags = $this->tag->getAll(\Auth::user()->id);
		
		return \Response::json( $tags, 200 );
	}
	
	public function tags(Request $request)
	{
		$term = $request->input('term');
		$tags = $this->tag->searchByUser($term, \Auth::user()->id);
		
		return \Response::json( $tags, 200 );
	}
	
	public function addTag(Request $request)
	{
		$id   = $request->input('id');
		$tag  = $request->input('tag');
		$find = $this->tag->search($tag);
				
		// If tag not found	
		if(!$find)
		{
			$find = $this->tag->create(array('title' => $tag, 'user_id' => \Auth::user()->id));
		}

        $user       = $this->user->find(\Auth::user()->id);
        $user->user_tags()->attach($find->id);
		$riiinglink = $this->riiinglink->find($id)->first();

        $riiinglink->tags()->attach($find->id);
		
		return \Response::json( $find , 200 );
	}
		
	public function removeTag(Request $request)
	{
		$id   =  $request->input('id');
		$tag  =  $request->input('tag');

        $user       = $this->user->find(\Auth::user()->id);
		$find       = $this->tag->search($tag);
        $riiinglink = $this->riiinglink->find($id)->first();

        $riiinglink->tags()->detach($find->id);
        $user->user_tags()->detach($find->id);

        $this->cleanTags($find->id);
		
		return \Response::json( $riiinglink, 200 );
	}

    public function cleanTags($tag_id){

        $riiinglinks = $this->riiinglink->findByHost(\Auth::user()->id)->lists('id');
        $results     = $this->riiinglink->findTags($tag_id,$riiinglinks);

        if($results->isEmpty())
        {
            $this->tag->delete($tag_id);
        }
    }

}