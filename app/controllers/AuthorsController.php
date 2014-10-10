<?php
class AuthorsController extends BaseController
{

	public function index()
	{
    $authors = Author
      ::orderBy('name', 'asc')
      ->where('name', '!=', '')
      ->paginate(100);

		return View::make('authors.index', [
      'authors' => $authors
    ]);
	}

  public function ajaxSearchAuthors()
  {
    $data = Input::all();

    $search = idx($data, 'search', '');
    $paginate = Author
      ::select('id', 'name')
      ->where('name', 'like', '%' . $search . '%')
      ->orderBy('name', 'asc')
      ->paginate(20);

    $items = [];
    foreach($paginate->getItems() as $item)
    {
      $items[] = [
        'id' => $item->id,
        'text' => $item->name,
      ];
    }

    return [
      'results' => $items,
      'lastPage' => $paginate->getLastPage(),
    ];
  }

  public function ajaxSearchAuthorsInit()
  {
    $data = Input::all();
    $ids = idx($data, 'ids', '');
    $ids = explode(',', $ids);
    $ids = array_filter($ids);

    if (!$ids)
    {
      return [];
    }

    $authors = Author
      ::select('id', 'name')
      ->whereIn('id', $ids)
      ->orderBy('name', 'asc')
      ->get();

    $items = [];
    foreach($authors as $item)
    {
      $items[] = [
        'id' => $item->id,
        'text' => $item->name,
      ];
    }

    return $items;
  }

}
