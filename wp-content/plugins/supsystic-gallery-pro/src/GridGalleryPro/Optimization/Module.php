<?php
class GridGalleryPro_Optimization_Module extends GridGallery_Optimization_Module
{
  public function onInit()
  {
    parent::onInit();
    $this->setOverloadController(true);
  }
}
