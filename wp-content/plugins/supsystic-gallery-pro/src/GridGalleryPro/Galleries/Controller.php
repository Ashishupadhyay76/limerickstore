<?php

class GridGalleryPro_Galleries_Controller extends GridGallery_Galleries_Controller
{
  public function requireNonces()
  {
    return array_merge(parent::requireNonces(), ['savePostsAction', 'savePagesAction', 'removePostsAction', 'addTagAction', 'allImageTagsAction', 'updateButtonsPresetAction', 'deleteButtonsPresetAction', 'updateButtonsSettingsAction', 'getWatermarkPhotosInfoAction', 'addOneWatermarkAction']);
  }
  /**
   * {@inheritdoc}
   */
  protected function getModelAliases()
  {
    $aliases = [
      'galleries' => 'GridGalleryPro_Galleries_Model_Galleries',
      'tags' => 'GridGalleryPro_Galleries_Model_Tags',
      'attributes' => 'GridGalleryPro_Galleries_Model_Attributes',
      'posts' => 'GridGalleryPro_Galleries_Model_Posts',
      'resources' => 'GridGalleryPro_Galleries_Model_Resources',
      'settings' => 'GridGalleryPro_Galleries_Model_Settings',
      'photos' => 'GridGallery_Photos_Model_Photos',
      'photos_pro' => 'GridGalleryPro_Photos_Model_Photos',
      'awesomeFonts' => 'GridGalleryPro_Galleries_Model_FontAwesome',
    ];

    // Backward compatible with version 1.3.7.
    // In case when user have new pro version installed and old free
    if (version_compare($this->getConfig()->get('plugin_version'), '1.3.7') <= 0) {
      unset($aliases['galleries']);
    }

    return array_merge(parent::getModelAliases(), $aliases);
  }

