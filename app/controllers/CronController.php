<?php
class CronController extends BaseController
{

  /**
   * Adds new packages, deletes removed packages.
   */
  public function refreshAll()
	{

    ini_set('max_execution_time', 300); //300 seconds = 5 minutes

    $packgist = new \Jleagle\Packagist('http://packagist.org');
		$packages = $packgist->all();

    foreach($packages as $package)
    {
      list($author, $name) = explode('/', $package, 2);
      $package = Package::firstOrNew(['author' => $author, 'name' => $name]);

      if (!$package->id)
      {
        $package->save();
      }
    }

    $current = Package::lists('full_name', 'id');

    // do an array diff to get removed packages

	}

  public function refreshPackage($x = null, $y = null)
  {

    if ($x && $y)
    {
      $package = Package::where('author', '=', $x)->where('name', '=', $y)->first();
    }
    else
    {
      $package = Package
        ::orderBy('updated_at', 'asc')
        ->where('author', '!=', '')
        ->where('name', '!=', '')
        ->first();
    }

    // Get latest info
    try
    {
      $packgist = new \Jleagle\Packagist('http://packagist.org');
      $data     = $packgist->package($package->full_name);
    }catch(Exception $e)
    {
      $package->delete();
      exit;
    }

    // Save package data
    $package->description = $data['description'];
    $package->downloads   = $data['downloads']['total'];
    $package->downloads_m = $data['downloads']['monthly'];
    $package->downloads_d = $data['downloads']['daily'];
    $package->stars       = $data['favers'];
    $package->type        = $data['type'];
    $package->repo        = $data['repository'];
    $package->save();

    // Get the latets version
    usort($data['versions'], [$this, '_cmp']);
    $data = $data['versions'][0];

    // Authors
    $ids = [];
    if (isset($data['authors']))
    {
      foreach($data['authors'] as $author)
      {
        if(isset($author['email']) && $author['email'])
        {
          $model = Author::firstOrNew(['email' => $author['email']]);
          foreach($author as $field => $value)
          {
            if($value)
            {
              $model->{$field} = $value;
            }
          }
          $model->save();
          $ids[] = $model->id;
        }
      }
    }
    $package->authors()->sync($ids);

    // Tags
    $ids = [];
    if (isset($data['keywords']))
    {
      foreach($data['keywords'] as $tag)
      {
        $model       = Tag::firstOrCreate(['name' => $tag]);
        $ids[] = $model->id;
      }
    }
    $package->tags()->sync($ids);

    // Dependencies
    $ids = [];
    if (isset($data['require']))
    {
      foreach($data['require'] as $fullNname => $version)
      {
        $explode = explode('/', $fullNname, 2);
        list($author, $name) = array_pad($explode, 2, '');

        $model = Package::firstOrCreate(
          ['author' => $author, 'name' => $name]
        );
        $ids[$model->id] = ['version' => $version];
      }
    }
    $package->dependencies()->sync($ids);

    print_r($data);
  }

  private function _cmp($a, $b)
  {
    $a = $a['time'];
    $b = $b['time'];

    if ($a == $b) {
      return 0;
    }
    return ($a > $b) ? -1 : 1;
  }

}
