<?php

class GridGalleryPro_Galleries_Model_Attributes extends GridGallery_Core_BaseModel
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

    $this->table = $this->db->prefix . 'gg_attributes';
  }

  public function append($photoId, $attributes)
  {
    if (!is_numeric($photoId) || $photoId < 1) {
      throw new InvalidArgumentException('Invalid photo identifier.');
    }

    $photoId = (int) $photoId;
    $this->clear($photoId);

    if (!empty($attributes)) {
      $query = $this->getQueryBuilder()
        ->insertInto($this->table)
        ->fields('pid', 'attributes')
        ->values($photoId, $this->db->_real_escape(serialize($attributes)));

      if (!$this->db->query($query->build())) {
        $default = 'Unexpected error';
        $message = $this->db->last_error ? $this->db->last_error : $default;
        throw new RuntimeException($message);
      }
    } else {
      return false;
    }
  }

  protected function clear($photoId)
  {
    $query = $this->getQueryBuilder()->deleteFrom($this->table)->Where('pid', '=', (int) $photoId);

    $this->db->query($query->build());
  }

  protected function getAttributesByGalleryId($galleryId)
  {
    $galleryId = (int) $galleryId;
    $res_table = $this->db->prefix . 'gg_galleries_resources';

    $query = $this->getQueryBuilder()
      ->select('pid, attributes')
      ->from($this->table)
      ->join($res_table)
      ->on($this->table . '.pid', '=', $res_table . '.resource_id')
      ->where($res_table . '.gallery_id', '=', $galleryId);

    return $this->db->get_results($query->build());
  }

  /**
   * Get all values for attributes
   */
  public function getAllAttributesValues($photos)
  {
    $values = [];
    $names = [];
    foreach ($photos as $photo) {
      if (isset($photo->attributes) && !is_null($photo->attributes)) {
        if (is_array($photo->attributes)) {
          foreach ($photo->attributes as $attr) {
            if (isset($attr['name']) && isset($attr['value'])) {
              $name = $attr['name'];
              $value = $attr['value'];
              $i = array_search($name, $names);
              if ($i !== false) {
                if (!in_array($value, $values[$i])) {
                  $values[$i][] = $value;
                }
              } else {
                $names[] = $name;
                $values[] = [$value];
              }
            }
          }
        }
      }
    }
    $attrValues = [];
    foreach ($names as $i => $name) {
      $attrValues[] = ['name' => $name, 'values' => $values[$i]];
    }
    return $attrValues;
  }

  public function renameAttributes($galleryId, $rename)
  {
    if (!is_numeric($galleryId) || $galleryId < 1) {
      throw new InvalidArgumentException('Invalid gallery identifier.');
    }
    if (!is_array($rename) || sizeof($rename) == 0) {
      return;
    }
    $oldNames = [];
    $newNames = [];
    foreach ($rename as $attribute) {
      if (property_exists($attribute, 'oldName') && property_exists($attribute, 'newName') && strlen($attribute->oldName) > 0 && strlen($attribute->newName) > 0) {
        $oldNames[] = $attribute->oldName;
        $newNames[] = $attribute->newName;
      }
    }
    if (sizeof($oldNames) == 0) {
      return;
    }

    $result = $this->getAttributesByGalleryId($galleryId);

    foreach ($result as $row) {
      $attributes = unserialize($row->attributes);
      $found = false;
      if (is_array($attributes)) {
        foreach ($attributes as $i => $value) {
          if (isset($value['name'])) {
            $n = array_search($value['name'], $oldNames);
            if ($n !== false) {
              $attributes[$i]['name'] = $newNames[$n];
              $found = true;
            }
          }
        }
      }
      if ($found) {
        $this->append($row->pid, $attributes);
      }
    }
    return true;
  }
}
