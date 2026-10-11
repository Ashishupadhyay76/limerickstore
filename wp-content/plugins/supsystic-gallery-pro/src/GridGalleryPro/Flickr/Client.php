<?php

require_once 'Model/phpFlickr.php';

/**
 * Client for working with Flickr API.
 */
class GridGalleryPro_Flickr_Client
{
  /** REQUEST TOKENT URL */
  const TOKEN_URL = 'http://www.flickr.com/services/oauth/request_token';

  /** Base AUTH URL */
  const AUTH_URL = 'https://www.flickr.com/services/oauth/authorize';

  const AUTH_TOKEN_URL = 'https://www.flickr.com/services/oauth/access_token';

  /**
   * @var string
   */
  private $clientId;

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
  private $userName;

  /**
   * @var string
   */
  private $nonce;

  /**
   * @var string
   */
  private $access_metod;

  /**
   * @var string
   */
  private $access_version;

  /**
   * @var int
   */
  private $timestamp;

  /**
   * @array
   */
  private $send_params;

  /**
   * Constructor.
   *
   * @param string $accessToken OAuth2 Access token.
   */
  public function __construct($accessToken = null)
  {
    $this->accessToken = $accessToken;
    $this->oauth = get_option('flickr_user_data');
  }

  public function request($method, $args = [])
  {
    $api = 'https://api.flickr.com/services/rest';
    $send_params = [
      'oauth_nonce' => md5(microtime() . mt_rand()),
      'oauth_consumer_key' => $this->getClientId(),
      'oauth_timestamp' => time(),
      'oauth_signature_method' => $this->getAccessMetod(),
      'oauth_version' => $this->getAccessVersion(),
      'oauth_token' => $this->oauth['oauth_token'],
      'nojsoncallback' => 1,
      'format' => 'json',
    ];
    $send_params = array_merge($send_params, ['method' => $method], $args);
    ksort($send_params);
    $baseurl = http_build_query($send_params);
    $baseurl = 'GET&' . urlencode($api) . '&' . urlencode($baseurl);
    $hash_key = $this->getClientSecret() . '&' . $this->oauth['oauth_token_secret'];
    $oauth_signature = base64_encode(hash_hmac('sha1', $baseurl, $hash_key, true));
    $send_params['oauth_signature'] = $oauth_signature;

    $response = wp_remote_get($api . '?' . http_build_query($send_params));
    apply_filters('http_request_timeout', 15);
    return json_decode(wp_remote_retrieve_body($response), true);
  }

  /**
   * Generate login URL
   * @return string
   * @throws Exception
   */
  public function getAuthorizationUrl()
  {
    $token = $this->getOauthSignature();
    return self::AUTH_URL . '?oauth_token=' . $token . '&perms=read';
  }

  /**
   * Getting oauth signature
   * @return mixed
   * @throws Exception
   */
  public function getOauthSignature()
  {
    if (!$this->clientId) {
      throw new Exception('Client ID is not specified.');
    }

    if (!$this->redirectUri) {
      throw new Exception('Redirect URI is not specified.');
    }

    $this->setParams();

    $baseurl = http_build_query($this->send_params);
    $baseurl = 'GET&' . urlencode(self::TOKEN_URL) . '&' . urlencode($baseurl);
    $hash_key = $this->getClientSecret() . '&';
    $oauth_signature = base64_encode(hash_hmac('sha1', $baseurl, $hash_key, true));
    $this->send_params['oauth_signature'] = $oauth_signature;

    $response = wp_remote_get(self::TOKEN_URL . '?' . http_build_query($this->send_params));
    $result = wp_remote_retrieve_body($response);
    return $this->getOauthToken($result);
  }

  /**
   * Saving oauth_secret, and return oauth token
   * @param $string
   * @return mixed
   */
  public function getOauthToken($string)
  {
    $oauth_token = explode('&', $string);
    $oauth_token = explode('=', $oauth_token[2]);
    update_option('oauth_secret', $oauth_token[1]);
    $token = explode('&', $string);
    $token = explode('=', $token[1]);
    return $token[1];
  }

  /**
   * Sends request to generate user data.
   *
   * @param string $token oauth_token.
   * @param string $secret oauth_verifier.
   * @throws Exception If client secret or redirect uri is not specified.
   * @throws RuntimeException If failed to get access token.
   * @return string
   */
  public function requestUserData($token, $secret)
  {
    $send_params = [
      'oauth_consumer_key' => $this->getClientId(),
      'oauth_nonce' => md5(microtime() . mt_rand()),
      'oauth_signature_method' => $this->getAccessMetod(),
      'oauth_timestamp' => time(),
      'oauth_token' => $token,
      'oauth_verifier' => $secret,
      'oauth_version' => $this->getAccessVersion(),
    ];
    $baseurl = http_build_query($send_params);
    $baseurl = 'GET&' . urlencode(self::AUTH_TOKEN_URL) . '&' . urlencode($baseurl);
    $hash_key = $this->getClientSecret() . '&' . get_option('oauth_secret');
    $oauth_signature = base64_encode(hash_hmac('sha1', $baseurl, $hash_key, true));
    $send_params['oauth_signature'] = $oauth_signature;
    $response = wp_remote_get(self::AUTH_TOKEN_URL . '?' . http_build_query($send_params));

    return wp_remote_retrieve_body($response);
  }

