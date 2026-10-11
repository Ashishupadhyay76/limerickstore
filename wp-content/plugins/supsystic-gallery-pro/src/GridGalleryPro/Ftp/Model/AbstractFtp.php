<?php

/**
 * Abstract class with common methods and properties for local and remote ftp
 * Class GridGalleryPro_Ftp_Model_AbstractFtp
 */
abstract class GridGalleryPro_Ftp_Model_AbstractFtp
{
  /**
   * @var string|null $folderName folder name with images on ftp server
   */
  public $folderName;
  /**
   * @var int|null $isRemote 0 for local ftp and 1 for remote
   */
  public $isRemote;
  /**
   * @var string|null $parseError whenever error occur in parseImage() function it stored in this variable
   */
  public $parseError;
  /**
   * @var array|null $images after successful parseImage() store array() with image url for display
   */
  public $images;

  /**
   * GridGalleryPro_Ftp_Model_AbstractFtp constructor.
   * @param RscSgg_Common_Collection $config
   */
  function __construct(RscSgg_Common_Collection $config)
  {
    $this->folderName = $config->get('folderName');
    $this->isRemote = $config->get('isRemote');
  }

  /**
   * Parse given folder for images.
   * @return bool true if parsing successful and store image urls in $images or
   * false if parsing is failed and store error message in $parseError
   */
  public function parseImages() {}

  /**
   * check if image file
   * @param string $file
   * @return bool true if file is image and false otherwise
   */
  protected function isImageFile($file)
  {
    preg_match('/[^\?]+\.(jpe?g|jpe|gif|png|img)\b/i', $file, $matches);

    return !empty($matches);
  }

  /**
   * Get correct filesystem path for given folder
   * @param string $folder ftp folder with images
   * @return string correct filesystem path
   */
  protected function getImgFolderUrl($folder)
  {
    $wpUploadDir = wp_upload_dir();

    $dirRelativePath = str_replace(DIRECTORY_SEPARATOR, '/', str_replace(['https://', 'http://'], '', str_replace('wp-content/uploads', '', str_replace($wpUploadDir['basedir'], '', $folder))));
    if (strpos($dirRelativePath, '/') !== 0) {
      $dirRelativePath = '/' . $dirRelativePath;
    }
    if (strpos($dirRelativePath, '/') !== strlen($dirRelativePath)) {
      $dirRelativePath = $dirRelativePath . '/';
    }

    return $wpUploadDir['baseurl'] . $dirRelativePath;
  }

  /**
   * Set parse error
   * @param string $message
   */
  public function setParseError($message)
  {
    $this->parseError = $message;
  }

  /**
   * @return bool true if parsing failed and false otherwise
   */
  public function hasParseError()
  {
    return !empty($this->parseError);
  }

  /**
   * @return null|string parse error
   */
  public function getParseError()
  {
    return $this->parseError;
  }

  /**
   * @return array|null get images url for display
   */
  public function getImages()
  {
    return $this->images;
  }
}
