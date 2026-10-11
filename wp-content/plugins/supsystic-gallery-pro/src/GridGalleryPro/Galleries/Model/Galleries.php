<?php

class GridGalleryPro_Galleries_Model_Galleries extends GridGallery_Galleries_Model_Galleries
{
  /**
   * Extends default gallery select
   *
   * @param object $gallery The result of the SELECT
   * @return mixed
   */
  protected function extend($gallery, $attributes = [])
  {
    if (!is_object($gallery)) {
      if ($this->debugEnabled) {
        wp_die(sprintf('The $gallery variable must be type of object, %s given', gettype($gallery)));
      }

      return $gallery;
    }

    $settingsModel = new GridGallery_Galleries_Model_Settings($this->debugEnabled);
    $settings = $settingsModel->getByGalleryId($gallery->id);
    if (is_object($settings)) {
      $gallery->settings_id = $settings->id;
      $gallery->settings = $settings->data;
    }

    // if(isset($attributes) && isset($attributes['membershipModel'])) {
    // 	$memberShipModel = $attributes['membershipModel'];
    // 	foreach($attributes['image-list'] as $oneImage) {
    // 		$gallery->photos[] = $memberShipModel->getGalleryAttachmenEmuledImage($oneImage);
    // 	}
    // } else {

    $resourcesModel = new GridGalleryPro_Galleries_Model_Resources($this->debugEnabled);

    if (isset($settings->data['showMore']) && $settings->data['showMore']['enabled'] === 'true' && property_exists($this, 'frontend')) {
      $limit = $settings->data['showMore']['imagesAmount'];
      if ($settings->data['categories']['enabled'] !== 'true') {
        // #821
        //					$resourcesData = $resourcesModel->showMore($gallery->id, $limit);
        $resourcesData = $resourcesModel->showMore($gallery->id);
      } else {
        $resourcesData = $resourcesModel->getByGalleryId($gallery->id);
      }
    } else {
      $resourcesData = $resourcesModel->getByGalleryId($gallery->id);
    }

    if (!$resourcesData) {
      return $gallery;
    }

    $photosModel = new GridGalleryPro_Photos_Model_Photos($this->debugEnabled);
    $photosModel->setGallerySettings(is_object($settings) ? $settings : false);
    $gallery->photos = $photosModel->getPhotos($resourcesData);

    // Place here because we need categories order both in frontend and backend.
    $gallery = $this->setCategoriesOrder($gallery);

    //Get all tags from gallery
    $tagsModel = new GridGalleryPro_Galleries_Model_Tags($this->debugEnabled);
    $gallery->tags = $tagsModel->getAllTags($gallery->id);

    if (isset($gallery->settings['attributes']) && isset($gallery->settings['attributes']['order']) && sizeof($gallery->settings['attributes']['order']) > 0) {
      $attributesModel = new GridGalleryPro_Galleries_Model_Attributes($this->debugEnabled);
      $gallery->attributes = $attributesModel->getAllAttributesValues($gallery->photos);
    }
    // }

    return $gallery;
  }

  /**
   * Set order of the categories
   * @param object $gallery
   * @return object $gallery
   */
  public function setCategoriesOrder($gallery)
  {
    $settingsModel = new GridGallery_Galleries_Model_Settings($this->debugEnabled);

    // If not set any categories
    if (!isset($gallery->settings['categories']['order'])) {
      $gallery->settings['categories']['order'] = ['__all__'];
    }

    $settings = $gallery->settings;

    $categories = ['__all__'];

    if (property_exists($gallery, 'photos')) {
      foreach ($gallery->photos as $photo) {
        if (property_exists($photo, 'tags') && is_array($photo->tags) && count($photo->tags) > 0) {
          foreach ($photo->tags as $tag) {
            $categories[] = $tag;
          }
        }
      }
    }

    // Add categories from posts or pages if enable.
    if (isset($settings['posts']) && $settings['posts']['enable']) {
      foreach ($settingsModel->getPostsToRender($gallery->id) as $post) {
        foreach ($post['categories'] as $category) {
          $categories[] = $category['name'];
        }
      }

      foreach ($settingsModel->getPagesToRender($gallery->id) as $page) {
        foreach ($page['categories'] as $category) {
          $categories[] = $category['name'];
        }
      }
    }

    // All stored categories in db for this gallery
    $categories = array_unique($categories);

    // Merge saved categories with new categories in db if new exist
    $merged = array_merge($settings['categories']['order'], array_diff($categories, $settings['categories']['order']));

    // Left only those categories that exist in db and filter empty
    $gallery->settings['categories']['order'] = array_filter(array_intersect($merged, $categories));

    return $gallery;
  }

  public function cloneGallery($galleryId, $cloneType, $parameters)
  {
    $language = $parameters['environment']->getLang();
    $tagsModel = $parameters['tags'];
    $hasError = false;
    $message = '';
    $res1 = parent::cloneGallery($galleryId, $cloneType, $parameters);
    if (isset($res1['message']) && isset($res1['isError']) && isset($res1['newGalleryId'])) {
      $message = $res1['message'];
      $hasError = $res1['isError'];
      $newGalleryId = $res1['newGalleryId'];

      if ($cloneType != 2) {
        // all tags attached to image
      }
    } else {
      $message = $language->translate('Pro plugin params Error!');
      $hasError = true;
    }

    return [
      'message' => $message,
      'isError' => $hasError,
      'newGalleryId' => $newGalleryId,
    ];
  }

