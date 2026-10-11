<?php

/**
 * Class GridGalleryPro_Galleries_Model_Settings
 *
 * @package GridGallery\Galleries\Model
 * @author Artur Kovalevsky
 */
class GridGalleryPro_Galleries_Model_Settings extends GridGallery_Galleries_Model_Settings
{
  public function getAutoPostsToRender($limit, $categories)
  {
    if (is_array($categories)) {
      $categories = implode(',', $categories);
    }

    if (strpos($categories, 'all') !== false) {
      $categories = '';
    }

    $rawPosts = get_posts([
      'numberposts' => $limit,
      'category' => $categories,
      'orderby' => 'date',
    ]);

    $posts = [];
    foreach ($rawPosts as $post) {
      $thumbnailId = get_post_thumbnail_id($post->ID);
      $image['attachment'] = wp_prepare_attachment_for_js($thumbnailId);
      $imageUrl = wp_get_attachment_image_src($thumbnailId, 'full');
      if ($imageUrl) {
        $imageUrl = current($imageUrl);
      }

      $id = $post->ID;

      $row = [];
      $row['author'] = get_user_by('id', $post->post_author)->user_login;
      $row['authorUrl'] = get_author_posts_url(get_the_author_meta($post->post_author), $row['author']);
      $row['title'] = $post->post_title;
      $row['content'] = strip_tags($post->post_content);
      $row['date'] = get_post_time('M j, Y', false, $id, true);
      $row['dateUrl'] = get_day_link(mysql2date('Y', $post->post_date_gmt), mysql2date('m', $post->post_date_gmt), mysql2date('d', $post->post_date_gmt));
      $row['categories'] = $this->getCategories($id);
      $row['url'] = get_permalink($id);
      $row['photo'] = $this->environment->getModule('galleries')->getModel('posts')->getPhoto($id, $post->post_content, $row['photoId']);
      $row['id'] = $id;
      $posts[] = $row;
    }
    return $posts;
  }

  public function getPostsToRender($gallery_id, $limit = null, $except = null, $category = null)
  {
    $posts = [];

    if (get_option('post_to_render' . $gallery_id)) {
      foreach (get_option('post_to_render' . $gallery_id) as $id) {
        if (count($posts) === $limit && !is_null($limit)) {
          return $posts;
        }

        if (!is_null($except) && !empty($except)) {
          $except_ids = array_map('intval', explode(',', $except));
          if (in_array($id, $except_ids)) {
            continue;
          }
        }

        $row = [];
        $post = get_post($id);
        $row['author'] = get_user_by('id', $post->post_author)->user_login;
        $row['authorUrl'] = get_author_posts_url(get_the_author_meta($post->post_author), $row['author']);
        $row['title'] = $post->post_title;
        $row['content'] = strip_tags($post->post_content);
        $row['date'] = get_post_time('M j, Y', false, $id, true);
        $row['dateUrl'] = get_day_link(mysql2date('Y', $post->post_date_gmt), mysql2date('m', $post->post_date_gmt), mysql2date('d', $post->post_date_gmt));
        $row['categories'] = $this->getCategories($id);

        if (!is_null($category) && !empty($category)) {
          $exists = false;
          foreach ($row['categories'] as $p_category) {
            if ($p_category['name'] == $category) {
              $exists = true;
            }
          }
          if (!$exists) {
            continue;
          }
        }

        $row['url'] = get_permalink($id);
        $row['photo'] = $this->environment->getModule('galleries')->getModel('posts')->getPhoto($id, $post->post_content, $row['photoId']);
        $row['id'] = $id;
        $posts[] = $row;
      }
    }
    return $posts;
  }

  public function getPagesToRender($gallery_id, $limit = null, $except = null, $category = null)
  {
    $pages = [];
    if (get_option('pages_to_render' . $gallery_id)) {
      foreach (get_option('pages_to_render' . $gallery_id) as $id) {
        if (count($pages) === $limit && !is_null($limit)) {
          return $pages;
        }

        if (!is_null($except) && !empty($except)) {
          $except_ids = array_map('intval', explode(',', $except));
          if (in_array($id, $except_ids)) {
            continue;
          }
        }

        $row = [];
        $page = get_post($id);
        //continue on failure
        if (is_null($page)) {
          continue;
        }
        $row['author'] = get_user_by('id', $page->post_author)->user_login;
        $row['authorUrl'] = get_author_posts_url(get_the_author_meta($page->post_author), $row['author']);
        $row['title'] = $page->post_title;
        $row['content'] = strip_tags($page->post_content);
        $row['date'] = get_post_time('M j, Y', false, $id, true);
        $row['dateUrl'] = get_day_link(mysql2date('Y', $page->post_date_gmt), mysql2date('m', $page->post_date_gmt), mysql2date('d', $page->post_date_gmt));
        $row['categories'] = $this->getCategories($id);

        if (!is_null($category) && !empty($category)) {
          $exists = false;
          foreach ($row['categories'] as $p_category) {
            if ($p_category['name'] == $category) {
              $exists = true;
            }
          }
          if (!$exists) {
            continue;
          }
        }

        $row['url'] = get_permalink($id);
        $row['photo'] = $this->environment->getModule('galleries')->getModel('posts')->getPhoto($id, $page->post_content, $row['photoId']);
        $row['id'] = $id;
        $pages[] = $row;
      }
    }
    return $pages;
  }
}
