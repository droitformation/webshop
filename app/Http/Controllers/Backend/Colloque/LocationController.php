<?php

namespace App\Http\Controllers\Backend\Colloque;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Requests\CreateLocationRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Droit\Location\Repo\LocationInterface;
use App\Droit\Service\UploadInterface;

class LocationController extends Controller
{
    protected $location;
    protected $upload;

    public function __construct(LocationInterface $location, UploadInterface $upload)
    {
        $this->location = $location;
        $this->upload   = $upload;
    }

    public function index()
    {
        $locations = $this->location->getAll();

        return view('backend.locations.index')->with(['locations' => $locations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLocationRequest $request)
    {
        $data = $request->except('file');
        $file = $this->upload->upload( $request->file('file') , 'files/colloques/cartes' , 'map');

        $data['map'] = $file['name'];

        $location = $this->location->create($data);

        return redirect('admin/location/'.$location->id)->with(['status' => 'success' , 'message' => 'Lieu crée']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $location = $this->location->find($id);

        if($request->ajax())
        {
            return response()->json( $location, 200 );
        }

        return view('backend.locations.show')->with(['location' => $location]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request, $id)
    {
        $data  = $request->except('file');
        $_file = $request->file('file',null);

        if($_file)
        {
            $file = $this->upload->upload( $request->file('file') , 'files/colloques/cartes' , 'map');
            $data['map'] = $file['name'];
        }

        $location = $this->location->update($data);

        return redirect('admin/location/'.$location->id)->with(['status' => 'success' , 'message' => 'Lieu mis à jour']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->location->delete($id);

        return redirect()->back()->with(['status' => 'success', 'message' => 'Lieu supprimée']);
    }

}
