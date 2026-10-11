<?php

/**
 * Class GridGalleryPro_Galleries_Module.
 * Implementation of the PRO version of the Galleries module.
 */
class GridGalleryPro_Galleries_Module extends GridGallery_Galleries_Module
{
  /**
   * {@inheritdoc}
   */
  public function onInit()
  {
    // Overload the free version with the pro.
    $this->setOverloadController(true);

    $this->setAjaxActions();

    add_action('save_post', [$this, 'checkPostsForGalleryCacheRemove'], 10, 1);
    add_action('delete_post', [$this, 'checkPostsForGalleryCacheRemove'], 10, 1);

    // Initialize galleries module from the free version.
    parent::onInit();
  }

  public function getFrontendJS()
  {
    return array_merge(parent::getFrontendJS(), [
      [
        'handle' => 'pro-frontend',
        'source' => $this->getProLocationUrl() . '/js/frontend.js',
      ],
      [
        'handle' => 'sgg-freewall',
        'source' => $this->getProLocationUrl() . '/js/lib/freewall.js',
      ],
    ]);
  }

  public function getFrontendCSS()
  {
    $frontendCssArr = [];
    $frontendCssArr[] = $this->getEnvironment()->getConfig()->get('plugin_url') . '/app/assets/css/libraries/fontawesome/font-awesome.min.css';
    $frontendCssArr[] = $this->getProLocationUrl() . '/css/grid-gallery-pro.galleries.effects.css';
    $frontendCssArr[] = $this->getProLocationUrl() . '/css/gg.pro.styles.css';
    return array_merge(parent::getFrontendCSS(), $frontendCssArr);
  }

  public function loadFrontendAssets()
  {
    parent::loadFrontendAssets();
    // wp_localize_script('pro-frontend', 'window.sggHelper',
    //     array(
    //         'ajax_url' => admin_url('admin-ajax.php'),
    //         'action' => $this->getConfig()->get('hooks_prefix', 'gg') . 'showMore',
    //         'action_download' => $this->getConfig()->get('hooks_prefix', 'gg') . 'downloadImage'
    //     )
    // );
    wp_enqueue_script('pro-frontend-helper', $this->getProLocationUrl() . '/js/pro-frontend-helper.js', ['jquery'], '1.0', true);
    $script = "window.sggHelperajax_url = '" . admin_url('admin-ajax.php') . "';";
    $script .= "window.sggHelperaction = '" . $this->getConfig()->get('hooks_prefix', 'gg') . 'showMore' . "';";
    $script .= "window.sggHelperaction_download = '" . $this->getConfig()->get('hooks_prefix', 'gg') . 'downloadImage' . "';";
    wp_add_inline_script('pro-frontend-helper', $script);
  }

  public function getBackendCSS()
  {
    $version = $this->getConfig()->get('pro_plugin_version');
    return array_merge(parent::getBackendCSS(), [
      [
        'source' => $this->getProLocationUrl() . '/css/backend.css',
        'version' => $version,
      ],
      [
        'source' => $this->getProLocationUrl() . '/css/tags.css',
        'version' => $version,
      ],
      $this->getProLocationUrl() . '/css/grid-gallery-pro.galleries.effects.css',
    ]);
  }

  public function getBackendJS()
  {
    $version = $this->getConfig()->get('pro_plugin_version');

    $scripts = array_merge(parent::getBackendJS(), [
      [
        'source' => $this->getProLocationUrl() . '/js/tags.js',
        'dependencies' => ['jquery-ui-autocomplete'],
        'version' => $version,
      ],
    ]);

    if ($this->isModule('galleries', 'settings')) {
      $scripts = array_merge($scripts, [
        [
          'handle' => 'pro-settings',
          'source' => $this->getProLocationUrl() . '/js/settings.js',
          'version' => $version,
          'dependencies' => ['jquery.jqGrid.min.js'],
        ],
      ]);
    }

    return $scripts;
  }

