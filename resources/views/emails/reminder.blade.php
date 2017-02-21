@extends('emails.layouts.notification')
@section('content')

    <?php $config = config('reminder.'.$reminder->type); ?>

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>
    <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell_header'] }}">
                <!-- Greeting -->
                <h1 style="{{ $style['header-1'] }}">{{ $reminder->title }}</h1>
                @if($item)
                    <p style="color: #000;margin-top: 5px;margin-bottom: 0px;">
                        <strong> Pour le {{ $config['name'] }}</strong><br/> {{ $item->titre or $item->title }}
                    </p>
                @endif
            </td>
        </tr>
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}">
                <div style="{{ $style['paragraph'] }}">
                    {!! $reminder->text !!}
                </div>
            </td>
        </tr>
    </table>
@stop
