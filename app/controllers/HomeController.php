<?php
class HomeController extends BaseController
{

	public function index()
	{
    $count = Package::count();

    $packages = Package::orderBy('created_at', 'desc')->limit(10);

		return View::make('index', [
      'packages' => $packages
    ]);



	}

}
