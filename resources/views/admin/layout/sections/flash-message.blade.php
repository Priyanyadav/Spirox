@if ($message = Session::get('success'))
    @if (!empty($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    {{ Session::forget('success') }}
@endif


@if ($message = Session::get('error'))
    @if (!empty($message))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    {{ Session::forget('success') }}
@endif
