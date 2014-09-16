@extends('...layout')

@section('content')

<h1>Authors</h1>

<?php echo $authors->links(); ?>

<div class="row">

  @foreach($authors as $author)
    <div class="col-xs-2">
      @include('authors.include', ['author' => $author])
    </div>
  @endforeach

</div>

@stop
