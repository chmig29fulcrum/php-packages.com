<?php
class PackagesController extends BaseController
{

	public function index()
	{
    $packages = Package
      ::where('name', '!=', '')
      ->where('author', '!=', '')
      ->orderBy('downloads_m', 'desc')
      ->paginate(100);

		return View::make('packages.index', [
      'packages' => $packages
    ]);
	}

  public function view($author, $name)
  {
    $package = Package
      ::where('name', '=', $name)
      ->where('author', '=', $author)

      ->with('packages', 'dependencies')
      ->firstOrFail();

    $package->tags->sortBy('name');
    $package->authors->sortBy('name');
    $package->dependencies->sortBy('name');

    return View::make('packages.view', [
      'package' => $package
    ]);
  }

}