  /**
   * Gallery settings.
   * Overloads the default method.
   * @param  RscSgg_Http_Request $request HTTP request.
   * @return RscSgg_Http_Response
   */
  public function settingsAction(RscSgg_Http_Request $request)
  {
    if (null === ($id = $request->query->get('gallery_id'))) {
      return $this->redirect($this->generateUrl('galleries'));
    }

    $this->getEnvironment()->getConfig()->load('@galleries/tooltips.php');
    $tooltips = $this->getEnvironment()->getConfig()->get('tooltips');
    $this->getEnvironment()->getConfig()->load('@galleries_pro/tooltips.php');
    $tooltips_pro = $this->getEnvironment()->getConfig()->get('tooltips');
    $tooltips = array_merge($tooltips, $tooltips_pro);

    $icon = $this->getEnvironment()->getConfig()->get('tooltips_icon');

    $tooltips = array_map([$this, 'rewrite'], $tooltips);

    $this->getEnvironment()->getTwig()->addGlobal('tooltips', $tooltips);
    $this->getEnvironment()->getTwig()->addGlobal('tooltips_icon', $icon);

    $this->getEnvironment()->getTwig()->addFunction(new Twig_SupTwgSgg_SimpleFunction('get_image_src', 'wp_get_attachment_image_src'));

    $gallery = $this->getGallery($id);
    $preset = $this->getPreset($gallery->settings_id);
    if (!property_exists($gallery, 'settings') || null === $gallery->settings) {
      $config = $this->getEnvironment()->getConfig();
      $config->load('@galleries/settings.php');

      $gallery->settings = unserialize($config->get('gallery_settings'));
    }

    $galleryModule = $this->getModule('galleries');
    // $gallery->settings['socialSharing'] = $galleryModule->initGallerySocialShare($gallery->settings);
    $gallery->settings['rtl'] = is_rtl();

    $buttonsPresets = get_option('grid_gallery_buttons_presets');

    if (!$buttonsPresets) {
      $buttonsPresets = current(include dirname(__FILE__) . '/configs/buttons_presets.php');
    }

    $posts = $this->getModel('posts');
    $uiModule = $this->getModule('ui');
    $fontList = array_merge($this->getModel('settings')->getFontsList(), $uiModule->getStandardFontsList());
    sort($fontList);
    $categoriesPresets = get_option('customCatsPresets');
    $paginationPresets = get_option('customPagesPresets');
    // $membershipModel = $this->getModel('membership');
    // $pageOptions = array(
    // 	'isSettingPage' => 1,
    // 	'isMembershipPluginActive' => $membershipModel->isPluginActive(),
    // 	'membershipInstallWpUrl' => $membershipModel->getPluginInstallWpUrl(),
    // 	'membershipInstallUrl' => $membershipModel->getPluginInstallUrl(),
    // );

    $membershipModel = false;
    $pageOptions = [];

    $awesomeFontModel = $this->getModel('awesomeFonts');
    $awesomeIconsList = $awesomeFontModel->getIconsClassList();

    if ($request->query['clone_type'] != null && $request->query['oldGalleryId'] != null) {
      $cloneType = $request->query['clone_type'];
      $oldGalleryId = $request->query['oldGalleryId'];
      if ($cloneType != 2) {
        // Image optimize
        $imageOptimizeModel = $this->getModel('imageOptimize');
        $optimizeInfo = $imageOptimizeModel->getInfoByGalleryId($oldGalleryId);
        if (!empty($optimizeInfo[0]['service_code'])) {
          $ioServiceCode = $optimizeInfo[0]['service_code'];
          $requirements = $imageOptimizeModel->checkRequirements();
          $imgOptimizationSett = $this->getModel('optimization')->getServiceSettings();

          if (!$requirements && isset($imgOptimizationSett['setting'][$ioServiceCode]['auth_key'])) {
            $ioParams = $imgOptimizationSett;
            $ioParams['current'] = $ioServiceCode;
          }
        }
        // CDN
        $cdnModel = $this->getModel('cdn');
        $cdnServiceCode = $cdnModel->getServiceCodeByGalleryId($oldGalleryId);
        $cdnRequirements = $cdnModel->checkRequirements();
        if (!$cdnRequirements && !empty($cdnServiceCode[0]['service_code'])) {
          $cdnServiceCode = $cdnServiceCode[0]['service_code'];
          $cdnSett = $cdnModel->getServiceSettings();
          if (isset($cdnSett['setting'][$cdnServiceCode])) {
            $cdnParams = $cdnSett;
            $cdnParams['current'] = $cdnServiceCode;
          }
        }
      }
    }

    $socialShareModel = $this->getModel('galleries');
    $socialShareList = $socialShareModel->getSocialShareListSelect();
    $gallery->settings['socialShareList'] = $socialShareList;

    // $socialShareModel = $this->getModel('socialSharing');
    // $networks = array();
    // if($socialShareModel->isPluginInstalled() && !empty($gallery->settings['socialSharing']['projectId'])) {
    //     $socialProjectId = (int)$gallery->settings['socialSharing']['projectId'];
    //     if(!is_null($socialProjectId)){
    //         $networks = $socialShareModel->getProjectNetworks($socialProjectId);
    //     }
    // }

    return $this->response('@galleries_pro/settings.twig', [
      'gallery' => $gallery,
      'preset' => $preset,
      'posts' => $posts->postsSelect(),
      'pages' => $posts->pagesSelect(),
      'postsRender' => $posts->getPostsInformation($request->query->get('gallery_id')),
      'pagesRender' => $posts->getPagesInformation($request->query->get('gallery_id')),
      'fontList' => $fontList,
      'categoriesPresets' => $categoriesPresets,
      'paginationPresets' => $paginationPresets,
      'buttonsPresets' => $buttonsPresets,
      'pageOptions' => $pageOptions,
      'awesomeIconsList' => $awesomeIconsList,
      'ioServiceParams' => isset($ioParams) ? json_encode($ioParams) : null,
      'cdnServiceParams' => isset($cdnParams) ? json_encode($cdnParams) : null,
      'networks' => isset($networks) ? $networks : null,
      'pluginUrl' => $this->getModuleUrl(),
    ]);
  }

