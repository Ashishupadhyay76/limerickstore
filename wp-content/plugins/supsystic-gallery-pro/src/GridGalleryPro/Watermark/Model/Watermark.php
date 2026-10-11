<?php
class GridGalleryPro_Watermark_Model_Watermark
{
  private $waterMarkPath;
  private $waterMarkHandle;
  private $waterMarkParams;
  private $waterMarkType;
  private $waterMarkOpacity;
  private $waterMarkPosition;
  private $waterMarkMargin;
  private $waterMarkRotate;
  private $waterMarkSize;
  // set This color to Background (rgba) (alpha must bee more than 127)
  private $newTransparentColorArr = [127, 127, 255, 127];
  private $transparencyColorHandle = 0;

  public $waterMarkImgIncorrect = false;

  public function __construct($imgPath, $type = 'img', $opacity = 100, $position = 'center_center', $margin = 0, $rotate = 0, $size = [100, 0, 100, 0])
  {
    $this->waterMarkPath = $imgPath;
    $this->waterMarkMargin = intval($margin);
    $this->waterMarkRotate = intval($rotate);
    $this->waterMarkSize = [];
    $perc = intval($size[0]);
    $this->waterMarkSize[0] = $perc > 100 || $perc <= 0 ? 100 : $perc;
    $from = intval($size[1]);
    $this->waterMarkSize[1] = $from == 1 ? 1 : 0;
    $perc = intval($size[2]);
    $this->waterMarkSize[2] = $perc > 100 || $perc <= 0 ? 100 : $perc;
    $from = intval($size[3]);
    $this->waterMarkSize[3] = $from == 1 ? 1 : 0;

    $opacity = intval($opacity);
    if ($opacity < 0) {
      $this->waterMarkOpacity = 0;
    } elseif ($opacity > 100) {
      $this->waterMarkOpacity = 100;
    } else {
      $this->waterMarkOpacity = 100 - $opacity;
    }

    if (self::isValidTypeValid($type)) {
      $this->waterMarkType = $type;
    } else {
      $this->waterMarkType = 'img';
    }

    if (self::isValidPositionValue($position)) {
      $this->waterMarkPosition = $position;
    } else {
      $this->waterMarkPosition = 'center_center';
    }
  }

  public function __destruct()
  {
    if ($this->waterMarkHandle) {
      imagedestroy($this->waterMarkHandle);
      $this->waterMarkHandle = null;
    }
  }

  public static function getHash($srcImageFilePath)
  {
    return hash('md5', $srcImageFilePath);
  }

  public static function getHashByFileName($fileName, $widthAndHeight = null, $galleryId = null, $imgExt = 'jpg')
  {
    return self::getHash($fileName) . '_' . $widthAndHeight . '-' . $galleryId . '-watermarked.' . $imgExt;
  }

  public function addWaterMarkToImage($srcImgFilePath, $newFilePath)
  {
    if ($this->waterMarkImgIncorrect) {
      return -1;
    }

    // create once
    if (!$this->waterMarkHandle) {
      $resCreated = $this->createWatermarkHandle();
      if ($resCreated < 0) {
        return $resCreated;
      }
    }

    $imgParams = getimagesize($srcImgFilePath);
    if ($imgParams[0] == 0 || $imgParams[1] == 0) {
      return -10;
    }

    $imgHandle = $this->getImageHandle($srcImgFilePath, $imgParams);
    if (!$imgHandle) {
      return -11;
    }
    $rawHandle = $this->waterMarkHandle;
    $rawParams = $this->waterMarkParams;
    $this->resizeWatermarkHandle(strpos($newFilePath, '_0x0-') === false, $imgParams[0], $imgParams[1]);

    // calc Watermark Position
    $positionArr = $this->getPositionByCode($this->waterMarkPosition, $this->waterMarkMargin, $imgParams[0], $imgParams[1], $this->waterMarkParams[0], $this->waterMarkParams[1]);

    if ($positionArr == null || count($positionArr) != 2) {
      return -12;
    }

    if (in_array($this->waterMarkParams[2], [3, 4])) {
      if ($this->imagecopymergeForPng($imgHandle, $this->waterMarkHandle, $positionArr[0], $positionArr[1], 0, 0, $this->waterMarkParams[0], $this->waterMarkParams[1], $this->waterMarkOpacity)) {
        return -13;
      }
    } else {
      if (!imagecopymerge($imgHandle, $this->waterMarkHandle, $positionArr[0], $positionArr[1], 0, 0, $this->waterMarkParams[0], $this->waterMarkParams[1], $this->waterMarkOpacity)) {
        return -13;
      }
    }
    $this->waterMarkHandle = $rawHandle;
    $this->waterMarkParams = $rawParams;

    imagejpeg($imgHandle, $newFilePath, 100);
    /*header('Content-Type: image/jpg');imagejpeg($imgHandle, null, 100);/**/
    imagedestroy($imgHandle);
    return 1;
  }

