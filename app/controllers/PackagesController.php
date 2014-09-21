<?php
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Jleagle\Packagist;

//SK4-5E17-988F-0F53-A238-0889

class PackagesController extends BaseController
{

  public function index()
  {

    $data           = Input::all();
    $data['types']  = idx($data, 'types', '');
    $data['tags']   = idx($data, 'tags', '');
    $data['search'] = idx($data, 'search', '');
    $data['order']  = idx($data, 'order', 'downloads');


    $packages = Package
      ::where('name', '!=', '')
      ->where('author', '!=', '');

    // Types
    if ($data['types'])
    {
      $types = explode(',', $data['types']);
      $packages = $packages->whereIn('type', $types);
    }

    // Tags
    if ($data['tags'])
    {
      // todo - fix - not working for 'ajax'
      $tags = explode(',', $data['tags']);
      $tags = Tag::whereIn('id', $tags)->with('packages')->get();
      $packageIds = [];
      foreach($tags as $v)
      {
        foreach($v->packages as $vv)
        {
          $packageIds[] = $vv->id;
        }
      }
      $packages = $packages->whereIn('id', $packageIds);
    }

    // Search
    if ($data['search'])
    {
      $packages = $packages->where(function ($query) use ($data) {
          $query
            ->where('author', 'LIKE', '%'.$data['search'].'%')
            ->orWhere('name', 'LIKE', '%'.$data['search'].'%')
            ->orWhere('description', 'LIKE', '%'.$data['search'].'%');
        }
      );
    }

    // Order
    if (in_array($data['order'], ['name', 'author']))
    {
      $packages = $packages->orderBy($data['order'], 'asc')->orderBy('downloads_m', 'desc');
    }
    else
    {
      $packages = $packages->orderBy('downloads_m', 'desc');
    }

    // Get packages
    $packages = $packages->paginate(50)->appends([
        'types'   => $data['types'],
        'tags'   => $data['tags'],
        'search' => $data['search'],
        'order'  => $data['order'],
    ]);

    // Get types for select
    $types = DB::table('packages')
      ->select('type', DB::raw('count(type) as count'))
      ->where('type', '<>', '')
      ->groupBy('type')
      ->orderBy('count', 'desc')
      ->lists('type', 'type');

    return View::make('packages.index', [
        'packages' => $packages,
        'types' => $types,
        'data' => $data,
        'orders' => array_combine(['downloads', 'name', 'author'], ['Downloads', 'Name', 'Author']),
      ]);
  }

  public function view($author, $name)
  {
    try
    {
      $package = Package
        ::where('name', '=', $name)
        ->where('author', '=', $author)
        ->with('packages', 'dependencies')
        ->firstOrFail();
    }
    catch(ModelNotFoundException $e)
    {
      return Redirect::route('home')->with('message', 'This package does not exist.');
    }

    $package->tags->sortBy('name');
    $package->authors->sortBy('name');
    $package->dependencies->sortByDesc('downloads_m');
    $package->packages->sortByDesc('downloads_m');

    return View::make('packages.view', [
        'package' => $package
      ]);
  }

  public function refreshPackage($author, $name)
  {
    $package = Package
      ::where('author', '=', $author)
      ->where('name', '=', $name)
      ->get()
      ->first();

    if (is_null($package))
    {
      return Redirect
        ::route('package', [$author, $name])
        ->with('message', ['class' => 'danger', 'message' => 'No such package.']);
    }
    elseif(strtotime($package->updated_at) > Carbon::now()->subMinutes(30)->timestamp)
    {
      return Redirect
        ::route('package', [$author, $name])
        ->with('message', ['class' => 'danger', 'message' => 'Packages can only be manually updated every 30 minutes.']);
    }
    else
    {

      if ($this->_refreshPackage($package))
      {
        return Redirect
          ::route('package', [$author, $name])
          ->with('message', ['class' => 'success', 'message' => 'Package updated.']);
      }
      else
      {
        return Redirect
          ::route('package', [$author, $name])
          ->with('message', ['class' => 'danger', 'message' => 'Something went wrong.']);
      }
    }
  }

