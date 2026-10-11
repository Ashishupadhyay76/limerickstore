<?php

/**
 * Class GridGalleryPro_Ftp_Model_LocalFtp class for parsing local ftp folder for images
 */
class GridGalleryPro_Ftp_Model_LocalFtp extends GridGalleryPro_Ftp_Model_AbstractFtp
{
  /**
   * @var string $_tmpFolder temp folder to save images before import them to gallery
   */
  private $_tmpFolder;
  /**
   * @inheritdoc
   */
  function __construct(RscSgg_Common_Collection $config)
  {
    parent::__construct($config);

    $wpUploadDir = wp_upload_dir();
    $this->_tmpFolder = $wpUploadDir['basedir'] . '/grid-gallery/ftp-tmp/';
  }
  /**
   * @inheritdoc
   */
  public function parseImages()
  {
    if (!is_dir($this->_tmpFolder) && !@mkdir($this->_tmpFolder)) {
      $this->setParseError('Can not create temp folder');
      return false;
    }

    $files = glob($this->_tmpFolder . '*');
    foreach ($files as $file) {
      @unlink($file);
    }

    $wpUploadDir = wp_upload_dir();
    $folder = $wpUploadDir['basedir'] . DIRECTORY_SEPARATOR . $this->folderName;

    $files = $this->getFilesList($folder);

    if (empty($files)) {
      $this->setParseError('Can not open directory, or directory is empty');
      return false;
    }

    $imagesUrl = [];

    //		$folder = $this->getImgFolderUrl($folder);

    foreach ($files as $file) {
      if ($this->isImageFile($file)) {
        $localFile = str_replace('%', '', urlencode($file));
        $fp = fopen($this->_tmpFolder . $localFile, 'w');
        fwrite($fp, file_get_contents($folder . DIRECTORY_SEPARATOR . $file));
        fclose($fp);

        $imagesUrl[] = $this->getImgFolderUrl($this->_tmpFolder) . $localFile;
      }
    }

    if (empty($imagesUrl)) {
      $this->setParseError('Can not find images in provided folder');
      return false;
    } else {
      $this->images = $imagesUrl;
      return true;
    }
  }

  /**
   * @param string $path folder path in local ftp server
   * @return array each element is file of given folder
   */
  private function getFilesList($path)
  {
    $files = [];
    if (is_dir($path)) {
      $dirHandle = opendir($path);
      while (($file = readdir($dirHandle)) !== false) {
        if ($file != '.' && $file != '..' && $file != '.svn' && is_file($path . DIRECTORY_SEPARATOR . $file)) {
          $files[] = $file;
        }
      }
    }

    return $files;
  }
}
