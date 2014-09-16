@extends('layout')

@section('content')


<div class="jumbotron">
  <div class="container">
      <h1>Hello, world!</h1>
      <p>...</p>
      <p><a class="btn btn-primary btn-lg" role="button">Learn more</a></p>
  </div>
</div>


  <h1>Tags</h1>

  <?php echo $tags->links(); ?>

    @foreach($tags as $tag)

      <h3 style="display: inline;">{{ link_to_route('tag', $tag->name, [$tag->id, $tag->name], ['class' => 'label label-primary']) }}</h3>

    @endforeach

  </div>

@stop
