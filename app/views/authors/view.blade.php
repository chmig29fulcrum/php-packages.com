@extends('...layout')

@section('content')

  <div class="page-header">
    <a href="mailto:{{ $author->email }}" class="btn btn-default pull-right">Email</a>

    <?php if ($author->homepage){ ?>
      <a href="{{ $author->homepage }}" class="btn btn-default pull-right" style="margin-right: 5px;">Website</a>
    <?php } ?>

    <h1>Author: {{ $author->name }}</h1>
  </div>

  <div class="row">
    @foreach($author->packages as $package)
      <div class="col-xs-6">
        @include('packages.include', ['package' => $package])
      </div>
    @endforeach
  </div>

@stop
