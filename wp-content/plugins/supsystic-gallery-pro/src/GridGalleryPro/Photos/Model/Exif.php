<?php
class GridGalleryPro_Photos_Model_Exif extends GridGallery_Core_BaseModel
{
  protected $isFilterInited = false;
  /**
   * flash param available values
   */
  protected $flashValArr = [];
  /**
   * orientation param available values
   */
  protected $orientationValArr = [];

  public function onInit()
  {
    $this->orientationValArr = [
      '1' => $this->translate('Horizontal (normal)'),
      '2' => $this->translate('Mirror horizontal'),
      '3' => $this->translate('Rotate 180'),
      '4' => $this->translate('Mirror vertical'),
      '5' => $this->translate('Mirror horizontal and rotate 270 CW'),
      '6' => $this->translate('Rotate 90 CW'),
      '7' => $this->translate('Mirror horizontal and rotate 90 CW'),
      '8' => $this->translate('Rotate 270 CW'),
    ];

    $this->flashValArr = [
      '0' => $this->translate('No Flash'),
      '1' => $this->translate('Fired'),
      '5' => $this->translate('Fired, Return not detected'),
      '7' => $this->translate('Fired, Return detected'),
      '8' => $this->translate('On, Did not fire'),
      '9' => $this->translate('On, Fired'),
      '13' => $this->translate('On, Return not detected'),
      '15' => $this->translate('On, Return detected'),
      '16' => $this->translate('Off, Did not fire'),
      '20' => $this->translate('Off, Did not fire, Return not detected'),
      '24' => $this->translate('Auto, Did not fire'),
      '25' => $this->translate('Auto, Fired'),
      '29' => $this->translate('Auto, Fired, Return not detected'),
      '31' => $this->translate('Auto, Fired, Return detected'),
      '32' => $this->translate('No flash function'),
      '48' => $this->translate('Off, No flash function'),
      '65' => $this->translate('Fired, Red-eye reduction'),
      '69' => $this->translate('Fired, Red-eye reduction, Return not detected'),
      '71' => $this->translate('Fired, Red-eye reduction, Return detected'),
      '73' => $this->translate('On, Red-eye reduction'),
      '77' => $this->translate('On, Red-eye reduction, Return not detected'),
      '79' => $this->translate('On, Red-eye reduction, Return detected'),
      '80' => $this->translate('Off, Red-eye reduction'),
      '88' => $this->translate('Auto, Did not fire, Red-eye reduction'),
      '89' => $this->translate('Auto, Fired, Red-eye reduction'),
      '93' => $this->translate('Auto, Fired, Red-eye reduction, Return not detected'),
      '95' => $this->translate('Auto, Fired, Red-eye reduction, Return detected'),
    ];
  }

  public function getExif($attachmentId)
  {
    // get atachment info
    $attachmentArr = wp_prepare_attachment_for_js($attachmentId);
    if (!empty($attachmentArr) && !empty($attachmentArr['url'])) {
      // get file path
      $filePath = $this->getFilePathFromUrl($attachmentArr['url']);
      if ($filePath) {
        // add specific image metadata function
        if (!$this->isFilterInited) {
          add_filter('wp_read_image_metadata', [$this, 'sggFilterWpReadImageMetadata'], 10, 4);
          $this->isFilterInited = true;
        }
        // get exif information
        $exifArr = wp_read_image_metadata($filePath);
        if (!empty($exifArr)) {
          $someExifInfo = [
            'aperture' => isset($exifArr['aperture']) ? $exifArr['aperture'] : null,
            'camera' => isset($exifArr['camera']) ? $exifArr['camera'] : null,
            'focal_length' => isset($exifArr['focal_length']) ? $exifArr['focal_length'] : null,
            'iso' => isset($exifArr['iso']) ? $exifArr['iso'] : null,
            'shutter_speed' => isset($exifArr['shutter_speed']) ? $exifArr['shutter_speed'] : null,
            'orientation' => isset($exifArr['orientation']) && isset($this->orientationValArr[$exifArr['orientation']]) ? $this->orientationValArr[$exifArr['orientation']] : null,
            'cameraMake' => isset($exifArr['cameraMake']) ? $exifArr['cameraMake'] : null,
            'originalAperture' => isset($exifArr['originalAperture']) ? $exifArr['originalAperture'] : null,
            'shutterSpeed' => isset($exifArr['shutterSpeed']) ? $exifArr['shutterSpeed'] : null,
            'focalLength' => isset($exifArr['focalLength']) ? $exifArr['focalLength'] : null,
            'exposureBiasValue' => isset($exifArr['exposureBiasValue']) ? $exifArr['exposureBiasValue'] : null,
            'flash' => isset($exifArr['flash']) && isset($this->flashValArr[$exifArr['flash']]) ? $this->flashValArr[$exifArr['flash']] : null,
            'location' => isset($exifArr['location']) ? $exifArr['location'] : null,
          ];
          return $someExifInfo;
        }
      }
    }
    return null;
  }

