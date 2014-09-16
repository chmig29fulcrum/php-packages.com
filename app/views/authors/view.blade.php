@extends('...layout')

@section('content')

  <div class="page-header">
    <a href="mailto:{{ $author->email }}" class="btn btn-default pull-right">Email</a>

    <?php if ($author->homepage){ ?>
    <a href="{{ $author->homepage }}" class="btn btn-default pull-right" style="margin-right: 5px;">Website</a>
    <?php } ?>

    <h1>{{ $author->name }}</h1>
  </div>

  @foreach($author->packages as $package)

    @include('packages.include', ['package' => $package])

  @endforeach

@stop
