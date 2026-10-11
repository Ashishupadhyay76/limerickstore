<?php

class GridGalleryPro_Facebook_Controller extends GridGallery_Core_BaseController
{
  public $fbApiVersoin = 'v2.7';

  public function requireNonces()
  {
    return ['completeAction', 'refreshAction', 'logoutAction', 'savePagesAction'];
  }
  /**
   * Index page of the Facebook module.
   * If user is not authorized yet, then we ask for authorization.
   * Otherwise we show page about current user.
   *
   * @return RscSgg_Http_Response
   */

  protected function getModelAliases()
  {
    return [
      'photos' => 'GridGallery_Photos_Model_Photos',
      'galleries' => 'GridGallery_Galleries_Model_Galleries',
    ];
  }

  public function indexAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $id = get_option('flickr_slider_id');
    $galleries = $this->getModel('galleries');
    $stats = $this->getEnvironment()->getModule('stats');
    $stats->save('Facebook.tab');

    if ($galleryId) {
      if ($galleryId != $id) {
        update_option('flickr_slider_id', $galleryId);
      }
    } else {
      $galleryId = $id;
    }

    $galleryTitle = $galleries->getById($galleryId)->title;

    if (0 || !get_option('facebook_token')) {
      return $this->authorizationAction($request);
    }
    $client = $this->getClient($galleryId);
    $fb = $client->fb(get_option('facebook_token'));
    $images = [];
    $limit = 999;
    $offset = 0;
    $data = [];
    do {
      try {
        $response = $fb->api("$this->fbApiVersoin/me/photos/uploaded?fields=album,images,name,picture&limit=$limit&offset=$offset");
        $data = array_merge($data, $response['data']);
      } catch (Exception $e) {
        delete_option('facebook_token');
        return $this->authorizationAction($request);
      }
      $offset += $limit;
    } while (isset($response['paging']['next']));

    foreach ($data as $item) {
      $original = array_shift($item['images']);
      $thumbnail = current(array_slice($item['images'], -2, 1));
      $images['albums'][$item['album']['name']]['photos'][] = [
        'original' => $original,
        'thumbnail' => $thumbnail,
      ];
    }

    $pageImages = [];
    $albumsData = [];
    $pages = get_option('facebook_pages');

    if (!empty($pages)) {
      foreach (explode(',', $pages) as $id) {
        if (empty($id)) {
          continue;
        }

        try {
          $response = $fb->api("$this->fbApiVersoin/$id/?fields=name,albums{name,photos{images}}&limit=9999");
        } catch (Exception $e) {
          try {
            $group = $fb->api("$this->fbApiVersoin/search?q=$id&type=group&limit=1");
            $groupId = $group['data'][0]['id'];
            $response = $fb->api("$this->fbApiVersoin/$groupId/?fields=name,albums{name,photos{images}}&limit=9999");
          } catch (Exception $e) {
            continue;
          }
        }
        if (isset($response['albums'])) {
          $albumsData = array_merge($albumsData, $response['albums']['data']);

          for ($i = 0; $i < count($albumsData); $i++) {
            if (isset($albumsData[$i]['photos']['paging']['next'])) {
              $resp['paging']['next'] = $albumsData[$i]['photos']['paging']['next'];
              do {
                $resp = $fb->api(str_replace('https://graph.facebook.com/', '', $resp['paging']['next']));
                $albumsData[$i]['photos']['data'] = array_merge($albumsData[$i]['photos']['data'], $resp['data']);
              } while (isset($resp['paging']['next']));
            }
          }
          if (!empty($albumsData)) {
            foreach ($albumsData as $album) {
              foreach ($album['photos']['data'] as $photo) {
                $original = array_shift($photo['images']);
                $thumbnail = current(array_slice($photo['images'], -2, 1));
                $pageImages[$response['name']]['albums'][$album['name']]['photos'][] = [
                  'original' => $original,
                  'thumbnail' => $thumbnail,
                ];
              }
            }
          }
        }
      }
    }

    return $this->response('@facebook/index.twig', [
      'id' => $galleryId,
      'images' => $images,
      'galleryName' => $galleryTitle,
      'pageImages' => $pageImages,
      'pages' => $pages,
      'lazyLoadUrl' => $this->getEnvironment()->getConfig()->get('plugin_url') . '/app/assets/js/jquery.lazyload.min.js',
    ]);
  }

  /**
   * Asks user to authorize with Facebook.
   *
   * @return RscSgg_Http_Response
   */
  public function authorizationAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $galleries = $this->getModel('galleries');
    $client = $this->getClient($galleryId);
    $galleryTitle = $galleries->getById($galleryId)->title;

    try {
      return $this->response('@facebook/authorization.twig', [
        'url' => $client->getAuthorizationUrl(),
        'id' => $galleryId,
        'galleryName' => $galleryTitle,
      ]);
    } catch (Exception $e) {
      return $this->response('error.twig', ['message' => $e->getMessage()]);
    }
  }

  public function completeAction(RscSgg_Http_Request $request)
  {
    $code = $request->query->get('code');
    $galleryId = $request->query->get('id');

    if (!$code) {
      $message = $this->translate('Authorization code is not specified.');

      return $this->response('error.twig', [
        'message' => $message,
      ]);
    }

    try {
      $client = $this->getClient();
      $fb = $client->startLoginSession($code);
      update_option('facebook_token', $fb->getAccessToken());
    } catch (Exception $e) {
      return $this->response('error.twig', [
        'message' => $e->getMessage(),
      ]);
    }

    return $this->redirect($this->generateUrl('facebook', 'index', ['id' => $galleryId]));
  }

  public function refreshAction(RscSgg_Http_Request $request)
  {
    $savedThumbs = get_option('facebook_photos');
    $client = $this->getClient();
    $thumbs = $client->getUserPhotos('refresh');

    foreach ($thumbs as $url) {
      if (!in_array($url, $savedThumbs)) {
        $images[] = $url;
      }
    }
    update_option('facebook_photos', $thumbs);
    return $this->response(RscSgg_Http_Response::AJAX, ['images' => $images]);
  }

  public function listAction(RscSgg_Http_Request $request)
  {
    return $this->response(RscSgg_Http_Response::AJAX, [
      'galleries' => $this->getModel('galleries')->getList(),
    ]);
  }

  public function logoutAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');

    delete_option('facebook_token');
    delete_option('facebook_photos');
    delete_option('facebook_pages');

    @session_destroy();

    return $this->redirect($this->generateUrl('facebook', 'index', ['id' => $galleryId]));
  }

  /**
   * @return GridGallery_Facebook_Client
   */
  protected function getClient($galleryId = 0)
  {
    /** @var GridGallery_Facebook_Module $facebook */
    $facebook = $this->getModule('facebook');

    return $facebook->getClient($galleryId);
  }

  public function savePagesAction(RscSgg_Http_Request $request)
  {
    update_option('facebook_pages', $request->post->get('id', null));
    return $this->redirect($this->generateUrl('facebook', 'index', []));
  }
}
