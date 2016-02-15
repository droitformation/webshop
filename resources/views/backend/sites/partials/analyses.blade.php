<div class="panel panel-success">
    <div class="panel-body">
        <h4 class="text-success"><i class="fa fa-shopping-cart"></i> &nbsp;Dernières analyses</h4>
        <table class="table">
            <thead>
            <tr>
                <th class="col-sm-1">Action</th>
                <th class="col-sm-3">Auteur</th>
                <th class="col-sm-3">Date de publication</th>
                <th class="col-sm-4">Résumé</th>
                <th class="col-sm-1"></th>
            </tr>
            </thead>
            <tbody class="selects">
                @if(!empty($analyses))
                    @foreach($analyses as $analyse)
                        <tr>
                            <td><a class="btn btn-sky btn-sm" href="{{ url('admin/analyse/'.$analyse->id) }}">éditer</a></td>
                            <td>
                                @if(isset($analyse->analyse_authors))
                                    @foreach($analyse->analyse_authors as $analyse_authors)
                                        <p><strong>{{ $analyse_authors->name }}</strong></p>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $analyse->pub_date->formatLocalized('%d %B %Y') }}</td>
                            <td>{{ $analyse->abstract }}</td>
                            <td class="text-right">
                                <form action="{{ url('admin/analyse/'.$analyse->id) }}" method="POST" class="form-horizontal">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <button data-what="supprimer" data-action="analyse" class="btn btn-danger btn-sm deleteAction">x</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>