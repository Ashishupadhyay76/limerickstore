<?php

class GridGalleryPro_Galleries_Attachment extends GridGallery_Galleries_Attachment
{
  public function getAttachment($attachmentId, $width, $height = null, $cropPosition = null, $cropQuality = null)
  {
    $attachmentModel = new GridGallery_Galleries_Attachment();
    $parentUrl = $attachmentModel->getAttachment($attachmentId, $width, $height, $cropPosition, $cropQuality);
    $newUrl = $this->getImgUrlWithWatermark($parentUrl, [
      'width' => $width,
      'height' => $height,
      'attachmentId' => $attachmentId,
      'cropPosition' => $cropPosition,
    ]);
    return $newUrl;
  }

  protected function crop($attachment, $width, $height = null, $cropPosition = null, $cropQuality = null)
  {
    $attachmentModel = new GridGallery_Galleries_Attachment();
    $returnUrl = $attachmentModel->crop($attachment, $width, $height, $cropPosition, $cropQuality);
    $newUrl = $this->getImgUrlWithWatermark($returnUrl, [
      'width' => $width,
      'height' => $height,
      'attachmentId' => $attachment['id'],
      'cropPosition' => $cropPosition,
    ]);
    return $newUrl;
  }

  public function getImgUrlWithWatermark($oldUrl, $attachmenOpt = null)
  {
    // check image for
    if (strpos($oldUrl, '-watermarked.') === false) {
      if (
        property_exists($this, 'watermark') &&
        isset($this->watermark['enabled']) &&
        $this->watermark['enabled'] == true &&
        isset($this->watermark['type']) &&
        !empty($this->watermark['url']) &&
        isset($this->watermark['margin']) &&
        isset($this->watermark['position']) &&
        isset($this->watermark['rotate']) &&
        isset($this->watermark['transparency'])
      ) {
        if (!isset($this->watermark['sizeThumbnails'])) {
          $this->watermark['sizeThumbnails'] = 100;
          $this->watermark['sizeThumbnailsFrom'] = 0;
          $this->watermark['sizePopup'] = 100;
          $this->watermark['sizePopupFrom'] = 0;
        }
        // init property once
        if (!property_exists($this, 'watermarkObj')) {
          $this->watermarkObj = new GridGalleryPro_Watermark_Model_Watermark($this->replaceUrlToFilePath(urldecode($this->watermark['url'])), $this->watermark['type'], $this->watermark['transparency'], $this->watermark['position'], $this->watermark['margin'], $this->watermark['rotate'], [
            $this->watermark['sizeThumbnails'],
            $this->watermark['sizeThumbnailsFrom'],
            $this->watermark['sizePopup'],
            $this->watermark['sizePopupFrom'],
          ]);
        }

        $widthAndHeight = '';
        if (isset($attachmenOpt['width']) && $attachmenOpt['width'] == '0' && isset($attachmenOpt['height']) && $attachmenOpt['height'] == '0') {
          $widthAndHeight = '0x0';
        }
        $newFilePath = $this->getHashedFullFilePath($oldUrl, $widthAndHeight, $this->watermark, $srcFilePath, $newFileName);

        $canCreateWatermark = $this->canCreateWatermarkByShowForParam($widthAndHeight == '0x0');
        if ($oldUrl && !$this->watermarkObj->waterMarkImgIncorrect && $canCreateWatermark) {
          $addWmError = false;
          $fileExists = is_file($newFilePath);
          if (!$fileExists) {
            if (isset($this->watermark['toCreateWatermark']) && $this->watermark['toCreateWatermark']) {
              // create new Image with Watermark
              $errId = $this->watermarkObj->addWaterMarkToImage($srcFilePath, $newFilePath);
              // $errorStr = WaterMark::getErrorStrById($errId);
              if ($errId <= 0) {
                $addWmError = true;
              }
              // remove Image with previous watermark
              if (isset($this->oldWatermark) && isset($this->oldWatermark['enabled']) && $this->oldWatermark['enabled'] == true) {
                // for Full Images
                $oldFileNameForCheck = $oldUrl;
                // for Cropped Images
                if ($widthAndHeight == '' && isset($this->oldGallSetting) && isset($attachmenOpt['attachmentId'])) {
                  // get Old URL
                  $attachmentModel = new GridGallery_Galleries_Attachment();
                  $oldFileNameForCheck = $attachmentModel->getAttachment($attachmenOpt['attachmentId'], $this->oldGallSetting['photo_width'], $this->oldGallSetting['photo_height'], isset($attachmenOpt['cropPosition']) ? $attachmenOpt['cropPosition'] : null, $this->oldGallSetting['crop_quality']);
                }
                $oldSettingFilePath = $this->getHashedFullFilePath($oldFileNameForCheck, $widthAndHeight, $this->oldWatermark, $oldSrcFilePath, $oldFileName);

                if ($oldFileName != $newFileName && is_file($oldSettingFilePath)) {
                  unlink($oldSettingFilePath);
                }
              }
            }
          }

          //if($addWmError != true && $fileExists) {
          if ($addWmError != true || $fileExists) {
            // replace filename in URL
            $strPosSlash = strrpos($oldUrl, '/');
            if ($strPosSlash !== false) {
              $oldUrl = substr($oldUrl, 0, $strPosSlash + 1) . $newFileName;
            }
          }
        }
      }
    }
    return $oldUrl;
  }

  /**
   * Watermark Show For values
   * '1':'Thumbnails'
   * '2':'Popup Images'
   * '3':'Thumbnail and Popup Images'
   * @return bool
   */
  public function canCreateWatermarkByShowForParam($isFullSizeImage)
  {
    $needToCreateThisWm = false;
    $showWatermarkFor = 3;
    if (isset($this->watermark['show_for'])) {
      $showWatermarkFor = intval($this->watermark['show_for']);
    }
    if (in_array($showWatermarkFor, [1, 3]) && !$isFullSizeImage) {
      $needToCreateThisWm = true;
    } elseif (in_array($showWatermarkFor, [2, 3]) && $isFullSizeImage) {
      $needToCreateThisWm = true;
    }
    return $needToCreateThisWm;
  }

  public function getHashedFullFilePath($oldUrl, $widthAndHeightStr, $watermark, &$srcFilePath = null, &$newFileName = null)
  {
    $srcFilePath = $this->replaceUrlToFilePath($oldUrl);

    $strToHash = $srcFilePath . '_' . urldecode($watermark['url']) . '_' . $watermark['type'] . '_' . $watermark['transparency'] . '_' . $watermark['position'] . '_' . $watermark['margin'] . '_' . $watermark['rotate'];
    if (isset($watermark['sizeThumbnails'])) {
      $strToHash = $strToHash . '_' . $watermark['sizeThumbnails'] . '_' . $watermark['sizeThumbnailsFrom'] . '_' . $watermark['sizePopup'] . '_' . $watermark['sizePopupFrom'];
    }
    $galleryId = isset($watermark['galleryId']) ? $watermark['galleryId'] : null;
    $newFileName = GridGalleryPro_Watermark_Model_Watermark::getHashByFileName($strToHash, $widthAndHeightStr, $galleryId);

    $newFilePath = dirname($srcFilePath) . DIRECTORY_SEPARATOR . $newFileName;
    return $newFilePath;
  }
}
