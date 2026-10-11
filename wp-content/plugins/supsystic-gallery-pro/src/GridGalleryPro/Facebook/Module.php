<?php

class GridGalleryPro_Facebook_Module extends RscSgg_Mvc_Module
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

    // Client ID
    $config->add('facebook_key', '1612081092370131');

    // Client Secret
    $config->add('facebook_secret', '8addb5f886c12f229a252bc569ea0740');

    // Authenticator's Facebook URL
    $config->add('facebook_redirect', 'https://supsystic.com/authenticator/index.php/authenticator/facebook');

    // Authenticator redirect uri
    $config->add('facebook_state', $environment->generateUrl('facebook', 'complete', ['_wpnonce' => wp_create_nonce('supsystic-gallery')]));
  }

  /**
   * Returns Facebook client.
   *
   * @return GridGallery_Facebook_Client
   */
  public function getClient($galleryId = 0)
  {
    $environment = $this->getEnvironment();

    if (!$this->client) {
      $config = $this->getEnvironment()->getConfig();
      $client = new GridGalleryPro_Facebook_Client();

      $client->setClientKey($config->get('facebook_key'));
      $client->setClientSecret($config->get('facebook_secret'));
      $client->setRedirectUri($config->get('facebook_redirect'));
      $client->setState($config->get('facebook_state'));

      $this->client = $client;
    }

    //$this->client->setState($environment->generateUrl('facebook', 'complete', array('id' => $galleryId)));

    return $this->client;
  }
}