  public function getWatermarkPhotosInfoAction(RscSgg_Http_Request $request)
  {
    $responceArray = [];
    $route = $request->post->get('route');

    if (isset($route['gallery_id'])) {
      $galleryId = intval($route['gallery_id']);

      $settings = $this->getModel('settings')->get($galleryId);
      $resources = $this->getModel('resources');
      $resourcesData = $resources->getByGalleryId($galleryId);

      $photoModel = $this->getModel('photos');
      $photos = $photoModel->getPhotos($resourcesData);

      if (isset($route['last_gall_setting'])) {
        $responceArray['galery_settings'] = GridGalleryPro_Galleries_Attachment::prepareWatermarkedImageParams($route['last_gall_setting']);

        if (isset($settings->data)) {
          $responceArray['galery_old_settings'] = GridGalleryPro_Galleries_Attachment::prepareWmImgParamsFromGallerySett($settings);
        }
        if (isset($settings->data['watermark'])) {
          $settings->data['watermark']['galleryId'] = $galleryId;
          $responceArray['old_watermark'] = $settings->data['watermark'];
        }

        $attachmentProModel = new GridGalleryPro_Galleries_Attachment();
        $attachmentSimpleModel = new GridGallery_Galleries_Attachment();
        $attachmentProModel->watermark = $route['watermark'];

        if (count($photos) > 0) {
          foreach ($photos as $onePhoto) {
            $cropPosition = isset($onePhoto->attachment['cropPosition']) ? $onePhoto->attachment['cropPosition'] : null;

            $calcAttachUrl = $attachmentSimpleModel->getAttachment($onePhoto->attachment_id, $responceArray['galery_settings']['photo_width'], $responceArray['galery_settings']['photo_height'], $cropPosition, $responceArray['galery_settings']['crop_quality']);

            $watermarkedFilePath1 = $attachmentProModel->getHashedFullFilePath($calcAttachUrl, '', $route['watermark']);
            $watermarkedFilePath2 = $attachmentProModel->getHashedFullFilePath($onePhoto->attachment['url'], '0x0', $route['watermark']);

            if (!is_file($watermarkedFilePath1) || !is_file($watermarkedFilePath2)) {
              $onePhoto = [
                'url' => $onePhoto->attachment['url'],
                'attachment_id' => $onePhoto->attachment_id,
                'cropPosition' => $cropPosition,
              ];
              $responceArray['photos'][] = $onePhoto;
            }
          }
        }
      }
    }

    $responceArray['success'] = isset($responceArray['photos']) ? true : false;

    return $this->response(RscSgg_Http_Response::AJAX, $responceArray);
  }

  public function addOneWatermarkAction(RscSgg_Http_Request $request)
  {
    $responceArray = [];
    $route = $request->post->get('route');
    if (isset($route['galery_settings']) && isset($route['photo'])) {
      $route['watermark']['toCreateWatermark'] = true;
      $attachmentModel = new GridGalleryPro_Galleries_Attachment();
      $attachmentModel->watermark = $route['watermark'];
      if (isset($route['old_watermark'])) {
        $attachmentModel->oldWatermark = $route['old_watermark'];
      }
      if (isset($route['galery_old_settings'])) {
        $attachmentModel->oldGallSetting = $route['galery_old_settings'];
      }

      $decodedUrl = urldecode($route['photo']['url']);

      $r1 = $attachmentModel->getImgUrlWithWatermark($decodedUrl, ['width' => '0', 'height' => '0']);
      $r2 = $attachmentModel->getAttachment($route['photo']['attachment_id'], $route['galery_settings']['photo_width'], $route['galery_settings']['photo_height'], $route['photo']['cropPosition'], $route['galery_settings']['crop_quality']);
      $responceArray['success'] = true;
    } else {
      $responceArray['success'] = false;
    }

    return $this->response(RscSgg_Http_Response::AJAX, $responceArray);
  }

