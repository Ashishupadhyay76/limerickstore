<?php

class GridGalleryPro_License_Model_Helper extends GridGallery_Core_BaseModel
{
  private $siteUrl;

  private $_apiUrl = '';

  private $options;

  private $errors;

  /**
   * Constructor.
   */
  public function __construct(RscSgg_Environment $environment = null)
  {
    parent::__construct();

    $this->environment = $environment;
    $this->siteUrl = get_bloginfo('wpurl') . '/';
    $this->_initApiUrl();
  }

  public function getPrefix()
  {
    if (null === $this->environment) {
      throw new RuntimeException('Environment not yet injected.');
    }

    return $this->environment->getConfig()->get('hooks_prefix');
  }

  public function check($options)
  {
    $time = time();
    $this->options = $options;
    $lastCheck = (int) get_option('_last_important_check_' . $this->getPrefix());
    if (!$lastCheck || $time - $lastCheck >= 3 * 30 * 24 * 3600 /** 0*/ /*remove last!!!*/) {
      $resData = $this->_req(
        'check',
        array_merge(
          [
            'url' => $this->siteUrl,
            'plugin_code' => 'gallery_by_supsystic_pro',
          ],
          $this->getCredentials(),
        ),
      );
      if ($resData) {
        $this->_updateLicenseData($resData['data']['save_data']);
        if (!empty($resData['data']['save_data']['days_left']) && $resData['data']['save_data']['days_left'] > 0) {
          $this->_setActive();
        }
      } else {
        $resData = $this->_req(
          'check',
          array_merge(
            [
              'url' => $this->siteUrl,
              'plugin_code' => 'gallery_by_supsystic_trial',
            ],
            $this->getCredentials(),
          ),
        );
        if ($resData) {
          $this->_updateLicenseData($resData['data']['save_data']);
          if (!empty($resData['data']['save_data']['days_left']) && $resData['data']['save_data']['days_left'] > 0) {
            $this->_setActive();
          }
        } else {
          $this->_setExpired($options);
        }
      }
      update_option('_last_important_check_' . $this->getPrefix(), $time);
    } else {
      $daysLeft = $options->get('license_days_left');
      if ($daysLeft) {
        $lastServerCheck = $options->get('license_last_check');
        $day = 24 * 3600;
        $daysPassed = floor(($time - $lastServerCheck) / $day);
        if ($daysPassed > 0) {
          $daysLeft -= $daysPassed;
          $options->save('license_days_left', $daysLeft);
          $options->save('license_last_check', time());
          if ($daysLeft < 0) {
            $resData = $this->_req(
              'check',
              array_merge(
                [
                  'url' => $this->siteUrl,
                  'plugin_code' => 'gallery_by_supsystic_pro',
                ],
                $this->getCredentials(),
              ),
            );
            if ($resData) {
              $this->_updateLicenseData($resData['data']['save_data']);
              if (!empty($resData['data']['save_data']['days_left']) && $resData['data']['save_data']['days_left'] > 0) {
                $this->_setActive();
              }
            } else {
              $this->_setExpired($options);
            }
          }
        }
      }
    }
    return true;
  }
  public function activate($options, $d = [])
  {
    if (!$this->options) {
      $this->options = $options;
    }
    $d['mail'] = isset($d['mail']) ? trim($d['mail']) : '';
    $d['key'] = isset($d['key']) ? trim($d['key']) : '';
    if (!empty($d['mail'])) {
      if (!empty($d['key'])) {
        $this->setCredentials($d['mail'], $d['key']);
        $resData = $this->_req(
          'activate',
          array_merge(
            [
              'url' => $this->siteUrl,
              'plugin_code' => 'gallery_by_supsystic_pro',
            ],
            $this->getCredentials(),
          ),
        );
        if (!empty($resData)) {
          $options->save('license_use_trial', false);
          $this->_updateLicenseData($resData['data']['save_data']);
          $this->_setActive();
          return true;
        } else {
          $resData = $this->_req(
            'activate',
            array_merge(
              [
                'url' => $this->siteUrl,
                'plugin_code' => 'gallery_by_supsystic_trial',
              ],
              $this->getCredentials(),
            ),
          );
          if (!empty($resData)) {
            $options->save('license_use_trial', true);
            $this->_updateLicenseData($resData['data']['save_data']);
            $this->_setActive();
            return true;
          }
        }
      } else {
        $this->pushError(__('Please enter your License Key', PPS_LANG_CODE), 'key');
      }
    } else {
      $this->pushError(__('Please enter your Email address', PPS_LANG_CODE), 'email');
    }
    return false;
  }
  public function useLicenseGatewayServer($data)
  {
    $response = wp_remote_post('http://vps-a76bb1d6.vps.ovh.net/wp-admin/admin-ajax.php?mod=licensegateway&action=getSupsysticAnswer&pl=lgs', [
      'body' => $data,
      'redirection' => 5,
    ]);
    if (!empty($response) && !empty($response['body'])) {
      $response['body'] = base64_decode($response['body']);
      $response['body'] = unserialize($response['body']);
      return $response;
    }
    return false;
  }
  private function _updateLicenseData($saveData)
  {
    $this->options->save('license_save_name', $saveData['license_save_name']);
    $this->options->save('license_save_val', $saveData['license_save_val']);
    $this->options->save('license_days_left', $saveData['days_left']);
    $this->options->save('license_last_check', time());
    //dbPps::query('UPDATE @__modules SET active = 1 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
  }
  private function _setExpired($options)
  {
    //replace slider code here
    update_option('_last_expire_' . $this->getPrefix(), 1);
    $this->_removeActive();
    if ($this->enbOptimization()) {
      //            dbPps::query('UPDATE @__modules SET active = 0 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
      $options->save('license_days_left', -1);
    }
  }
  public function isExpired()
  {
    //replace slider code here
    return (int) get_option('_last_expire_' . $this->getPrefix());
  }
  public function isActive($options)
  {
    $option = get_option($options->get('license_save_name'));
    return $option && $option == $options->get('license_save_val');
  }
  public function _setActive()
  {
    update_option('_site_transient_update_plugins', ''); // Trigger plugins updates check
    update_option($this->options->get('license_save_name'), $this->options->get('license_save_val'));
    $this->options->save('_love_for_all_', 1);
    //replace slider code here
    delete_option('_last_expire_' . $this->getPrefix());
  }
  public function _removeActive()
  {
    $name = $this->options->get('license_save_name');
    $this->options->save('_love_for_all_', 0);
    if (!empty($name)) {
      delete_option($name);
    }
  }
  public function setCredentials($email, $key)
  {
    $this->setEmail($email);
    $this->setLicenseKey($key);
  }
  public function setEmail($email)
  {
    $this->options->save('license_email', base64_encode($email));
  }
  public function setLicenseKey($key)
  {
    $this->options->save('license_key', base64_encode($key));
  }
  public function getEmail($options)
  {
    if ($this->options) {
      return base64_decode($this->options->get('license_email'));
    } elseif ($options) {
      return base64_decode($options->get('license_email'));
    } else {
      return false;
    }
  }
  public function getLicenseKey($options)
  {
    if ($this->options) {
      return base64_decode($this->options->get('license_key'));
    } elseif ($options) {
      return base64_decode($options->get('license_key'));
    } else {
      return false;
    }
  }
  public function getCredentials($options = null)
  {
    return [
      'email' => $this->getEmail($options),
      'key' => $this->getLicenseKey($options),
    ];
  }
  private function _req($action, $data = [], $gateway = false)
  {
    $data = array_merge($data, [
      'mod' => 'manager',
      'pl' => 'lms',
      'action' => $action,
    ]);
    if ($gateway) {
      $response = $this->useLicenseGatewayServer($data);
    } else {
      $response = wp_remote_post($this->_apiUrl, [
        'body' => $data,
      ]);
    }
    if (is_wp_error($response)) {
      // Try it with native CURL - maybe this will work
      $curlNativeTry = $this->_reqWithCurl($data);
      if ($curlNativeTry) {
        $response = ['body' => $curlNativeTry];
      }
    }
    if (!is_wp_error($response)) {
      if (isset($response['body']) && !empty($response['body']) && ($resArr = $this->jsonDecode($response['body']))) {
        if (!$resArr['error']) {
          return $resArr;
        } else {
          //Error
          $this->pushError($resArr['errors']);
        }
      } else {
        $this->pushError(isset($response['response']) && $response['response']['message'] && !empty($response['response']['message']) ? $response['response']['message'] : $this->trans('There was a problem with sending request to our authentication server. Please try latter.'));
      }
    } else {
      $this->pushError($response);
    }
    return false;
  }
  private function _reqWithCurl($data, $withSsl = true)
  {
    if (!function_exists('curl_init')) {
      return false;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->_apiUrl);
    if ($withSsl) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cacert.pem');
    } else {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);

