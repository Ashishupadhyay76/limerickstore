<?php

class GridGalleryPro_Tumblr_Module extends RscSgg_Mvc_Module
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
    $config->add('tumblr_key', 'Up8Ivm1QULp9nt1QZlZlS7Ryd1c0DPFqMAYXbyn8d3xAPXHd7R');

    // Client Secret
    $config->add('tumblr_secret', 'SRxnhdBCuGoMWNs3JPWe2dKQYtOuCrj2KIJohIlu0XWuu3kHDw');

    // Authenticator's Instagram URL
    $config->add('tumblr_redirect', 'http://supsystic.com/authenticator/index.php/authenticator/tumblr');

    // Authenticator redirect uri
    $config->add(
      'tumblr_state',
      $environment->generateUrl('tumblr', 'complete', [
        '_wpnonce' => wp_create_nonce('supsystic-gallery'),
      ]),
    );
  }

  /**
   * Returns Tumblr client.
   *
   * @return GridGallery_Tumblr_Client
   */
  public function getClient($galleryId = 0)
  {
    $environment = $this->getEnvironment();

    if (!$this->client) {
      $config = $this->getEnvironment()->getConfig();
      $client = new GridGalleryPro_Tumblr_Client();

      $client->setClientKey($config->get('tumblr_key'));
      $client->setClientSecret($config->get('tumblr_secret'));
      $client->setRedirectUri($config->get('tumblr_redirect'));
      $client->setState($config->get('tumblr_state'));

      $this->client = $client;
    }

    //$this->client->setState($environment->generateUrl('tumblr', 'complete', array('id' => $galleryId)));

    return $this->client;
  }
}
