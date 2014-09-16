<div class="thumbnail">

  <a href="{{ route('author', [$author->id, Str::slug($author->name)]) }}">
    <img src="{{ \forxer\Gravatar\Gravatar::image($author->email, 256, 'mm', null, 'png', false) }}" alt="" />
  </a>

  <div class="caption">
    <h4>{{ $author->name_email }}&nbsp;</h4>
  </div>

</div>
