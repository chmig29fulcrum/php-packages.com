<?php
class TagsController extends BaseController
{

	public function index()
	{
    $tags = Tag::orderBy('name', 'asc')->paginate(100);

		return View::make('tags.index', [
      'tags' => $tags
    ]);
	}

  public function view($id, $slug)
  {
    $tag = Tag::where('id', '=', $id)->firstOrFail();

    return View::make('tags.view', [
      'tag' => $tag
    ]);
  }

}
