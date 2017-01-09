@if(isset($sites) && isset($current_site) && $sites->contains('id',$current_site))
    <?php $site = $sites->find($current_site); ?>
    <ul class="site-dropdown site-sidebar">
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
@endif