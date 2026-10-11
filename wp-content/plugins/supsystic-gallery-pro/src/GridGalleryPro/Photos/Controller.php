<?php

class GridGalleryPro_Photos_Controller extends GridGallery_Photos_Controller
{
  /**
   * {@inheritdoc}
   */
  protected function getModelAliases()
  {
    return array_merge(parent::getModelAliases(), [
      'resources' => 'GridGalleryPro_Galleries_Model_Resources',
      'photos' => 'GridGalleryPro_Photos_Model_Photos',
      'tags' => 'GridGalleryPro_Galleries_Model_Tags',
      'exif' => 'GridGalleryPro_Photos_Model_Exif',
      'settings' => 'GridGalleryPro_Galleries_Model_Settings',
      'yutubeImgPars' => 'GridGalleryPro_Video_Model_Vendors_YoutubeParser',
      'vimeoImgPars' => 'GridGalleryPro_Video_Model_Vendors_VimeoParser',
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function updateAttachmentAction(RscSgg_Http_Request $request)
  {
    $attachmentId = $request->post->get('attachment_id');
    $replaceAttachmentId = (int) $request->post->get('replace_attachment_id');
    if ($replaceAttachmentId) {
      $attachmentId = $replaceAttachmentId;
    }
    //$link         = $request->post->get('link');
    //$target       = $request->post->get('target', '_self');
    $video = $request->post->get('video');
    $linkedImages = $request->post->get('linkedImages');
    $hoverCaptionImage = $request->post->get('hoverCaptionImageInp');
    $captionDescription = $request->post->get('captionDescription');
    $buttonLinkTitle = $request->post->get('buttonLinkTitle');
    $buttonLinkUrl = $request->post->get('buttonLinkUrl');
    $imageKeywords = $request->post->get('imageKeywords');

    $update = [];
    if (isset($video)) {
      $update['video'] = $video;
    }
    if (isset($linkedImages)) {
      $update['linkedImages'] = $linkedImages;
    }
    if (isset($hoverCaptionImage)) {
      $update['hoverCaptionImage'] = $hoverCaptionImage;
    }
    if (isset($captionDescription)) {
      $update['captionDescription'] = $captionDescription;
    }
    if (isset($buttonLinkTitle)) {
      $update['buttonLinkTitle'] = $buttonLinkTitle;
    }
    if (isset($buttonLinkUrl)) {
      $update['buttonLinkUrl'] = $buttonLinkUrl;
    }
    if (isset($imageKeywords)) {
      $update['imageKeywords'] = $imageKeywords;
    }

    $this->getModel('photos')->updateMetadata($attachmentId, $update);
    return parent::updateAttachmentAction($request);
  }

  public function getAttachmentsByIdAction($huh)
  {
    $attachments = trim($this->getRequest()->post->get('attachments'));

    $images = [];

    if (!empty($attachments)) {
      foreach (explode(',', $attachments) as $id) {
        $images[] = wp_prepare_attachment_for_js($id);
      }
    }

    return $this->response(RscSgg_Http_Response::AJAX, [
      'attachments' => $images,
    ]);
  }

  public function getAttachmentArrByAction(RscSgg_Http_Request $request)
  {
    $parameters = $request->post->get('route');
    $galleryId = !empty($parameters['gallery_id']) ? ((int) $parameters['gallery_id']) : 0;
    $attachmentArr = [];
    $response = ['error' => true];

    if ($galleryId) {
      $resourceModel = $this->getModel('resources');
      $photoModel = $this->getModel('photos');

      $photosIdsArr = [];
      // get photos info from resource
      $resourceObjArr = $resourceModel->getByGalleryId($galleryId);

      if (count($resourceObjArr)) {
        foreach ($resourceObjArr as $oneRes) {
          if ($oneRes->resource_type == 'photo') {
            $photosIdsArr[] = $oneRes->resource_id;
          }
        }
        // get attachment info from photos
        $attachmentArr = $photoModel->getAttachmentArrBy($photosIdsArr);
        $response['attachmentArr'] = $attachmentArr;
        $response['error'] = false;
      }
    }
    if ($response['error']) {
      $response['message'] = $this->translate('Error occurred!');
    }
    return $this->response(RscSgg_Http_Response::AJAX, $response);
  }

  public function updatePhotoExifInfoAction(RscSgg_Http_Request $request)
  {
    $parameters = $request->post->get('route');
    $attachmentId = !empty($parameters['attachment_id']) ? ((int) $parameters['attachment_id']) : 0;
    $response = ['error' => true];
    if ($attachmentId) {
      $exifModel = $this->getModel('exif');
      $photoModel = $this->getModel('photos');
      $exifModel->updateExifForAttachment($attachmentId, $photoModel->getMetadataField('exif'));
      $response['success'] = true;
      $response['error'] = false;
    }
    if ($response['error']) {
      $response['message'] = $this->translate('Error occurred!');
    }
    return $this->response(RscSgg_Http_Response::AJAX, $response);
  }

  protected function addPhotoResForAddAction($_photos, $_attachment, $_request)
  {
    $exifModel = $this->getModel('exif');
    $_photos->setExifModel($exifModel);
    return $_photos->add($_attachment->ID, $_request->post->get('folder_id', 0), [
      'saveExifData' => $_request->post->get('save_exif_data', 0),
    ]);
  }

  public function addImageToGalleryHandler($params)
  {
    if (!empty($params['gallery_id']) && isset($params['attachment'])) {
      $galleryId = (int) $params['gallery_id'];
      $settings = $this->getModel('settings')->get($galleryId);
      // check settings and adding watermark
      if (isset($settings->data['watermark']) && $settings->data['watermark']['enabled'] == 1 && !empty($params['attachment']->guid)) {
        $settings->data['watermark']['toCreateWatermark'] = true;
        $settings->data['watermark']['galleryId'] = $galleryId;
        $attachmentModel = new GridGalleryPro_Galleries_Attachment();
        $attachmentModel->watermark = $settings->data['watermark'];
        // prepare Preview Photo Params
        $wpPhotoSett = GridGalleryPro_Galleries_Attachment::prepareWmImgParamsFromGallerySett($settings);
        // prepare cropPosition
        $cpMetaRes = get_post_meta($params['attachment']->ID, 'cropPosition');
        $cropPosition = null;
        if ($cpMetaRes && !empty($cpMetaRes)) {
          $cropPosition = $cpMetaRes[0];
        }

        $wmShowFor = $settings->data['watermark']['show_for'];
        if ($wmShowFor == 3 || $wmShowFor == 2) {
          $r1 = $attachmentModel->getImgUrlWithWatermark($params['attachment']->guid, ['width' => '0', 'height' => '0']);
        }
        if ($wmShowFor == 3 || $wmShowFor == 1) {
          $r2 = $attachmentModel->getAttachment($params['attachment']->ID, $wpPhotoSett['photo_width'], $wpPhotoSett['photo_height'], $cropPosition, $wpPhotoSett['crop_quality']);
        }
      }
    }
  }

  public function addImageFromVideoUrlAction(RscSgg_Http_Request $request)
  {
    $responseArray = ['success' => false];
    $videoType = trim($request->post->get('videoType'));
    $videoUrl = trim($request->post->get('videoUrl'));
    $galleryId = (int) $request->post->get('galleryId');

    if (!$galleryId) {
      $responseArray['error'] = $this->translate('Error. Incorrect gallery params. Send message to support.');
    }
    if (!$videoUrl) {
      $responseArray['error'] = $this->translate('Enter "Video url" value');
    } elseif (!in_array($videoType, ['youtube', 'vimeo'])) {
      /// 'vimeo'
      $responseArray['error'] = $this->translate('Please select exist "Video type"');
    } else {
      // add image to wp Gallery
      if ($videoType == 'youtube') {
        $videoVendor = $this->getModel('yutubeImgPars');
      } else {
        $videoVendor = $this->getModel('vimeoImgPars');
      }
      $photoModel = $this->getModel('photos');

      $videoVendor->setEnvironment($this->getEnvironment());
      $attachmentId = $videoVendor->addAttachmentFromUrl($videoUrl);

      if ($attachmentId) {
        // add video tab value to Image parameter
        $photoModel->setVideo($attachmentId, $videoUrl);

        // add image to gallery
        $this->getModule('galleries')->cleanCache($galleryId);

        if ($photoModel->add($attachmentId, 0, ['saveExifData' => 0])) {
          $this->getModel('resources')->attach($galleryId, 'photo', $photoModel->getByAttachmentId($attachmentId)->id, true);
          $attachmentInfo = get_post($attachmentId);

          $imageParams = [
            'gallery_id' => $galleryId,
            'attachment' => $attachmentInfo,
          ];
          do_action('sgg_add_new_image_to_gallery', $imageParams);
          $responseArray['success'] = true;
        } else {
          $responseArray['error'] = $this->translate('Error when add photo to gallery');
        }
      }

      if (!$responseArray['success']) {
        $responseArray['error'] = $videoVendor->getAllErrorString();
      }
    }
    return $this->response(RscSgg_Http_Response::AJAX, $responseArray);
  }

  public function saveEditedImageAction(RscSgg_Http_Request $request)
  {
    $response = ['success' => false];
    $galleryId = (int) $request->post->get('galleryId');
    $sourceAttachmentId = (int) $request->post->get('sourceAttachmentId');
    $dataUrl = $request->post->get('imageDataUrl');

    if (!$galleryId || !$sourceAttachmentId || !$dataUrl) {
      $response['message'] = $this->translate('Invalid parameters');
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    // Parse data URL
    if (preg_match('/^data:(image\/(png|jpeg|jpg));base64,(.*)$/', $dataUrl, $matches)) {
      $mime = $matches[1];
      $ext = $matches[2] === 'jpeg' ? 'jpg' : $matches[2];
      $base64 = $matches[3];
      $imageData = base64_decode($base64);
      if ($imageData === false) {
        $response['message'] = $this->translate('Failed to decode image data');
        return $this->response(RscSgg_Http_Response::AJAX, $response);
      }
    } else {
      $response['message'] = $this->translate('Unsupported image format');
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    // Get original attachment for naming
    $originalAttachment = get_post($sourceAttachmentId);
    if (!$originalAttachment || $originalAttachment->post_type !== 'attachment') {
      $response['message'] = $this->translate('Original image not found');
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    // Build filename for new attachment
    $baseName = sanitize_file_name($originalAttachment->post_name);
    $filename = $baseName . '-edited-' . time() . '.' . $ext;

    // Save new file to uploads
    $upload = wp_upload_bits($filename, null, $imageData);
    if (!empty($upload['error'])) {
      $response['message'] = $upload['error'];
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    // Create new attachment
    $filetype = wp_check_filetype($upload['file'], null);
    $attachment = [
      'post_mime_type' => $filetype['type'],
      'post_title' => sanitize_text_field($baseName . ' edited'),
      'post_content' => '',
      'post_status' => 'inherit',
    ];
    $newAttachmentId = wp_insert_attachment($attachment, $upload['file']);
    if (is_wp_error($newAttachmentId) || !$newAttachmentId) {
      $response['message'] = $this->translate('Failed to create attachment');
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    // Generate metadata for new attachment
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attachData = wp_generate_attachment_metadata($newAttachmentId, $upload['file']);
    wp_update_attachment_metadata($newAttachmentId, $attachData);

    // Get photo model and resources model
    $photoModel = $this->getModel('photos');
    $resourcesModel = $this->getModel('resources');

    // Find the photo entry in gallery for the original attachment
    $originalPhoto = $photoModel->getByAttachmentId($sourceAttachmentId);
    if (!$originalPhoto) {
      $response['message'] = $this->translate('Original photo not found in gallery');
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    // Add new photo to database
    if (!$photoModel->add($newAttachmentId, 0, ['saveExifData' => 0])) {
      $response['message'] = $this->translate('Failed to add new photo to database');
      return $this->response(RscSgg_Http_Response::AJAX, $response);
    }

    $newPhotoId = $photoModel->getInsertId();

    // Update the resource in gallery to point to new photo
    $resourcesModel->updateResource($galleryId, 'photo', $originalPhoto->id, $newPhotoId);

    // Clean gallery cache
    $this->getModule('galleries')->cleanCache($galleryId);

    $response['success'] = true;
    $response['attachmentId'] = $newAttachmentId;
    $response['message'] = $this->translate('Edited image saved successfully');

    return $this->response(RscSgg_Http_Response::AJAX, $response);
  }
}