  public function updateExifForAttachment($attachmentId, $metafieldCode)
  {
    $someExifInfo = $this->getExif($attachmentId);
    if ($someExifInfo) {
      update_post_meta((int) $attachmentId, $metafieldCode, $someExifInfo);
      return true;
    }
    return false;
  }

  /**
   * Fiter for wp_read_image_metadata event
   */
  public function sggFilterWpReadImageMetadata($meta, $file, $sourceimagetype, $iptc)
  {
    if (function_exists('exif_read_data')) {
      // get exif data from file
      $exif = @exif_read_data($file);
      //
      if (is_array($exif) && !empty($exif)) {
        if (!empty($exif['Make'])) {
          $meta['cameraMake'] = $exif['Make'];
        }
        if (!empty($exif['FNumber'])) {
          $meta['originalAperture'] = $exif['FNumber'];
        }
        if (!empty($exif['ExposureTime'])) {
          $meta['shutterSpeed'] = $exif['ExposureTime'];
        }
        if (!empty($exif['FocalLength'])) {
          $meta['focalLength'] = $exif['FocalLength'];
        }
        if (!empty($exif['ExposureBiasValue'])) {
          $meta['exposureBiasValue'] = $exif['ExposureBiasValue'];
        }
        if (!empty($exif['Flash'])) {
          $meta['flash'] = $exif['Flash'];
        }
        $location = '';
        if (!empty($exif['GPSLatitude']) && !empty($exif['GPSLongitude'])) {
          $latitude = '';
          $longitude = '';
          if (is_array($exif['GPSLatitude'])) {
            if (isset($exif['GPSLatitude'][0])) {
              $latitude .= self::calcDividerInString($exif['GPSLatitude'][0]) . '&#176;';
            }
            if (isset($exif['GPSLatitude'][1])) {
              $latitude .= self::calcDividerInString($exif['GPSLatitude'][1]) . "'";
            }
            if (isset($exif['GPSLatitude'][2])) {
              $latitude .= self::calcDividerInString($exif['GPSLatitude'][2]) . '&quot; ';
            }
            if (isset($exif['GPSLatitudeRef'])) {
              $latitude .= $exif['GPSLatitudeRef'] . ', ';
            }
          }
          if (is_array($exif['GPSLongitude'])) {
            if (isset($exif['GPSLongitude'][0])) {
              $longitude .= self::calcDividerInString($exif['GPSLongitude'][0]) . '&#176;';
            }
            if (isset($exif['GPSLongitude'][1])) {
              $longitude .= self::calcDividerInString($exif['GPSLongitude'][1]) . "'";
            }
            if (isset($exif['GPSLongitude'][2])) {
              $longitude .= self::calcDividerInString($exif['GPSLongitude'][2]) . '&quot; ';
            }
            if (isset($exif['GPSLongitudeRef'])) {
              $longitude .= $exif['GPSLongitudeRef'];
            }
          }
          $location = $latitude . $longitude;
        }
        $meta['location'] = $location;
      }
    }
    return $meta;
  }

  public function calcDividerInString($str)
  {
    $calculated = false;
    $posDelim = strpos($str, '/');
    if ($posDelim !== false) {
      $varA = floatval(substr($str, 0, $posDelim));
      $varB = floatval(substr($str, $posDelim + 1));
      $calculated = round($varA / $varB, 2);
    }
    return $calculated ? $calculated : $str;
  }

  /**
   * Replace url to file path
   */
  public function getFilePathFromUrl($url)
  {
    $filePath = null;
    if ($url) {
      $wpUploadInfo = wp_upload_dir(null, false);
      $wpUploadDir = $wpUploadInfo['basedir'];
      $wpUploadUrl = $wpUploadInfo['baseurl'];
      $sslUploadUrl = $wpUploadUrl;
      if (is_ssl()) {
        $wpUploadUrl = str_replace('http://', 'https://', $wpUploadUrl);
      }
      if (strpos($url, $wpUploadUrl) !== false) {
        $filePath = str_replace($wpUploadUrl, realpath($wpUploadDir), $url);
      } elseif (strpos($url, $sslUploadUrl) !== false) {
        $filePath = str_replace($sslUploadUrl, realpath($wpUploadDir), $url);
      }
    }
    return $filePath;
  }
}
