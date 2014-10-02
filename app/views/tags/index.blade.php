@extends('...layout')

@section('content')

<h1>Tags</h1>
<p>Showing tags assigned to more than one package</p>

<div id="tags">
  @foreach($words as $word => $array)
    <a href="{{ route('packages', ['tags' => $array['data'][0]]) }}" style="font-size: {{ $array['size'] }}px">{{ $word }}</a>
  @endforeach
</div>

@stop
