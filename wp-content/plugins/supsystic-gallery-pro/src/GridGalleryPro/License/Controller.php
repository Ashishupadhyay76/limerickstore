<?php

class GridGalleryPro_License_Controller extends GridGallery_Core_BaseController
{
  public function requireNonces()
  {
    return ['completeAction', 'activateAction'];
  }

  public function indexAction(RscSgg_Http_Request $request)
  {
    $helper = $this->getHelper();
    $options = $this->getOptions();
    if (!$helper->isActive($options)) {
      $useTrial = $options->get('license_use_trial');
      $userEmail = base64_decode($options->get('license_email'));
      $userKey = base64_decode($options->get('license_key'));
      return $this->response('@license/index.twig', ['useTrial' => $useTrial, 'licenseEmail' => $userEmail, 'licenseKey' => $userKey]);
    } else {
      $daysLeft = $options->get('license_days_left');
      $date = date('Y-m-d');
      $dateLeft = date('Y-m-d', strtotime("$date +$daysLeft day"));
      return $this->response('@license/active.twig', ['days' => $daysLeft, 'dateLeft' => $dateLeft]);
    }
  }

  public function activateAction(RscSgg_Http_Request $request)
  {
    $data = $request->post->all();
    $options = $this->getOptions();
    $status = false;
    $errors = [];

    if ($this->getHelper()->activate($options, $data)) {
      $status = true;
    } else {
      $errors = $this->getHelper()->getErrors();
    }

    return $this->response(RscSgg_Http_Response::AJAX, ['status' => $status, 'errors' => $errors]);
  }

  protected function getOptions()
  {
    return $this->getModule('license')->getOptions();
  }

  protected function getHelper()
  {
    return $this->getModule('license')->getHelper();
  }
  public function dismissNoticeAction(RscSgg_Http_Request $request)
  {
    $this->getOptions()->save('dismiss_pro_opt', 1); // Just save it
  }
}
