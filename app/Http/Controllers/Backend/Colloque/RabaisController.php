<?php namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Droit\Inscription\Repo\RabaisInterface;
use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\User\Repo\UserInterface;

class RabaisController extends Controller
{
    protected $rabais;
    protected $colloque;
    protected $user;

    public function __construct( RabaisInterface $rabais, ColloqueInterface $colloque, UserInterface $user)
    {
        $this->rabais   = $rabais;
        $this->colloque = $colloque;
        $this->user     = $user;
    }

    public function index(Request $request)
    {
        $rabais  = $this->rabais->getAll();

        $data = $rabais->map(function ($item, $key) {
            return $item->title;
        })->all();

        if($request->ajax()) {
            return response()->json( $data, 200 );
        }

        return view('backend.rabais.index')->with(['rabais' => $rabais]);
    }
    
    public function create()
    {
        $colloques = $this->colloque->getCurrent(true,false);

        return view('backend.rabais.create')->with(['colloques' => $colloques]);
    }

    public function store(Request $request)
    {
        $rabai = $this->rabais->create( $request->all() );

        flash('Rabais crée')->success();

        return redirect('admin/rabais/'.$rabai->id);
    }
    
    public function show($id)
    {
        $colloques = $this->colloque->getCurrent(true,false);
        $rabais    = $this->rabais->find($id);

        return view('backend.rabais.show')->with(['rabais' => $rabais, 'colloques' => $colloques]);
    }

    public function update($id,Request $request)
    {
        $this->rabais->update( $request->all() );

        flash('Rabais mise à jour')->success();

        return redirect('admin/rabais/'.$id);
    }


    public function destroy($id)
    {
        $this->rabais->delete($id);

        flash('Rabais supprimé')->success();

        return redirect()->back();
    }

    public function search($user_id,$colloque_id)
    {
       /* $result = $this->rabais->search($term);
        $rabais = $result ? $result->id : null;

        $valid   = $result ? true : false;
        $message = $result ? 'Valide' : 'Ce rabais n\'est pas valide';
        $value   = $result->value.' CHF de moins';

        if($result && $result->type == 'colloque' && !$result->colloques->isEmpty()){
            $valid   = $result->colloques->contains('id', $colloque_id) ? true : false;
            $message = !$valid ? 'Ce rabais n\'est pas valide pour ce colloque' : $message;
        }*/

        //return response()->json(['value' => $value, 'rabais' => $rabais]);
    }

    public function all(Request $request)
    {
        $user = $this->user->find($request->input('id'));

        $rabais = $this->rabais->getAll();
        $data   = $rabais->reject(function ($value, $key) use($user){
            return $user->used_rabais->contains('title',$value->title);
        })->map(function ($item, $key) {
            return $item->title;
        })->all();

        return response()->json( $data, 200 );
    }

    public function has(Request $request)
    {
        $user = $this->user->find($request->input('id'));
        $has  = $user->used_rabais->contains('title',$request->input('title'));

        $result = $has ? 'A déjà obtenu ce rabais' : null;

        return response()->json(['result' => $result ? true : null, 'message' => $result], 200 );
    }

    public function add(Request $request)
    {
        $user = $this->user->find($request->input('id'));

        $exist = $this->rabais->search($request->input('title'));
        $find  = $exist ? $exist : $this->rabais->create(['title' => $request->input('title')]);
        $has   = $user->used_rabais->contains('title',$request->input('title'));

        if($exist && !$has){
            $user->rabais()->attach($find->id);

            return response()->json(['result' => null], 200 );
        }

        return response()->json(['result' => false], 200 );
    }

    public function remove(Request $request)
    {
        $user = $this->user->find($request->input('id'));
        $find = $this->rabais->search($request->input('title'));

        if($find){
            $user->rabais()->detach($find->id);
            return response()->json( 'ok', 200 );
        }

        return response()->json( 'ok', 200 );
    }
}
