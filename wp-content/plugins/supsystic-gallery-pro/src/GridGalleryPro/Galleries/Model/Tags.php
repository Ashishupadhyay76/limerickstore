<?php

class GridGalleryPro_Galleries_Model_Tags extends GridGallery_Core_BaseModel
{
  /**
   * @var string
   */
  protected $table;

  /**
   * Constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->table = $this->db->prefix . 'gg_tags';
  }

  /**
   * Adds the tag to the photo in the gallery.
   * @param  int    $photoId   Photo ID.
   * @param  string $tags      Tags.
   * @throws RuntimeException
   * @return int Inserted ID.
   */
  public function add($photoId, $tags)
  {
    $tags = trim(addslashes(htmlspecialchars((string) $tags, ENT_QUOTES)));

    if (!is_numeric($photoId) || $photoId < 1) {
      throw new InvalidArgumentException('Invalid photo identifier.');
    }

    $photoId = (int) $photoId;

    $this->clear($photoId);

    if (!empty($tags)) {
      $query = $this->getQueryBuilder()->insertInto($this->table)->fields('pid', 'tags')->values($photoId, $tags);

      if (!$this->db->query($query->build())) {
        $default = 'Unexpected error';
        $message = $this->db->last_error ? $this->db->last_error : $default;

        throw new RuntimeException($message);
      }
      return $this->db->insert_id;
    } else {
      return false;
    }
  }

  public function append($photoId, $tags)
  {
    $tags = trim(addslashes(htmlspecialchars((string) $tags, ENT_QUOTES)));

    if (!is_numeric($photoId) || $photoId < 1) {
      throw new InvalidArgumentException('Invalid photo identifier.');
    }

    $photoId = (int) $photoId;

    if (!empty($tags)) {
      $tagsData = $this->getByPhotoId($photoId);
      $currentTags = isset($tagsData) ? $tagsData->tags : '';
      $arrUniqueMergedTags = array_unique(array_merge(explode(',', $currentTags), explode(',', $tags)));
      $addTags = array_diff($arrUniqueMergedTags, ['']);
      $tags = implode(',', $addTags);
      $this->clear($photoId);

      $query = $this->getQueryBuilder()->insertInto($this->table)->fields('pid', 'tags')->values($photoId, $tags);

      if (!$this->db->query($query->build())) {
        $default = 'Unexpected error';
        $message = $this->db->last_error ? $this->db->last_error : $default;

        throw new RuntimeException($message);
      }
      return $this->db->insert_id;
    } else {
      return false;
    }
  }

  public function remove($photoId, $tag = 'all')
  {
    if (!is_numeric($photoId) || $photoId < 1) {
      throw new InvalidArgumentException('Invalid photo identifier.');
    }

    $photoId = (int) $photoId;
    // obstructing previous Bug (name with ',')
    $tag = trim($tag, ',');

    if ($tag != 'all') {
      $photoTags = explode(',', (string) $this->getByPhotoId($photoId)->tags);
      foreach ($photoTags as $key => $value) {
        if ($value == $tag) {
          unset($photoTags[$key]);
        }
      }

      $alltags = implode(',', $photoTags);
    } else {
      $alltags = '';
    }

    $this->clear($photoId);

    if ($alltags != '') {
      $query = $this->getQueryBuilder()->insertInto($this->table)->fields('pid', 'tags')->values($photoId, $alltags);

      if (!$this->db->query($query->build())) {
        $default = 'Unexpected error';
        $message = $this->db->last_error ? $this->db->last_error : $default;

        throw new RuntimeException($message);
      }
    }

    return $this->db->insert_id;
  }

  public function renameCategory($photoId, $oldTagName, $newTagName)
  {
    $photoId = (int) $photoId;
    if (!$photoId) {
      throw new InvalidArgumentException('Invalid photo identifier.');
    }
    if ($oldTagName == 'all') {
      throw new InvalidArgumentException('Incorrect tag.');
    }
    // obstructing previous Bug (name with ',')
    $oldTagName = trim($oldTagName, ',');
    $photoTags = explode(',', (string) $this->getByPhotoId($photoId)->tags);
    $photoHasOldTag = false;

    foreach ($photoTags as $key => $value) {
      if ($value == '') {
        unset($photoTags[$key]);
      }
      if ($value == $oldTagName) {
        unset($photoTags[$key]);
        $photoHasOldTag = true;
      }
    }
    if ($photoHasOldTag) {
      $photoTags[] = $newTagName;
      $photoTags = array_unique($photoTags);
      $newTags = implode(',', $photoTags);
      $this->clear($photoId);

      $query = $this->getQueryBuilder()->insertInto($this->table)->fields('pid', 'tags')->values($photoId, $newTags);

      if (!$this->db->query($query->build())) {
        $default = 'Unexpected error';
        $message = $this->db->last_error ? $this->db->last_error : $default;

        throw new RuntimeException($message);
      }
      return $this->db->insert_id;
    }
    return null;
  }
  /**
   * Get all tags from category
   */
  public function getAllTags($category_id)
  {
    $alltags = [];
    if (!is_numeric($category_id) || $category_id < 1) {
      throw new InvalidArgumentException('Invalid photo identifier.');
    }

    $category_id = (int) $category_id;
    $res_table = $this->db->prefix . 'gg_galleries_resources';

    $query = $this->getQueryBuilder()
      ->select('tags')
      ->from($this->table)
      ->join($res_table)
      ->on($this->table . '.pid', '=', $res_table . '.resource_id')
      ->where($res_table . '.gallery_id', '=', $category_id);

    $tags = $this->db->get_results($query->build());
    foreach ($tags as $tag) {
      if ($tag->tags) {
        if (strpos($tag->tags, ',') !== false) {
          $subtags = explode(',', $tag->tags);
          $alltags = array_diff(array_merge($alltags, $subtags), ['']);
        } else {
          $alltags[] = $tag->tags;
        }
      }
    }

    if (count($alltags)) {
      $alltags = array_unique($alltags);
    }

    return $alltags;
  }

  protected function clear($photoId)
  {
    $query = $this->getQueryBuilder()->deleteFrom($this->table)->Where('pid', '=', (int) $photoId);

    $this->db->query($query->build());
  }

  /**
   * Removes the tag by identifier.
   * @param int $id Tag Id.
   */
  public function removeById($id)
  {
    if (!$this->removeBy('id', (int) $id)) {
      throw new RuntimeException($this->db->last_error);
    }
  }

  public function getByPhotoId($photoId)
  {
    return $this->getBy('pid', (int) $photoId);
  }

  public function getByPhotoIds($resourceIds)
  {
    $query = $this->getQueryBuilder()->select('pid, tags')->from($this->table)->where('pid', 'IN', implode(',', $resourceIds));

    $tags = $this->db->get_results($query->build(), ARRAY_A);

    return $tags;
  }

  protected function getBy($field, $value)
  {
    $query = $this->getQueryBuilder()->select('*')->from($this->table)->where($field, '=', $value);

    return $this->db->get_row($query->build());
  }

  protected function removeBy($field, $value)
  {
    $query = $this->getQueryBuilder()->deleteFrom($this->table)->where($field, '=', $value);

    if (!$this->db->query($query->build())) {
      return false;
    }

    return true;
  }
}
