<?php

/**
 * Plugin Name: Photo Gallery PRO by Supsystic
 * Plugin URI: http://supsystic.com
 * Description: Easy to use Gallery with professional gallery templates. Show off your best design, photography and creative work
 * Version: 2.10.0
 * Author: supsystic.com
 * Author URI: https://supsystic.com
 * Requires Plugins: gallery-by-supsystic
 **/

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'loader.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wpUpdater.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'galleryUpdater.php';

$loader = new SupsysticGalleryPro_Loader();
$loader->setProVersion('2.10.00');
$loader->setPrefix('GridGalleryPro');
$loader->setPath(dirname(__FILE__) . '/src');
$loader->load();
