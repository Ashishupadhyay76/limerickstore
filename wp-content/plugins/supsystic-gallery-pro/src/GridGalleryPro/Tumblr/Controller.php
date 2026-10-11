<?php

class GridGalleryPro_Tumblr_Controller extends GridGallery_Core_BaseController
{
  /**
   * Index page of the Tumblr module.
   * If user is not authorized yet, then we ask for authorization.
   * Otherwise we show page about current user.
   *
   * @return RscSgg_Http_Response
   */
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
    $stats->save('Tumblr.tab');

    if ($galleryId) {
      if ($galleryId != $id) {
        update_option('flickr_slider_id', $galleryId);
      }
    } else {
      $galleryId = $id;
    }

    if (!get_option('tumblr_token')) {
      return $this->redirect($this->generateUrl('tumblr', 'authorization', ['id' => $galleryId, 'galleryName' => $galleries->getById($galleryId)->title]));
    }

    return $this->response('@tumblr/index.twig', [
      'images' => get_option('tumblr_photos'),
      'id' => $galleryId,
      'galleryName' => $galleries->getById($galleryId)->title,
    ]);
  }

  /**
   * Asks user to authorize with Tumblr.
   *
   * @return RscSgg_Http_Response
   */
  public function authorizationAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $galleries = $this->getModel('galleries');
    $client = $this->getClient();

    try {
      return $this->response('@tumblr/authorization.twig', ['url' => $client->getAuthorizationUrl(), 'id' => $galleryId, 'galleryName' => $galleries->getById($galleryId)->title]);
    } catch (Exception $e) {
      return $this->response('error.twig', ['message' => $e->getMessage()]);
    }
  }

  public function completeAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $oauth_verifier = $request->query->get('oauth_verifier');
    $oauth_token = $request->query->get('oauth_token');
    $oauth_token_secret = $request->query->get('oauth_token_secret');

    if (!$oauth_verifier) {
      $message = $this->translate('Authorization oauth_verifier is not specified.');

      return $this->response('error.twig', [
        'message' => $message,
      ]);
    }

    try {
      $client = $this->getClient();
      $tumblr = $client->getTumblr($oauth_verifier, $oauth_token, $oauth_token_secret);
      update_option('tumblr_photos', $client->getUserPhotos());
    } catch (Exception $e) {
      return $this->response('error.twig', [
        'message' => $e->getMessage(),
      ]);
    }

    return $this->redirect($this->generateUrl('tumblr', 'index', ['id' => $galleryId]));
  }

  public function refreshAction(RscSgg_Http_Request $request)
  {
    $savedThumbs = get_option('tumblr_photos');
    $client = $this->getClient();
    $thumbs = $client->getUserPhotos();

    foreach ($thumbs as $url) {
      if (!in_array($url, $savedThumbs)) {
        $images[] = $url;
      }
    }
    update_option('tumblr_photos', $thumbs);
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

    delete_option('tumblr_token');
    delete_option('tumblr_photos');

    return $this->redirect($this->generateUrl('tumblr', 'index', ['id' => $galleryId]));
  }

  /**
   * @return GridGallery_Tumblr_Client
   */
  protected function getClient($galleryId = 0)
  {
    /** @var GridGallery_Tumblr_Module $tumblr */
    $tumblr = $this->getModule('tumblr');

    return $tumblr->getClient($galleryId);
  }
}
