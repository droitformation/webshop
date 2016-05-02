<li id="search">
    <a href="javascript:;"><i class="fa fa-search opacity-control"></i></a>
    <form action="{{ url('admin/search/user') }}" method="post">{!! csrf_field() !!}
        <input type="text" class="search-query" name="term" placeholder="Recherche...">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>
</li>