@if(Session::has('success'))
<div class="alert alert-block alert-success fade in">
    <button type="button" class="close" data-dismiss="alert"></button>
    <p>Success! {!! Session::get('success') !!}</p>
</div>
@endif

@if(Session::has('error'))
@if(Session::get('error')!='')
<div class="alert alert-block alert-danger fade in">
    <button type="button" class="close" data-dismiss="alert"></button>
    <p>Error! {{Session::get('error')}}</p>
</div>
@endif
@endif

@if(!empty($validerrors))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert"></button>
    <strong>Error!</strong>
    <div class="media">
        @foreach ($validerrors as $v)
        <p class="media-heading">{{$v}}</p>
        @endforeach
    </div>
</div>
@endif
@isset($errors)
@if ($errors->any())
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert"></button>
    <strong>Error!</strong>
    <div class="media">
        @foreach ($errors->all() as $error)
        <p class="media-heading">{{ $error }}</p>
        @endforeach
    </div>
</div>
@endif
@endisset