  public function resizeWatermarkHandle($isThumbnail, $imgWidth, $imgHeight)
  {
    $ind = $isThumbnail ? 0 : 2;
    $perc = $this->waterMarkSize[$ind];
    $from = $this->waterMarkSize[$ind + 1];

    if ($perc == 100 && $from == 0) {
      return 1;
    }

    $width = $this->waterMarkParams[0];
    $height = $this->waterMarkParams[1];

    if ($from == 0) {
      $w = $width;
      $h = $height;
    } else {
      $w = $imgWidth;
      $h = $imgHeight;
    }
    if ($perc != 100) {
      $w = ($w * $perc) / 100;
      $h = ($h * $perc) / 100;
    }

    $r = $width / $height;
    $newWidth = $w;
    $newHeight = $h;
    if ($w / $h > $r) {
      $newWidth = $h * $r;
      $newHeight = $h;
    } else {
      $newHeight = $w / $r;
      $newWidth = $w;
    }

    @$newWaterMark = imagecreatetruecolor((int) $newWidth, (int) $newHeight);
    if ($this->waterMarkParams[2] <= 2) {
      $transparentSourceIndex = imagecolortransparent($this->waterMarkHandle);

      if ($transparentSourceIndex !== -1) {
        $transparentColor = imagecolorsforindex($this->waterMarkHandle, $transparentSourceIndex);
        $transparentDestIndex = imagecolorallocate($newWaterMark, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);
        imagecolortransparent($newWaterMark, $transparentDestIndex);
        imagefill($newWaterMark, 0, 0, $transparentDestIndex);
      }
    } else {
      imagealphablending($newWaterMark, false);
      imagesavealpha($newWaterMark, true);
    }

    @imagecopyresampled($newWaterMark, $this->waterMarkHandle, 0, 0, 0, 0, (int) $newWidth, (int) $newHeight, (int) $width, (int) $height);

    $this->waterMarkHandle = $newWaterMark;
    $this->waterMarkParams[0] = imagesx($newWaterMark);
    $this->waterMarkParams[1] = imagesy($newWaterMark);
    return 1;
  }

