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
        <h1>Reverse Packagist <small>{{ $count }} repos</small></h1>
        <ul>
          <li>Find out who uses your package</li>
          <li>Search for packages of a certain type</li>
        </ul>
    </div>
  </div>

  <div class="page-header">
    <h1>Packages added in the last 7 days</h1>
  </div>

  <div>
    <div class="row">
      @foreach($packages as $package)
        <div class="col-xs-12 col-sm-6">
          @include('packages.include', ['package' => $package])
        </div>
      @endforeach
    </div>
  </div>

@stop
