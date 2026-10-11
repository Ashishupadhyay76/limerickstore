<?php

class GridGalleryPro_Photos_Model_Photos extends GridGallery_Photos_Model_Photos
{
  /**
   * @var array|null
   * @see GridGalleryPro_Photos_Model_Photos::getMetadataAliases()
   */
  protected $metadataAliases;

  protected $exifModel = null;
  public $gallerySettings = false;

  public function setGallerySettings($settings)
  {
    $this->gallerySettings = $settings;
  }

  public function getPhotos($resourcesData)
  {
    $photos = [];

    if (count($resourcesData)) {
      // prepare ResourceIdsArr
      $resourceIds = [];
      foreach ($resourcesData as $oneResource) {
        $resourceIds[] = $oneResource->resource_id;
      }

      $query = $this->getQueryBuilder()
        ->select('p.*, a.attributes')
        ->from($this->table . ' as p')
        ->leftJoin($this->db->prefix . 'gg_attributes as a')
        ->on('p.id', '=', 'a.pid')
        ->where('p.id', 'IN', implode(',', $resourceIds));

      $photosList = $this->db->get_results($query->build());

      if (count($photosList)) {
        $photosList = $this->preExtend($photosList, $resourceIds);
        foreach ($photosList as $element) {
          $element->attributes = unserialize((string) $element->attributes);
          $photoExtend = $this->extend($element);
          //if(is_null($photoExtend->attachment)) {
          if (!empty($photoExtend->link_type)) {
            $photoExtend = $this->extendRemoteImage($photoExtend);
          }
          array_push($photos, $photoExtend);
        }
      }
    }
    return $photos;
  }

  public function extendRemoteImage($photo)
  {
    $full = $photo->link_full;
    $thumb = $photo->link_thumb;
    if ($photo->link_type == 1) {
      $minSize = 220;
      $sizeType = 'w';
      $settings = $this->gallerySettings;
      $area = is_object($settings) && isset($settings->data['area']) ? $settings->data['area'] : false;
      $grid = isset($area['grid']) ? $area['grid'] : 0;

      $thumbSize = $minSize;
      if ($grid == 2) {
        //horizontal
        if ($area && !empty($area['photo_height']) && isset($area['photo_height_unit']) && empty($area['photo_height_unit'])) {
          $thumbSize = (int) $area['photo_height'];
        }
        $sizeType = 'h';
      } else {
        if ($area && !empty($area['photo_width']) && isset($area['photo_width_unit']) && empty($area['photo_width_unit'])) {
          $thumbSize = (int) $area['photo_width'];
        }
      }
      if ($thumbSize < $minSize) {
        $thumbSize = $minSize;
      }
      $thumb = str_replace('[sw]', $sizeType . $thumbSize, $thumb);
    }

    $photo->attachment = [
      'id' => null,
      'title' => '',
      'filename' => '',
      'url' => $full,
      'link' => '',
      'alt' => '',
      'author' => 0,
      'description' => '',
      'caption' => '',
      'name' => '',
      'status' => 'inherit',
      'uploadedTo' => 0,
      'date' => 0,
      'modified' => 0,
      'menuOrder' => 0,
      'mime' => 'image/jpeg',
      'type' => 'image',
      'subtype' => 'jpeg',
      'icon' => '',
      'dateFormatted' => '',
      'nonces' => [],
      'editLink' => '',
      'meta' => null,
      'authorName' => '',
      'filesizeInBytes' => 0,
      'filesizeHumanReadable' => '0 KB',
      'height' => 0,
      'width' => 0,
      'orientation' => 'landscape',
      'sizes' => [
        'thumbnail' => [
          'url' => $photo->link_default,
          'height' => 0,
          'width' => 0,
          'orientation' => 'landscape',
        ],
        'medium' => [
          'url' => $thumb,
          'height' => 0,
          'width' => 0,
          'orientation' => 'landscape',
        ],
        'large' => [
          'url' => $full,
          'height' => 0,
          'width' => 0,
          'orientation' => 'landscape',
        ],
        'full' => [
          'url' => $full,
          'height' => 0,
          'width' => 0,
          'orientation' => 'landscape',
        ],
      ],
      'external_link' => null,
      'target' => '_self',
      'video' => null,
      'linkedImages' => '',
      'hoverCaptionImage' => null,
      'rel' => null,
      'captionEffect' => '',
      'cropPosition' => 'center-center',
      'isRemoteImage' => 1,
      'thumbUrl' => $thumb,
    ];

    return $photo;
  }