  /**
   * Shortcode callback.
   * @param  array $attributes An array of the shortcode parameters.
   * @return string
   */
  public function getGallery($attributes)
  {
    if (is_feed()) {
      return;
    }
    $this->loadFrontendAssets();
    $id = $attributes['id'];
    $cachePath = $this->getCache($id);
    $settingsModel = $this->getModel('settings');
    //$membershipModel = $this->getModel('membership');

    $membershipModel = false;

    // if($membershipModel->isPluginActive() && isset($attributes['image-list']) && count($attributes['image-list'])) {
    // 	$cachePath .= '-' . md5(json_encode($attributes['image-list']));
    // 	$attributes['membershipModel'] = $this->getModel('membership');
    // }

    global $wpdb;
    // $optValue = get_option($wpdb->prefix . $this->getConfig()->get('db_prefix') . 'rand_sorts');

    // if($optValue === false
    // 	|| !isset($optValue['id']) || !isset($optValue['val'])
    // 	|| !($optValue['id'] == $id && $optValue['val'] === true)) {

    // 	if (file_exists($cachePath) && $this->getEnvironment()->isProd()) {
    // 		$this->initSocialSharePlugin($id);
    // 		$cacheEntry = file_get_contents($cachePath);

    // 		// if CDN enable, replace HTTP_HOST
    // 		$this->replacePhotoHttpHostForCdnServer($cacheEntry, $id);
    // 		// if two identical galleries on page
    // 		$cacheEntry = preg_replace('/grid-gallery-([\d]+)-[\d]+/', 'grid-gallery-\1-' . rand(1, 99999), $cacheEntry);
    // 		return $cacheEntry;
    // 	}
    // }

    // Backward compatible with version 1.3.7.
    // In case when user have new pro version installed and old free we return old realization.
    if (version_compare($this->getConfig()->get('plugin_version'), '1.3.7') <= 0) {
      return parent::getGallery($attributes);
    }

    if ($init = $this->initGallery($attributes)) {
      extract($init);
      $limit = null;
      $posts = [];
      $pages = [];
      $postsLength = 0;

      if (property_exists($gallery, 'photos') && is_array($gallery->photos)) {
        foreach ($gallery->photos as $photo) {
          $photo->attachment['caption'] = html_entity_decode($photo->attachment['caption']);
          $photo->attachment['description'] = html_entity_decode($photo->attachment['description']);

          if (isset($photo->attachment['linkedImages']) && !empty($photo->attachment['linkedImages'])) {
            $linkedImages = [];
            $linkedImagesIds = explode(',', $photo->attachment['linkedImages']);
            foreach ($linkedImagesIds as $attachmentId) {
              $attachment = wp_prepare_attachment_for_js($attachmentId);
              array_push($linkedImages, [
                'id' => $attachment['id'],
                'title' => $attachment['title'],
                'alt' => $attachment['alt'],
                'description' => $attachment['description'],
                'full' => $attachment['sizes']['full']['url'],
                'thumbnail' => isset($attachment['sizes']['thumbnail']) ? $attachment['sizes']['thumbnail']['url'] : '',
              ]);
            }
            $photo->attachment['linkedImages'] = $linkedImages;
          }
        }
      }

      if (isset($settings->data['showMore']) && $settings->data['showMore']['enabled'] === 'true') {
        if (!empty($gallery->photos)) {
          $limit = $settings->data['showMore']['imagesAmount'] - count($gallery->photos);
        } else {
          $limit = $settings->data['showMore']['imagesAmount'];
        }
      }
      if (isset($settings->data['posts']) && $settings->data['posts']['enable'] === '1') {
        if (isset($settings->data['posts']['autoposts']) && $settings->data['posts']['autoposts']) {
          //if user doesn't select any category or doesn't set autoposts number
          $autopostsCategories = isset($settings->data['posts']['autopostsCategories']) ? $settings->data['posts']['autopostsCategories'] : '';
          $autopostsNumber = isset($settings->data['posts']['autopostsNumber']) ? $settings->data['posts']['autopostsNumber'] : 5;
          $posts = $settingsModel->getAutoPostsToRender($autopostsNumber, $autopostsCategories);
          $postsLength = $settings->data['posts']['autopostsNumber'];
        } else {
          $posts = $settingsModel->getPostsToRender($attributes['id'], $limit);
          if (!is_null($limit)) {
            $posts = array_slice($posts, 0, $limit);
            $limit = $limit - count($posts);
            $pages = array_slice($pages, 0, $limit);
          }
          $pages = $settingsModel->getPagesToRender($attributes['id'], $limit);

          $postsLength = count($posts) + count($pages);
        }
      }

      $thumbNailWidth = (int) get_option('thumbnail_size_w');
      $thumbNailHeight = (int) get_option('thumbnail_size_h');
      if (!$thumbNailWidth) {
        $thumbNailWidth = 150;
      }
      if (!$thumbNailHeight) {
        $thumbNailHeight = 150;
      }
      $settings->data['thumbnailSize'] = $thumbNailWidth . 'x' . $thumbNailHeight;

      if (!empty($settings->data['socialSharing']['gallerySharing']['socialIcons'])) {
        $galleryModel = $this->getModel('galleries');
        $socialIcons = $galleryModel->getSocialShareList($settings->data['socialSharing']['gallerySharing']['socialIcons']);
      } else {
        $socialIcons = false;
      }

      $gallery->random_val = rand(1, 99999);
      $renderData = $this->render('@galleries_pro/shortcode/gallery.twig', [
        'gallery' => $gallery,
        'settings' => is_object($settings) ? $settings->data : $settings,
        'socialIcons' => $socialIcons,
        'colorbox' => $this->getEnvironment()->getModule('colorbox')->getLocationUrl(),
        'categories' => isset($gallery->settings['categories']) ? $gallery->settings['categories'] : [],
        'postsLength' => $postsLength,
        'posts' => $posts,
        'pages' => $pages,
        'isMobile' => $settingsModel->isMobile(true),
        'mobile' => isset($settings->data['box']['mobile']) ? $settingsModel->isMobile($settings->data['box']['mobile']) : null,
      ]);

      // if (isset($this->cacheDirectory)) {
      //     file_put_contents($cachePath, $renderData);
      // }
      // if CDN enable, replace HTTP_HOST
      $this->replacePhotoHttpHostForCdnServer($renderData, $id);

      $showLoveLink = '';
      $settings = get_option('sg_settings');
      if (empty($settings['remove_love_link'])) {
        if (!empty($settings['add_love_link'])) {
          $showLoveLink = $this->render('@galleries/lovelink/show.twig', []);
        } else {
          $showLoveLink = $this->render('@galleries/lovelink/showhidden.twig', []);
        }
      } else {
        $showLoveLink = '';
      }
      if (!$this->checkLoveLink()) {
        $showLoveLink = '';
      }
      $renderData = $renderData . $showLoveLink;

      return $renderData;
    }
  }

