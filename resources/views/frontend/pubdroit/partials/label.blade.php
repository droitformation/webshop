<ul class="nav">
    <li><a href="{{ url('categorie/5') }}"><i class="fa fa-star"></i> &nbsp; Nouveautés</a></li>
    @if(!$domains->isEmpty())
        <li>
            <form action="{{ url('sort')}}" method="post">{!! csrf_field() !!}
                <input type="hidden" name="title" value="Domaine">
                <input type="hidden" name="label" value="domain">
                <select name="search[domain_id]" class="dropdow-select">
                    <option value="">Collections</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain->id }}">{{ $domain->title }}</option>
                    @endforeach
                </select>
            </form>
        </li>
    @endif
    @if(!$categories->isEmpty())
        <li>
            <form action="{{ url('sort')}}" method="post">{!! csrf_field() !!}
                <input type="hidden" name="title" value="Catégorie">
                <input type="hidden" name="label" value="categorie">
                <select name="search[categorie_id]" class="dropdow-select">
                    <option value="">Thèmes</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}">{{ $categorie->title }}</option>
                    @endforeach
                </select>
            </form>
        </li>
    @endif
    @if(!$authors->isEmpty())
        <li>
            <form action="{{ url('sort')}}" method="post">{!! csrf_field() !!}
                <input type="hidden" name="title" value="Auteur">
                <input type="hidden" name="label" value="author">
                <select name="search[author_id]" class="dropdow-select">
                    <option value="">Auteurs</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            </form>
        </li>
    @endif
</ul>