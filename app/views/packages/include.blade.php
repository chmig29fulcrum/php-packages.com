<?php if ($package->name){ ?>

  <a href="{{ route('package', [$package->author, $package->name]) }}" class="panel panel-info package-include">

    <div class="panel-heading">
      <span class="badge pull-right">{{ number_format($package->downloads_m) }}</span>
      <h3 class="panel-title">{{ $package->full_name_spaces }} ({{ $package->type }})</h3>
    </div>

    <div class="panel-body">
      {{ $package->description or '&nbsp;' }}
    </div>

  </a>

<?php }else{ ?>

  <div class="panel panel-info">

    <div class="panel-heading">
      <h3 class="panel-title">{{ $package->author }} {{ $package->name ? $package->name : $package->pivot->version }}</h3>
    </div>

  </div>

<?php } ?>