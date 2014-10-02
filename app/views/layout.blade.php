<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Packages</title>

    {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'); }}
    {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'); }}
    {{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'); }}
    {{ HTML::style('css/vendor/gh-fork-ribbon.css'); }}
    {{ HTML::style('css/global.css'); }}
    @section('css')
    @show

    <!--[if lt IE 9]>
      {{ HTML::script('//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'); }}
      {{ HTML::script('//oss.maxcdn.com/respond/1.4.2/respond.min.js'); }}
    <![endif]-->

  </head>
  <body>

    <div class="github-fork-ribbon-wrapper right-bottom visible-lg-inline-block">
      <div class="github-fork-ribbon">
        <a href="https://github.com/Jleagle/reverse-packagist">Fork me on GitHub</a>
      </div>
    </div>

    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">

        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Reverse Packagist</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

          <ul class="nav navbar-nav">
            <li {{ (Request::is('*home*') ? 'class="active"' : '') }}>{{ link_to_route('home', 'Home') }}</li>
            <li {{ (Request::is('*package*') ? 'class="active"' : '') }}>{{ link_to_route('packages', 'Packages') }}</li>
            <li {{ (Request::is('*author*') ? 'class="active"' : '') }}>{{ link_to_route('authors', 'Authors') }}</li>
            <li {{ (Request::is('*tag*') ? 'class="active"' : '') }}>{{ link_to_route('tags', 'Tags') }}</li>
          </ul>

        </div>
      </div>
    </nav>

    <div class="container">
      @yield('content')
    </div>

    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'); }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'); }}
    @section('js')
    @show

  </body>
</html>
