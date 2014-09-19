@extends('...layout')

@section('content')

  <h1>Search Packages</h1>

  {{ Form::model($data, ['role' => 'form', 'class' => '']) }}

  <div class="row">
    <div class="col-xs-6">
      <div class="form-group">
        {{ Form::label('type[]', 'Package Type') }}
        {{ Form::select('type[]', $types, null, ['class' => 'form-control', 'multiple', 'id' => 'type']) }}
      </div>
    </div>
    <div class="col-xs-6">
      <div class="form-group">
        {{ Form::label('tags', 'Tags') }}
        {{ Form::text('tags', null, ['class' => 'form-control', 'id' => 'tags']) }}
      </div>
    </div>
    <div class="col-xs-6">
      <div class="form-group">
        {{ Form::label('search', 'Search') }}
        {{ Form::text('search', null, ['class' => 'form-control']) }}
      </div>
    </div>
    <div class="col-xs-6">
      <div class="form-group">
        {{ Form::label('order', 'Order By') }}
        {{ Form::select('order', $orders, null, ['class' => 'form-control']) }}
      </div>
    </div>
  </div>

  {{ Form::submit('Search', ['class' => 'btn btn-primary']) }}

  {{ Form::close() }}

  <?php echo $packages->links(); ?>

  <div class="row" id="results">
  @foreach($packages as $package)
    <div class="col-xs-6">
      @include('packages.include', ['package' => $package])
    </div>
  @endforeach
  </div>

@stop

@section('js')

  {{ HTML::script('js/vendor/jquery.select2.min.js') }}
  {{ HTML::script('js/vendor/jquery.highlight.js') }}

  <script>
  $("select#type").select2({
    placeholder: "Package Types",
    allowClear: true
  });

  $("input#tags").select2({
    multiple: true,
    placeholder: "Tags",
    minimumInputLength: 2,
    ajax: {
      url: "{{ route('search-tags') }}",
      dataType: 'json',
      quietMillis: 200,
      data: function (term, page) {
        return {
          page: page,
          search: term
        };
      },
      results: function (data, page) {
        return {
          results: data.results,
          more: (page < data.lastPage)
        };
      }
    },
    initSelection: function(element, callback) {
      var ids = $(element).val();
      if (ids !== "") {
        $.ajax(
          "{{ route('search-tags-init') }}", {
            dataType: "json",
            data: {
              ids: ids
            }
          }
        ).done(function(data) {
          callback(data);
        });
      }
    }
  });

  $("#results").highlight('{{ $data['search'] or '' }}', {
    caseSensitive: true,
    wordsOnly: false
  });
  </script>

@stop

@section('css')

  {{ HTML::style('css/vendor/select2.css') }}
  {{ HTML::style('css/vendor/select2-bootstrap.css') }}

  <style>
  .highlight {
      background-color: #FFFF88;
  }
  </style>

@stop
