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

  public function view($id, $slug = null)
  {
    $tag = Tag::where('id', '=', $id)->firstOrFail();
    $tag->packages->sortByDesc('downloads_m');

    return View::make('tags.view', [
      'tag' => $tag
    ]);
  }

  public function ajaxSearchTags()
  {
    $data = Input::all();

    $search = idx($data, 'search', '');
    $paginate = Tag
      ::select('id', 'name')
      ->where('name', 'like', '%' . $search . '%')
      ->orderBy('name', 'asc')
      ->paginate(10);

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

  public function ajaxSearchTagsInit()
  {
    $data = Input::all();
    $ids = explode(',', $data['ids']);
    $ids = array_filter($ids);

    $packages = Tag
      ::select('id', 'name')
      ->whereIn('id', $ids)
      ->orderBy('name', 'asc')
      ->get();

    $items = [];
    foreach($packages as $item)
    {
      $items[] = [
        'id' => $item->id,
        'text' => $item->name,
      ];
    }

    return $items;
  }

}
