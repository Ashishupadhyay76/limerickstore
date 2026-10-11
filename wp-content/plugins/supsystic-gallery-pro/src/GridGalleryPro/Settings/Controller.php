<?php

class GridGalleryPro_Settings_Controller extends GridGallery_Settings_Controller
{
  public function requireNonces()
  {
    return ['saveSettingsAction'];
  }

  public function saveSettingsAction(RscSgg_Http_Request $request)
  {
    $optionsName = $this->getConfig()->get('db_prefix') . 'settings';
    $currentSettings = get_option($optionsName);
    $settings = $request->post->get('settings', []);

    if (!$currentSettings) {
      $currentSettings = [];
    }

    if (!current_user_can('manage_options')) {
      if (isset($currentSettings['access_roles'])) {
        $settings['access_roles'] = $currentSettings['access_roles'];
      }
    }

    // This functions only checks one dimension of n-dimensional array and
    // if array have sub array-elements they are casted to string and since
    // php 5.4 it throws notices
    $diff = @array_diff($settings, $currentSettings);
    $intersect = @array_intersect($settings, $currentSettings);
    $merge = array_merge($intersect, $diff);

    update_option($optionsName, $merge);
    return $this->redirect($this->generateUrl('settings'));
  }
}
