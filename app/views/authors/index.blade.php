@extends('...layout')

@section('content')

<h1>Authors</h1>

<?php echo $authors->links(); ?>

<div class="row">

  @foreach($authors as $author)
    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
      <div class="thumbnail">

        <a href="{{ route('packages', ['authors' => $author->id]) }}">
          <img src="{{ \forxer\Gravatar\Gravatar::image($author->email, 256, 'mm', null, 'png', false) }}" alt="" />
        </a>

        <div class="caption">
          <h4>{{ $author->name_email }}&nbsp;</h4>
        </div>

      </div>
    </div>
  @endforeach

</div>

@stop
