<?php
/**
 * VillaTheme_Plugin_Check_Update
 */

// no direct access allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'VillaTheme_Plugin_Check_Update' ) ) {

	/**
	 * Class VillaTheme_Plugin_Check_Update
	 * 1.1.0
	 *
	 * Package URL has no secrets. One-time opaque token is returned as `package_token`
	 * and sent via Authorization: Bearer on download. Do not cache package_token.
	 * Site is sent as POST `site` + X-VillaTheme-Site, never in User-Agent.
	 */
	class VillaTheme_Plugin_Check_Update {
		/**
		 * The plugin current version
		 * @var string
		 */
		public $current_version;

		/**
		 * The plugin remote update path
		 * @var string
		 */
		public $update_path;

		/**
		 * Plugin Slug (plugin_directory/plugin_file.php)
		 * @var string
		 */
		public $plugin_slug;

		/**
		 * Plugin name (plugin_file)
		 * @var string
		 */
		public $slug;

		/**
		 * The item name while requesting to update api
		 * @var string
		 */
		public $request_name;

		/**
		 * The item ID in marketplace
		 * @var string
		 */
		public $plugin_id;


		/**
		 * The item name while requesting to update api
		 * @var string
		 */
		public $plugin_file_path;

		/**
		 * The item name while requesting to update api
		 * @var string
		 */
		public $banners;
		/**
		 * To verify on server
		 * @var
		 */
		public $key;
		/**
		 * This is product Id on VillaTheme
		 * @var
		 */
		public $item_id;
		public $setting_url;
		public $messages;
		/**
		 * Guard against get_site_transient recursion on inject_cached_update.
		 *
		 * @var bool
		 */
		protected $injecting = false;

		/**
		 * Initialize a new instance of the WordPress Auto-Update class
		 *
		 * VillaTheme_Plugin_Check_Update constructor.
		 *
		 * @param $current_version
		 * @param $update_path
		 * @param $plugin_slug
		 * @param $slug
		 * @param $item_id
		 * @param string $key
		 * @param string $setting_url
		 */
		public function __construct( $current_version, $update_path, $plugin_slug, $slug, $item_id, $key = '', $setting_url = '' ) {
			// Set the class public variables
			$this->current_version = $current_version;
			$this->update_path     = $update_path;
			$this->plugin_slug     = $plugin_slug;
			$this->slug            = $slug;
			$this->key             = $key;
			$this->item_id         = $item_id;
			$this->setting_url     = $setting_url;

			//Action hide notices
			if (isset($_GET['villatheme_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['villatheme_nonce'])),'villatheme_nonce')) {
				if ( isset( $_GET[ $this->slug . '_dismiss_notices' ] ) && sanitize_text_field( wp_unslash( $_GET[ $this->slug . '_dismiss_notices' ] ) ) ) {
					update_option( $this->slug . '_' . $this->current_version . '_dismiss_notices', current_time( 'timestamp', true ) );
				}
				if ( isset( $_GET[ $this->slug . '_hide' ] ) && sanitize_text_field( wp_unslash( $_GET[ $this->slug . '_hide' ] ) ) ) {
					set_transient( $this->slug . '_hide', 1, 2592000 );
				}
			}
			// Register before admin_init/_maybe_update_plugins so the check is not skipped.
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ), 1 );
			add_filter( 'site_transient_update_plugins', array( $this, 'inject_cached_update' ) );

			// Define the alternative response for information checking
			add_filter( 'plugins_api', array( $this, 'check_info' ), 5, 3 );

			// Mint a fresh one-time package token right before WordPress downloads the zip.
			add_filter( 'upgrader_pre_download', array( $this, 'refresh_package_before_download' ), 10, 4 );

			$this->messages = get_option( $this->slug . '_messages', array() );
			if ( ! get_option( $this->slug . '_' . $this->current_version . '_dismiss_notices' ) ) {
				if ( ! get_transient( $this->slug . '_hide' ) ) {
					if ( ! $this->key ) {
						add_action( 'admin_notices', array( $this, 'update_key_notice' ) );
					} else {
						if ( is_array( $this->messages ) && count( $this->messages ) ) {
							if ( isset( $this->messages['update'] ) && $this->messages['update'] == 2 ) {
								add_action( 'admin_notices', array( $this, 'renew_key_notice' ) );
							}
						}
					}
				}
			}
			add_action( 'villatheme_save_and_check_key_' . $this->slug, array( $this, 'save_and_check_key' ) );
			add_action( $this->slug . '_key', array( $this, 'key_active' ) );
		}

		/**
		 * This class must be initialized before a plugin saves its settings
		 * $auto_update_key may be changed so we should pass it to the hook
		 *
		 * @param $auto_update_key
		 */
		public function save_and_check_key( $auto_update_key ) {
			$this->key = $auto_update_key;

			$this->clear_update_cache();
			$this->request_remote_version();
			$this->messages = get_option( $this->slug . '_messages', array() );
			$this->flush_update_transient();
		}

		/**
		 * Drop cached plugin update data so Save & Check Key always hits the API.
		 */
		public function clear_update_cache() {
			delete_transient( 'villatheme_item_' . $this->item_id );
			delete_site_transient( 'villatheme_item_' . $this->item_id );

			if ( function_exists( 'wp_clean_plugins_cache' ) ) {
				wp_clean_plugins_cache( true );
			} else {
				delete_site_transient( 'update_plugins' );
				wp_cache_delete( 'plugins', 'plugins' );
			}
		}

		/**
		 * Show key is active
		 */
		public function key_active() {
			$this->messages = get_option( $this->slug . '_messages', array() );

			if ( $this->key && isset( $this->messages ) ) {
				if ( count( $this->messages ) ) {
					if ( ! empty( $this->messages['message'] ) ) {
						?>
                        <p class="villatheme-key-active"><?php echo esc_html( $this->messages['message'] ) ?></p>
						<?php
					}
				}
			}
		}

		/**
		 * Show update key wordpress
		 */
		public function renew_key_notice() {
			$plugin = get_plugin_data( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->plugin_slug );
			?>
            <div class="villatheme-dashboard notice notice-warning">
                <div class="villatheme-content">
                    <p><?php echo wp_kses_post( 'Hello! Your <strong>' . $plugin['Name'] . '</strong>  has expired so you no longer receive automatic updates as well as support. Please go to renew now.' ) ?>
                        <a class="button button-primary" target="_blank"
                           href="<?php echo esc_url( 'https://codecanyon.net/downloads' ) ?>"><?php echo esc_html( 'Renew' ) ?></a>
                        <a href="<?php echo esc_url( add_query_arg( array( $this->slug . '_hide' => 1 ,'villatheme_nonce'=> wp_create_nonce( 'villatheme_nonce' )) ) ) ?>"
                           class="button"><?php echo esc_html( 'Skip' ) ?></a>
                    </p>
                    <a target="_self"
                       href="<?php echo esc_url( add_query_arg( array( $this->slug . '_dismiss_notices' => 1 ,'villatheme_nonce'=> wp_create_nonce( 'villatheme_nonce' )) ) ); ?>"
                       class="button notice-dismiss vi-button-dismiss"><?php echo esc_html( 'Dismiss' ) ?></a>
                </div>
            </div>
			<?php
		}

		/**
		 * Show update key wordpress
		 */
		public function update_key_notice() {
			$plugin = get_plugin_data( WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->plugin_slug );
			?>
            <div class="villatheme-dashboard error">
                <div class="villatheme-content">
                    <p><?php echo wp_kses_post( 'Hello! Would you like to receive automatic update? Please activate your copy of  <strong>' . $plugin['Name'] . '</strong>' ) ?></p>
                    <p>
                        <a href="<?php echo esc_url( $this->setting_url ) ?>#update"
                           class="button button-primary"><?php echo esc_html( 'Activate' ) ?></a>
                        <a href="<?php echo esc_url( add_query_arg( array( $this->slug . '_hide' => 1 ,'villatheme_nonce'=> wp_create_nonce( 'villatheme_nonce' )) ) ) ?>"
                           class="button"><?php echo esc_html( 'Hide' ) ?></a>
                    </p>
                    <a target="_self"
                       href="<?php echo esc_url( add_query_arg( array( $this->slug . '_dismiss_notices' => 1,'villatheme_nonce'=> wp_create_nonce( 'villatheme_nonce' ) ) ) ); ?>"
                       class="button notice-dismiss vi-button-dismiss"><?php echo esc_html( 'Dismiss' ) ?></a>
                </div>
            </div>
			<?php
		}

		/**
		 * Add our self-hosted autoupdate plugin to the filter transient
		 *
		 * @param $transient
		 *
		 * @return object $ transient
		 */
		public function check_update( $transient ) {
			if ( ! is_object( $transient ) ) {
				return $transient;
			}

			$meta = $this->get_remote_meta();

			if ( ! is_array( $meta ) ) {
				return $transient;
			}

			return $this->apply_meta_to_transient( $transient, $meta );
		}

		/**
		 * Inject a cached update when WordPress *reads* update_plugins (plugins.php).
		 *
		 * @param mixed $transient Transient value.
		 *
		 * @return mixed
		 */
		public function inject_cached_update( $transient ) {
			if ( $this->injecting || ! is_object( $transient ) ) {
				return $transient;
			}

			$cached = get_transient( 'villatheme_item_' . $this->item_id );

			if ( ! is_array( $cached ) ) {
				return $transient;
			}

			$this->injecting = true;
			$transient       = $this->apply_meta_to_transient( $transient, $cached );
			$this->injecting = false;

			return $transient;
		}

		/**
		 * After Save & Check Key, write this plugin into the update_plugins transient.
		 */
		public function flush_update_transient() {
			$transient = get_site_transient( 'update_plugins' );

			if ( ! is_object( $transient ) ) {
				$transient = new stdClass();
			}

			$transient = $this->check_update( $transient );

			if ( empty( $transient->last_checked ) ) {
				$transient->last_checked = time();
			}

			set_site_transient( 'update_plugins', $transient );
		}

		/**
		 * @param object $transient Update transient.
		 * @param array  $meta      Remote metadata.
		 *
		 * @return object
		 */
		protected function apply_meta_to_transient( $transient, $meta ) {
			$version = isset( $meta['version'] ) ? $meta['version'] : 0;
			$url     = isset( $meta['url'] ) ? $meta['url'] : '';

			if ( ! isset( $transient->response ) || ! is_array( $transient->response ) ) {
				$transient->response = array();
			}

			if ( version_compare( $this->current_version, (string) $version, '<' ) ) {
				$obj              = new stdClass();
				$obj->id          = $this->plugin_slug;
				$obj->slug        = $this->slug;
				$obj->plugin      = $this->plugin_slug;
				$obj->new_version = $version;
				$obj->url         = $url;
				$obj->package     = $this->key ? $this->get_public_package_url() : '';

				$transient->response[ $this->plugin_slug ] = $obj;

				if ( isset( $transient->no_update[ $this->plugin_slug ] ) ) {
					unset( $transient->no_update[ $this->plugin_slug ] );
				}
			} else {
				if ( isset( $transient->response[ $this->plugin_slug ] ) ) {
					unset( $transient->response[ $this->plugin_slug ] );
				}

				$item              = new stdClass();
				$item->id          = $this->plugin_slug;
				$item->slug        = $this->slug;
				$item->plugin      = $this->plugin_slug;
				$item->new_version = $this->current_version;
				$item->url         = $url;
				$item->package     = '';

				$transient->no_update[ $this->plugin_slug ] = $item;
			}

			return $transient;
		}

		/**
		 * Mint a fresh Bearer token and download the zip immediately before install.
		 *
		 * @param bool|string|WP_Error $reply      Short-circuit value.
		 * @param string               $package    Package URL from the update transient.
		 * @param WP_Upgrader          $upgrader   Upgrader instance.
		 * @param array                $hook_extra Extra arguments passed by WordPress 5.5+.
		 *
		 * @return bool|string|WP_Error
		 */
		public function refresh_package_before_download( $reply, $package, $upgrader, $hook_extra = array() ) {
			if ( false !== $reply ) {
				return $reply;
			}

			if ( ! $this->key || ! is_string( $package ) || '' === $package ) {
				return $reply;
			}

			if ( ! $this->is_package_for_this_plugin( $package, $hook_extra ) ) {
				return $reply;
			}

			$remote = $this->request_remote_version( true );

			if ( ! is_array( $remote ) || empty( $remote['package'] ) || empty( $remote['package_token'] ) ) {
				return new WP_Error(
					'villatheme_package_refresh_failed',
					'Unable to refresh the VillaTheme download package. Please try again.'
				);
			}

			if ( $upgrader && isset( $upgrader->skin ) && is_object( $upgrader->skin ) && method_exists( $upgrader->skin, 'feedback' ) ) {
				$upgrader->skin->feedback( 'downloading_package', $remote['package'] );
			}

			return $this->download_package_with_token( $remote['package'], $remote['package_token'] );
		}

		/**
		 * Download zip with Authorization: Bearer (token never goes in the URL).
		 *
		 * @param string $url   Package endpoint URL.
		 * @param string $token One-time download token.
		 *
		 * @return string|WP_Error Local tempfile path or error.
		 */
		private function download_package_with_token( $url, $token ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';

			$url   = (string) $url;
			$token = (string) $token;

			if ( '' === $url || '' === $token ) {
				return new WP_Error( 'villatheme_package_empty', 'Download package data is incomplete.' );
			}

			$tmp = wp_tempnam( $url );

			if ( ! $tmp ) {
				return new WP_Error( 'http_no_file', __( 'Could not create temporary file.' ) );
			}

			$headers = array_merge(
				$this->get_update_site_headers(),
				array(
					'Authorization'               => 'Bearer ' . $token,
					'X-VillaTheme-Download-Token' => $token,
				)
			);

			$response = wp_safe_remote_get(
				$url,
				array(
					'timeout'    => 300,
					'stream'     => true,
					'filename'   => $tmp,
					'user-agent' => $this->get_update_user_agent(),
					'headers'    => $headers,
				)
			);

			if ( is_wp_error( $response ) ) {
				wp_delete_file( $tmp );

				return $response;
			}

			$code = (int) wp_remote_retrieve_response_code( $response );

			if ( 200 !== $code ) {
				wp_delete_file( $tmp );

				return new WP_Error(
					'villatheme_package_http_error',
					sprintf( 'Download failed with HTTP %d.', $code )
				);
			}

			$content_md5 = wp_remote_retrieve_header( $response, 'content-md5' );

			if ( $content_md5 ) {
				$md5_check = verify_file_md5( $tmp, $content_md5 );

				if ( is_wp_error( $md5_check ) ) {
					wp_delete_file( $tmp );

					return $md5_check;
				}
			}

			$handle = fopen( $tmp, 'rb' );

			if ( $handle ) {
				$magic = fread( $handle, 4 );
				fclose( $handle );

				if ( ! is_string( $magic ) || 0 !== strpos( $magic, 'PK' ) ) {
					wp_delete_file( $tmp );

					return new WP_Error(
						'villatheme_package_invalid',
						'Downloaded file is not a valid plugin package.'
					);
				}
			}

			return $tmp;
		}

		/**
		 * Whether the package URL belongs to the current plugin update.
		 *
		 * @param string $package    Package URL.
		 * @param array  $hook_extra Extra arguments from upgrader_pre_download.
		 *
		 * @return bool
		 */
		private function is_package_for_this_plugin( $package, $hook_extra = array() ) {
			if ( ! empty( $hook_extra['plugin'] ) && (string) $hook_extra['plugin'] === (string) $this->plugin_slug ) {
				return true;
			}

			$updates = get_site_transient( 'update_plugins' );

			if ( is_object( $updates ) && ! empty( $updates->response[ $this->plugin_slug ]->package )
				&& (string) $updates->response[ $this->plugin_slug ]->package === (string) $package ) {
				return true;
			}

			$parsed = wp_parse_url( $package );

			if ( empty( $parsed['path'] ) || false === strpos( (string) $parsed['path'], '/download' ) ) {
				return false;
			}

			$query = array();

			if ( ! empty( $parsed['query'] ) ) {
				parse_str( (string) $parsed['query'], $query );
			}

			return isset( $query['id'] ) && (string) absint( $query['id'] ) === (string) absint( $this->item_id );
		}

		/**
		 * Public package endpoint used in the update transient (no secrets).
		 *
		 * @return string
		 */
		private function get_public_package_url() {
			return add_query_arg(
				'id',
				absint( $this->item_id ),
				untrailingslashit( (string) $this->update_path ) . '/download'
			);
		}

		/**
		 * Cached metadata only (version, changelog, message) — never includes package secrets.
		 *
		 * @return array|false
		 */
		public function get_remote_meta() {
			$cached = get_transient( 'villatheme_item_' . $this->item_id );

			if ( is_array( $cached ) ) {
				unset( $cached['package'], $cached['package_token'] );

				return $cached;
			}

			$remote = $this->request_remote_version();

			if ( ! is_array( $remote ) ) {
				return false;
			}

			unset( $remote['package'], $remote['package_token'] );

			return $remote;
		}

		/**
		 * Return the remote version data (includes a fresh package URL when available).
		 *
		 * @return array|false
		 */
		public function get_remote_version() {
			return $this->request_remote_version();
		}

		/**
		 * Customer site URL for v4 bind. Sent in body and X-VillaTheme-Site, not User-Agent.
		 *
		 * @return string
		 */
		private function get_update_site() {
			return (string) get_site_url();
		}

		/**
		 * Generic User-Agent without the site URL (access logs and proxies leak UA).
		 *
		 * @return string
		 */
		private function get_update_user_agent() {
			global $wp_version;

			return 'WordPress/' . $wp_version;
		}

		/**
		 * Headers that identify this site to downloads/v4 and /download.
		 *
		 * @return array<string, string>
		 */
		private function get_update_site_headers() {
			return array(
				'X-VillaTheme-Site' => $this->get_update_site(),
			);
		}

		/**
		 * Call the update API and cache metadata without package secrets.
		 *
		 * @param bool $with_token When true, request a one-time package_token for download.
		 *
		 * @return array|false
		 */
		private function request_remote_version( $with_token = false ) {
			if ( ! $this->item_id ) {
				return false;
			}

			if ( $with_token && ! $this->key ) {
				return false;
			}

			$site = $this->get_update_site();
			$body = array(
				'key'  => $this->key,
				'id'   => $this->item_id,
				'site' => $site,
			);

			if ( $with_token ) {
				$body['token'] = 1;
			}

			$timeout = 15;
			if ( $with_token || ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
				$timeout = 30;
			}

			$this->log_update_debug(
				'downloads/v4 request',
				array(
					'url'     => $this->update_path,
					'id'      => $this->item_id,
					'site'    => $site,
					'token'   => $with_token ? 1 : 0,
					'timeout' => $timeout,
					'key'     => $this->mask_secret( (string) $this->key ),
				)
			);

			$request = wp_remote_post(
				$this->update_path,
				array(
					'user-agent' => $this->get_update_user_agent(),
					'timeout'    => $timeout,
					'headers'    => $this->get_update_site_headers(),
					'body'       => $body,
				)
			);

			if ( is_wp_error( $request ) ) {
				$error = $request->get_error_message();
				$this->log_update_debug( 'downloads/v4 wp_error', $error );
				$this->store_request_message( $error, 0 );

				return false;
			}

			$http = (int) wp_remote_retrieve_response_code( $request );
			$raw  = (string) wp_remote_retrieve_body( $request );

			$this->log_update_debug(
				'downloads/v4 response',
				array(
					'http' => $http,
					'body' => $this->sanitize_response_for_log( $raw ),
				)
			);

			if ( 429 === $http ) {
				$this->store_request_message( 'Too many requests. Please wait 10 seconds and try again.', 0 );

				return false;
			}

			if ( 200 !== $http ) {
				$this->store_request_message( sprintf( 'Update API HTTP %d.', $http ), 0 );

				return false;
			}

			$result = json_decode( $raw, true );

			if ( ! is_array( $result ) ) {
				$this->store_request_message( 'Update API returned invalid JSON.', 0 );

				return false;
			}

			if ( ! empty( $result['message'] ) ) {
				$message = json_decode( $result['message'], true );

				if ( is_array( $message ) ) {
					update_option( $this->slug . '_messages', $message );
					$this->messages = $message;
				}
			}

			$api_code = isset( $result['code'] ) ? (int) $result['code'] : 0;

			// HTTP 200 with JSON code 400 is still an API error — do not cache it for 24h.
			if ( $api_code >= 400 ) {
				return $result;
			}

			$cache = $result;
			unset( $cache['package'], $cache['package_token'] );
			set_transient( 'villatheme_item_' . $this->item_id, $cache, DAY_IN_SECONDS );

			return $result;
		}

		/**
		 * Persist a request-level message for the Auto Update Key UI.
		 *
		 * @param string $message Message text.
		 * @param int    $status  1 = ok, 0 = error.
		 */
		private function store_request_message( $message, $status ) {
			$data = array(
				'message' => (string) $message,
				'status'  => (int) $status,
				'update'  => 0,
			);

			update_option( $this->slug . '_messages', $data );
			$this->messages = $data;
		}

		/**
		 * Mask secrets in debug output.
		 *
		 * @param string $value Raw secret.
		 *
		 * @return string
		 */
		private function mask_secret( $value ) {
			$len = strlen( $value );

			if ( $len <= 8 ) {
				return $len ? str_repeat( '*', $len ) : '';
			}

			return substr( $value, 0, 4 ) . str_repeat( '*', $len - 8 ) . substr( $value, - 4 );
		}

		/**
		 * Decode API JSON for logs and strip package_token.
		 *
		 * @param string $raw Response body.
		 *
		 * @return array|string
		 */
		private function sanitize_response_for_log( $raw ) {
			$decoded = json_decode( $raw, true );

			if ( ! is_array( $decoded ) ) {
				return $raw;
			}

			if ( ! empty( $decoded['package_token'] ) ) {
				$decoded['package_token'] = $this->mask_secret( (string) $decoded['package_token'] );
			}

			if ( ! empty( $decoded['package'] ) ) {
				$decoded['package'] = (string) $decoded['package'];
			}

			return $decoded;
		}

		/**
		 * Write request/response debug when WP_DEBUG is on.
		 *
		 * @param string       $label Log label.
		 * @param mixed        $data  Payload.
		 */
		private function log_update_debug( $label, $data ) {
			if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
				return;
			}

			$payload = is_string( $data ) ? $data : wp_json_encode( $data );

			if ( function_exists( 'wc_get_logger' ) ) {
				wc_get_logger()->debug(
					$label . ' ' . $payload,
					array( 'source' => 'woocommerce-multi-currency-update' )
				);

				return;
			}

			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( '[WMC Update] ' . $label . ' ' . $payload );
		}


		/**
		 * Add our self-hosted description to the filter
		 *
		 * @param boolean $false
		 * @param array $action
		 * @param object $arg
		 *
		 * @return bool|object
		 */
		public function check_info( $false, $action, $arg ) {
			if ( isset( $arg->slug ) && $arg->slug === $this->slug ) {
				$changelog = $this->get_changelog();

				return new WP_Error( 'villatheme_premium_plugin', nl2br( $changelog ), 'changelog' );
			}

			return $false;
		}

		private function get_changelog() {
			$item = $this->get_remote_meta();

			return ( is_array( $item ) && isset( $item['changelog'] ) ) ? $item['changelog'] : '';
		}
	}
}