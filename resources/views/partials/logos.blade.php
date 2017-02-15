<div class="sites-logos-wrapper logos-{{ isset($class) ? $class : '' }}">
    <section class="container">
        <div class="row">
            <div class="col-md-12 sites-logos">
                <?php $fac_sites = config('sites.fac_sites'); ?>
                @foreach($fac_sites as $name => $logo)
                    @if($site->slug != $name)
                        <a target="_blank" href="{{ $logo['url'] }}">
                            <img src="{{ asset('files/sites/'.$logo['image']) }}" alt="{{ $name }}" />
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
</div>