@extends('...layout')

@section('content')

  <div class="page-header">
    <h1>{{ $tag->name }}</h1>
  </div>

  <div class="row">
    @foreach($tag->packages as $package)
      <div class="col-xs-6">
        @include('packages.include', ['author' => $package])
      </div>
    @endforeach
  </div>

@stop
