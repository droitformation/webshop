<div class="flex flex-row justify-end filter-inscriptions">

    <form class="block" action="{{ url('admin/inscription/colloque/'.$colloque->id) }}" method="post">{!! csrf_field() !!}
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Recherche par numéro ou prénom/nom...">
            <span class="input-group-btn">
                <button class="btn btn-info" type="submit"><i class="fa fa-search"></i></button>
            </span>
        </div>
    </form>

    <a class="btn btn-default block {{ $current == '' ? 'btn-inverse' : '' }}" style="margin-left: 1px;" href="{{ url('admin/inscription/colloque/'.$colloque->id) }}">Tous</a>

    @foreach(['free' => 'Gratuit','payed' => 'Payés','pending' => 'Non payés'] as $status => $name)
        <form action="{{ url('admin/inscription/colloque/'.$colloque->id) }}" method="post" style="margin-left: 1px;" class="block">{!! csrf_field() !!}
            <input name="status" value="{{ $status }}" type="hidden">
            <button type="submit" class="btn btn-default {{ $current == $status ? 'btn-inverse' : '' }}">{{ $name }}</button>
        </form>
    @endforeach
</div>