  public function createWatermarkHandle()
  {
    if ($this->waterMarkType == 'img') {
      $this->waterMarkParams = getimagesize($this->waterMarkPath);
      if ($this->waterMarkParams[0] == 0 || $this->waterMarkParams[1] == 0) {
        $this->waterMarkImgIncorrect = true;
        return -21;
      }

      $tmpHandle = $this->getImageHandle($this->waterMarkPath, $this->waterMarkParams);

      if (!$tmpHandle) {
        $this->waterMarkImgIncorrect = true;
        return -22;
      }
    } else {
      return -20;
    }

    if ($this->waterMarkRotate != 0) {
      //IMG_BICUBIC, IMG_GENERALIZED_CUBIC, IMG_CATMULLROM, IMG_HERMITE, IMG_POWER, IMG_QUADRATIC, IMG_SINC, IMG_NEAREST_NEIGHBOUR, IMG_WEIGHTED4, IMG_TRIANGLE
      imagesetinterpolation($tmpHandle, IMG_TRIANGLE);
      imagealphablending($tmpHandle, false);
      imagesavealpha($tmpHandle, true);
      $this->transparencyColorHandle = imagecolorallocatealpha($tmpHandle, $this->newTransparentColorArr[0], $this->newTransparentColorArr[1], $this->newTransparentColorArr[2], 127);

      $rotateImgHandle = imagerotate($tmpHandle, -1 * $this->waterMarkRotate, $this->transparencyColorHandle, 0);

      $ind = $this->setNewBackgroundColorToTransparent($rotateImgHandle);

      $this->waterMarkParams[0] = imagesx($rotateImgHandle);
      $this->waterMarkParams[1] = imagesy($rotateImgHandle);
      $this->waterMarkHandle = $rotateImgHandle;

      imagedestroy($tmpHandle);
    } else {
      $this->waterMarkHandle = $tmpHandle;
      //			$rotateImgHandle = imagerotate($tmpHandle,0,$this->transparencyColorHandle,0);
      //			$this->waterMarkHandle = $rotateImgHandle;
    }
    return 1;
  }

  public function setNewBackgroundColorToTransparent($imgHandle, $ox = 0, $oy = 0)
  {
    $this->transparencyColorHandle = imagecolorallocatealpha($imgHandle, $this->newTransparentColorArr[0], $this->newTransparentColorArr[1], $this->newTransparentColorArr[2], $this->newTransparentColorArr[3]);

    $index = imagecolortransparent($imgHandle, $this->transparencyColorHandle);

    imagefill($imgHandle, $ox, $oy, $this->transparencyColorHandle);
    return $index;
  }

  public static function isValidTypeValid($type)
  {
    $permittedTypeList = ['img', 'text'];
    return in_array($type, $permittedTypeList);
  }

  public static function isValidPositionValue($position)
  {
    $permittedPositionList = ['top_left', 'left_top', 'top_right', 'right_top', 'left_bottom', 'bottom_left', 'right_bottom', 'bottom_right', 'left_center', 'right_center', 'center_center', 'top_center', 'bottom_center'];
    return in_array($position, $permittedPositionList);
  }

  public function getPositionByCode($code, $margin, $imgWidth, $imgHeight, $wmWidth, $wmHeight)
  {
    $resArr = null;
    switch ($code) {
      case 'top_left':
      case 'left_top':
        $resArr = [$margin, $margin];
        break;
      case 'top_right':
      case 'right_top':
        $resArr = [$imgWidth - $wmWidth - $margin, $margin];
        break;
      case 'left_bottom':
      case 'bottom_left':
        $resArr = [$margin, $imgHeight - $wmHeight - $margin];
        break;
      case 'right_bottom':
      case 'bottom_right':
        $resArr = [$imgWidth - $wmWidth - $margin, $imgHeight - $wmHeight - $margin];
        break;
      case 'left_center':
        $resArr = [$margin, $imgHeight / 2 - $wmHeight / 2];
        break;
      case 'right_center':
        $resArr = [$imgWidth - $wmWidth - $margin, $imgHeight / 2 - $wmHeight / 2];
        break;
      case 'center_center':
        $resArr = [$imgWidth / 2 - $wmWidth / 2, $imgHeight / 2 - $wmHeight / 2];
        break;
      case 'top_center':
        $resArr = [$imgWidth / 2 - $wmWidth / 2, $margin];
        break;
      case 'bottom_center':
        $resArr = [$imgWidth / 2 - $wmWidth / 2, $imgHeight - $wmHeight - $margin];
        break;
      default:
        break;
    }
    return $resArr;
  }