  public function savePostsAction(RscSgg_Http_Request $request)
  {
    $post = $this->getModel('posts')->savePost($request->post->get('galleryId'), $request->post->get('id'));

    $photo = $post->photo ? $post->photo : plugins_url('', __FILE__) . '/img/noimage.png';

    return $this->response(RscSgg_Http_Response::AJAX, [
      'post_author' => get_user_by('id', $post->post_author)->user_nicename,
      'post_date' => $post->post_date,
      'comment_count' => $post->comment_count,
      'type' => 'Post',
      'photo' => $photo,
    ]);
  }

  public function savePagesAction(RscSgg_Http_Request $request)
  {
    $page = $this->getModel('posts')->savePage($request->post->get('galleryId'), $request->post->get('id'));

    $photo = $page->photo ? $page->photo : plugins_url('', __FILE__) . '/img/noimage.png';

    return $this->response(RscSgg_Http_Response::AJAX, [
      'page_author' => get_user_by('id', $page->post_author)->user_nicename,
      'page_date' => $page->post_date,
      'comment_count' => $page->comment_count,
      'type' => 'Page',
      'photo' => $photo,
    ]);
  }

  public function removePostsAction(RscSgg_Http_Request $request)
  {
    $currentPosts = get_option('post_to_render' . $request->post->get('galleryId'));
    $currentPages = get_option('pages_to_render' . $request->post->get('galleryId'));
    !is_array($currentPosts) && ($currentPosts = [$currentPosts]);
    !is_array($currentPages) && ($currentPages = [$currentPages]);
    foreach ($request->post->get('id') as $id) {
      if (($key = array_search($id, $currentPosts)) !== false) {
        unset($currentPosts[$key]);
      }
      if (($key = array_search($id, $currentPages)) !== false) {
        unset($currentPages[$key]);
      }
    }
    update_option('post_to_render' . $request->post->get('galleryId'), $currentPosts);
    update_option('pages_to_render' . $request->post->get('galleryId'), $currentPages);
    return $this->response(RscSgg_Http_Response::AJAX);
  }

  /**
   * Add Tag Action.
   * Adds the tag for the photo.
   * @param  RscSgg_Http_Request $request
   * @return RscSgg_Http_Response
   */
  public function addTagAction(RscSgg_Http_Request $request)
  {
    $photoId = $request->post->get('photo_id');
    $words = $request->post->get('tags');

    $tags = $this->getModel('tags');

    try {
      $tags->add($photoId, $words);
    } catch (Exception $e) {
      return $this->response(RscSgg_Http_Response::AJAX, $this->getErrorResponseData($e->getMessage()));
    }

    $this->getModule('galleries')->cleanCache($request->post->get('gallery_id'));

    return $this->response(RscSgg_Http_Response::AJAX, $this->getSuccessResponseData());
  }

  /**
   * Add Tag Action.
   * Adds the tag for the photo.
   * @param  RscSgg_Http_Request $request
   * @return RscSgg_Http_Response
   */
  public function allImageTagsAction(RscSgg_Http_Request $request)
  {
    $photosIds = $request->post->get('ids');
    $tag = $request->post->get('tag');
    $oldTag = $request->post->get('old_tag');
    $gallery_id = $request->post->get('gallery_id');
    $type = $request->post->get('type');

    $tags = $this->getModel('tags');

    foreach ($photosIds as $photoId) {
      try {
        if (isset($type) && $type == 'delcat') {
          if ($tag == 'allcat') {
            $tags->remove($photoId);
          } else {
            $tags->remove($photoId, $tag);
          }
        } elseif (!empty($type) && $type == 'rencat') {
          if (!empty($oldTag) && !empty($tag)) {
            $tags->renameCategory($photoId, $oldTag, $tag);
          }
        } else {
          $tags->append($photoId, $tag);
        }
      } catch (Exception $e) {
        return $this->response(RscSgg_Http_Response::AJAX, $this->getErrorResponseData($e->getMessage()));
      }
    }

    $this->getModule('galleries')->cleanCache($gallery_id);

    return $this->response(RscSgg_Http_Response::AJAX, $this->getSuccessResponseData());
  }

