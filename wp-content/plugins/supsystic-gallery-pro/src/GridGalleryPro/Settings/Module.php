<?php

class GridGalleryPro_Settings_Module extends GridGallery_Settings_Module
{
  public function onInit()
  {
    parent::onInit();
    $this->setOverloadController(true);
  }

  public function getTemplatesAliases()
  {
    return [
      'settings.index' => '@settings_pro/index.twig',
    ];
  }
}