  public function getImageHandle($filePath, $imgParams)
  {
    $imgHandle = null;
    switch ($imgParams[2]) {
      case 1:
        if (function_exists('imagecreatefromgif')) {
          $imgHandle = imagecreatefromgif($filePath);
        }
        break;
      case 2:
        if (function_exists('imagecreatefromjpeg')) {
          $imgHandle = imagecreatefromjpeg($filePath);
        }
        break;
      case 3: // from PNG id = 3 but not 4
      case 4:
        if (function_exists('imagecreatefrompng')) {
          $imgHandle = imagecreatefrompng($filePath);
        }
        break;
      case 6:
        if (function_exists('imagecreatefrombmp')) {
          $imgHandle = imagecreatefrombmp($filePath);
        }
        break;
      case 8:
        if (function_exists('imagecreatefromwbmp')) {
          $imgHandle = imagecreatefromwbmp($filePath);
        }
        break;
      case 16:
        if (function_exists('imagecreatefromxpm')) {
          $imgHandle = imagecreatefromxpm($filePath);
        }
        break;
      default:
        $imgHandle = null;
        break;
    }
    return $imgHandle;
  }

  // special algorithm for Png images (alpha adding)
  function imagecopymergeForPng($imgHandle, $watermImg, $destX, $destY, $watermX, $watermY, $sourceWidth, $sourceHeight, $transparent)
  {
    if (!isset($transparent)) {
      return false;
    }
    $transparent /= 100;
    // Get image width and height
    $w = $this->waterMarkParams[0];
    $h = $this->waterMarkParams[1];
    // Turn alpha blending off
    imagealphablending($watermImg, false);
    // Find the most opaque pixel in the image (the one with the smallest alpha value)
    $minalpha = 127;
    for ($x = 0; $x < $w; $x++) {
      for ($y = 0; $y < $h; $y++) {
        $alpha = (imagecolorat($watermImg, (int) $x, (int) $y) >> 24) & 0xff;
        if ($alpha < $minalpha) {
          $minalpha = $alpha;
        }
      };
    }
    //loop through image pixels and modify alpha for each
    for ($x = 0; $x < $w; $x++) {
      for ($y = 0; $y < $h; $y++) {
        //get current alpha value (represents the TANSPARENCY!)
        $colorxy = imagecolorat($watermImg, (int) $x, (int) $y);
        (float) ($alpha = ($colorxy >> 24) & 0xff);
        //calculate new alpha
        if ($minalpha !== 127) {
          (float) ($alpha = 127 + (127 * $transparent * ($alpha - 127)) / (127 - $minalpha));
        } else {
          (float) ($alpha += 127 * $transparent);
        }
        //get the color index with new alpha
        @$alphacolorxy = imagecolorallocatealpha($watermImg, ($colorxy >> 16) & 0xff, ($colorxy >> 8) & 0xff, $colorxy & 0xff, $alpha);
        //set pixel with the new color + opacity
        if (!imagesetpixel($watermImg, (int) $x, (int) $y, (int) $alphacolorxy)) {
          return false;
        }
      }
    }
    // The image copy
    imagecopy($imgHandle, $watermImg, (int) $destX, (int) $destY, (int) $watermX, (int) $watermY, (int) $sourceWidth, (int) $sourceHeight);
    imagesavealpha($imgHandle, true);
  }

  public static function getErrorStrById($id)
  {
    $errorArr = [
      -1 => 'waterMarkImg Creating Error',
      -10 => 'Source image incorrect',
      -11 => 'Source image entry incorrect',
      -12 => 'calculating Watermark position error',
      -13 => 'Image overlay error',
      -20 => 'Watermark type incorrect',
      -21 => 'Watermark image incorrect',
      -22 => 'Watermark image entry incorrect',
    ];
    if (isset($errorArr[$id])) {
      return $errorArr[$id];
    }
    return 'Unknown Error!';
  }
}
