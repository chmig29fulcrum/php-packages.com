<?php
class Tag extends Eloquent
{

	protected $table = 'tags';

	protected $hidden = [];

  protected $fillable = ['name'];

  public function packages()
  {
    return $this->belongsToMany('Package');
  }

}
