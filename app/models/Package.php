<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Package extends Eloquent
{

  // use SoftDeletingTrait;

	protected $table = 'packages';

	protected $hidden = [];

  protected $fillable = ['author', 'name'];

  public function dependencies()
  {
    return $this
      ->belongsToMany('Package', null, 'package_id', 'dependency_id')
      ->withPivot('version')
      ->withTimestamps();
  }

  public function packages()
  {
    return $this
      ->belongsToMany('Package', null, 'dependency_id', 'package_id')
      ->withPivot('version')
      ->withTimestamps();
  }

  public function authors()
  {
    return $this->belongsToMany('Author');
  }

  public function tags()
  {
    return $this->belongsToMany('Tag');
  }

  // Accessors
  public function getFullNameAttribute()
  {
    return $this->author.'/'.$this->name;
  }
  public function getFullNameSpacesAttribute()
  {
    return $this->author.' / '.$this->name;
  }
  public function getLastUpdatedAttribute()
  {
    // http://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago

    $full = false;

    $now = new DateTime;
    $ago = new DateTime($this->updated_at);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

}