  /**
   * Update custom attributes Action.
   * @param  RscSgg_Http_Request $request
   * @return RscSgg_Http_Response
   */
  public function saveAttributesAction(RscSgg_Http_Request $request)
  {
    $attributes = $request->post->get('attributes');
    $gallery_id = $request->post->get('gallery_id');

    $attrModel = $this->getModel('attributes');

    foreach ($attributes as $photo) {
      try {
        if (isset($photo['id'])) {
          $attrModel->append($photo['id'], isset($photo['attributes']) ? $photo['attributes'] : []);
        }
      } catch (Exception $e) {
        return $this->response(RscSgg_Http_Response::AJAX, $this->getErrorResponseData($e->getMessage()));
      }
    }

    $this->getModule('galleries')->cleanCache($gallery_id);

    return $this->response(RscSgg_Http_Response::AJAX, $this->getSuccessResponseData());
  }

  /**
   * Returns the gallery.
   * @param  int $id Gallery Id.
   * @return array|object|null
   */
  protected function getGallery($id)
  {
    $galleries = $this->getModel('galleries');

    if (!is_object($galleries)) {
      return null;
    }

    return $galleries->getById((int) $id);
  }

  protected function getPreset($id)
  {
    $presets = $this->getModel('preset');

    if (!is_object($presets)) {
      return null;
    }

    return $presets->getBySettingsId((int) $id);
  }

  public function updateButtonsPresetAction(RscSgg_Http_Request $request)
  {
    if (isset($request->post['route']['preset'])) {
      $preset = $request->post['route']['preset'];
      $buttonsPresets = get_option('grid_gallery_buttons_presets');

      if (!$buttonsPresets) {
        $config = $this->getEnvironment()->getConfig();
        $config->load('@galleries_pro/buttons_presets.php');
        $buttonsPresets = $config->get('buttons_presets');
      }

      $presets = array_merge($preset, $buttonsPresets);

      update_option('grid_gallery_buttons_presets', $presets);

      return $this->response(RscSgg_Http_Response::AJAX, [
        'success' => 'ok',
      ]);
    }

    return $this->response(RscSgg_Http_Response::AJAX, [
      'success' => 'error',
    ]);
  }

  public function deleteButtonsPresetAction(RscSgg_Http_Request $request)
  {
    if (isset($request->post['route']['name'])) {
      $name = $request->post['route']['name'];
      $buttonsPresets = get_option('grid_gallery_buttons_presets');

      if (!$buttonsPresets) {
        $config = $this->getEnvironment()->getConfig();
        $config->load('@galleries_pro/buttons_presets.php');
        $buttonsPresets = $config->get('buttons_presets');
      }

      unset($buttonsPresets[$name]);

      update_option('grid_gallery_buttons_presets', $buttonsPresets);

      return $this->response(RscSgg_Http_Response::AJAX, [
        'success' => 'ok',
      ]);
    }

    return $this->response(RscSgg_Http_Response::AJAX, [
      'success' => 'error',
    ]);
  }

  public function updateButtonsSettingsAction(RscSgg_Http_Request $request)
  {
    if (isset($request->post['route']['gallery_id']) && isset($request->post['route']['buttons'])) {
      $id = $request->post['route']['gallery_id'];
      $settingsModel = $this->getModel('settings');
      $data = $settingsModel->get($id)->data;
      if (!isset($data['buttons'])) {
        $data['buttons'] = [];
      }
      $data['buttons'] = array_merge($data['buttons'], $request->post['route']['buttons']);

      $settingsModel->save($id, $data);

      $this->getModule('galleries')->cleanCache($id);

      return $this->response(RscSgg_Http_Response::AJAX, [
        'success' => 'ok',
      ]);
    }

    return $this->response(RscSgg_Http_Response::AJAX, [
      'success' => 'error',
    ]);
  }

