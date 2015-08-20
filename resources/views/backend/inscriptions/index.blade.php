@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/inscription/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>


        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4><i class="fa fa-tasks"></i> &nbsp;Inscriptions</h4>
            </div>

            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table" style="margin-bottom: 0px;" id="generic">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-2">No</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

             {{--               @if(!empty($inscriptions))
                                @foreach($inscriptions as $inscription)
                                    <tr>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/page/'.$inscription->id) }}">&Eacute;diter</a></td>
                                        <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">
                                                {!! csrf_field() !!}
                                                <button data-action="no: {{ $inscription->inscription_no }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif--}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@stop