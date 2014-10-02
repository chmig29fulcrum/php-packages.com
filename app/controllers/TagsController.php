<?php
use Jleagle\WordCloud;

class TagsController extends BaseController
{

	public function index()
	{
    $tags = DB::select('
      SELECT tags.id, tags.name, count(tag_id) as count
      FROM package_tag
      LEFT JOIN tags on tags.id = package_tag.tag_id
      GROUP BY tag_id
      HAVING count > 1
      ORDER BY count DESC
    ');

    $cloud = new WordCloud();
    foreach($tags as $tag)
    {
      $cloud->addWord($tag->name, [$tag->id], $tag->count);
    }

		return View::make('tags.index', [
      'words' => $cloud->getWords('count', 'desc', 14, 36),
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

  public function ajaxSearchTagsInit()
  {
    $data = Input::all();
    $ids = idx($data, 'ids', '');
    $ids = explode(',', $ids);
    $ids = array_filter($ids);

    if (!$ids)
    {
      return [];
    }

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