  public function updateNetworksSettingsAction(RscSgg_Http_Request $http_Request)
  {
    $route = $http_Request->post->get('route');
    $gallery_id = (int) $route['gallery_id'];
    $networks_settings = $route['networks'];
    $networks_validation = [];

    foreach ($networks_settings as $key => $networks_setting) {
      if (strlen($networks_setting) === 0) {
        $networks_validation = array_merge($networks_validation, [$key => true]);
      } else {
        $networks_validation = array_merge($networks_validation, [$key => strlen(filter_var($networks_setting, FILTER_VALIDATE_URL)) > 0 ? true : false]);
      }
    }

    if (!empty($gallery_id) && !empty($networks_settings) && !array_search(false, $networks_validation)) {
      $settingsModel = $this->getModel('settings');
      $data = $settingsModel->get($gallery_id)->data;

      if (!isset($data['custom_network'])) {
        $data['custom_network'] = [];
      }
      $data['custom_network'] = array_merge($data['custom_network'], $networks_settings);
      $settingsModel->save($gallery_id, $data);

      return $this->response(RscSgg_Http_Response::AJAX, [
        'success' => 'ok',
      ]);
    } else {
      return $this->response(RscSgg_Http_Response::AJAX, [
        'success' => 'error',
        'errors' => $networks_validation,
      ]);
    }
  }

  public function showMoreAction()
  {
    $request = $this->getRequest();
    $gallery_id = $request->post->get('gallery_id');
    $limit = $request->post->get('limit');
    $offset = $request->post->get('offset', 0);
    $except = $request->post->get('except');
    $except_posts = $request->post->get('except_posts');
    $category = $request->post->get('category');
    $twig = $this->getEnvironment()->getTwig();
    $posts = [];
    $pages = [];

    $galleriesModule = $this->getModule('galleries');
    $protocol = is_ssl() ? '-ssl' : '';

    $settingsModel = $this->getModel('settings');
    $settings = $settingsModel->get($gallery_id);
    if ($settingsModel->isMobile(true)) {
      $cachePath = $galleriesModule->cacheDirectory . DIRECTORY_SEPARATOR . $gallery_id . 'm' . '-' . $offset . $protocol;
    } else {
      $cachePath = $galleriesModule->cacheDirectory . DIRECTORY_SEPARATOR . $gallery_id . '-' . $offset . $protocol;
    }

    if ($galleriesModule->cacheDirectory && file_exists($cachePath) && !$this->getEnvironment()->isProd() && empty($category) && $settings->data['showMore']['enabled'] !== 'true') {
      $data = file_get_contents($cachePath);
      return $this->response(RscSgg_Http_Response::AJAX, unserialize($data));
    }

    $except_count = count(array_filter(explode(',', $except)));
    $except_posts_count = count(array_filter(explode(',', $except_posts)));

    $resourcesModel = $this->getModel('resources');
    $resources = $resourcesModel->showMore($gallery_id, $limit, $except, $category);

    $photos = $this->getModel('photos_pro')->getPhotos($resources);
    // setting Image position
    $positionModel = $this->getModel('position');
    $positions = $positionModel->setPosition($photos, 'gallery', $gallery_id);

    foreach ($photos as $index => $row) {
      foreach ($positions as $pos) {
        if ($row->id == $pos->photo_id) {
          $photos[$index]->position = $pos->position;
        }
      }
    }
    // ASC && DESC sort
    if (isset($settings->data['sort'])) {
      $photos = $positionModel->sort($photos, $settings->data['sort']);
    } else {
      $photos = $positionModel->sort($photos);
    }

    $limit = $limit - count($photos);

    $total = $resourcesModel->showMoreTotalCount($gallery_id, $category);
    $remaining = $total - count($photos) - $except_count;
    $remaining_posts = 0;
    if (isset($settings->data['posts']) && $settings->data['posts']['enable'] === '1' && $limit > 0) {
      $total_posts = 0;
      if (get_option('post_to_render' . $gallery_id)) {
        $total_posts += count(get_option('post_to_render' . $gallery_id));
      }
      if (get_option('pages_to_render' . $gallery_id)) {
        $total_posts += count(get_option('pages_to_render' . $gallery_id));
      }

      $posts = $settingsModel->getPostsToRender($gallery_id, $limit, $except_posts, $category);
      $limit = $limit - count($posts);
      $pages = $settingsModel->getPagesToRender($gallery_id, $limit, $except_posts, $category);

      $remaining_posts = $total_posts - count($posts) - count($pages) - $except_posts_count;
    }

    foreach ($photos as $photo) {
      $photo->attachment['caption'] = html_entity_decode($photo->attachment['caption']);
      $photo->attachment['description'] = html_entity_decode($photo->attachment['description']);
    }
    if (isset($settings->data['watermark'])) {
      $settings->data['watermark']['galleryId'] = $gallery_id;
    }

    $images = preg_replace(
      '/\s+/',
      ' ',
      $twig->render('@galleries_pro/show_more.twig', [
        'photos' => $photos,
        'posts' => $posts,
        'pages' => $pages,
        'settings' => $settings->data,
        'gallery' => ['id' => $gallery_id],
      ]),
    );

    $data = [
      'success' => true,
      'images' => $images,
      'remaining' => $remaining,
      'remaining_posts' => $remaining_posts,
    ];

    if ($galleriesModule->cacheDirectory) {
      file_put_contents($cachePath, serialize($data));
    }

    return $this->response(RscSgg_Http_Response::AJAX, $data);
  }

