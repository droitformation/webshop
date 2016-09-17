@if($errors->has())
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" alert-dismissible role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endif


