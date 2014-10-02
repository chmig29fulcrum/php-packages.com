<?php
class HomeController extends BaseController
{

	public function index()
	{
    $count = Package::count();
    $count = number_format($count);

    $days = 3;

    $packages = Package
      ::orderBy('created_at', 'desc')
      ->where('name', '!=', '')
      ->where('created_at', '>', date("Y-m-d H:i:s", strtotime('-'.$days.' days')))
      ->limit(50)
      ->get();

		return View::make('index', [
        'packages' => $packages,
        'count'    => $count,
        'days'     => $days,
    ]);

	}

  public function faqs()
  {
    // why is an author not showing up, because we only track authors with emails for uniqueness
  }

}
