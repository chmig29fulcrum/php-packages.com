<?php
class HomeController extends BaseController
{

	public function index()
	{
    $count = Package::count();
    $count = number_format($count);

    $packages = Package
      ::orderBy('created_at', 'desc')
      ->where('name', '!=', '')
      ->where('created_at', '>', date("Y-m-d H:i:s", strtotime('-7 days')))
      ->limit(50)
      ->get();

		return View::make('index', [
      'packages' => $packages,
      'count' => $count,
    ]);

	}

  public function faqs()
  {
    // why is an author not showing up, because we only track authors with emails for uniqueness
  }

}
