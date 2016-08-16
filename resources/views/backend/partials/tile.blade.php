<?php $site_id = isset($site_id) ? '/'.$site_id : ''; ?>
<li>
    <a class="shortcut-tiles tiles-{{ $config['color'] }}" href="{{ url('admin/'.$config['title'].''.$site_id) }}">
        <div class="tiles-body tiles-body-menu">
            <i class="fa fa-{{ $config['icon'] }}"></i><p class="pull-right">{{ $config['title'] }}</p>
        </div>
    </a>
</li>