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
		$members = $this->member->getAll();

        if($request->ajax()) {
            return response()->json( $members->pluck('title')->all(), 200 );
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
        $members = $this->member->search($request->input('term'),true);
        
        $data = $members->map(function ($member, $key) {
            return ['label' => $member->title, 'value' => $member->id];
        })->all();

        if($request->ajax()) {
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

        flash('Membre crée')->success();

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

        flash('Membre supprimé')->success();

        return redirect('admin/member');
	}
}