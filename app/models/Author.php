<?php
class Author extends Eloquent
{

	protected $table = 'authors';

	protected $hidden = [];

  protected $fillable = ['name', 'email', 'homepage', 'role'];

  public function packages()
  {
    return $this->belongsToMany('Package');
  }

  public function getNameEmailAttribute()
  {
    return $this->name ? $this->name : $this->email;
  }

}
