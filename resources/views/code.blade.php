
<div class="row">
    <div class="visible-print text-center">
        {!! QrCode::size(200)->generate(url('code?no=12-2015/34')) !!}
        <p>Scan me to return to the original page.</p>

        {{ url('presence/9531/'.config('services.qrcode.key')) }}

    </div>
</div>
