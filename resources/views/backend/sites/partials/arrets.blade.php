<div class="panel panel-info">
    <div class="panel-body">
        <h4 class="text-info"><i class="fa fa-table"></i> &nbsp;Derniers arrêts</h4>
        <table class="table">
            <thead>
            <tr>
                <th class="col-sm-1">Action</th>
                <th class="col-sm-2">Référence</th>
                <th class="col-sm-2">Date de publication</th>
                <th class="col-sm-6">Résumé</th>
                <th class="col-sm-1 no-sort"></th>
            </tr>
            </thead>
            <tbody class="selects">
                @if(!$arrets->isEmpty())
                    @foreach($arrets as $arret)
                        <tr>
                            <td><a class="btn btn-sky btn-sm" href="{{ url('admin/arret/'.$arret->id) }}">éditer</a></td>
                            <td><strong>{{ $arret->reference }}</strong></td>
                            <td>{{ $arret->pub_date->formatLocalized('%d %B %Y') }}</td>
                            <td>{{ $arret->abstract }}</td>
                            <td class="text-right">
                                <form action="{{ url('admin/arret/'.$arret->id) }}" method="POST" class="form-horizontal">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <button data-what="supprimer" data-action="arrêt {{ $arret->reference }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>