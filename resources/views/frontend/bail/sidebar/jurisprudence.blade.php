<h5><a href="{{ url($site->slug.'/page/jurisprudence') }}">Jurisprudence <i class="pull-right fa fa-arrow-circle-right"></i></a></h5>
@if( Request::is($site->slug.'/page/jurisprudence') )

    <div id="masterFilter">
        <div class="widget list categories clear">
            <h3 class="title"><i class="icon-tasks"></i> &nbsp;Catégories</h3>
            @if(!$categories->isEmpty())
                <select id="arret-chosen" name="chosen-select" data-placeholder="Choisir une ou plusieurs catégories" style="width:100%" multiple class="chosen-select category">
                    @foreach($categories as $categorie)
                        <option value="c{{ $categorie->id }}">{{ $categorie->title }}</option>
                    @endforeach
                </select>
            @endif
        </div><!--END WIDGET-->
        <div class="widget list annees clear">
            <h3 class="title"><i class="icon-calendar"></i> &nbsp;Années</h3>
            @if(!empty($years))
                <ul id="arret-annees" class="list annees clear">
                    @foreach($years as $year)
                        <li><a rel="y{{ $year }}" href="#">Paru en {{ $year }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div><!--END jusriprudence-->
@endif