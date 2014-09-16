@extends('...layout')

@section('content')

<h1>Home</h1>

<?php echo $packages->links(); ?>

@foreach($packages as $package)

  @include('packages.include', ['package' => $package])

@endforeach

@stop
