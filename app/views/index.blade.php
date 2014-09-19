@extends('layout')

@section('content')

  <?php
  $message = Session::get('message');
  if ($message)
  {
    echo '<div class="alert alert-danger" role="alert">'.$message.'</div>';
  }
  ?>

  <div class="jumbotron">
    <div class="container">
        <h1>Reverse Packagist</h1>
        <p>{{ $count }} repos total</p>
    </div>
  </div>

  <div class="page-header">
    <h1>Newest</h1>
  </div>

  <div>
    <div class="row">
      @foreach($packages as $package)
        <div class="col-xs-6">
          @include('packages.include', ['package' => $package])
        </div>
      @endforeach
    </div>
  </div>

@stop
