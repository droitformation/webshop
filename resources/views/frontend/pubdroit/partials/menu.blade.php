<ul class="top-nav">
    @if(!$menus->isEmpty())
        <?php $menu = $menus->whereLoose('position','main'); ?>
        @if(!$menu->isEmpty())
            <?php $menu = $menu->first()->load('pages'); ?>
            @if(!$menu->pages->isEmpty())
                @foreach($menu->pages as $page)
                    <li>{!! $page->page_url !!}</li>
                @endforeach
            @endif
        @endif
    @endif
</ul>