  /**
   * View Action
   * Renders single gallery page
   *
   * @param RscSgg_Http_Request $request
   * @return RscSgg_Http_Response
   */
  public function viewAction(RscSgg_Http_Request $request)
  {
    $params = $this->getViewActionParams($request);
    return $this->response('@galleries_pro/view.twig', $params);
  }

  public function downloadImageAction()
  {
    $request = $this->getRequest();
    $image = $request->query->get('image');
    $galeryId = $request->query->get('id');
    $attachment = $request->query->get('attachment');
    $size = $request->query->get('size');
    if (empty($size)) {
      $size = 'full';
    }

    $watermark = false;
    if (!empty($galeryId) && !empty($attachment)) {
      $settings = $this->getModel('settings')->get($galeryId);
      $watermark = isset($settings->data['watermark']);
    }

    if (empty($image)) {
      if (!empty($attachment)) {
        $info = image_get_intermediate_size($attachment, $size);
        if ($info === false) {
          $info = ['url' => wp_get_attachment_image_url($attachment, $size), 'width' => 0, 'height' => 0];
        }
        $image = $info['url'];

        if (!empty($image) && $watermark) {
          if (!empty($settings->data['watermark']['download'])) {
            $settings->data['watermark']['galleryId'] = $galeryId;
            $settings->data['watermark']['toCreateWatermark'] = true;
            $attachmentModel = new GridGalleryPro_Galleries_Attachment();
            $attachmentModel->watermark = $settings->data['watermark'];
            $image = $attachmentModel->getImgUrlWithWatermark($image, ['width' => $info['width'], 'height' => $info['height'], 'attachmentId' => $attachment]);
          }
        }
      }
    } else {
      if (!empty($attachment) && $watermark) {
        if (empty($settings->data['watermark']['download'])) {
          $image = wp_get_attachment_image_url($attachment, $size);
        }
      }
    }

    $jsonData = '';
    $filename = '';
    if (!empty($image)) {
      $curlSession = curl_init();
      curl_setopt($curlSession, CURLOPT_URL, $image);
      curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
      curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

      $jsonData = curl_exec($curlSession);
      curl_close($curlSession);

      $data = explode('/', $image);
      $data = explode('?', end($data));
      $filename = $data[0];
    }
    if (!empty($jsonData)) {
      if (ob_get_contents()) {
        ob_end_clean();
      }
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      if (ob_get_contents()) {
        ob_end_clean();
      }
    }
    echo $jsonData;
    die();
  }

  protected function getCloneParams()
  {
    $params = parent::getCloneParams();
    return array_merge($params, [
      'tags' => $this->getModel('tags'),
    ]);
  }
}
