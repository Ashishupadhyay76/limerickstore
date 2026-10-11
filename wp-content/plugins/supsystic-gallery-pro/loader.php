<?php
#[\AllowDynamicProperties]
class SupsysticGalleryPro_Loader
{
  /**
   * @var string
   */
  protected $prefix;

  /**
   * @var string
   */
  protected $path;

  public function load()
  {
    add_action('sgg_plugin_loaded', [$this, 'register']);

    if (is_admin()) {
      add_action('admin_notices', [$this, 'notActiveNotice']);
      add_action('activated_plugin', [$this, 'setLoadOrder']);
      add_action('sgg_before_menu_register', [$this, 'setCapability'], 0);
    }
  }

  public function register(RscSgg_Environment $plugin)
  {
    remove_action('admin_notices', [$this, 'notActiveNotice']);

    $this->config = $plugin->getConfig();
    $config = $this->config;
    $config->add('pro_plugin_version', $this->version);
    $config->add('pro_modules_prefix', $this->prefix);
    $config->add('pro_modules_path', $this->path);
    $config->add('plugin_product_code', 'gallery_by_supsystic_pro');

    if (property_exists($this, 'capability')) {
      $menu = $config->get('plugin_menu');
      $menu['capability'] = $this->capability;
      $config->set('plugin_menu', $menu);
    }

    define('S_YOUR_SECRET_HASH_' . $config->get('plugin_product_code'), 'WjJGc2JHVnllUzFpZVMxemRYQnplWE4wYVdNZ1VGSlBJSEJzZFdkcGJnPT0=');

    $loader = $plugin->getLoader();
    $loader->add($this->prefix, $this->path);

    $resolver = $plugin->getResolver();
    $modules = $resolver->getModulesList();

    if (is_dir($dir = $this->path . '/' . $this->prefix . '/License')) {
      $className = $this->prefix . '_' . basename($dir) . '_Module';

      if (!class_exists($className)) {
        if ($plugin->isPluginPage()) {
          wp_die('Cannot locate license module.');
        }
        return;
      }

      $license = new $className($plugin, $dir, $this->prefix);
      $modules->add('license', $license);
    }

    if ($license->isLegal()) {
      // Get list of the PRO modules.
      $nodes = glob($this->path . '/' . $this->prefix . '/*');

      if ($nodes === false || count($nodes) === 0) {
        return;
      }

      // If we need to replace free module - replace it.
      foreach ($nodes as $node) {
        $node = str_replace('\\', '/', $node);

        if (is_dir($node) && file_exists($module = $node . '/Module.php')) {
          $className = $this->prefix . '_' . basename($node) . '_Module';
          $name = strtolower(basename(dirname($module)));
          $free = $modules[$name];
          $location = $free instanceof RscSgg_Mvc_Module ? $free->getLocation() : $node;
          $modules[$name] = new $className($plugin, $location, $this->prefix);
        }
      }

      // Replace old list of the modules with new.
      $resolver->setModulesList($modules);
      $config->add('is_pro', true);
    }

    $updater = new SupsysticGalleryUpdater();
    $updater->setDirectory(basename(dirname(__FILE__)));
    $updater->setEnvironment($plugin);
    $updater->setFile('index.php');
    $updater->setProductCode($config->get('plugin_product_code'));
    $updater->subscribe();
  }

  public function notActiveNotice()
  {
    $installUrl = add_query_arg(
      [
        's' => 'Photo+Gallery+by+Supsystic',
        'tab' => 'search',
        'type' => 'term',
      ],
      admin_url('plugin-install.php'),
    );
    $installWpUrl = 'https://wordpress.org/plugins/gallery-by-supsystic/';
    include dirname(__FILE__) . '/resources/notice.php';
  }
  /**
   * Returns Prefix.
   * @return string
   */
  public function getPrefix()
  {
    return $this->prefix;
  }

  /**
   * Sets Prefix.
   * @param string $prefix
   */
  public function setPrefix($prefix)
  {
    $this->prefix = $prefix;
  }

  /**
   * Returns Path.
   * @return string
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * Sets Path.
   * @param string $path
   */
  public function setPath($path)
  {
    $this->path = $path;
  }

  public function setProVersion($version)
  {
    $this->version = $version;
  }

  public function setCapability($menu)
  {
    $alowedRoles = [];
    $settings = get_option('sg_settings'); // db prefix here
    if ($settings && isset($settings['access_roles'])) {
      $alowedRoles = $settings['access_roles'];
    }

    if (isset($_GET['debug'])) {
    }
    if (!isset($_COOKIE[LOGGED_IN_COOKIE])) {
      return $menu;
    }

    $cookie = $_COOKIE[LOGGED_IN_COOKIE];
    $cookie_elements = explode('|', $cookie);
    $login = array_shift($cookie_elements);
    $userdata = WP_User::get_data_by('login', $login);

    $current_user = new WP_User();
    $current_user->init($userdata);
    if ($current_user) {
      foreach ($current_user->roles as $role) {
        if (in_array($role, $alowedRoles)) {
          $this->capability = 'read';
          return $menu->setCapability('read');
        }
      }
    }
    return $menu;
  }

  // This method change load order to load pro before free version.
  // This need to apply action hooks on free version like sgg_before_menu_register.
  public function setLoadOrder()
  {
    $pluginBaseName = str_replace('loader.php', 'index.php', plugin_basename(__FILE__));

    if ($plugins = get_option('active_plugins')) {
      if ($key = array_search($pluginBaseName, $plugins)) {
        array_splice($plugins, $key, 1);
        array_unshift($plugins, $pluginBaseName);
        update_option('active_plugins', $plugins);
      }
    }
  }
}
