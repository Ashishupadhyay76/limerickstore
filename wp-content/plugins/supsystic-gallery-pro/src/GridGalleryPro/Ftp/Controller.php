<?php
error_reporting(E_ALL);

class GridGalleryPro_Ftp_Controller extends GridGallery_Core_BaseController
{
  protected function getModelAliases()
  {
    return [
      'photos' => 'GridGallery_Photos_Model_Photos',
      'galleries' => 'GridGallery_Galleries_Model_Galleries',
    ];
  }

  public function indexAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');
    $galleries = $this->getModel('galleries');
    $galleryTitle = $galleries->getById($galleryId)->title;

    if ($request->post->isEmpty()) {
      $ftp = new GridGalleryPro_Ftp_Model_LocalFtp(new RscSgg_Common_Collection());
    } else {
      if ($request->post->get('isRemote')) {
        $ftp = new GridGalleryPro_Ftp_Model_RemoteFtp($request->post);
      } else {
        $ftp = new GridGalleryPro_Ftp_Model_LocalFtp($request->post);
      }

      if ($ftp->parseImages()) {
        return $this->response('@ftp/index.twig', [
          'id' => $galleryId,
          'galleryName' => $galleryTitle,
          'images' => $ftp->getImages(),
        ]);
      }
    }

    return $this->response('@ftp/folder.twig', [
      'id' => $galleryId,
      'galleryName' => $galleryTitle,
      'ftp' => $ftp,
    ]);
  }
}
