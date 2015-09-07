<div class="row">
    <div class="col-md-3">
        <div class="form-group clearfix">
            <select id="galleryfilter" class="form-control pull-left">
                <option data-filter="all">Show All</option>
                <option data-filter="industrial">Industrial</option>
                <option data-filter="architecture">Architecture</option>
                <option data-filter="nature">Nature</option>
                <option data-filter="architecture industrial">Architecture &amp; Industrial</option>
            </select>
        </div>
    </div>

    <div class="col-md-9">
        <div class="pull-right">
            <div class="btn-toolbar form-group clearfix">
                <div class="btn-group">
                    <button class="btn btn-default sort active" data-sort="default" data-order="desc">Default</button>
                    <button class="btn btn-default sort" data-sort="data-name" data-order="desc"><i class="fa fa-sort-alpha-asc"></i><span class="hidden-xs"> Name</span></button>
                    <button class="btn btn-default sort" data-sort="data-name" data-order="asc"><i class="fa fa-sort-alpha-desc"></i><span class="hidden-xs"> Name</span></button>
                </div>
                <div class="btn-group">
                    <button class="btn btn-default active" id="GoGrid"><i class="fa fa-th"></i></button>
                    <button class="btn btn-default" id="GoList"><i class="fa fa-th-list"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ul class="gallery list-unstyled">

            @if(!empty($colloques))
                @foreach($colloques as $colloque)

                    <li class="mix industrial" data-name="Rusty">
                        <a href="{{ asset($colloque->illustration) }}">
                            <img src="{{ asset($colloque->illustration) }}">
                        </a>
                        <h4>{{ $colloque->titre }} | {{ $colloque->sujet }}</h4>
                    </li>

                @endforeach
            @endif
            <li class="gap"></li> <!-- "gap" elements fill in the gaps in justified grid -->
        </ul>
    </div>
</div>
