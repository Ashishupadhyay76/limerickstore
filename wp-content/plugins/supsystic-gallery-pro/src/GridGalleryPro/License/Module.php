<?php

class GridGalleryPro_License_Module extends RscSgg_Mvc_Module
{
  private $options;

  private $helper;

  /**
   * {@inheritdoc}
   */
  public function onInit()
  {
    $environment = $this->getEnvironment();
    $config = $environment->getConfig();

    $this->registerMenu();

    $config->add('disable_msg', '');

    add_action('admin_footer', [$this, 'loadAssets']);

    add_action('admin_notices', [$this, 'checkActivation']);
    add_action('init', [$this, 'addAfterInit']);

    $this->_licenseCheck();
    $this->filterActionLinks();
  }

  public function getLocationUrl()
  {
    return untrailingslashit(plugin_dir_url(__FILE__));
  }

  public function addLicenseLink($links)
  {
    $environment = $this->getEnvironment();

    if (is_array($links)) {
      $linkTitle = null;
      $options = $this->getOptions();
      $helper = $this->getHelper();
      $expired = $helper->isExpired();
      $isActive = $helper->isActive($options);

      if (!$isActive) {
        $linkTitle = $environment->translate('Activate License');
      } elseif ($expired) {
        $linkTitle = $environment->translate('Renew License');
      }

      if ($linkTitle) {
        $href = $environment->generateUrl('license');
        $links[] = '<a href="' . $href . '">' . $linkTitle . '</a>';
      }
    }

    return $links;
  }

  /**
   * Loads the assets required by the module
   */
  public function loadAssets()
  {
    $environment = $this->getEnvironment();

    if (!$environment->isModule('license')) {
      return;
    }

    wp_enqueue_script('jquery');

    wp_enqueue_script('supsystic-license-js', $this->getLocationUrl() . '/assets/js/license.js', [], '1.0.0', 'all');

    wp_enqueue_style('supsystic-license-style', $this->getLocationUrl() . '/assets/css/license-styles.css', [], '1.0.0', 'all');
  }

  protected function registerMenu()
  {
    $menu = $this->getMenu();
    $plugin_menu = $this->getConfig()->get('plugin_menu');
    $capability = $plugin_menu['capability'];

    $submenu = $menu->createSubmenuItem();
    $submenu
      ->setCapability($capability)
      ->setMenuSlug($menu->getMenuSlug() . '&module=license')
      ->setMenuTitle($this->translate('License'))
      ->setPageTitle($this->translate('License'))
      ->setModuleName('license')
      ->setSortOrder(80);

    $menu->addSubmenuItem('license', $submenu)->register();
  }

  public function addAfterInit()
  {
    if (!function_exists('getProPlugDirPps')) {
      return;
    }
    //add_action('in_plugin_update_message-'. getProPlugDirPps(). '/'. getProPlugFilePps(), array($this, 'checkDisabledMsgOnList'), 1, 2);
  }

  public function checkDisabledMsgOnList($plugin_data, $r)
  {
    if ($this->getHelper()->isExpired()) {
      $msg = 'Your license is expired. Once you extend your license - you will be able to Update PRO version. Go to License tab and click on "Re-activate" button to re-activate your PRO version.';
      $this->getEnvironment()->getConfig()->set('disable_msg', $msg);
    }
  }

  public function isActive()
  {
    return $this->getHelper()->isActive($this->getOptions());
  }

  public function checkActivation()
  {
    $options = $this->getOptions();

    if (!$this->getHelper()->isActive($options)) {
      $isDismissable = true;
      $msgClasses = 'error';
      if ($this->getHelper()->isExpired()) {
        if ($this->getHelper()->_getPluginCode() == 'gallery_by_supsystic_pro') {
          $msg = sprintf(
            '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. Your plugin <b>PRO license is expired</b>. It means that your <b>PRO version will not work</b>. To activate PRO functionality - with all features, options, updates and premium support, just <b>extend PRO version license - follow <a href="%s" target="_blank">this link</a></b>',
            $this->getEnvironment()->getMenu()->getMenuTitle(),
            $this->getExtendUrl(),
          );
        } else {
          $msg = sprintf(
            '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. Your plugin <b>Trial license is expired</b>. It means that your <b>PRO version will not work</b>. To activate PRO functionality - with all features, options, updates and premium support, just <b>purchase PRO version - follow <a href="https://supsystic.com/plugins/photo-gallery/" target="_blank">this link</a></b>',
            $this->getEnvironment()->getMenu()->getMenuTitle(),
          );
        }
      } else {
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. You need to activate your copy of PRO version %s. Go to <a href="%s">License</a> tab and finish your software activation process.',
          $this->getEnvironment()->getMenu()->getMenuTitle(),
          $this->getEnvironment()->getMenu()->getMenuTitle(),
          $this->getEnvironment()->generateUrl('license'),
        );
      }

      // Make it little bit pretty)
      $msg = '<p>' . $msg . '</p>';
      if ($isDismissable) {
        $dismiss = (int) $this->getOptions()->get('dismiss_pro_opt');
        if ($dismiss) {
          return;
        } // it was already dismissed by user - no need to show it again
        // Those classes required to display close "X" button in message
        $msgClasses .= ' notice is-dismissible supsystic-pro-notice sg-notification';
        wp_enqueue_script('sg-supsystic-dismiss-license-js', $this->getLocationUrl() . '/assets/js/dismiss.license.js', [], '1.0.0', 'all');
      }
      $html = '<div class="' . $msgClasses . '">' . $msg . '</div>';
      echo $html;
    }
  }
  public function getExtendUrl()
  {
    return $this->getHelper()->getExtendUrl();
  }
  private function _licenseCheck()
  {
    $options = $this->getOptions();
    $helper = $this->getHelper();

    if ($helper->isActive($options)) {
      $helper->check($options);
      $helper->checkPreDeactivateNotify($options);
      //            $this->load();
    }
  }
  private function _updateDb()
  {
    $this->getHelper()->updateDb();
  }

  private function getModuleNamespace(RscSgg_Mvc_Module $module)
  {
    $config = $this->getEnvironment()->getConfig();

    return $config->get('pro_modules_prefix');
  }

  private function getModel($name)
  {
    $className = $this->buildModelClassName($name);

    if (!class_exists($className)) {
      throw new InvalidArgumentException(sprintf('Can\'t find model %s.', $className));
    }

    $model = new $className($this->getEnvironment());

    return $model;
  }

  private function buildModelClassName($name)
  {
    return $this->getModuleNamespace($this) . '_' . ucfirst($this->getModuleName()) . '_Model_' . ucfirst($name);
  }

  public function getOptions()
  {
    if (null === $this->options) {
      $this->options = $this->getModel('options');
    }

    return $this->options;
  }

  public function getHelper()
  {
    if (null === $this->helper) {
      $this->helper = $this->getModel('helper');
    }

    return $this->helper;
  }

  private function filterActionLinks()
  {
    $root = $this->getEnvironment()->getPluginPath();
    $pluginId = plugin_basename($root . '/index.php');

    add_filter('plugin_action_links_' . $pluginId, [$this, 'addLicenseLink']);
  }
  public function isLegal()
  {
    if (!$this->isActive()) {
      return (int) $this->getOptions()->get('_love_for_all_');
    }
    return true;
  }
}
