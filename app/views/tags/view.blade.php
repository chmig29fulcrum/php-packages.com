@extends('...layout')

@section('content')

  <div class="page-header">
    <h1>{{ $tag->name }}</h1>
  </div>

  @foreach($tag->packages as $package)

    @include('packages.include', ['author' => $package])

  @endforeach

@stop
