<?php
class GridGalleryPro_Video_Model_Vendors_YoutubeParser extends GridGalleryPro_Video_Model_Vendors_VideoThumbNailParser
{
  public function getRegex()
  {
    return [
      // old
      '#(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/(?:v|e|embed)/([A-Za-z0-9\-_]+)#',
      // YouTube URL. After http(s) support a or v for Youtube Lyte and v or vh for Smart Youtube plugin
      '#(?:https?(?:a|vh?)?://)?(?:www\.)?youtube(?:\-nocookie)?\.com/watch\?.*v=([A-Za-z0-9\-_]+)#',
      // shortened youtu.be URL. After http(s) a or v for Youtube Lyte and v or vh for Smart Youtube plugin
      '#(?:https?(?:a|vh?)?://)?youtu\.be/([A-Za-z0-9\-_]+)#',
    ];
  }

  public function getUrlThumbnail()
  {
    $thumbUrl = null;

    $videoId = $this->getVideoKey();
    if ($videoId) {
      $maxres = 'http://img.youtube.com/vi/' . $videoId . '/maxresdefault.jpg';
      $response = wp_remote_head($maxres);
      if (!is_wp_error($response) && $response['response']['code'] == '200') {
        $thumbUrl = $maxres;
      } else {
        $thumbUrl = 'http://img.youtube.com/vi/' . $videoId . '/0.jpg';
      }
    }
    return $thumbUrl;
  }
}
