<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Package extends Eloquent
{

  use SoftDeletingTrait;

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

}
