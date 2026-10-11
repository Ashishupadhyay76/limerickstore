<?php
class GridGalleryPro_Video_Model_Vendors_VimeoParser extends GridGalleryPro_Video_Model_Vendors_VideoThumbNailParser
{
  public function getRegex()
  {
    return [
      //			'#<object[^>]+>.+?http://vimeo\.com/moogaloop.swf\?clip_id=([A-Za-z0-9\-_]+)&.+?</object>#s', // Standard Vimeo embed code
      // Vimeo iframe player
      //			'#(?:https?:)?//player\.vimeo\.com/video/([0-9]+)#',
      // JR_embed shortcode
      //			'#\[vimeo id=([A-Za-z0-9\-_]+)]#',
      // Another shortcode
      //			'#\[vimeo clip_id="([A-Za-z0-9\-_]+)"[^>]*]#',
      // Yet another shortcode
      //			'#\[vimeo video_id="([A-Za-z0-9\-_]+)"[^>]*]#',

      // Vimeo URL
      '#(?:https?://)?(?:www\.)?vimeo\.com/([0-9]+)#',
      // Channel URL
      '#(?:https?://)?(?:www\.)?vimeo\.com/channels/(?:[A-Za-z0-9]+)/([0-9]+)#',
    ];
  }

  public function getUrlThumbnail()
  {
    $thumbUrl = null;

    $videoId = $this->getVideoKey();
    if ($videoId) {
      $infoLink = 'http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/' . $videoId;
      // get video info
      $responseEntry = file_get_contents($infoLink);
      if ($responseEntry) {
        // parse result
        $tmpRespEntry = json_decode($responseEntry);
        if (!empty($tmpRespEntry->thumbnail_url)) {
          $thumbUrl = $tmpRespEntry->thumbnail_url;
        }
      }
    }
    return $thumbUrl;
  }
}
