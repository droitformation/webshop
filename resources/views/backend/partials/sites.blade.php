@if(isset($sites) && !$sites->isEmpty())
    @foreach($sites as $site)
        <li class="dropdown demodrop sitedrop">
            <a href="#" class="dropdown-toggle tooltips color-{{ $site->slug }}" data-toggle="dropdown">{{ $site->nom }}</a>
            <ul class="dropdown-menu arrow dropdown-menu-form site-dropdown">
                <?php $site_config = config('sites.'.$site->slug); ?>
                @if(!empty($site_config))
                    @if(isset($site_config['site']))
                        @foreach($site_config['site'] as $config)
                            @include('backend.partials.tile', ['config' => $config, 'site_id' => $site->id])
                        @endforeach
                    @endif
                    @if(isset($site_config['general']))
                        @foreach($site_config['general'] as $config)
                                @include('backend.partials.tile', ['config' => $config])
                        @endforeach
                    @endif
                @endif
            </ul>
        </li>
    @endforeach
@endif