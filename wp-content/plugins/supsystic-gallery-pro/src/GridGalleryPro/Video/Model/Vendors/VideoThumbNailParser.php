<?php
class GridGalleryPro_Video_Model_Vendors_VideoThumbNailParser
{
  protected $url;
  protected $environment;
  protected $errors = [];
  // https://codex.wordpress.org/Function_Reference/get_allowed_mime_types
  protected $allowedFileMimeTypes = [
    'jpg|jpeg|jpe' => 'image/jpeg',
    'gif' => 'image/gif',
    'png' => 'image/png',
    // 'bmp'		=> 'image/bmp',
    // 'tif|tiff'	=> 'image/tiff',
    'ico' => 'image/x-icon',
  ];

  public function getRegex()
  {
    return [];
  }

  public function setUrl($url)
  {
    $this->url = $url;
  }
  public function setEnvironment($env)
  {
    $this->environment = $env;
  }
  public function getAllErrorString()
  {
    if (count($this->errors)) {
      return implode('; ', $this->errors);
    } else {
      return null;
    }
  }

  public function getVideoKey()
  {
    $videoKey = false;
    $matches = [];

    $tplInd = 0;
    $patterns = $this->getRegex();
    $notFound = true;

    while ($notFound && $tplInd < count($patterns)) {
      $mRes = preg_match($patterns[$tplInd], $this->url, $matches);
      if ($mRes && count($matches)) {
        $videoKey = $matches[1];
      }
      $tplInd++;
    }
    if ($videoKey === false) {
      $this->errors[] = $this->environment->translate('Error! Unknown url template or Incorrect url: ' . $this->url);
    }
    return $videoKey;
  }

  public function getUrlFileAndUpload($url)
  {
    $arrRes = [];

    if ($url) {
      // get WP res
      $wpRgetRes = wp_remote_get($url, []);
      // get file format
      $mimeFromHeader = wp_remote_retrieve_header($wpRgetRes, 'content-type');
      if ($mimeFromHeader) {
        if (in_array($mimeFromHeader, $this->allowedFileMimeTypes)) {
          $arrRes['mime'] = $mimeFromHeader;

          $wpFileInfo = wp_upload_bits(basename($url), '', wp_remote_retrieve_body($wpRgetRes));
          if (!empty($wpFileInfo['file'])) {
            $arrRes['filepath'] = $wpFileInfo['file'];
          } else {
            $this->errors[] = $this->environment->translate('Error when file upload.');
          }
        } else {
          $this->errors[] = $this->environment->translate('Error. Unsuported file mime type. It\'s not an image.');
        }
      } else {
        $this->errors[] = $this->environment->translate('Error. Incorrect upload file. Can\'t retrieve header');
      }
    }
    /**
     * ['mime', 'filepath']
     */
    return $arrRes;
  }

  public function addFileToAttachment($fileInfo, $attachmentTitle = '')
  {
    if (!empty($fileInfo) && !empty($fileInfo['mime']) && !empty($fileInfo['filepath'])) {
      // prepare attachment params
      $attachmentParams = [
        'post_title' => $attachmentTitle,
        'post_mime_type' => $fileInfo['mime'],
      ];

      $attachmentId = wp_insert_attachment($attachmentParams, $fileInfo['filepath'], 0);
      if (!$attachmentId) {
        $this->errors[] = $this->environment->translate('Error when insert attachment.');
      }

      require_once ABSPATH . 'wp-admin/includes/image.php';
      $attachData = wp_generate_attachment_metadata($attachmentId, $fileInfo['filepath']);
      wp_update_attachment_metadata($attachmentId, $attachData);
      return $attachmentId;
    }
    return false;
  }

  public function addAttachmentFromUrl($url)
  {
    $this->url = $url;
    $thumbUrl = $this->getUrlThumbnail();
    $fileInfo = $this->getUrlFileAndUpload($thumbUrl);

    return $this->addFileToAttachment($fileInfo, basename($thumbUrl) . '_' . date('H:i:s'));
  }
}
