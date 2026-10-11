<?php

class GridGalleryPro_Galleries_Model_Posts extends GridGallery_Core_BaseModel
{
  private $posts_;
  private $pages_;

  public function __construct()
  {
    parent::__construct();
  }

  public function getGalleriesByPost($id)
  {
    global $wpdb;
    $galleries = [];
    $result = $this->db->get_results('SELECT option_name FROM ' . $wpdb->options . " WHERE option_name LIKE 'post_to_render%' AND option_value LIKE '%\"" . $id . "\"%'");
    foreach ($result as $i => $row) {
      array_push($galleries, substr($row->option_name, 14));
    }

    $result = $this->db->get_results('SELECT option_name FROM ' . $wpdb->options . " WHERE option_name LIKE 'pages_to_render%' AND option_value LIKE '%\"" . $id . "\"%'");
    foreach ($result as $i => $row) {
      array_push($galleries, substr($row->option_name, 15));
    }
    return $galleries;
  }

  public function postsSelect()
  {
    global $wpdb;
    if (!$this->posts_) {
      $this->posts_ = $this->db->get_results('SELECT ID, post_title  FROM ' . $wpdb->posts . " WHERE post_type = 'post' AND post_status = 'publish' ORDER BY ID DESC");
    }
    $postsSelect = [];
    foreach ($this->posts_ as $post) {
      $postsSelect[$post->ID] = $post->post_title;
    }

    update_option('posts_select', $postsSelect);
    return $postsSelect;
  }

  public function pagesSelect()
  {
    global $wpdb;
    if (!$this->pages_) {
      $this->pages_ = $this->db->get_results('SELECT ID, post_title  FROM ' . $wpdb->posts . " WHERE post_type = 'page' AND post_status ='publish' ORDER BY ID DESC");
    }
    $pagesSelect = [];
    foreach ($this->pages_ as $page) {
      $pagesSelect[$page->ID] = $page->post_title;
    }

    update_option('pages_select', $pagesSelect);
    return $pagesSelect;
  }

  public function getPostsInformation($galleryId)
  {
    $postsRender = [];

    if (get_option('post_to_render' . $galleryId)) {
      foreach (get_option('post_to_render' . $galleryId) as $id) {
        $row = [];
        $post = get_post($id);
        if (!$post) {
          continue;
        }
        $row['author'] = get_user_by('id', $post->post_author)->user_nicename;
        $row['title'] = $post->post_title;
        $row['id'] = $post->ID;
        $row['comments'] = $post->comment_count;
        $row['date'] = $post->post_date;
        $row['type'] = 'Post';
        $photo = $this->getPhoto($id, $post->post_content);
        $row['photo'] = !empty($photo) ? $photo : plugins_url('', dirname(__FILE__)) . '/img/noimage.png';
        $postsRender[] = $row;
      }
    }
    return $postsRender;
  }

  public function getPagesInformation($galleryId)
  {
    $pagesRender = [];

    if (get_option('pages_to_render' . $galleryId)) {
      foreach (get_option('pages_to_render' . $galleryId) as $id) {
        $row = [];
        $page = get_page($id);
        if (!$page) {
          continue;
        }
        $row['author'] = get_user_by('id', $page->post_author)->user_nicename;
        $row['title'] = $page->post_title;
        $row['id'] = $page->ID;
        $row['comments'] = $page->comment_count;
        $row['date'] = $page->post_date;
        $row['type'] = 'Page';
        $row['photo'] = !empty($this->getPhoto($id, $page->post_content)) ? $this->getPhoto($id, $page->post_content) : plugins_url('', dirname(__FILE__)) . '/img/noimage.png';
        $pagesRender[] = $row;
      }
    }
    return $pagesRender;
  }

  public function savePost($galleryId, $postId)
  {
    $posts_id = [];
    $currentPosts = get_option('post_to_render' . $galleryId);
    if (!$currentPosts) {
      array_push($posts_id, $postId);
    } else {
      foreach ($currentPosts as $current) {
        array_push($posts_id, $current);
      }
      array_push($posts_id, $postId);
    }
    update_option('post_to_render' . $galleryId, $posts_id);
    $post = (array) get_post($postId);
    $post['photo'] = $this->getPhoto($postId, $post['post_content']);

    return (object) $post;
  }

  public function savePage($galleryId, $pageId)
  {
    $pages_id = [];
    $currentPages = get_option('pages_to_render' . $galleryId);
    if (!$currentPages) {
      array_push($pages_id, $pageId);
    } else {
      foreach ($currentPages as $current) {
        array_push($pages_id, $current);
      }
      array_push($pages_id, $pageId);
    }
    update_option('pages_to_render' . $galleryId, $pages_id);
    $page = (array) get_post($pageId);
    $page['photo'] = $this->getPhoto($pageId, $page['post_content']);

    return (object) $page;
  }

  public function getPhoto($postId, $content, &$photoId = '')
  {
    $photoId = get_post_thumbnail_id($postId);
    if ($photoId) {
      return wp_get_attachment_thumb_url($photoId);
    }

    $photoId = '';
    $tag = strpos($content, '<img ');
    if ($tag === false) {
      return false;
    }
    $img = substr($content, $tag, strpos($content, '>', $tag + 5) - $tag + 1);
    try {
      $attr = new SimpleXMLElement($img);
      if (!isset($attr['src'])) {
        return false;
      }
      if (isset($attr['class'])) {
        $classes = explode(' ', (string) $attr['class']);
        foreach ($classes as $i => $class) {
          if (strpos($class, 'wp-image-') === 0) {
            $photoId = substr($class, 9);
            break;
          }
        }
      }
      return (string) $attr['src'];
    } catch (Exception $e) {
    }

    return false;
  }
}

?>
