<?php

class SupsysticGalleryUpdater
{
  private $productCode;
  private $directory;
  private $file;
  private $environment;

  public function subscribe()
  {
    add_filter('pre_set_site_transient_update_plugins', [$this, 'checkForUpdates']);
    add_filter('plugins_api', [$this, 'apiCall'], 10, 3);
  }

  public function checkForUpdates($checkedData)
  {
    return wpUpdaterSgg::getInstance($this->directory, $this->file, $this->productCode, null, null, $this->environment)->checkForPluginUpdate($checkedData);
  }

  public function apiCall($def, $action, $args)
  {
    return wpUpdaterSgg::getInstance($this->directory, $this->file, $this->productCode, null, null, $this->environment)->myPluginApiCall($def, $action, $args);
  }

  /**
   * Returns ProductCode.
   * @return mixed
   */
  public function getProductCode()
  {
    return $this->productCode;
  }

  /**
   * Sets ProductCode.
   * @param mixed $productCode
   */
  public function setProductCode($productCode)
  {
    $this->productCode = $productCode;
  }

  /**
   * Returns Directory.
   * @return mixed
   */
  public function getDirectory()
  {
    return $this->directory;
  }

  /**
   * Sets Directory.
   * @param mixed $directory
   */
  public function setDirectory($directory)
  {
    $this->directory = $directory;
  }

  /**
   * Returns File.
   * @return mixed
   */
  public function getFile()
  {
    return $this->file;
  }

  /**
   * Sets File.
   * @param mixed $file
   */
  public function setFile($file)
  {
    $this->file = $file;
  }

  /**
   * Returns Environment.
   * @return mixed
   */
  public function getEnvironment()
  {
    return $this->environment;
  }

  /**
   * Sets Environment.
   * @param mixed $environment
   */
  public function setEnvironment($environment)
  {
    $this->environment = $environment;
  }
}
