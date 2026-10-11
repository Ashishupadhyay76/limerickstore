<?php

class GridGalleryPro_Photos_Module extends GridGallery_Photos_Module
{
  /**
   * Overload module with the new features.
   */
  public function onInit()
  {
    // Allow to overload module controller.
    $this->setOverloadController(true);

    // Extends attachments with the data from the PRO version.
    // Now 'wp_prepare_attachment_for_js' will be return the fields
    // like video, external link and link target.
    add_filter('wp_prepare_attachment_for_js', [$this, 'prepareAttachmentForJs']);

    add_action('wp_ajax_getAttachmentsById', [$this->getController(), 'getAttachmentsByIdAction']);
    add_action('sgg_add_new_image_to_gallery', [$this->getController(), 'addImageToGalleryHandler']);
    parent::onInit();
  }

  /**
   * Extends attachments with the data from the PRO version.
   * @param  array $data Attachment data.
   * @return array
   */
  public function prepareAttachmentForJs($data)
  {
    $photos = new GridGalleryPro_Photos_Model_Photos();
    $id = $data['id'];

    // Called 'Extranal link', because 'link' reserved by WordPress.
    $data['external_link'] = get_post_meta($id, $photos->getMetadataField('link'), true);

    $data['target'] = get_post_meta($id, $photos->getMetadataField('target'), true);

    $data['video'] = get_post_meta($id, $photos->getMetadataField('video'), true);

    $data['linkedImages'] = get_post_meta($id, $photos->getMetadataField('linkedImages'), true);

    $data['hoverCaptionImage'] = get_post_meta($id, $photos->getMetadataField('hoverCaptionImage'), true);

    $data['captionDescription'] = get_post_meta($id, $photos->getMetadataField('captionDescription'), true);

    $data['buttonLinkTitle'] = get_post_meta($id, $photos->getMetadataField('buttonLinkTitle'), true);

    $data['buttonLinkUrl'] = get_post_meta($id, $photos->getMetadataField('buttonLinkUrl'), true);

    $data['imageKeywords'] = get_post_meta($id, $photos->getMetadataField('imageKeywords'), true);

    $data['exif'] = get_post_meta($id, $photos->getMetadataField('exif'), true);

    return $data;
  }

  protected function normalizeSlashes($str)
  {
    if (is_array($str)) {
      return array_map([$this, 'normalizeSlashes'], $str);
    }
    return str_replace('\\', '/', $str);
  }

  /**
   * Returns the URL to the PRO version of the module.
   * @return string
   */
  public function getProLocationUrl()
  {
    [$dirname, $plugins] = $this->normalizeSlashes([dirname(__FILE__), realpath(WP_PLUGIN_DIR)]);
    $url = plugins_url(str_replace($plugins, '', $dirname));
    return $url;
  }

  public function getBackendJS()
  {
    if (!$this->getEnvironment()->isModule('galleries')) {
      return parent::getBackendJS();
    }

    $version = $this->getConfig()->get('pro_plugin_version');
    $scripts = array_merge(parent::getBackendJS(), [
      // Toast UI Image Editor dependencies (CDN)
      $this->getProLocationUrl() . '/assets/js/lib/tui/tui-color-picker.min.js',
      $this->getProLocationUrl() . '/assets/js/lib/tui/tui-image-editor.min.js',
      [
        'handle' => 'pro.photos.js',
        'source' => $this->getProLocationUrl() . '/assets/js/photos.js',
        'dependencies' => ['jquery'],
        'version' => $version,
      ],
    ]);

    return $scripts;
  }

  public function getBackendCSS()
  {
    if (!$this->getEnvironment()->isModule('galleries')) {
      return parent::getBackendCSS();
    }
    $styles = array_merge(parent::getBackendCSS(), [$this->getProLocationUrl() . '/assets/js/lib/tui/tui-color-picker.min.css', $this->getProLocationUrl() . '/assets/js/lib/tui/tui-image-editor.min.css']);
    return $styles;
  }
}