  public function initGallery($attributes)
  {
    $galleries = $this->getModel('galleries');
    $galleries->frontend = true;
    $gallery = $galleries->getById($attributes);

    if (!$gallery) {
      return;
    }

    $settings = $this->getGallerySettings($attributes['id']);

    // $settings->data['socialSharing'] = $this->initGallerySocialShare($settings->data);
    $posArray = ['left', 'center', 'right'];

    $settings->data['area']['position'] = isset($settings->data['area']['position']) && isset($posArray[$settings->data['area']['position']]) ? $posArray[$settings->data['area']['position']] : 'center';

    $settings->data['rtl'] = is_rtl();

    if (property_exists($gallery, 'photos') && is_array($gallery->photos)) {
      $positionModel = $this->getModel('position');
      $positions = $positionModel->setPosition($gallery->photos, 'gallery', $gallery->id);

      foreach ($gallery->photos as $index => $row) {
        foreach ($positions as $pos) {
          if ($row->id == $pos->photo_id) {
            $gallery->photos[$index]->position = $pos->position;
          }
        }
      }

      //ASC && DESC sort
      if (isset($settings->data['sort'])) {
        $gallery->photos = $positionModel->sort($gallery->photos, $settings->data['sort']);
      } else {
        $gallery->photos = $positionModel->sort($gallery->photos);
      }
      // #821
      if (isset($settings->data['showMore']) && $settings->data['showMore']['enabled'] === 'true' && !is_admin()) {
        if ($settings->data['area']['grid'] == '4' && $settings->data['mosaic']['use2Layout'] != '1') {
        } else {
          $limit = $settings->data['showMore']['imagesAmount'];
          $totalPhotos = count($gallery->photos);
          $settings->data['total'] = $totalPhotos;
          $gallery->photos = array_slice($gallery->photos, 0, (int) $limit);
        }
      }
    }

    $settingsModel = $this->getModel('settings');
    return compact('gallery', 'settings', 'settingsModel');
  }

  /**
   * Returns the URL to the PRO version of the module.
   * @return string
   */
  public function getProLocationUrl()
  {
    [$dirname, $plugins] = $this->normalizeSlashes([dirname(__FILE__), realpath(WP_PLUGIN_DIR)]);

    $url = plugins_url(str_replace($plugins, '', $dirname));

    return $url;
  }

  public function isTranslationPluginExists()
  {
    return class_exists('frameTbs');
  }

  public function getCache($galleryId)
  {
    $protocol = is_ssl() ? '-ssl' : '';
    $cachePath = $this->cacheDirectory . DIRECTORY_SEPARATOR . $galleryId;
    if ($this->getModel('settings')->isMobile(true)) {
      $cachePath .= 'm';
    }
    if ($this->isTranslationPluginExists() && method_exists(frameTbs::_()->getModule('lang'), 'getLocale')) {
      $cachePath .= '-' . frameTbs::_()->getModule('lang')->getLocale();
    }
    $cachePath .= $protocol;
    return $cachePath;
  }

