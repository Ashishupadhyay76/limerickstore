<?php

include 'lib/tumblrPHP.php';

/**
 * Client for working with Tumblr API.
 */
class GridGalleryPro_Tumblr_Client
{
  private $tumblr;
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
  public function getAuthorizationUrl()
  {
    if (!$this->clientKey) {
      throw new Exception('Client ID is not specified.');
    }

    if (!$this->redirectUri) {
      throw new Exception('Redirect URI is not specified.');
    }

    $tumblr = new Tumblr($this->clientKey, $this->clientSecret);
    $token = $tumblr->getRequestToken();
    $oauth_token = $token['oauth_token'];
    $oauth_token_secret = $token['oauth_token_secret'];
    $url = $tumblr->getAuthorizeURL($token['oauth_token']);

    return $this->getRedirectUri() .
      '?' .
      http_build_query([
        'url' => $url,
        'oauth_token' => $oauth_token,
        'oauth_token_secret' => $oauth_token_secret,
        'state' => $this->getState(),
      ]);
  }

  /**
   * @param $oauth_verifier
   * @param $oauth_token
   * @param $oauth_token_secret
   * @return Tumblr
   * @throws Exception
   */
  public function getTumblr($oauth_verifier, $oauth_token, $oauth_token_secret)
  {
    if (!$this->getClientSecret()) {
      throw new Exception('Client secret is not specified.');
    }

    if (!$this->getRedirectUri()) {
      throw new Exception('Redirect URI is not specified.');
    }

    $oauth_verifier = trim($oauth_verifier);
    $tumblr = new Tumblr($this->clientKey, $this->clientSecret, $oauth_token, $oauth_token_secret);
    $token = $tumblr->getAccessToken($oauth_verifier);
    update_option('tumblr_token', $token['oauth_token']);
    $this->tumblr = new Tumblr($this->clientKey, $this->clientSecret, $token['oauth_token'], $token['oauth_token_secret']);

    return $this->tumblr;
  }

  /**
   * @return string
   */
  public function getBlogName()
  {
    return $this->tumblr->oauth_get('/user/info')->response->user->blogs[0]->name . '.tumblr.com';
  }

  /**
   * @return array
   */
  public function getUserPhotos()
  {
    $images = [];
    $posts = $this->tumblr->oauth_get('/blog/' . $this->getBlogName() . '/posts?api_key=' . $this->clientKey);
    foreach ($posts->response->posts as $post) {
      $images = array_merge($images, $this->getPostPhotos($post->photos));
    }
    return $images;
  }

  /**
   * @param $photos
   * @return array
   */
  public function getPostPhotos($photos)
  {
    $images = [];
    foreach ($photos as $photo) {
      array_push($images, $photo->original_size->url);
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
