@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="{{ url('admin/products') }}" method="post">
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-md-2">
                                @include('backend.products.partials.sort',['title' => 'Catégories', 'items' => $categories, 'types' => 'categories', 'type' => 'categorie_id'])
                            </div>
                            <div class="col-md-2">
                                @include('backend.products.partials.sort',['title' => 'Auteurs',    'items' => $authors, 'types' => 'authors', 'type' => 'author_id'])
                            </div>
                            <div class="col-md-2">
                                @include('backend.products.partials.sort',['title' => 'Domaines',   'items' => $domains,  'types' => 'domains', 'type' => 'domain_id'])
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br/>
                                <button class="btn btn-primary" type="submit"><i class="fa fa-filter"></i> &nbsp;Trier</button>
                                <a class="btn btn-default" href="{{ url('admin/products') }}">Tous</a>
                            </div>
                            <div class="col-md-3">
                                <label>Rechercher</label>
                                <div class="input-group">
                                    <input type="text" name="term" class="form-control" placeholder="ISBN, titre">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">Ok</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div>
                            <div class="col-md-1 text-right">
                                <label>&nbsp;</label><br/>
                                <a href="{{ url('admin/product/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <?php 
                        if(isset($search) && !empty($search))
                        {
                            $key  = key($search);
                            $name = str_replace('_id','',$key).'s';
                            $id   = $search[$key];

                            echo '<h3>'.$$name->find($id)->title.'</h3>';
                        }
                    ?>

                    <div class="row">
                        @if(!$products->isEmpty())
                        @foreach($products as $product)

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <p>
                                                <img style="height: 50px; float:left; margin-right: 10px;" src="{{ asset('files/products/'.$product->image) }}" />
                                                <a href="{{ url('admin/product/'.$product->id) }}"><span class="title">{{ $product->title }}</span></a>
                                            </p>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="{{ url('admin/product/'.$product->id) }}" class="btn btn-xs btn-info">&nbsp;éditer&nbsp;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                        @endif
                    </div>

                    @if($paginate)
                        {!! $products->links() !!}
                    @endif

                </div>
            </div>

        </div>
    </div>

@stop