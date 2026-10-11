<?php

class GridGalleryPro_Galleries_Model_Resources extends GridGallery_Galleries_Model_Resources
{
  public function showMore($galleryId, $limit = null, $except = null, $category = null)
  {
    $settingsModel = new GridGallery_Galleries_Model_Settings();
    $settings = $settingsModel->get($galleryId);

    $this->db->query('SET SQL_BIG_SELECTS = 1');
    $query =
      'SELECT DISTINCT resource_id, resource_type, tags, pos.photo_id, pos.position 
	    FROM ' .
      $this->db->prefix .
      'gg_galleries_resources
        LEFT JOIN (
            SELECT photo_id, scope_id, position 
            FROM ' .
      $this->db->prefix .
      'gg_photos_pos 
            WHERE scope_id = ' .
      (int) $galleryId .
      '
        ) AS pos ON ' .
      $this->db->prefix .
      'gg_galleries_resources.resource_id = pos.photo_id
        LEFT JOIN ' .
      $this->db->prefix .
      'gg_tags ON ' .
      $this->table .
      '.resource_id = ' .
      $this->db->prefix .
      'gg_tags.pid';

    $query .= ' WHERE gallery_id = ' . (int) $galleryId;

    if (!empty($category) && $category !== '__all__') {
      $tagStr = '';
      $category = explode(',', $category);
      $lastElement = end($category);
      if (is_array($category)) {
        foreach ($category as $cat) {
          if ($cat === $lastElement) {
            $tagStr .= "'$cat|$cat$'";
          } else {
            $tagStr .= "'$cat|$cat$|'";
          }
        }
        $query .= ' AND tags RLIKE ' . $tagStr;
      }
    }

    if ($except) {
      $except_ids = array_map('intval', explode(',', $except));
      $query .= ' AND ' . $this->table . '.resource_id NOT IN (' . implode(',', $except_ids) . ')';
    }

    $sortTo = isset($settings->data['sort']['sortto']) ? $settings->data['sort']['sortto'] : 'asc';
    $query .= ' ORDER BY pos.position ' . strtoupper($sortTo);

    if (isset($settings->data['categories']['multiselect_results']) && $settings->data['categories']['multiselect_results'] === 'all_selected' && !empty($category) && $category !== '__all__') {
      $allImg = $this->db->get_results($query);
      sort($category);

      foreach ($allImg as $key => $img) {
        $tags = explode(',', $img->tags);
        sort($tags);
        if ($category != $tags) {
          unset($allImg[$key]);
        }
      }
      if ($limit) {
        $allImg = array_slice($allImg, 0, (int) $limit);
      }
      return $allImg;
    }

    if ($limit) {
      $query .= ' LIMIT ' . (int) $limit;
    }

    return $this->db->get_results($query);
  }

  public function showMoreTotalCount($galleryId, $category = null)
  {
    $settingsModel = new GridGallery_Galleries_Model_Settings();
    $settings = $settingsModel->get($galleryId);

    if (isset($settings->data['categories']['multiselect_results']) && $settings->data['categories']['multiselect_results'] === 'all_selected' && $category !== '__all__' && $category !== '') {
      return count($this->showMore($galleryId, null, null, $category));
    }

    $query =
      'SELECT COUNT(DISTINCT resource_id) as count
        FROM ' .
      $this->db->prefix .
      'gg_galleries_resources
        LEFT JOIN ' .
      $this->db->prefix .
      'gg_tags ON ' .
      $this->table .
      '.resource_id = ' .
      $this->db->prefix .
      'gg_tags.pid
        WHERE gallery_id = ' .
      (int) $galleryId;

    if (!empty($category) && $category !== '__all__') {
      $tagStr = '';
      $category = explode(',', $category);
      $lastElement = end($category);
      if (is_array($category)) {
        foreach ($category as $cat) {
          if ($cat === $lastElement) {
            $tagStr .= "'$cat|$cat$'";
          } else {
            $tagStr .= "'$cat|$cat$|'";
          }
        }
        $query .= ' AND tags RLIKE ' . $tagStr;
      }
    }

    $total = current($this->db->get_results($query));
    return $total->count;
  }

  /**
   * Updates a resource in gallery to point to a new resource
   * @param int $galleryId
   * @param string $resourceType
   * @param int $oldResourceId
   * @param int $newResourceId
   * @return bool
   */
  public function updateResource($galleryId, $resourceType, $oldResourceId, $newResourceId)
  {
    $query = $this->getQueryBuilder()->update($this->table)->set('resource_id', (int) $newResourceId)->where('gallery_id', '=', (int) $galleryId)->andWhere('resource_type', '=', $resourceType)->andWhere('resource_id', '=', (int) $oldResourceId);

    return $this->db->query($query->build()) !== false;
  }
}
