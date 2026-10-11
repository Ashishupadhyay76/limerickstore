<?php
class GridGalleryPro_Optimization_Controller extends GridGallery_Optimization_Controller
{
  protected function getModelAliases()
  {
    return array_merge(parent::getModelAliases(), [
      'settings' => 'GridGalleryPro_Galleries_Model_Settings',
    ]);
  }

  public function prepareCdnPhotoList($route, $galleryArr, &$photos, &$galleryInfo)
  {
    $optimizePreview = false;
    $attachmentSimpleModel = new GridGallery_Galleries_Attachment();
    $resources = $this->getModel('resources');
    $photoModel = $this->getModel('photos');

    if (isset($route['data']['optimize-preview']) && $route['data']['optimize-preview'] == 1) {
      $optimizePreview = true;
    }

    $gallKeyList = array_keys($galleryArr);
    foreach ($gallKeyList as $galleryId) {
      $galleryId = intval($galleryId);
      if ($galleryId) {
        $galleryInfo[$galleryId]['size'] = 0;
        $currGallerySettings = $this->getModel('settings')->get($galleryId);
        $currGalleryResourcesData = $resources->getByGalleryId($galleryId);
        $currGalleryPhotoInfo = $photoModel->getPhotos($currGalleryResourcesData);

        if (count($currGalleryPhotoInfo) > 0) {
          if ($optimizePreview && isset($currGallerySettings->data)) {
            $settingsForWmPreview = GridGallery_Galleries_Attachment::prepareWmImgParamsFromGallerySett($currGallerySettings);
          }

          $imgWithWatermark = false;
          if (isset($currGallerySettings->data['watermark']) && $currGallerySettings->data['watermark']['enabled'] == 1) {
            $attachmentProModel = new GridGalleryPro_Galleries_Attachment();
            $currGallerySettings->data['watermark']['galleryId'] = $galleryId;
            $imgWithWatermark = true;
          }

          foreach ($currGalleryPhotoInfo as $onePhoto) {
            if (isset($onePhoto->attachment['url'])) {
              $photoToAdd = [];
              // prepare main image
              if ($imgWithWatermark) {
                $photoToAdd['img_url'] = $attachmentProModel->getHashedFullFilePath($onePhoto->attachment['url'], '0x0', $currGallerySettings->data['watermark']);
                $photoToAdd['img_url'] = $attachmentSimpleModel->replacePathToUrl($photoToAdd['img_url'], true);
                $photoToAdd['type'] = 'watermark';
              } else {
                $photoToAdd['img_url'] = $onePhoto->attachment['url'];
                $photoToAdd['attachment_id'] = $onePhoto->attachment_id;
              }
              $galleryInfo[$galleryId]['size'] += filesize($attachmentSimpleModel->replaceUrlToFilePath($photoToAdd['img_url']));

              // prepare preview image
              if ($optimizePreview) {
                $cropPosition = isset($onePhoto->attachment['cropPosition']) ? $onePhoto->attachment['cropPosition'] : null;

                // get Thumbnail Image Url
                $calcAttachUrl = $attachmentSimpleModel->getAttachment($onePhoto->attachment_id, $settingsForWmPreview['photo_width'], $settingsForWmPreview['photo_height'], $cropPosition, $settingsForWmPreview['crop_quality']);

                if ($imgWithWatermark) {
                  $calcAttachUrl = $attachmentProModel->getHashedFullFilePath($calcAttachUrl, '', $currGallerySettings->data['watermark']);
                  $calcAttachUrl = $attachmentSimpleModel->replacePathToUrl($calcAttachUrl, true);
                  $photoToAdd['preview_type'] = 'watermark';
                }

                if ($calcAttachUrl && $onePhoto->attachment['url'] != $calcAttachUrl) {
                  $photoToAdd['preview_url'] = $calcAttachUrl;
                  $galleryInfo[$galleryId]['size'] += filesize($attachmentSimpleModel->replaceUrlToFilePath($photoToAdd['preview_url']));
                }
              }

              if (!empty($onePhoto->attachment['linkedImages'])) {
                $linkedImagesIds = explode(',', $onePhoto->attachment['linkedImages']);
                foreach ($linkedImagesIds as $linkedImgAttachId) {
                  $liAttachment = wp_prepare_attachment_for_js($linkedImgAttachId);
                  $oneLinkedImg = [];
                  $oneLinkedImg['img_url'] = $liAttachment['sizes']['full']['url'];
                  $oneLinkedImg['attachment_id'] = $linkedImgAttachId;
                  $oneLinkedImg['type'] = 'linked-image';
                  $galleryInfo[$galleryId]['size'] += filesize($attachmentSimpleModel->replaceUrlToFilePath($oneLinkedImg['img_url']));

                  if ($optimizePreview && isset($liAttachment['sizes']['thumbnail'])) {
                    $oneLinkedImg['preview_url'] = $liAttachment['sizes']['thumbnail']['url'];
                    $galleryInfo[$galleryId]['size'] += filesize($attachmentSimpleModel->replaceUrlToFilePath($oneLinkedImg['preview_url']));
                  }
                  $photos[$galleryId][] = $oneLinkedImg;
                }
              }
              $photos[$galleryId][] = $photoToAdd;
            }
          }
        }
      }
    }
    return true;
  }
}