  public function getSocialShareListSelect()
  {
    return [
      1 => 'Facebook',
      2 => 'X',
      20 => 'Telegram',
      21 => 'Viber',
      18 => 'WhatsApp',
      13 => 'Linkedin',
      6 => 'Reddit',
      7 => 'Pinterest',
      19 => 'Tumblr',
      11 => 'Livejournal',
      4 => 'VKontakte',
      12 => 'Odnoklassniki',
      14 => 'Print',
      15 => 'Add Bookmark',
    ];
  }

  public function getSocialShareList($ids = false)
  {
    $socialShareList = [];
    $socialShareList[] = [
      'id' => 1,
      'name' => 'Facebook',
      'url' => 'https://www.facebook.com/sharer.php?u={url}',
      'class' => 'facebook',
      'brand_primary' => '#3b5998',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 2,
      'name' => 'X',
      'url' => 'https://twitter.com/share?url={url}&text={title}',
      'class' => 'xtwitter',
      'brand_primary' => '#55acee',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 4,
      'name' => 'VKontakte',
      'url' => 'https://vk.com/share.php?url={url}',
      'class' => 'vk',
      'brand_primary' => '#45668e',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 6,
      'name' => 'Reddit',
      'url' => 'https://reddit.com/submit?url={url}&title={title}',
      'class' => 'reddit',
      'brand_primary' => '#cee3f8',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 7,
      'name' => 'Pinterest',
      'url' => 'https://pinterest.com/pin/create/link/?url={url}&description={title}',
      'class' => 'pinterest',
      'brand_primary' => '#cc2127',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 11,
      'name' => 'Livejournal',
      'url' => 'https://www.livejournal.com/update.bml?subject={title}&event={url}',
      'class' => 'livejournal',
      'brand_primary' => '#3399ff',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 12,
      'name' => 'Odnoklassniki',
      'url' => 'https://connect.ok.ru/offer?url={url}&title={title}',
      'class' => 'odnoklassniki',
      'brand_primary' => '#3399ff',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 13,
      'name' => 'Linkedin',
      'url' => 'https://www.linkedin.com/shareArticle?mini=true&title={title}&url={url}',
      'class' => 'linkedin',
      'brand_primary' => '#3399ff',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 14,
      'name' => 'Print',
      'url' => '#',
      'class' => 'print',
      'brand_primary' => '#3399ff',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 15,
      'name' => 'Add Bookmark',
      'url' => '#',
      'class' => 'bookmark',
      'brand_primary' => '#3399ff',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 18,
      'name' => 'WhatsApp',
      'url' => 'https://web.whatsapp.com/send?text={url}',
      'class' => 'whatsapp',
      'brand_primary' => '#43C353',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 19,
      'name' => 'Tumblr',
      'url' => 'https://www.tumblr.com/share/link?url={url}&name={title}&description={description}',
      'class' => 'tumblr',
      'brand_primary' => '#36465D',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 20,
      'name' => 'Telegram',
      'url' => 'https://t.me/share/url?url={url}&text={title}',
      'class' => 'telegram',
      'brand_primary' => '#229ED9',
      'brand_secondary' => '#ffffff',
    ];
    $socialShareList[] = [
      'id' => 21,
      'name' => 'Viber',
      'url' => 'viber://forward?text={title}%20{url}',
      'class' => 'viber',
      'brand_primary' => '#7360f2',
      'brand_secondary' => '#ffffff',
    ];

    if ($ids) {
      // global $post;
      // $url = $post ? urlencode(get_the_permalink($post)) : urlencode(home_url());
      // $title = $post ? get_the_title($post) : get_bloginfo('name');
      // $description = $post ? urlencode( wp_trim_words($post->post_content)) : urlencode(get_bloginfo('description '));

      $originalKeys = array_keys($ids);
      foreach ($socialShareList as $key => $share) {
        if (in_array($share['id'], $ids)) {
          $newListReturn[] = $socialShareList[$key];
        }
      }
      if (!empty($newListReturn)) {
        foreach ($newListReturn as $key => $list) {
          $socialUrl = $newListReturn[$key]['url'];
          $newListReturn[$key]['url_original'] = $socialUrl;
          // $socialUrl = str_replace('{url}', $url, $socialUrl);
          // $socialUrl = str_replace('{title}', $title, $socialUrl);
          // $socialUrl = str_replace('{description}', $description, $socialUrl);
          $newListReturn[$key]['url'] = $socialUrl;
        }
      }
      $customSort = function ($a, $b) use ($ids) {
        return array_search($a['id'], $ids) - array_search($b['id'], $ids);
      };
      usort($newListReturn, $customSort);
      return $newListReturn;
    }
    return $socialShareList;
  }
}
