<ul id="facdroit">
    @if(isset($colloque->adresse))
        <li class="mb-5">{!! $colloque->adresse->adresse !!}</li>
    @else
        <li>{!! \Registry::get('inscription.infos.nom') !!}</li>
        <li class="mb-5">{!! \Registry::get('inscription.infos.adresse') !!}</li>
    @endif
    {!! !empty(\Registry::get('shop.infos.telephone')) ? '<li>Tél. '.\Registry::get('shop.infos.telephone').'</li>' : '' !!}
    {!! !empty(\Registry::get('shop.infos.email')) ? '<li>'.\Registry::get('shop.infos.email').'</li>' : '' !!}
</ul>