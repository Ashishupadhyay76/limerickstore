<?php

class GridGalleryPro_Googledrive_Controller extends GridGallery_Core_BaseController
{
  public function requireNonces()
  {
    return ['completeAction', 'logoutAction', 'sideloadSaveAction'];
  }

  public function indexAction(RscSgg_Http_Request $request)
  {
    if (!get_option('googledrive_token')) {
      return $this->authorizationAction($request);
    }
    /**
     * @var Google_Client $client
     */
    $client = $this->getModule('googledrive')->getClient();
    $client->setAccessToken(get_option('googledrive_token'));

    if ($client->isAccessTokenExpired()) {
      return $this->authorizationAction($request);
    }

    $googleDrive = new Google_Service_Drive($client);
    $root = null;
    $parentFolder = null;
    $folders = [];
    $images = [];

    //get current folder
    $root = $request->query->get('folder');
    //if folder not set starts in root
    if (!$root) {
      $root = 'root';
      $parentFolder = null;
    } else {
      $folderData = $googleDrive->files->get($root, [
        'fields' => 'parents',
      ]);
      $parentFolder = $folderData->parents[0];
    }

    $pageToken = null;
    do {
      $listFiles = $googleDrive->files->listFiles([
        'q' => "(mimeType='application/vnd.google-apps.folder' or mimeType='image/jpeg' or mimeType='image/gif') and '$root' in parents",
        'spaces' => 'drive',
        'pageToken' => $pageToken,
        'pageSize' => 999,
        'fields' => 'files(trashed,folderColorRgb,id,kind,mimeType,name,starred,thumbnailLink,webContentLink,webViewLink),nextPageToken',
      ]);

      $pageToken = $listFiles->getNextPageToken();

      foreach ($listFiles->getFiles() as $file) {
        if ($file->trashed) {
          continue;
        }

        if ($file->mimeType == 'application/vnd.google-apps.folder') {
          $folders[] = [
            'id' => $file->id,
            'name' => $file->name,
            'color' => $file->folderColorRgb,
          ];
        } else {
          $images[] = [
            'id' => $file->id,
            'name' => $file->name,
            'thumbnailLink' => $file->thumbnailLink,
            'webContentLink' => $file->webContentLink,
          ];
        }
      }
    } while ($pageToken != null);

    $galleryId = $request->query->get('id');
    $galleriesModel = $this->getModule('galleries')->getModel('galleries');
    $galleryTitle = $galleriesModel->getById($galleryId)->title;

    return $this->response('@googledrive/index.twig', [
      'id' => $galleryId,
      'galleryName' => $galleryTitle,
      'parentFolder' => $parentFolder,
      'folders' => $folders,
      'images' => $images,
    ]);
  }

  public function authorizationAction($request)
  {
    $galleryId = $request->query->get('id');
    $galleryTitle = $this->getModule('galleries')->getModel('galleries')->getById($galleryId)->title;
    /**
     * @var Google_Client $client
     */
    //		$client = $this->getModule('googledrive')->getClient();
    //		$client->setState(urlencode($this->getEnvironment()
    //			->generateUrl('googledrive','complete',array(
    //				'id' => $galleryId,
    //			))
    //		));

    $url = 'http://supsystic.com/authenticator/index.php/authenticator/drive';

    $url .=
      '?ref=' .
      base64_encode(
        $this->getEnvironment()->generateUrl('googledrive', 'complete', [
          'id' => $galleryId,
          '_wpnonce' => wp_create_nonce('supsystic-gallery'),
        ]),
      );

    $modulePath = $this->getModule('googledrive')->getLocationUrl();

    return $this->response('@googledrive/authorization.twig', [
      //			'url' => $client->createAuthUrl(),
      'url' => $url,
      'id' => $galleryId,
      'galleryName' => $galleryTitle,
      'logoSrc' => $modulePath . '/img/g-normal.png',
    ]);
  }

  public function completeAction($request)
  {
    $code = $request->query->get('googleAuthCode');
    $galleryId = $request->query->get('id');

    if (!$code) {
      $message = $this->translate('Authorization code is not specified.');
      return $this->response('error.twig', [
        'message' => $message,
      ]);
    }
    try {
      /**
       * @var Google_Client $client
       */
      $client = $this->getModule('googledrive')->getClient();
      update_option('googledrive_token', $client->authenticate($code));
    } catch (Exception $e) {
      return $this->response('error.twig', [
        'message' => $e->getMessage(),
      ]);
    }

    return $this->redirect($this->generateUrl('googledrive', 'index', ['id' => $galleryId]));
  }

  public function logoutAction(RscSgg_Http_Request $request)
  {
    $galleryId = $request->query->get('id');

    /**
     * @var Google_Client $client
     */
    $client = $this->getModule('googledrive')->getClient();
    $client->revokeToken(get_option('googledrive_token'));
    delete_option('googledrive_token');

    return $this->redirect($this->generateUrl('googledrive', 'index', ['id' => $galleryId]));
  }

  public function sideloadSaveAction(RscSgg_Http_Request $request)
  {
    $selectedImages = $request->post->get('urls');
    $onlyLinks = $request->post->get('onlyLinks');
    $galleriesModule = $this->getModule('galleries');
    /**
     * @var GridGalleryPro_Googledrive_Module $module
     */
    $module = $this->getModule('googledrive');
    $attachID = [];

    $client = $this->getModule('googledrive')->getClient();
    $client->setAccessToken(get_option('googledrive_token'));

    if (!$client->isAccessTokenExpired()) {
      $googleDrive = new Google_Service_Drive($client);
      if ($onlyLinks) {
        $photos = $galleriesModule->getModel('photos_pro');
        foreach ($selectedImages as $image) {
          $file = $googleDrive->files->get($image['id'], ['fields' => 'mimeType,thumbnailLink']);
          if (strpos($file->mimeType, 'image') !== false) {
            /* thumbnailLink is not permanent :(

						/*$thumb = $file->thumbnailLink;
						$beginSize = stripos($thumb, '=s');
						if($beginSize > 0) {
							$full = substr($thumb, 0, $beginSize).'=s0';
							$thumb = substr($thumb, 0, $beginSize).'=[sw]';
						} else {
							$full = $thumb;
						}*/
            $thumb = 'https://drive.google.com/thumbnail?authuser=0&id=' . $image['id'] . '&sz=';
            if ($photos->addRemotePhoto(1, $thumb . 's0', $thumb . '[sw]', $thumb . 's220')) {
              $attachID[] = $photos->getInsertId();
            }
          }
        }
      } elseif ($module->createTmpFolder()) {
        $photos = $galleriesModule->getModel('photos');
        foreach ($selectedImages as $image) {
          $content = $googleDrive->files->get($image['id'], [
            'alt' => 'media',
          ]);

          $localFile = str_replace('%', '', urlencode($image['name']));
          $fp = fopen($module->getTmpFolder() . $localFile, 'w', true);
          fwrite($fp, $content);
          fclose($fp);
          $url = $module->getTmpUrl() . $localFile;

          $id = $galleriesModule->media_sideload_image($url, 0);

          if ($photos->add($id)) {
            $attachID[] = $photos->getInsertId();
          }
        }

        $module->cleanTmpFolder();
      }
    }

    return $this->response(RscSgg_Http_Response::AJAX, ['msh' => 'Loaded', 'ids' => $attachID]);
  }
}
