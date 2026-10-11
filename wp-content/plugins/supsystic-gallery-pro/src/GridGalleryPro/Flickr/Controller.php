<?php

class GridGalleryPro_Flickr_Controller extends GridGallery_Core_BaseController
{
  public function requireNonces()
  {
    return ['completeAction', 'refreshAction', 'logoutAction'];
  }

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
    $client = $this->getClient($galleryId);
    $stats->save('Flickr.tab');
    if ($galleryId) {
      if ($galleryId != $id) {
        update_option('flickr_slider_id', $galleryId);
      }
    } else {
      $galleryId = $id;
    }

    if (!get_option('flickr_user')) {
      return $this->redirect($this->generateUrl('flickr', 'authorization', ['id' => $galleryId, 'galleryName' => $galleries->getById($galleryId)->title]));
    }

    return $this->response('@flickr/index.twig', [
      'images' => $client->getUserFoldersAndFiles(),
      'id' => $galleryId,
      'galleryName' => $galleries->getById($galleryId)->title,
    ]);
  }

  /**
   * Return html with album photos for ajax loading albums
   * @param RscSgg_Http_Request $request
   * @return RscSgg_Http_Response
   */
  public function getAlbumAction(RscSgg_Http_Request $request)
  {
    $albumId = $request->post->get('albumId');
    $client = $this->getClient();

    return $this->response(RscSgg_Http_Response::AJAX, [
      'photos_html' => $this->response('@flickr/includes/album.twig', [
        'images' => $client->getUserAlbum($albumId),
      ])->getContent(),
    ]);
  }

  /**
   * Asks user to authorize with Flickr API.
   *
   * @return RscSgg_Http_Response
   */
  public function authorizationAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $galleries = $this->getModel('galleries');
    $client = $this->getClient($galleryId);

    try {
      return $this->response('@flickr/authorization.twig', ['url' => $client->getAuthorizationUrl(), 'id' => $galleryId, 'galleryName' => $galleries->getById($galleryId)->title]);
    } catch (Exception $e) {
      return $this->response('error.twig', ['message' => $e->getMessage()]);
    }
  }

  public function completeAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $client = $this->getClient();
    $token = $request->query->get('oauth_token');
    $secret = $request->query->get('oauth_verifier');
    $data = $client->requestUserData($token, $secret);
    $user = $client->getUserName($data);
    $client->setUser($user);

    if (!$data) {
      $message = $this->translate('Empty user data.');

      return $this->response('error.twig', [
        'message' => $message,
      ]);
    }

    try {
      update_option('flickr_user', $client->getUser());
      update_option('flickr_user_data', $client->getUserAuthData($data));
      update_option('flickr_thumbnails', $client->getUserThumbnails());
    } catch (Exception $e) {
      return $this->response('error.twig', [
        'message' => $e->getMessage(),
      ]);
    }

    return $this->redirect($this->generateUrl('flickr', 'index', ['id' => $galleryId]));
  }

  public function refreshAction(RscSgg_Http_Request $request)
  {
    $savedThumbs = get_option('flickr_thumbnails');
    $client = $this->getClient();
    $thumbs = $client->getUserThumbnails();

    foreach ($thumbs as $url) {
      if (!in_array($url, $savedThumbs)) {
        $images[] = $url;
      }
    }

    update_option('flickr_thumbnails', $thumbs);
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

    delete_option('flickr_user');
    delete_option('flickr_thumbnails');

    return $this->redirect($this->generateUrl('flickr', 'index', ['id' => $galleryId]));
  }

  /**
   * @return GridGallery_Flickr_Client
   */
  protected function getClient($galleryId = 0)
  {
    /** @var GridGallery_Flickr_Module $flickr */
    $flickr = $this->getModule('flickr');
    return $flickr->getClient($galleryId);
  }
}