    curl_close($ch);
    return $result ? $result : false;
  }
  private function _initApiUrl()
  {
    if (empty($this->_apiUrl)) {
      $this->_apiUrl = 'http://updates.supsystic.com/';
    }
  }
  public function enbOptimization()
  {
    return false;
  }
  public function checkPreDeactivateNotify($options)
  {
    $daysLeft = (int) $options->get('license_days_left');
    if ($daysLeft > 0 && $daysLeft <= 15) {
      // Notify before 3 days
      add_action('admin_notices', [$this, 'showPreDeactivationNotify']);
    }
  }
  public function sendRenewStarted()
  {
    $show = true;
    $acRenew = get_option('sgg_ac_renew', false);
    if (!empty($acRenew)) {
      $currentDate = date('Y-m-d h:i:s');
      if ($currentDate > $acRenew) {
        $show = true;
      } else {
        $show = false;
      }
    }
    if ($show) {
      $apiUrl = 'https://supsystic.com/wp-admin/admin-ajax.php';
      $reqUrl = $apiUrl . '?action=ac_renew_started';
      $email = $this->getEmail($this->options);
      $data = [
        'body' => [
          'key' => 'kJ#f3(FjkF9fasd124t5t589u9d4389r3r3R#2asdas3(#R03r#(r#t-4t5t589u9d4389r3r3R#$%lfdj',
          // 'user_name' => $mail['username'],
          'user_email' => $email,
          'site_url' => get_bloginfo('wpurl'),
          'site_name' => get_bloginfo('name'),
          'plugin_code' => 'sgg',
          'is_pro' => true,
        ],
      ];
      $response = wp_remote_post($reqUrl, $data);
      update_option('sgg_ac_renew', date('Y-m-d h:i:s', time() + 2592000));
    }
  }
  public function showPreDeactivationNotify()
  {
    $daysLeft = (int) $this->options->get('license_days_left');
    $msg = '';
    if ($this->_getPluginCode() == 'gallery_by_supsystic_pro') {
      if ($daysLeft == 0) {
        $this->sendRenewStarted();
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. License for plugin <b>will expire today</b>. It means that your <b>PRO version will not work</b>. To continue use PRO functionality - with all features, options, updates and premium support, just <b>extend PRO version license - follow <a href="%s" target="_blank">this link</a></b>',
          $this->environment->getMenu()->getMenuTitle(),
          $this->environment->getModule('License')->getExtendUrl(),
        );
      } elseif ($daysLeft == 1) {
        $this->sendRenewStarted();
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. License for plugin <b>will expire tomorrow</b>. It means that your <b>PRO version will not work</b>. To continue use PRO functionality - with all features, options, updates and premium support, just <b>extend PRO version license - follow <a href="%s" target="_blank">this link</a></b>',
          $this->environment->getMenu()->getMenuTitle(),
          $this->environment->getModule('License')->getExtendUrl(),
        );
      } else {
        $this->sendRenewStarted();
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. License for plugin <b>will expire in %s days</b>. It means that your <b>PRO version will not work</b>. To continue use PRO functionality - with all features, options, updates and premium support, just <b>extend PRO version license - follow <a href="%s" target="_blank">this link</a></b>',
          $this->environment->getMenu()->getMenuTitle(),
          $daysLeft,
          $this->environment->getModule('License')->getExtendUrl(),
        );
      }
    } else {
      if ($daysLeft == 0) {
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. Trial <b>will expire today</b>. It means that your <b>PRO version will not work</b>. To continue use PRO functionality - with all features, options, updates and premium support, just <b>purchase PRO version - follow <a href="https://supsystic.com/plugins/photo-gallery/" target="_blank">this link</a></b>',
          $this->environment->getMenu()->getMenuTitle(),
        );
      } elseif ($daysLeft == 1) {
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. Trial <b>will expire tomorrow</b>. It means that your <b>PRO version will not work</b>. To continue use PRO functionality - with all features, options, updates and premium support, just <b>purchase PRO version - follow <a href="https://supsystic.com/plugins/photo-gallery/" target="_blank">this link</a></b>',
          $this->environment->getMenu()->getMenuTitle(),
        );
      } else {
        $msg = sprintf(
          '<span class="dashicons dashicons-warning" style="color:red"></span> <b>%s</b>. Trial <b>will expire in %s days</b>. It means that your <b>PRO version will not work</b>. To continue use PRO functionality - with all features, options, updates and premium support, just <b>purchase PRO version - follow <a href="https://supsystic.com/plugins/photo-gallery/" target="_blank">this link</a></b>',
          $this->environment->getMenu()->getMenuTitle(),
          $daysLeft,
        );
      }
    }
    echo '<div class="error notice is-dismissible supsystic-pro-notice stb-notification"><p>' . $msg . '</p></div>';
  }
  public function updateDb()
  {
    if (!$this->enbOptimization()) {
      return;
    }
    $time = time();
    //replace slider code here
    $lastCheck = (int) get_option('_last_wp_check_imp_' . $this->getPrefix());
    if (!$lastCheck || $time - $lastCheck >= 3 * 30 * 24 * 3600 /** 0/*remove last!!!*/) {
      if ($this->isActive($this->options)) {
        //dbPps::query('UPDATE @__modules SET active = 1 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
      } else {
        //dbPps::query('UPDATE @__modules SET active = 0 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
      }
      //remove slider code here
      update_option('_last_wp_check_imp_' . $this->getPrefix(), $time);
    }
  }
  public function _getPluginCode()
  {
    $options = get_option('sg__opts_data');
    $licenseIsTrial = isset($options['license_use_trial']) ? $options['license_use_trial']['value'] : false;
    if (!empty($licenseIsTrial)) {
      return 'gallery_by_supsystic_trial';
    } else {
      return 'gallery_by_supsystic_pro';
    }
  }
  public function getExtendUrl()
  {
    $license = $this->getCredentials();
    $license['key'] = md5($license['key']);
    $license = urlencode(base64_encode(implode('|', $license)));
    return $this->_apiUrl . '?mod=manager&pl=lms&action=extend&plugin_code=' . $this->_getPluginCode() . '&lic=' . $license;
  }
  private function jsonDecode($str)
  {
    if (is_array($str)) {
      return $str;
    }
    if (is_object($str)) {
      return (array) $str;
    }
    return empty($str) ? [] : json_decode($str, true);
  }

  private function pushError($error)
  {
    if (is_wp_error($error) && method_exists($error, 'get_error_message')) {
      $error = $error->get_error_message();
    }

    if (is_object($error) && !method_exists($error, '__toString')) {
      $error = (array) $error;
    }

    if (is_array($error)) {
      $error = array_pop($error);
    }

    $this->errors[] = $error;
  }

  public function getErrors()
  {
    $errors = $this->errors;
    $this->errors = [];

    return $errors;
  }

  public function getFirstError()
  {
    return reset($this->errors);
  }

  public function hasErrors()
  {
    return count($this->errors) > 0;
  }

  public function haveErrors()
  {
    return $this->hasErrors();
  }

  private function trans($str)
  {
    if ($this->environment instanceof RscSgg_Environment) {
      return $this->environment->translate($str);
    }

    return __($str);
  }
}