  public function cleanCache($galleryId, $cleanOtherCache = false)
  {
    if (empty($galleryId)) {
      return;
    }

    $cachePath = $this->getConfig()->get('plugin_cache_galleries') . DIRECTORY_SEPARATOR;
    $files = glob($cachePath . $galleryId . '*');

    if ($cleanOtherCache) {
      // remove all cache
      array_map('unlink', glob($cachePath . '*'));
      return;
    }

    foreach ($files as $file) {
      if (preg_match('/^(?:\d+(?:$|-.*$|m))/m', basename($file))) {
        if (file_exists($file)) {
          unlink($file);
        }
      }
    }
  }

  protected function normalizeSlashes($str)
  {
    if (is_array($str)) {
      return array_map([$this, 'normalizeSlashes'], $str);
    }

    return str_replace('\\', '/', $str);
  }

  public function setAjaxActions()
  {
    $hooks_prefix = $this->getConfig()->get('hooks_prefix', 'gg');
    $actions = [$hooks_prefix . 'showMore', $hooks_prefix . 'downloadImage'];
    foreach ($actions as $action) {
      add_action('wp_ajax_' . $action, [$this->getController(), str_replace($hooks_prefix, '', $action) . 'Action']);
      add_action('wp_ajax_nopriv_' . $action, [$this->getController(), str_replace($hooks_prefix, '', $action) . 'Action']);
    }
  }

  /**
   * Registers the Gallery by Supsystic shortcode in the WordPress.
   */
  public function registerShortcode()
  {
    parent::registerShortcode();
    $attachment = new GridGalleryPro_Galleries_Attachment();
    $this->getEnvironment()
      ->getTwig()
      ->addFunction(new Twig_SupTwgSgg_SimpleFunction('get_attachment', [$attachment, 'getAttachment']));

    $this->getEnvironment()
      ->getTwig()
      ->addFunction(new Twig_SupTwgSgg_SimpleFunction('set_attachment_settings', [$attachment, 'setAttachmentSettings']));

    $this->getEnvironment()
      ->getTwig()
      ->addFunction(new Twig_SupTwgSgg_SimpleFunction('get_img_url_with_watermark', [$attachment, 'getImgUrlWithWatermark']));

    $this->getEnvironment()
      ->getTwig()
      ->addFunction(new Twig_SupTwgSgg_SimpleFunction('check_gd_function_exists', [$this, 'checkGdFunctionExists']));
  }

  /**
   * Check server for php component GD (Image Processing and Generation)
   */
  public function checkGdFunctionExists()
  {
    if (!function_exists('imagepng')) {
      return false;
    }
    if (!function_exists('imagecopymerge')) {
      return false;
    }
    if (!function_exists('getimagesize')) {
      return false;
    }
    if (!function_exists('imagerotate')) {
      return false;
    }
    if (!function_exists('imageSaveAlpha')) {
      return false;
    }
    if (!function_exists('imageAlphaBlending')) {
      return false;
    }
    if (!function_exists('imagefill')) {
      return false;
    }
    if (!function_exists('imagecolortransparent')) {
      return false;
    }
    if (!function_exists('imagecolorallocatealpha')) {
      return false;
    }
    if (!function_exists('imagesetinterpolation')) {
      return false;
    }
    return true;
  }

  /**
   * check current post category list and Remove gallery cache
   */
  public function checkPostsForGalleryCacheRemove($postId)
  {
    // if this not "create" action (update or delete)
    if (count($_REQUEST) > 0 && $postId) {
      $ggSet = new GridGallery_Galleries_Model_Settings();
      $ggList = $ggSet->getSettListByRegExp();

      if (count($ggList) > 0) {
        $categoriesArr = wp_get_post_categories($postId);

        foreach ($ggList as $key => $val) {
          $needToRemoveGalleryCache = false;

          // for nonSelected (all categories)
          if (!isset($ggList[$key]->data['posts']['autopostsCategories'])) {
            $needToRemoveGalleryCache = true;
          } elseif (in_array('all', $ggList[$key]->data['posts']['autopostsCategories'])) {
            // Auto Posts Categories == 'all'
            $needToRemoveGalleryCache = true;
          } else {
            $interSectRes = array_intersect($ggList[$key]->data['posts']['autopostsCategories'], $categoriesArr);

            if (count($interSectRes) > 0) {
              $needToRemoveGalleryCache = true;
            }
          }

          if ($needToRemoveGalleryCache) {
            $this->cleanCache($ggList[$key]->gallery_id);
          }
        }
      }
      $galleries = $this->getModel('posts')->getGalleriesByPost($postId);
      foreach ($galleries as $key => $gallery_id) {
        $this->cleanCache((int) $gallery_id);
      }
    }
  }
}
