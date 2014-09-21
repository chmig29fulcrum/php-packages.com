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
    <div class="btn-toolbar  pull-right" role="toolbar">
      <div class="btn-group">
        <a class="btn btn-default" href="https://packagist.org/packages/{{ $package->full_name }}">Packagist</a>
        <a class="btn btn-default" href="{{ $package->repo }}">Repo</a>
      </div>
      <div class="btn-group">
        {{ link_to_route('update-package', 'Updated '.$package->last_updated, [$package->author, $package->name], ['class' => 'btn btn-default']) }}
      </div>
    </div>
    <h1>Package: {{ $package->name }} <small>{{ $package->author }} ({{ $package->type }})</small></h1>
  </div>

  <?php if ($package->description){ ?>
  <div class="well well-sm">{{ $package->description }}</div>
  <?php } ?>

  <div id="tags">
    @foreach($package->tags as $tag)
      {{ link_to_route('tag', $tag->name, [$tag->id, $tag->name], ['type' => 'button', 'class' => 'btn btn-primary']) }}
    @endforeach
  </div>

  <div class="row">
    @foreach($package->authors as $author)
      <div class="col-xs-12 col-sm-6">
        <h2 style="white-space: nowrap; overflow: hidden;">
          <a href="{{ route('author', [$author->id, Str::slug($author->name)]) }}">
          <img src="{{ \forxer\Gravatar\Gravatar::image($author->email, 256, 'mm', null, 'png', false) }}" alt="" style="height: 64px;" />
          </a>
          {{ $author->name }}
          <small>{{ $author->role }}</small>
        </h2>
      </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-6">

      <h2>Uses</h2>
      @foreach($package->dependencies as $v)
        @include('packages.include', ['package' => $v])
      @endforeach

    </div>
    <div class="col-xs-12 col-sm-6">

      <h2>Used by</h2>
      @foreach($package->packages as $v)
        @include('packages.include', ['package' => $v])
      @endforeach

    </div>
  </div>

@stop
