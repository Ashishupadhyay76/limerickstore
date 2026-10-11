<?php

class GridGalleryPro_Flickr_Module extends RscSgg_Mvc_Module
{
  protected $client;

  /**
   * {@inheritdoc}
   */
  public function onInit()
  {
    add_action('init', [$this, 'setConfig'], 10, 1);
  }

  public function setConfig()
  {
    $environment = $this->getEnvironment();
    $config = $environment->getConfig();

    $config->add('flickr_id', '768a3192d61581696c131947696887cb');

    // Client Secret
    $config->add('flickr_secret', 'f7a4d5d580987820');

    // Oauth
    $config->add('oauth_nonce', md5(microtime() . mt_rand()));

    $config->add('timestamp', time());

    $config->add('sig_method', 'HMAC-SHA1');

    $config->add('oauth_version', '1.0');

    $config->add(
      'flickr_redirect',
      $environment->generateUrl('flickr', 'complete', [
        '_wpnonce' => wp_create_nonce('supsystic-gallery'),
      ]),
    );

    //$config->add('permissions', 'read');

    // Authenticator redirect uri
    $config->add('flickr_state', $environment->generateUrl('flickr', 'complete', ['_wpnonce' => wp_create_nonce('supsystic-gallery')]));
  }

  /**
   * Returns Flickr client.
   *
   * @return GridGallery_Flickr_Client
   */
  public function getClient($galleryId = 0)
  {
    $environment = $this->getEnvironment();

    if (!$this->client) {
      $config = $this->getEnvironment()->getConfig();
      $client = new GridGalleryPro_Flickr_Client();

      $client->setClientId($config->get('flickr_id'));
      $client->setClientSecret($config->get('flickr_secret'));
      $client->setRedirectUri($config->get('flickr_redirect'));
      $client->setState($config->get('flickr_state'));
      $client->setNonce($config->get('oauth_nonce'));
      $client->setAccessMetod($config->get('sig_method'));
      $client->setAccessVersion($config->get('oauth_version'));
      $client->setTimestamp($config->get('timestamp'));

      $this->client = $client;
    }

    //$this->client->setState($environment->generateUrl('flickr', 'complete', array('id' => $galleryId)));

    return $this->client;
  }
}