  /**
   * Returns the attachment metadata fields.
   * @return array
   */
  public function getMetadataAliases()
  {
    if (!$this->metadataAliases) {
      $this->metadataAliases = array_merge(parent::getMetadataAliases(), [
        'link' => '_grid_gallery_link',
        'target' => '_grid_gallery_target',
        'video' => '_grid_gallery_video',
        'linkedImages' => '_grid_gallery_linked_images',
        'hoverCaptionImage' => '_grid_gallery_hover_caption_image',
        'captionDescription' => '_grid_gallery_caption_description',
        'buttonLinkTitle' => '_grid_gallery_button_link_title',
        'buttonLinkUrl' => '_grid_gallery_button_link_url',
        'imageKeywords' => '_grid_gallery_image_keywords',
        'exif' => '_grid_gallery_exif',
      ]);
    }

    return $this->metadataAliases;
  }

  /**
   * Returns the metadata field name by his alias.
   * @param string $alias Field alias.
   */
  public function getMetadataField($alias)
  {
    $metadata = $this->getMetadataAliases();

    return isset($metadata[$alias]) ? $metadata[$alias] : null;
  }

  /**
   * Sets a target.
   * @param int    $attachmentId Attachment ID.
   * @param string $target       Target attribute (_self, _blank, etc).
   */
  public function setTarget($attachmentId, $target)
  {
    update_post_meta((int) $attachmentId, $this->getMetadataField('target'), $target);
  }

  /**
   * Sets a target.
   * @param int    $attachmentId Attachment ID.
   * @param string $target       Target attribute (_self, _blank, etc).
   */
  // public function setTarget($attachmentId, $target)
  // {
  //     update_post_meta(
  //         (int)$attachmentId,
  //         $this->getMetadataField('target'),
  //         $target
  //     );
  // }

  /**
   * Sets the video url.
   * @param int    $attachmentId Attachment ID.
   * @param string $video Video  URL.
   */
  public function setVideo($attachmentId, $video)
  {
    update_post_meta((int) $attachmentId, $this->getMetadataField('video'), $video);
  }

  public function setLinkedImages($attachmentId, $images)
  {
    update_post_meta((int) $attachmentId, $this->getMetadataField('linkedImages'), $images);
  }

  public function setHoverCaptionImage($attachmentId, $hoverCaptionImage)
  {
    $hoverCaptionImage = stripslashes($hoverCaptionImage);
    update_post_meta((int) $attachmentId, $this->getMetadataField('hoverCaptionImage'), $hoverCaptionImage);
  }

  public function setCaptionDescription($attachmentId, $captionDescription)
  {
    $captionDescription = stripslashes($captionDescription);
    update_post_meta((int) $attachmentId, $this->getMetadataField('captionDescription'), $captionDescription);
  }

  public function setButtonLinkTitle($attachmentId, $buttonLinkTitle)
  {
    $buttonLinkTitle = stripslashes($buttonLinkTitle);
    update_post_meta((int) $attachmentId, $this->getMetadataField('buttonLinkTitle'), $buttonLinkTitle);
  }

  public function setButtonLinkUrl($attachmentId, $buttonLinkUrl)
  {
    $buttonLinkUrl = stripslashes($buttonLinkUrl);
    update_post_meta((int) $attachmentId, $this->getMetadataField('buttonLinkUrl'), $buttonLinkUrl);
  }

  public function setImageKeywords($attachmentId, $imageKeywords)
  {
    $imageKeywords = stripslashes($imageKeywords);
    update_post_meta((int) $attachmentId, $this->getMetadataField('imageKeywords'), $imageKeywords);
  }

  public function setExifModel($model)
  {
    $this->exifModel = $model;
  }

  /**
   * photo add
   * @return bool
   */
  public function add($attachmentId, $folderId = 0, $addOptions = [])
  {
    $saveRes = parent::add($attachmentId, $folderId);
    // check option for Export Exif data
    if ($saveRes && !empty($addOptions['saveExifData']) && $addOptions['saveExifData'] == 1 && !empty($this->exifModel)) {
      // save exif info
      $this->exifModel->updateExifForAttachment($attachmentId, $this->getMetadataField('exif'));
    }
    return $saveRes;
  }

  public function getAttachmentArrBy($photosArr)
  {
    $attachmentArr = [];
    if (count($photosArr)) {
      $query = $this->getQueryBuilder()->select('attachment_id')->from($this->table)->where('id', 'IN', implode(',', $photosArr));

      $resArr = $this->db->get_results($query->build(), ARRAY_A);
      if (count($resArr)) {
        foreach ($resArr as $onePhotoRec) {
          $attachmentArr[] = $onePhotoRec['attachment_id'];
        }
      }
    }
    return $attachmentArr;
  }

  public function addRemotePhoto($linkType, $linkFull, $linkThumb, $linkDefault)
  {
    //$linkType
    //1 - Google Drive
    $query = $this->getQueryBuilder()->insertInto($this->table)->fields('attachment_id', 'link_type', 'link_full', 'link_thumb', 'link_default')->values(0, $linkType, $linkFull, $linkThumb, $linkDefault);

    if (!$this->db->query($query->build())) {
      $this->lastError = $this->db->last_error;
      return false;
    }

    $this->insertId = $this->db->insert_id;
    return true;
  }
}
