<div class="widget clear">
    <h3 class="title">Articles de doctrine </h3>

    <div class="filtre">
        <h6>Par catégorie</h6>
        <div class="list categories clear selectList">
            <select style="width: 230px;" id="seminaire-chosen" class="seminaire-chosen chosen-select category" multiple data-placeholder="Filtrer par catégorie..." name="filter">
                @if(!empty($order))
                    @foreach($order as $key => $categorie)
                        <option value="c{{ $key }}">{{ ucfirst($categorie) }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <h6>Par année</h6>
        <ul id="seminaireannees" class="list annees clear">
            @if(!empty($annees))
                <ul id="arret-annees" class="list annees clear">
                    @foreach($annees as $year)
                        <li><a rel="y{{ $year }}" href="#">Paru en {{ $year }}</a></li>
                    @endforeach
                </ul>
            @endif
        </ul>
        <h6>Par auteur</h6>
        <div class="list auteurs clear selectList">
            <select style="width: 230px;" class="seminaire-chosen chosen-select author"  multiple data-placeholder="Filtrer par auteur..." name="filter">
                @if(!$auteurs->isEmpty())
                    @foreach($auteurs as $id => $author)
                        <option value="a{{ $id }}">{{ $author }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
