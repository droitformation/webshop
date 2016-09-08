<?php namespace App\Http\Controllers\Backend\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Droit\Adresse\Repo\AdresseInterface;
use App\Droit\Member\Repo\MemberInterface;

class MemberController extends Controller {

    protected $member;
    protected $adresse;

    public function __construct(MemberInterface $member, AdresseInterface $adresse)
    {
        $this->member   = $member;
        $this->adresse  = $adresse;
	}

	/**
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $data = [];

		$members = $this->member->getAll();

        if(!$members->isEmpty())
        {
            foreach($members as $result)
            {
                $data[] = $result->title;
            }
        }

        if($request->ajax())
        {
            return response()->json( $data, 200 );
        }

        return view('backend.members.index')->with(['members' => $members]);
	}

    public function create()
    {
        return view('backend.members.create');
    }

    public function show($id)
    {
        $member = $this->member->find($id);

        return view('backend.members.show')->with(['member' => $member]);
    }

    public function search(Request $request)
    {
        $data = [];
        $term = $request->input('term');

        $member = $this->member->search($term,true);

        if(!$member->isEmpty())
        {
            foreach($member as $result)
            {
                $data[] = ['label' => $result->title, 'value' => $result->id];
            }
        }

        if($request->ajax())
        {
            return response()->json( $data, 200 );
        }
    }
	
	public function store(Request $request)
	{
        if($request->ajax())
        {
            $id     = $request->input('id');
            $member = $request->input('member');
            $find   = $this->member->search($member);

            $adresse = $this->adresse->find($id);
            $adresse->members()->attach($find->id);

            return response()->json( $find , 200 );
        }

        $member = $this->member->create($request->all());

        alert()->success('Membre crée');

        return redirect('admin/member');
	}
		
	public function destroy(Request $request)
	{
        $id = $request->input('id');

        if($request->ajax())
        {
            $member = $request->input('member');
            $find   = $this->member->search($member);

            $adresse   = $this->adresse->find($id);
            $adresse->members()->detach($find->id);

            return response()->json( $member, 200 );
        }

        $this->member->delete($id);

        alert()->success('Membre supprimé');

        return redirect('admin/member');
	}
}