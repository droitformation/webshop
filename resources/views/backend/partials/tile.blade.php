<?php $site_id = isset($site_id) ? '/'.$site_id : ''; ?>
<li>
    <a class="shortcut-tiles tiles-{{ $config['color'] }}" href="{{ url('admin/'.$config['url'].''.$site_id) }}">
        <div class="tiles-body tiles-body-menu">
            <i class="fa fa-{{ $config['icon'] }}"></i><p class="pull-right">{{ $config['name'] }}</p>
        </div>
    </a>
</li>