  // Cron
  public function updateOldestPackage()
  {
    $packages = Package
      ::orderBy('updated_at', 'asc')
      ->limit(3)
      ->get();

    $this->_refreshPackage($packages[0]);
    sleep(20);
    $this->_refreshPackage($packages[1]);
    sleep(20);
    $this->_refreshPackage($packages[2]);

    return [];
  }

  // Cron
  public function checkForNewPackages()
  {

    ini_set('max_execution_time', 300); //300 seconds = 5 minutes

    // Get latest from Packagist
    $packgist = new Packagist('http://packagist.org');
    $packgist = $packgist->all();

    // Get latest from our database
    $packages = [];
    $results = DB::select('select author, name from packages');
    foreach($results as $result)
    {
      $packages[] = $result->author.'/'.$result->name;
    }

    // Diff
    $needToDelete = array_diff($packages, $packgist);
    $needToAdd = array_diff($packgist, $packages);

    // Delete
    foreach($needToDelete as $v)
    {
      list($author, $name) = explode('/', $v, 2);
      Package::where('author', '=', $author)->where('name', '=', $name)->delete();
    }

    // Add
    foreach($needToAdd as $v)
    {
      list($author, $name) = explode('/', $v, 2);
      $package = Package::firstOrCreate(['author' => $author, 'name' => $name]);
      $this->_refreshPackage($package);
    }

    return [
      'added'   => count($needToDelete),
      'deleted' => count($needToAdd),
    ];

  }


  private function _refreshPackage(Package $package)
  {

    ini_set('max_execution_time', 60); // Seconds

    // Get latest info
    try
    {
      $packgist = new Packagist('http://packagist.org');
      $data     = $packgist->package($package->author, $package->name);
    }catch(\Exception $e)
    {
      $package->delete();
      return Redirect::route('home');
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
    usort($data['versions'], function ($a, $b)
      {
        $a = $a['time'];
        $b = $b['time'];

        if ($a == $b) {
          return 0;
        }
        return ($a > $b) ? -1 : 1;
      }
    );

    if (isset($data['versions'][0]))
    {
      $data = $data['versions'][0];

      // Authors
      $ids = [];
      if(isset($data['authors']))
      {
        foreach($data['authors'] as $author)
        {
          if(isset($author['email']) && $author['email'])
          {
            $model = \Author::firstOrNew(['email' => $author['email']]);
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
      if(isset($data['keywords']))
      {
        foreach($data['keywords'] as $tag)
        {
          $model = \Tag::firstOrCreate(['name' => $tag]);
          $ids[] = $model->id;
        }
      }
      $package->tags()->sync($ids);

      // Dependencies
      $ids = [];
      if(isset($data['require']))
      {
        foreach($data['require'] as $fullNname => $version)
        {
          $explode = explode('/', $fullNname, 2);
          list($author, $name) = array_pad($explode, 2, '');

          $model = \Package
            ::where('author', '=', $author)
            ->where('name', '=', $name)
            ->get()->first();

          if($model)
          {
            $ids[$model->id] = ['version' => $version];
          }
        }
      }
      $package->dependencies()->sync($ids);
    }

    $package->touch();

    return $data;
  }

  public function ajaxSearchPackages()
  {
    $data = Input::all();

    $search = idx($data, 'search', '');
    $paginate = Package
      ::select('type')
      ->where('type', '<>', '')
      ->where('type', 'like', '%'.$search.'%')
      ->groupBy('type')
      ->orderBy('type', 'asc')
      ->paginate(20);

    $items = [];
    foreach($paginate->getItems() as $item)
    {
      $items[] = [
        'id' => $item->type,
        'text' => $item->type,
      ];
    }

    return [
      'results' => $items,
      'lastPage' => $paginate->getLastPage(),
    ];
  }

}
