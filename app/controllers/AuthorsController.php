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

  public function view($id, $slug = null)
  {
    $author = Author::where('id', '=', $id)->firstOrFail();
    $author->packages->sortByDesc('downloads_m');

    return View::make('authors.view', [
      'author' => $author
    ]);
  }

}
