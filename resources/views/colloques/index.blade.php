@extends('layouts.colloque')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Colloques</h2>
            <p>&nbsp;</p>
            <?php
                $item = $colloques->first();
            echo '<pre>';
            print_r($item);
            echo '</pre>';
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="grid">
                <div class="grid-sizer"></div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc">
                            <header>
                                <span style="width:60px;height: 60px; background: #ddd; float: left; margin-right: 10px;">
                                     <?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
                                    {{ $item->start_at->formatLocalized('%B') }}
                                    {{ $item->start_at->format('d') }}
                                    {{ $item->start_at->format('Y') }}
                                </span>
                                <p>{{ $item->titre }}<br/>{{ $item->soustitre }}</p>
                            </header>
                            <div class="body">
                                <address>
                                    <p><strong>Lieu:</strong></p>
                                    <p>{{ $item->location->name }}, {{ $item->location->adresse }}</p>
                                </address>
                            </div>
                            <footer>
                                @if(isset($item->centres))
                                    @foreach($item->centres as $center)
                                       <a href="{{ $center->url }}">
                                           <img width="80px" src="{{ asset('files/logos/'.$center->logo) }}">
                                       </a>
                                    @endforeach
                                @endif
                            </footer>
                        </div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="item-bloc"></div>
                    </div>
                </div>

            </div>


        </div>
    </div>


@stop