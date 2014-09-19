@extends('../layout')

@section('content')

  <?php
  $message = Session::get('message');
  if ($message)
  {
    echo '<div class="alert alert-'.$message['class'].'" role="alert">'.$message['message'].'</div>';
  }
  ?>

  <div class="page-header">
    {{ link_to_route('update-package', 'Updated '.$package->last_updated, [$package->author, $package->name], ['class' => 'btn btn-default pull-right']) }}
    <h1>{{ $package->name }} <small>{{ $package->author }}</small></h1>
  </div>

  @foreach($package->tags as $tag)
    {{ link_to_route('tag', $tag->name, [$tag->id, $tag->name], ['type' => 'button', 'class' => 'btn btn-primary']) }}
  @endforeach

  <div class="row">
    <div class="col-xs-6">

      @foreach($package->authors as $author)
        <h2>
          <a href="{{ route('author', [$author->id, Str::slug($author->name)]) }}">
          <img src="{{ \forxer\Gravatar\Gravatar::image($author->email, 256, 'mm', null, 'png', false) }}" alt="" style="height: 64px;" />
          </a>
          {{ $author->name }}
          <small>{{ $author->role }}</small>
        </h2>
      @endforeach

    </div>
    <div class="col-xs-6">

      <ul class="list-group">
        <li class="list-group-item">Cras justo odio</li>
        <li class="list-group-item">Dapibus ac facilisis in</li>
        <li class="list-group-item">Morbi leo risus</li>
        <li class="list-group-item">Porta ac consectetur ac</li>
        <li class="list-group-item">Vestibulum at eros</li>
      </ul>

    </div>
  </div>

  <div class="row">
    <div class="col-xs-6">

      <h2>Uses</h2>
      @foreach($package->dependencies as $v)
        @include('packages.include', ['package' => $v])
      @endforeach

    </div>
    <div class="col-xs-6">

      <h2>Used by</h2>
      @foreach($package->packages as $v)
        @include('packages.include', ['package' => $v])
      @endforeach

    </div>
  </div>

@stop