  /**
   * Getting user name from string
   * @param $string
   * @return mixed
   */
  public function getUserAuthData($string)
  {
    $data = [];
    foreach (explode('&', $string) as $chunk) {
      $properties = explode('=', $chunk);
      $data[urldecode($properties[0])] = urldecode($properties[1]);
    }
    return $data;
  }

  /**
   * Getting user name from string
   * @param $string
   * @return mixed
   */
  public function getUserName($string)
  {
    $userName = explode('&', $string);
    $userName = explode('=', $userName[4]);
    return $userName[1];
  }

  /**
   * Getting user thumbnails
   * @return array
   */
  public function getUserThumbnails()
  {
    if (!$this->getUser()) {
      $this->setUser(get_option('flickr_user'));
    }
    $flickr = new phpFlickr($this->getClientId(), $this->getClientSecret());
    $result = $flickr->people_findByUserName($this->getUser());
    $nsid = $result['nsid'];
    $photos = $flickr->people_getPublicPhotos($nsid);
    foreach ($photos['photos']['photo'] as $photo) {
      $imageUrls[] = $flickr->buildPhotoURL($photo);
    }
    return $imageUrls;
  }

  /**
   * Getting user thumbnails
   * @return array
   */
  public function getUserFoldersAndFiles()
  {
    $_photos = [];
    $notInSetPhotos = $this->request('flickr.photos.getNotInSet', ['extras' => 'url_m,url_o']);
    if ($notInSetPhotos) {
      foreach ($notInSetPhotos['photos']['photo'] as $photo) {
        $_photos[] = [
          'title' => $photo['title'],
          'original' => $photo['url_o'],
          'thumbnail' => $photo['url_m'],
        ];
      }
    }

    $_albums = [];
    $photosetsList = $this->request('flickr.photosets.getList');

    if ($photosetsList) {
      foreach ($photosetsList['photosets']['photoset'] as $photoset) {
        $_albums[] = [
          'id' => $photoset['id'],
          'title' => $photoset['title']['_content'],
          'photosCount' => $photoset['photos'],
          'photos' => [],
        ];
      }
    }
    return ['albums' => $_albums, 'photos' => $_photos];
  }

  /**
   * Get photos for single user album in flickr. need it coz it is not possible to load all photos in all albums by
   * one request, so we will be load album gradually by ajax
   * @param $albumId
   * @return array
   */
  public function getUserAlbum($albumId)
  {
    $photoset = $this->request('flickr.photosets.getPhotos', ['photoset_id' => $albumId, 'extras' => 'url_m,url_o']);
    $photos = [];
    if ($photoset) {
      foreach ($photoset['photoset']['photo'] as $photo) {
        $photos[] = [
          'title' => $photo['title'],
          'original' => $photo['url_o'],
          'thumbnail' => $photo['url_m'],
        ];
      }
    }
    return $photos;
  }

  /**
   * Getting user images
   * @return mixed
   */
  public function getUserImages()
  {
    $flickr = new phpFlickr($this->getClientId());
    $result = $flickr->people_findByUserName($this->getUser());
    $nsid = $result['nsid'];
    $photos = $flickr->people_getPublicPhotos($nsid);
    return $photos['photos']['photo'];
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
   * @param string $clientId
   */
  public function setClientId($clientId)
  {
    $this->clientId = $clientId;
  }

  /**
   * @return string
   */
  public function getClientId()
  {
    return $this->clientId;
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

  public function setNonce($nonce)
  {
    $this->nonce = $nonce;
  }

  public function getNonce()
  {
    return $this->nonce;
  }

  public function setAccessMetod($metod)
  {
    $this->access_metod = $metod;
  }

  public function getAccessMetod()
  {
    return $this->access_metod;
  }

  public function setAccessVersion($version)
  {
    $this->access_version = $version;
  }

  public function getAccessVersion()
  {
    return $this->access_version;
  }

  public function setTimestamp($stamp)
  {
    $this->timestamp = $stamp;
  }

  public function getTimestamp()
  {
    return $this->timestamp;
  }

  public function setParams()
  {
    $this->send_params = [
      'oauth_callback' => $this->getredirectUri(),
      'oauth_consumer_key' => $this->getClientId(),
      'oauth_nonce' => $this->getNonce(),
      'oauth_signature_method' => $this->getAccessMetod(),
      'oauth_timestamp' => $this->getTimestamp(),
      'oauth_version' => $this->getAccessVersion(),
    ];
  }

  public function getParams()
  {
    return $this->send_params;
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
