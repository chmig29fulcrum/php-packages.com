@extends('...layout')

@section('content')

<h1>Tags</h1>

<?php echo $tags->links(); ?>

  @foreach($tags as $tag)

    <h3 style="display: inline;">
      {{ link_to_route('packages', $tag->name, ['tags' => $tag->id], ['class' => 'label label-primary']) }}
    </h3>

  @endforeach

</div>

@stop
