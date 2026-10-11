<?php

if (session_id() == '') {
  @session_start();
}
require_once 'lib/facebook.php';

/**
 * Client for working with Facebook API.
 */
class GridGalleryPro_Facebook_Client
{
  private $facebook;

  /**
   * @var string
   */
  private $clientKey;

  /**
   * @var string
   */
  private $clientSecret;

  /**
   * @var string
   */
  private $redirectUri;

  /**
   * @var string
   */
  private $accessToken;

  /**
   * @var string
   */
  private $state;

  /**
   * @var array
   */
  private $user;

  /**
   * @var string
   */

  /**
   * Constructor.
   *
   * @param string $accessToken OAuth1.0a Access token.
   */
  public function __construct($accessToken = null)
  {
    $this->accessToken = $accessToken;
  }

  /**
   * Returns authorization URL.
   * @return string Authorization URL.
   * @throws Exception If client id or redirect uri is not specified.
   */
  public function getAuthorizationUrl($scope = 'user_photos,public_profile')
  {
    if (!$this->clientKey) {
      throw new Exception('Client ID is not specified.');
    }

    if (!$this->redirectUri) {
      throw new Exception('Redirect URI is not specified.');
    }

    $facebook = new Facebook([
      'appId' => $this->clientKey,
      'secret' => $this->clientSecret,
    ]);

    $loginUrl = $facebook->getLoginUrl([
      'scope' => $scope,
      'redirect_uri' => $this->redirectUri . '/complete/',
    ]);

    return $this->getRedirectUri() .
      '?' .
      http_build_query([
        'url' => $loginUrl,
        'state' => $this->getState(),
      ]);
  }

  public function startLoginSession($code)
  {
    $this->facebook = new Facebook([
      'appId' => $this->clientKey,
      'secret' => $this->clientSecret,
    ]);
    $this->facebook->accessToken($code, $this->redirectUri . '/complete/');
    return $this->facebook;
  }

  public function fb($code)
  {
    $fb = new Facebook([
      'appId' => $this->clientKey,
      'secret' => $this->clientSecret,
    ]);

    return $fb->setAccessToken($code);
  }

  public function getUserPhotos($action = null)
  {
    $images = [];
    $response = $this->facebook->api('/me/photos/uploaded?fields=album,images,name,picture');
    foreach ($response['data'] as $photo) {
      $images[] = $photo['source'];
    }
    return $images;
  }

  /**
   * @param string $accessToken
   */
  public function setAccessToken($accessToken)
  {
    $this->accessToken = $accessToken;
  }

  /**
   * @return string
   */
  public function getAccessToken()
  {
    return $this->accessToken;
  }

  /**
   * @param string $clientKey
   */
  public function setClientKey($clientKey)
  {
    $this->clientKey = $clientKey;
  }

  /**
   * @return string
   */
  public function getClientKey()
  {
    return $this->clientKey;
  }

  /**
   * @param string $clientSecret
   */
  public function setClientSecret($clientSecret)
  {
    $this->clientSecret = $clientSecret;
  }

  /**
   * @return string
   */
  public function getClientSecret()
  {
    return $this->clientSecret;
  }

  /**
   * @param string $redirectUri
   */
  public function setRedirectUri($redirectUri)
  {
    $this->redirectUri = $redirectUri;
  }

  /**
   * @return string
   */
  public function getRedirectUri()
  {
    return $this->redirectUri;
  }

  /**
   * @param string $state
   */
  public function setState($state)
  {
    $this->state = $state;
  }

  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }

  /**
   * @param array $user
   */
  public function setUser($user)
  {
    $this->user = $user;
  }

  /**
   * @return array
   */
  public function getUser()
  {
    return $this->user;
  }

  protected function getResponseCode($response)
  {
    return (int) wp_remote_retrieve_response_code($response);
  }

  protected function getResponseMessage($response)
  {
    return wp_remote_retrieve_response_message($response);
  }

  protected function getResponseBody($response)
  {
    return json_decode(wp_remote_retrieve_body($response), true);
  }
}
