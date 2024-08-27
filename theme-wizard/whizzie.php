<?php
/**
 * Wizard
 *
 * @package rj-mojo
 * @author RP Leocoders Pro
 * @since 1.0.0
 */

class Whizzie {

	protected $version = '1.1.0';

	/** @var string Current theme name, used as namespace in actions. */
	protected $theme_name = '';
	protected $theme_title = '';

	/** @var string Wizard page slug and title. */
	protected $page_slug = '';
	protected $page_title = '';

	/** @var array Wizard steps set by user. */
	protected $config_steps = array();

	/**
	 * Relative plugin url for this plugin folder
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_url = '';

	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

	// Where to find the widget.wie file
	protected $widget_file_url = '';

	/**
	 * Constructor
	 *
	 * @param $config	Our config parameters
	 */
	public function __construct( $config ) {
		$this->set_vars( $config );
		$this->init();
	}

	/**
	 * Set some settings
	 * @since 1.0.0
	 * @param $config	Our config parameters
	 */
	public function set_vars( $config ) {

		require_once trailingslashit( WHIZZIE_DIR ) . 'tgmpa/class-tgm-plugin-activation.php';
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgmpa/required-plugins.php';
		require_once trailingslashit( WHIZZIE_DIR ) . 'classes/class-whizzie-widget-importer.php';

		if( isset( $config['page_slug'] ) ) {
			$this->page_slug = esc_attr( $config['page_slug'] );
		}
		if( isset( $config['page_title'] ) ) {
			$this->page_title = esc_attr( $config['page_title'] );
		}
		if( isset( $config['steps'] ) ) {
			$this->config_steps = $config['steps'];
		}

		$this->plugin_path = trailingslashit( dirname( __FILE__ ) );
		$relative_url = str_replace( get_template_directory(), '', $this->plugin_path );
		$this->plugin_url = trailingslashit( get_template_directory_uri() . $relative_url );
		$current_theme = wp_get_theme();
		$this->theme_title = $current_theme->get( 'Name' );
		$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
		$this->page_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_page_slug', $this->theme_name . '-setup' );
		$this->parent_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_parent_slug', '' );

		$this->widget_file_url = trailingslashit( WHIZZIE_DIR ) . 'content/widgets.wie';

	}

	/**
	 * Hooks and filters
	 * @since 1.0.0
	 */
	public function init() {

		add_action( 'after_switch_theme', array( $this, 'redirect_to_wizard' ) );
		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			add_action( 'init', array( $this, 'get_tgmpa_instance' ), 30 );
			add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_init', array( $this, 'get_plugins' ), 30 );
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_setup_plugins', array( $this, 'setup_plugins' ) );
		add_action( 'wp_ajax_setup_widgets', array( $this, 'setup_widgets' ) );

	}

	public function redirect_to_wizard() {
		global $pagenow;
		if( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) && current_user_can( 'manage_options' ) ) {
			wp_redirect( admin_url( 'themes.php?page=' . esc_attr( $this->page_slug ) ) );
		}
	}

	public function enqueue_scripts(){
		wp_enqueue_style( 'whizzie-style',get_template_directory_uri() . '/theme-wizard/assets/css/whizzie-admin-style.css', array(), time() );
		wp_register_script( 'rj-mojo', get_template_directory_uri() . '/theme-wizard/assets/js/whizzie.js', array( 'jquery' ), time() );
		wp_localize_script(
			'rj-mojo',
			'whizzie_params',
			array(
				'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
				'wpnonce' 		=> wp_create_nonce( 'whizzie_nonce' ),
				'verify_text'	=> esc_html( 'verifying', 'rj-mojo' )
			)
		);
		wp_enqueue_script( 'rj-mojo' );
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function tgmpa_load( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function get_tgmpa_instance() {
		$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}

	/**
	 * Make a modal screen for the wizard
	 */
	public function menu_page() {
		add_theme_page( esc_html__( $this->page_title ), esc_html__( $this->page_title ), 'manage_options', $this->page_slug, array( $this, 'wizard_page' ) );
	}

	/**
	 * Make an interface for the wizard
	 */
	public function wizard_page() {
		tgmpa_load_bulk_installer();
		// install plugins with TGM.
		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( 'Failed to find TGM' );
		}
		$url = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'whizzie-setup' );

		// copied from TGM
		$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
		$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.
		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true; // Stop the normal page form from displaying, credential request form will be shown.
		}
		// Now we have some credentials, setup WP_Filesystem.
		if ( ! WP_Filesystem( $creds ) ) {
			// Our credentials were no good, ask the user for them again.
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}
		/* If we arrive here, we have the filesystem */ ?>
		<div class="wrap">
			<?php printf( '<h1>%s</h1>', esc_html( $this->page_title ) );
			echo '<div class="card whizzie-wrap">';
				// The wizard is a list with only one item visible at a time
				$steps = $this->get_steps();
				echo '<ul class="whizzie-menu">';
				foreach( $steps as $step ) {
					$class = 'step step-' . esc_attr( $step['id'] );
					echo '<li data-step="' . esc_attr( $step['id'] ) . '" class="' . esc_attr( $class ) . '">';
						printf( '<h2>%s</h2>', esc_html( $step['title'] ) );
						// $content is split into summary and detail
						$content = call_user_func( array( $this, $step['view'] ) );
						if( isset( $content['summary'] ) ) {
							printf(
								'<div class="summary">%s</div>',
								wp_kses_post( $content['summary'] )
							);
						}
						if( isset( $content['detail'] ) ) {
							// Add a link to see more detail
							printf( '<p><a href="#" class="more-info">%s</a></p>', __( 'More Info', 'rj-mojo' ) );
							printf(
								'<div class="detail">%s</div>',
								$content['detail'] // Need to escape this
							);
						}
						// The next button
						if( isset( $step['button_text'] ) && $step['button_text'] ) {
							printf(
								'<div class="button-wrap"><a href="#" class="button button-primary do-it" data-callback="%s" data-step="%s">%s</a></div>',
								esc_attr( $step['callback'] ),
								esc_attr( $step['id'] ),
								esc_html( $step['button_text'] )
							);
						}
						// The skip button
						if( isset( $step['can_skip'] ) && $step['can_skip'] ) {
							printf(
								'<div class="button-wrap" style="margin-left: 0.5em;"><a href="#" class="button button-secondary do-it" data-callback="%s" data-step="%s">%s</a></div>',
								'do_next_step',
								esc_attr( $step['id'] ),
								__( 'Skip', 'rj-mojo' )
							);
						}

					echo '</li>';
				}
				echo '</ul>';
				echo '<ul class="whizzie-nav">';
					foreach( $steps as $step ) {
						if( isset( $step['icon'] ) && $step['icon'] ) {
							echo '<li class="nav-step-' . esc_attr( $step['id'] ) . '"><span class="dashicons dashicons-' . esc_attr( $step['icon'] ) . '"></span></li>';
						}
					}
				echo '</ul>';
				?>
				<div class="step-loading"><span class="spinner"></span></div>
			</div><!-- .whizzie-wrap -->

		</div><!-- .wrap -->
	<?php }

	/**
	 * Set options for the steps
	 * Incorporate any options set by the theme dev
	 * Return the array for the steps
	 * @return Array
	 */
	public function get_steps() {
		$dev_steps = $this->config_steps;
		$steps = array(
			'intro' => array(
				'id'			=> 'intro',
				'title'			=> __( 'Welcome to ', 'rj-mojo' ) . $this->theme_title,
				'icon'			=> 'dashboard',
				'view'			=> 'get_step_intro', // Callback for content
				'callback'		=> 'do_next_step', // Callback for JS
				'button_text'	=> __( 'Start Now', 'rj-mojo' ),
				'can_skip'		=> false // Show a skip button?
			),
			'plugins' => array(
				'id'			=> 'plugins',
				'title'			=> __( 'Plugins', 'rj-mojo' ),
				'icon'			=> 'admin-plugins',
				'view'			=> 'get_step_plugins',
				'callback'		=> 'install_plugins',
				'button_text'	=> __( 'Install Plugins', 'rj-mojo' ),
				'can_skip'		=> true
			),
			'widgets' => array(
				'id'			=> 'widgets',
				'title'			=> __( 'Widgets', 'rj-mojo' ),
				'icon'			=> 'welcome-widgets-menus',
				'view'			=> 'get_step_widgets',
				'callback'		=> 'install_widgets',
				'button_text'	=> __( 'Install Widgets', 'rj-mojo' ),
				'can_skip'		=> true
			),
			'done' => array(
				'id'			=> 'done',
				'title'			=> __( 'All Done', 'rj-mojo' ),
				'icon'			=> 'yes',
				'view'			=> 'get_step_done',
				'callback'		=> ''
			)
		);

		// Iterate through each step and replace with dev config values
		if( $dev_steps ) {
			// Configurable elements - these are the only ones the dev can update from config.php
			$can_config = array( 'title', 'icon', 'button_text', 'can_skip' );
			foreach( $dev_steps as $dev_step ) {
				// We can only proceed if an ID exists and matches one of our IDs
				if( isset( $dev_step['id'] ) ) {
					$id = $dev_step['id'];
					if( isset( $steps[$id] ) ) {
						foreach( $can_config as $element ) {
							if( isset( $dev_step[$element] ) ) {
								$steps[$id][$element] = $dev_step[$element];
							}
						}
					}
				}
			}
		}
		return $steps;
	}

	/**
	 * Print the content for the intro step
	 */
	public function get_step_intro() {
		$content = array();
		// The summary element will be the content visible to the user
		$content['summary'] = sprintf( '<p>%s</p>', 'Thank you for choosing to use this theme. To get you up and running as quickly as possible, you can use this wizard to configure the theme. It should only take a couple of minutes to go through all the steps, and you can choose to skip steps if you wish.', 'rj-mojo' );
		$content['summary'] .= sprintf( '<p>%s</p>', 'Click the button below to get started. If you decide not to go through the wizard now, you can return to this page any time you like.', 'rj-mojo' );
		return $content;
	}

	/**
	 * Get the content for the plugins step
	 * @return $content Array
	 */
	public function get_step_plugins() {
		$plugins = $this->get_plugins();
		$content = array();
		// The summary element will be the content visible to the user
		$content['summary'] = sprintf(
			'<p>%s</p>',
			__( 'This theme works best with some additional plugins. Click the button to install. You can still install or deactivate plugins later from the dashboard.', 'rj-mojo' )
		);
		$content = apply_filters( 'whizzie_filter_summary_content', $content );

		// The detail element is initially hidden from the user
		$content['detail'] = '<ul class="whizzie-do-plugins">';
		// Add each plugin into a list
		foreach( $plugins['all'] as $slug=>$plugin ) {
			$content['detail'] .= '<li data-slug="' . esc_attr( $slug ) . '">' . esc_html( $plugin['name'] ) . '<span>';
			$keys = array();
			if ( isset( $plugins['install'][ $slug ] ) ) {
			    $keys[] = 'Installation';
			}
			if ( isset( $plugins['update'][ $slug ] ) ) {
			    $keys[] = 'Update';
			}
			if ( isset( $plugins['activate'][ $slug ] ) ) {
			    $keys[] = 'Activation';
			}
			$content['detail'] .= implode( ' and ', $keys ) . ' required';
			$content['detail'] .= '</span></li>';
		}
		$content['detail'] .= '</ul>';

		return $content;
	}

	/**
	 * Print the content for the widgets step
	 * @since 1.1.0
	 */
	public function get_step_widgets() {
		$content = array();
		// Check if the widgets file is included
		$file = $this->has_widget_file();
		if( $file ) {
			$content['summary'] = sprintf(
				'<p>%s</p>',
				__( 'This theme adds content and functionality via widgets. Click the button to install these widgets - you can update them or deactivate at any time from the Customizer.', 'rj-mojo' )
			);
		} else {
			$content['summary'] = sprintf(
				'<p>%s</p>',
				__( 'No widgets.wie found.', 'rj-mojo' )
			);
		}

		$content = apply_filters( 'whizzie_filter_widgets_content', $content );
		return $content;
	}

	/**
	 * Print the content for the final step
	 */
	public function get_step_done() {
		$content = array();
		// The summary element will be the content visible to the user
		$content['summary'] = sprintf( '<p>%s</p>', 'Finished', 'rj-mojo' );
		return $content;
	}

	/**
	 * Get the plugins registered with TGMPA
	 */
	public function get_plugins() {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins = array(
			'all' 		=> array(),
			'install'	=> array(),
			'update'	=> array(),
			'activate'	=> array()
		);
		foreach( $instance->plugins as $slug=>$plugin ) {
			if( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
				// Plugin is installed and up to date
				continue;
			} else {
				$plugins['all'][$slug] = $plugin;
				if( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][$slug] = $plugin;
				} else {
					if( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][$slug] = $plugin;
					}
					if( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][$slug] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}

	/**
	 * Get the widgets.wie file from the /content folder
	 * @return Mixed	Either the file or false
	 * @since 1.1.0
	 */
	public function has_widget_file() {
		if( file_exists( $this->widget_file_url ) ) {
			return true;
		}
		return false;
	}

	public function setup_plugins() {
		if ( ! check_ajax_referer( 'whizzie_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found', 'rj-mojo' ) ) );
		}
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->get_plugins();

		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating Plugin', 'rj-mojo' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating Plugin', 'rj-mojo' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing Plugin', 'rj-mojo' ),
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success', 'rj-mojo' ) ) );
		}
		exit;
	}


	public function theme_create_primary_nav_menu(){

		 // ------- Create Nav Menu --------
		$menuname = 'Primary Menu';
		$bpmenulocation = 'primary';
		$menu_exists = wp_get_nav_menu_object( $menuname );

		if( !$menu_exists->name){

				$menu_id = wp_create_nav_menu($menuname);
					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __('Home','rj-mojo'),
						'menu-item-classes' => 'home',
						'menu-item-url' => home_url( '/' ),
						'menu-item-status' => 'publish'
					));

					$sub_menu  =   wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __('Products','rj-mojo'),
						'menu-item-classes' => 'products',
						'menu-item-url' => get_permalink(get_page_by_title('Products')),
						'menu-item-status' => 'publish'
					));

					$sub_menu_items = get_categories( array(
						'orderby' => 'name',
						'hide_empty'      => true,
					) );

					foreach ($sub_menu_items as $sub_menu_item) {
						wp_update_nav_menu_item($menu_id, 0, array(
							'menu-item-title' =>  __($sub_menu_item->name,'rj-mojo'),
							'menu-item-attr-title' =>  __($sub_menu_item->name,'rj-mojo'),
							// 'menu-item-classes' => $mid['class'],
							'menu-item-url' => __(get_category_link( $category->term_id ),'rj-mojo'),
							'menu-item-status' => 'publish',
							'menu-item-parent-id' => $sub_menu
						));
					}


					// $sub_menu_items = array(
					// 			'Property Market Place' 	=> array('class' => 'Property', 'title' => 'property-market-place.png'),
					// 			'Second Home'  						=> array('class' => 'Second', 'title' => 'second-home.png'),
					// 			'Booking System'  				=> array('class' => 'Booking', 'title' => 'booking-system.png'),
					// 			'Lead Managment'  				=> array('class' => 'Lead', 'title' => 'lead-managment.png'),
					// 			'DIM'  										=> array('class' => 'Developer', 'title' => 'developer-inventory-managment.png'),
					// 			'Broker/Agent' 						=> array('class' => 'Broker/Agent', 'title' => 'broker-agent.png'),
					// 			'CIDQ'  									=> array('class' => 'Construction', 'title' => 'construction.png'),
					// 			'Rental'  								=> array('class' => 'Rental', 'title' => 'rental.png'),
					// 			'CWSA'  									=> array('class' => 'Co-working', 'title' => 'co-working-space-application.png')
					// 		);

					// foreach ($sub_menu_items as $mname => $mid) {
					// 	wp_update_nav_menu_item($menu_id, 0, array(
					// 		'menu-item-title' =>  __($mname,'rj-mojo'),
					// 		'menu-item-attr-title' =>  rj_bookmark_theme_path . '/assets/images/leocoders_products/'.$mid['title'],
					// 		'menu-item-classes' => $mid['class'],
					// 		'menu-item-url' => get_permalink(get_page_by_title('Products')) . '/#tab-'.$mid['class'],
					// 		'menu-item-status' => 'publish',
					// 		'menu-item-parent-id' => $sub_menu
					// 	));
					// }

					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __('Social Sites','rj-mojo'),
						'menu-item-classes' => 'social-Sites',
						'menu-item-url' => get_permalink(get_page_by_title('Social Sites')),
						'menu-item-status' => 'publish'
					));

					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __('Contact','rj-mojo'),
						'menu-item-classes' => 'contact',
						'menu-item-url' => get_permalink(get_page_by_title('Contact Us')),
						'menu-item-status' => 'publish'
					));

				if( !has_nav_menu( $bpmenulocation ) ){
						$locations = get_theme_mod('nav_menu_locations');
						$locations[$bpmenulocation] = $menu_id;
						set_theme_mod( 'nav_menu_locations', $locations );
				}
		}
}
// ------- Create Products Menu --------
	public function theme_create_prodcut_nav_menu(){
		$menuname = $lblg_themename . 'Products Menu';
		$bpmenulocation = 'product';
		$menu_exists = wp_get_nav_menu_object( $menuname );

		if( !$menu_exists){
				$menu_id = wp_create_nav_menu($menuname);

				$sub_menu_items = array(
					'Property Market Place' 	=> array('class' => 'Property', 'title' => 'property-market-place.png'),
					'Second Home'  						=> array('class' => 'Second', 'title' => 'second-home.png'),
					'Booking System'  				=> array('class' => 'Booking', 'title' => 'booking-system.png'),
					'Lead Managment'  				=> array('class' => 'Lead', 'title' => 'lead-managment.png'),
					'DIM'  										=> array('class' => 'Developer', 'title' => 'developer-inventory-managment.png'),
					'Broker/Agent' 						=> array('class' => 'Broker/Agent', 'title' => 'broker-agent.png'),
					'CIDQ'  									=> array('class' => 'Construction', 'title' => 'construction.png'),
					'Rental'  								=> array('class' => 'Rental', 'title' => 'rental.png'),
					'CWSA'  									=> array('class' => 'Co-working', 'title' => 'co-working-space-application.png')
				);

				foreach ($sub_menu_items as $mname => $mid) {
					wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  __($mname,'rj-mojo'),
						'menu-item-attr-title' =>  rj_bookmark_theme_path . '/assets/images/leocoders_products/'.$mid['title'],
						'menu-item-classes' => $mid['class'],
						'menu-item-url' => get_permalink(get_page_by_title('Products')) . '/#tab-'.$mid['class'],
						'menu-item-status' => 'publish',
					));
				}


				if( !has_nav_menu( $bpmenulocation ) ){
						$locations = get_theme_mod('nav_menu_locations');
						$locations[$bpmenulocation] = $menu_id;
						set_theme_mod( 'nav_menu_locations', $locations );
				}
			}
		}


	/**
	 * Imports the widgets.wie file
	 * @since 1.1.0
	 */
	public function setup_widgets() {


		ini_set( 'upload_max_filesize', '30M' );
		ini_set( 'max_execution_time', '300' );

		// update_option('permalink_structure', '/%postname%/');

		// set the logo
		$logo_image = array( 'logo', 'favicon' );
		for ($i=0; $i < count($logo_image); $i++) {

			$image_url = rj_bookmark_theme_path .'/assets/images/'.$logo_image[$i].'.png';
			$image_name       = $logo_image[$i].'.png';

			$upload_dir       = wp_upload_dir(); // Set upload folder
			$image_data       = file_get_contents($image_url); // Get image data
			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
			$filename         = basename( $unique_file_name );

			if( wp_mkdir_p( $upload_dir['path'] ) ) {
				 $file = $upload_dir['path'] . '/' . $filename;
			} else {
				 $file = $upload_dir['basedir'] . '/' . $filename;
			}

			file_put_contents( $file, $image_data );

			$wp_filetype = wp_check_filetype( $filename, null );

			$attachment = array(
				 'post_mime_type' => $wp_filetype['type'],
				 'post_title'     => sanitize_file_name( $filename ),
				 'post_type'     =>  '',
				 'post_status'    => 'inherit'
			);

			$attach_id = wp_insert_attachment( $attachment, $file, $rp_leocoders_post_id );
			$attachment_url = wp_get_attachment_url( $attach_id );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			if ($i == 0) {
				update_option('site_logo', $attach_id);
			}
			if ($i == 1) {
				update_option('site_icon', $attach_id);
			}
		}

		$page_names = array(
										'Register' 					=> 'rj-register',
										'Login' 					=> 'rj-login',
										'Bookmarks' 				=> 'rj-bookmark',
										'My Bookmark' 				=> 'rj-my-bookmarks',
										'Submit Bookmark' 			=> 'rj-submit-bookmark',
										'My Account'				=> 'rj-myaccount',
										'Social Sites' 				=> 'rj-social-sties',
										'Products' 					=> 'rj-submit-bookmark',
										'Locations' 				=> 'rj-locations',
										'Contact Us' 				=> '',
										'Privacy Policy' 			=> '',
										'Content Policy' 			=> '',
										'Terms & Condition' 		=> '',
										'Cookie Policy' 			=> '',
									);

			foreach ($page_names as $page_name => $template) {
				$page = array(
					'post_title' => $page_name,
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_author' => 1,
					'post_slug' => strtolower(str_replace(' ', '_', $page_name))
				);
				if ( ! post_exists($page_name) ){
					$page_id = wp_insert_post($page);
					if($template != ''){
						add_post_meta( $page_id, '_wp_page_template', 'page-template/'.$template.'.php' );
					}
				}
			}

			// topbar

			$topbar_icons = array(
				'Facebook' => array(
					'www.facebook.com',
					'<svg viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h98.2V334.2H109.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H255V480H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64z"/></svg>',
					'#3b5998'
				),

				'Twitter' => array(
					'www.twitter.com',
				  	'<svg viewBox="0 0 448 512"><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"/></svg>',
				  	'#00acee'
				),

				'Instagram' => array(
					'www.instagram.com',
					'<svg viewBox="0 0 448 512"><path d="M194.4 211.7a53.3 53.3 0 1 0 59.3 88.7 53.3 53.3 0 1 0 -59.3-88.7zm142.3-68.4c-5.2-5.2-11.5-9.3-18.4-12c-18.1-7.1-57.6-6.8-83.1-6.5c-4.1 0-7.9 .1-11.2 .1c-3.3 0-7.2 0-11.4-.1c-25.5-.3-64.8-.7-82.9 6.5c-6.9 2.7-13.1 6.8-18.4 12s-9.3 11.5-12 18.4c-7.1 18.1-6.7 57.7-6.5 83.2c0 4.1 .1 7.9 .1 11.1s0 7-.1 11.1c-.2 25.5-.6 65.1 6.5 83.2c2.7 6.9 6.8 13.1 12 18.4s11.5 9.3 18.4 12c18.1 7.1 57.6 6.8 83.1 6.5c4.1 0 7.9-.1 11.2-.1c3.3 0 7.2 0 11.4 .1c25.5 .3 64.8 .7 82.9-6.5c6.9-2.7 13.1-6.8 18.4-12s9.3-11.5 12-18.4c7.2-18 6.8-57.4 6.5-83c0-4.2-.1-8.1-.1-11.4s0-7.1 .1-11.4c.3-25.5 .7-64.9-6.5-83l0 0c-2.7-6.9-6.8-13.1-12-18.4zm-67.1 44.5A82 82 0 1 1 178.4 324.2a82 82 0 1 1 91.1-136.4zm29.2-1.3c-3.1-2.1-5.6-5.1-7.1-8.6s-1.8-7.3-1.1-11.1s2.6-7.1 5.2-9.8s6.1-4.5 9.8-5.2s7.6-.4 11.1 1.1s6.5 3.9 8.6 7s3.2 6.8 3.2 10.6c0 2.5-.5 5-1.4 7.3s-2.4 4.4-4.1 6.2s-3.9 3.2-6.2 4.2s-4.8 1.5-7.3 1.5l0 0c-3.8 0-7.5-1.1-10.6-3.2zM448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM357 389c-18.7 18.7-41.4 24.6-67 25.9c-26.4 1.5-105.6 1.5-132 0c-25.6-1.3-48.3-7.2-67-25.9s-24.6-41.4-25.8-67c-1.5-26.4-1.5-105.6 0-132c1.3-25.6 7.1-48.3 25.8-67s41.5-24.6 67-25.8c26.4-1.5 105.6-1.5 132 0c25.6 1.3 48.3 7.1 67 25.8s24.6 41.4 25.8 67c1.5 26.3 1.5 105.4 0 131.9c-1.3 25.6-7.1 48.3-25.8 67z"/></svg>',
					'#833ab4'
				),

				'Pintrest' => array(
					'www.pintrest.com',
					'<svg viewBox="0 0 448 512"><path d="M384 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h72.6l-2.2-.8c-5.4-48.1-3.1-57.5 15.7-134.7c3.9-16 8.5-35 13.9-57.9c0 0-7.3-14.8-7.3-36.5c0-70.7 75.5-78 75.5-25c0 13.5-5.4 31.1-11.2 49.8c-3.3 10.6-6.6 21.5-9.1 32c-5.7 24.5 12.3 44.4 36.4 44.4c43.7 0 77.2-46 77.2-112.4c0-58.8-42.3-99.9-102.6-99.9C153 139 112 191.4 112 245.6c0 21.1 8.2 43.7 18.3 56c2 2.4 2.3 4.5 1.7 7c-1.1 4.7-3.1 12.9-4.7 19.2c-1 4-1.8 7.3-2.1 8.6c-1.1 4.5-3.5 5.5-8.2 3.3c-30.6-14.3-49.8-59.1-49.8-95.1C67.2 167.1 123.4 96 229.4 96c85.2 0 151.4 60.7 151.4 141.8c0 84.6-53.3 152.7-127.4 152.7c-24.9 0-48.3-12.9-56.3-28.2c0 0-12.3 46.9-15.3 58.4c-5 19.3-17.6 42.9-27.4 59.3H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64z"/></svg>',
					'#bd081c'
				),

				'Youtube' => array(
					'www.youtube.com',
					'<svg viewBox="0 0 448 512"><path d="M282 256.2l-95.2-54.1V310.3L282 256.2zM384 32H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64zm14.4 136.1c7.6 28.6 7.6 88.2 7.6 88.2s0 59.6-7.6 88.1c-4.2 15.8-16.5 27.7-32.2 31.9C337.9 384 224 384 224 384s-113.9 0-142.2-7.6c-15.7-4.2-28-16.1-32.2-31.9C42 315.9 42 256.3 42 256.3s0-59.7 7.6-88.2c4.2-15.8 16.5-28.2 32.2-32.4C110.1 128 224 128 224 128s113.9 0 142.2 7.7c15.7 4.2 28 16.6 32.2 32.4z"/></svg>',
					'#ff0000'
				),

				'Siteurl' => array(
					'www.siteurl.com',
					'<svg viewBox="0 0 640 512"><path d="M54.2 202.9C123.2 136.7 216.8 96 320 96s196.8 40.7 265.8 106.9c12.8 12.2 33 11.8 45.2-.9s11.8-33-.9-45.2C549.7 79.5 440.4 32 320 32S90.3 79.5 9.8 156.7C-2.9 169-3.3 189.2 8.9 202s32.5 13.2 45.2 .9zM320 256c56.8 0 108.6 21.1 148.2 56c13.3 11.7 33.5 10.4 45.2-2.8s10.4-33.5-2.8-45.2C459.8 219.2 393 192 320 192s-139.8 27.2-190.5 72c-13.3 11.7-14.5 31.9-2.8 45.2s31.9 14.5 45.2 2.8c39.5-34.9 91.3-56 148.2-56zm64 160a64 64 0 1 0 -128 0 64 64 0 1 0 128 0z"/></svg>',
					'#ff8c00'
				),

				'Linkedin' => array(
					'www.linkedin.com',
					'<svg viewBox="0 0 448 512"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>',
					'#0a66c2'
				),
			);

			foreach ($topbar_icons as $title => $urlicons){
				set_theme_mod('rj_mojo_topbar_'.strtolower($title).'_icon', $urlicons[1]);
				set_theme_mod('rj_mojo_topbar_'.strtolower($title).'_url', $urlicons[0]);
				set_theme_mod('rj_mojo_topbar_'.strtolower($title).'_text', $title);
				set_theme_mod('rj_mojo_topbar_'.strtolower($title).'_color', $urlicons[0]);
			}

			set_theme_mod('rj_mojo_topbar_submit_bookmark', 'Submit Bookmark');
			set_theme_mod('rj_mojo_topbar_submit_bookmark_url', get_permalink(get_page_by_title('Submit Bookmark')));
			set_theme_mod('rj_mojo_topbar_my_bookmark', 'My Bookmark');
			set_theme_mod('rj_mojo_topbar_my_bookmark_url', get_permalink(get_page_by_title('My Bookmark')));
			set_theme_mod('rj_mojo_topbar_my_account', 'My Account');
			set_theme_mod('rj_mojo_topbar_my_account_url', get_permalink(get_page_by_title('My Account')));
			set_theme_mod('rj_mojo_topbar_login_signup', 'Login/Resigter');
			set_theme_mod('rj_mojo_topbar_login_signup_url', get_permalink(get_page_by_title('Add Bookmark')));

			set_theme_mod('rj_bookmark_home_main_heading', 'Internet Content Powered by Business Product Marketers');


			$countries = array(
					"Antigua and Barbuda", "Equatorial Guinea", "Vietnam", "Maldives", "Central African Republic", "Ethiopia", "Fiji", "Armenia", "Iraq", "Switzerland", "South Korea", "Germany", "Italy", "Honduras", "Mexico", "Oman", "Togo", "South Sudan", "Mauritius", "New Zealand", "Liberia", "Algeria", "France", "Bolivia", "Slovenia", "Guatemala", "Cambodia", "Jamaica", "Ecuador", "Laos", "Slovakia", "Argentina", "Lithuania", "Turkmenistan", "Lebanon", "Palau", "Monaco", "North Macedonia", "Guinea", "Haiti", "Philippines", "Nauru", "Bangladesh", "Iceland", "Colombia", "Liechtenstein", "Netherlands", "Bahamas", "Timor-Leste", "Guinea-Bissau", "Hungary", "Luxembourg", "Ghana", "Mali", "Namibia", "Angola", "Portugal", "Latvia", "Senegal", "Panama", "Djibouti", "Kazakhstan", "Georgia", "Côte d'Ivoire", "Seychelles", "Bulgaria", "Mongolia", "Barbados", "Israel", "Cameroon", "Indonesia", "Cabo Verde", "Greece", "Papua New Guinea", "Uzbekistan", "Afghanistan", "United Kingdom", "Samoa", "Morocco", "Marshall Islands", "Congo", "China", "Kenya", "Dominican Republic", "Finland", "Botswana", "Australia", "Jordan", "Tonga", "Bosnia and Herzegovina", "Zimbabwe", "United Arab Emirates", "Hong Kong", "Eswatini", "Ukraine", "Sudan", "Czechia", "Russia", "Burkina Faso", "Saint Lucia", "Solomon Islands", "Sierra Leone", "Eritrea", "Micronesia", "Suriname", "Benin", "Brunei", "Croatia", "Kiribati", "Paraguay", "Palestine State", "Serbia", "Belarus", "India", "Japan", "North Korea", "Nepal", "Egypt", "Pakistan", "Romania", "Cuba", "Estonia", "Saudi Arabia", "Malawi", "Belgium", "Guyana", "Chile", "Sri Lanka", "Worldwide", "Grenada", "El Salvador", "Mozambique", "Brazil", "Libya", "Trinidad and Tobago", "Singapore", "San Marino", "Myanmar", "Nicaragua", "Uruguay", "Moldova", "Denmark", "Yemen", "Somalia", "Dominica", "Uganda", "Bahrain", "Qatar", "Peru", "Kyrgyzstan", "Costa Rica", "Andorra", "Madagascar", "South Africa", "Belize", "Austria", "Montenegro", "Sao Tome and Principe", "Azerbaijan", "Vanuatu", "Tajikistan", "Turkey", "United States of America", "Mauritania", "Nigeria", "Tanzania", "Gabon", "Albania", "Sweden", "Saint Vincent", "Ireland", "Lesotho", "Thailand", "Iran", "Poland", "Bhutan", "Comoros", "Gambia", "Holy See", "Spain", "Malaysia", "Zambia", "Niger", "Burundi", "Syria", "Rwanda", "Chad", "Saint Kitts and Nevis", "Kuwait", "Cyprus", "Norway", "Tunisia", "Venezuela", "Tuvalu", "Malta", "Canada"
			);


			for ($i=1; $i<=count($countries); $i++) {
				$term = term_exists( $countries[$i], 'rj_location' );

				// if (! $term) {
				// 	$country_location	=	wp_insert_term(
				// 		$countries[$i], // the term
				// 		'rj_location', // the taxonomy
				// 			array(
				// 				// 'description'=> $cat_details['cat_desc'],
				// 				'slug' => strtolower(str_replace(' ', '_', $countries[$i]))
				// 				)
				// 	);
				// }


				if (! $term) {
						$country_location	=	wp_insert_term(
							$countries[$i], // the term
							'rj_location', // the taxonomy
								array(
									// 'description'=> $cat_details['cat_desc'],
									'slug' => strtolower(str_replace(' ', '_', $countries[$i]))
									)
						);

						/*
							// add category image START
							$image_url = get_template_directory_uri().'/assets/images/countries/'.strtolower(str_replace(' ', '-', $countries[$i])).'.svg';

							$image_name= strtolower(str_replace(' ', '_', $countries[$i])) . '.svg';
							$upload_dir       = wp_upload_dir();
							$image_data       = file_get_contents($image_url);
							$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
							$filename= basename( $unique_file_name );
							if( wp_mkdir_p( $upload_dir['path'] ) ) {
								$file = $upload_dir['path'] . '/' . $filename;
							} else {
								$file = $upload_dir['basedir'] . '/' . $filename;
							}
							file_put_contents( $file, $image_data );
							$wp_filetype = wp_check_filetype( $filename, null );
							$attachment = array(
								'post_mime_type' => $wp_filetype['type'],
								'post_title'     => sanitize_file_name( $filename ),
								'post_content'   => '',
								'post_type'     => '',
								'post_status'    => 'inherit'
							);
							$attach_id = wp_insert_attachment( $attachment, $file );
							$attachment_url = wp_get_attachment_url( $attach_id );
							$term_meta_id = add_term_meta( $country_location['term_id'], 'rj_location-image-id', $attach_id, true ); */

					}else {
						$country_location = get_term_by('name', $countries[$i], 'post');
					}
			}



			exit;

		// for($i=0; $i<count($page_names); $i++){
				//Set the static front page
				// if( $i == 0 ){
				// 	$home = get_page_by_title( 'Home' );
				// 	update_option( 'page_on_front', $home->ID );
				// 	update_option( 'show_on_front', 'page' );
				// }
		// }




		set_theme_mod('rp_leocdoers_pro_slider_bgimage', rj_bookmark_theme_path . '/assets/images/slider.png');
		set_theme_mod('rp_leocdoers_pro_slider_heading', 'Revitalize your product strategy with accelerated results.');
		set_theme_mod('rp_leocdoers_pro_slider_paragraph', 'Boost your business growth with high-performing software solutions. Drive your success with accelerated software development strategies and tailored engineering solutions.');
		set_theme_mod('rp_leocdoers_pro_slider_btn_one_url', '#');
		set_theme_mod('rp_leocdoers_pro_slider_btn_one_text', 'Get Started');
		set_theme_mod('rp_leocdoers_pro_slider_btn_two_url', '#');
		set_theme_mod('rp_leocdoers_pro_slider_btn_two_text', 'How it Works');

		set_theme_mod('rp_leocdoers_pro_about_us_main_heading', 'Build or extend your Business development team.');
		set_theme_mod('rp_leocdoers_pro_about_us_image', rj_bookmark_theme_path . '/assets/images/aboutus.png');
		set_theme_mod('rp_leocdoers_pro_about_us_sub_heading', 'Strategies that get you on the path to success');
		set_theme_mod('rp_leocdoers_pro_about_us_para', 'Achieve scalability for your software operations with a dedicated custom engineering team. Seamlessly meet your company\'s operational demands by leveraging the expertise of a high-performing nearshore team proficient in the specific technologies your business requires.');
		set_theme_mod('rp_leocdoers_pro_about_us_btn_text', 'See How it Works');
		set_theme_mod('rp_leocdoers_pro_about_us_btn_url', '#');
		set_theme_mod('rp_leocdoers_pro_about_us_btn_icon', 'fas fa-arrow-right');

		set_theme_mod('rp_leocdoers_pro_services_main_heading', 'Bespoke software development solutions');
		set_theme_mod('rp_leocdoers_pro_services_main_para', 'We support companies that need agile teams of the best engineers. Build or extend your software development team with ease.');
		set_theme_mod('rp_leocdoers_pro_services_btn_text', 'View All');
		set_theme_mod('rp_leocdoers_pro_services_btn_url', get_permalink(get_page_by_title('Services')));

		set_theme_mod('rp_leocdoers_pro_project_bgimage', rj_bookmark_theme_path . '/assets/images/leocoders_projects/project-bg.png');
		set_theme_mod('rp_leocdoers_pro_project_main_heading', 'Your company needs great software solutions. Let’s build high-performing products');

		set_theme_mod('rp_leocdoers_pro_testimonial_bgimage', rj_bookmark_theme_path . '/assets/images/rp_testimonial/testimonial-bg.webp');
		set_theme_mod('rp_leocdoers_pro_testimonial_main_heading', '3+ years in the game and we\'re just getting Better_');
		set_theme_mod('rp_leocdoers_pro_testimonial_main_para', 'Before we start, we would like to better understand your needs. We’ll review your application and schedule a free estimation call.');
		set_theme_mod('rp_leocdoers_pro_testimonial_btn_text', 'Get Started');
		set_theme_mod('rp_leocdoers_pro_testimonial_btn_url', '#');

		set_theme_mod('rp_leocdoers_pro_partners_main_heading', 'Clients and Frequent Partners');
		set_theme_mod('rp_leocdoers_pro_partners_main_para', 'We foster strong alliances with industry-leading partners to deliver comprehensive solutions to our clients');
		set_theme_mod('rp_leocdoers_pro_partners_btn_text', 'View More');
		set_theme_mod('rp_leocdoers_pro_partners_btn_url', get_permalink(get_page_by_title('Partners')));


		// Contact us page
		set_theme_mod('rp_leocdoers_pro_contact_page_secone_img', rj_bookmark_theme_path . '/assets/images/contact-page/contact-exp-min.webp');
		set_theme_mod('rp_leocdoers_pro_contactus_page_secone_heading', 'Growth strategies to be effective & competitive');
		set_theme_mod('rp_leocdoers_pro_contactus_page_secone_para', 'Everything we do and dream up has a solid design impact. We create human-centered enterprise software that has the polished, snappy feel of the best consumer apps.');
		set_theme_mod('rp_leocdoers_pro_contactus_page_secone_btn_text', 'Get Started');
		set_theme_mod('rp_leocdoers_pro_contactus_page_secone_btn_url', '#');

		$contact_forms = array(
							'Contact Us' => array(
													'content' => '[text* your-name placeholder "Your Name"][email* your-email placeholder "Your Email"][textarea your-message placeholder "Your Message"][submit "Submit"]',
													'set_id'  => 'rp_leocdoers_pro_contactus_form_shortcode'
												),

							'Apply Now' => array(
													'content' => '[text* your-name placeholder "Your Name"][email* your-email placeholder "Your Email"][tel phone-no placeholder "Phone No"][text position-applied-for placeholder "Position Applied for"][textarea reason placeholder "Reason to Join Leocoders"][file* resume filetypes:.pdf|.doc|.docx][submit "Submit"]',
													'set_id'  => 'rp_leocoders_pro_career_apply_form_shortcode'
												),

							'Case Study' => array(
													'content' => '[text* your-full-name placeholder "Your Full Name"][email* your-email placeholder "Your Email"][tel phone-no placeholder "Phone No"][textarea your-message placeholder "Your Message"][submit "Send"]',
													'set_id'  => 'rp_leocoders_pro_case_study_form_shortcode'
												),

							'Products Form' => array(
													'content' => '[text* your-full-name placeholder "Your Full Name"][email* business-email placeholder "Your Email"][tel phone-no placeholder "Phone No"][select leoestate-products "Select Products" "Property Market Place" "Second Home" "Booking System" "Inventory Management" "Broker/Agent Application" "Construction And Internal Designer Quotation\'s" "Rental" "Co-working Space Application" "Lead Managment"][textarea your-message placeholder "Your Message"][submit "Send"]',
												),
						);

		foreach ($contact_forms as $contact_title => $contact_content){

				 $cf7content = $contact_content['content'] .'
				 [submit "Submit"]
				 "[your-subject]"
				 <[your-email]>
				 From: [your-name] <[your-email]>
				 Subject: [your-subject]

				 Message Body:
				 [your-message]

				 --
				 This e-mail was sent from a contact form on [_site_title] ([_site_url])
				 [_site_admin_email]
				 Reply-To: [your-email]

				 0
				 0

				 [_site_title] "[your-subject]"
				 [_site_title] <wordpress@leocoders.com>
				 Message Body:
				 [your-message]

				 --
				 This e-mail was sent from a contact form on [_site_title] ([_site_url])
				 [your-email]
				 Reply-To: [_site_admin_email]

				 0
				 0
				 Thank you for your message. It has been sent.
				 There was an error trying to send your message. Please try again later.
				 One or more fields have an error. Please check and try again.
				 There was an error trying to send your message. Please try again later.
				 You must accept the terms and conditions before sending your message.
				 The field is required.
				 The field is too long.
				 The field is too short.
				 There was an unknown error uploading the file.
				 You are not allowed to upload files of this type.
				 The file is too big.
				 There was an error uploading the file.';

				 $cf7_post = array(
				 'post_title'    => wp_strip_all_tags( $contact_title ),
				 'post_content'  => $contact_content['content'],
				 'post_status'   => 'publish',
				 'post_type'     => 'wpcf7_contact_form',
				 );
				 // Insert the post into the database
					$cf7post_id = wp_insert_post( $cf7_post );

				 add_post_meta($cf7post_id, "_form", $contact_content['content']);

				 $cf7mail_data  = array('subject' => '[_site_title] "[your-subject]"',
						 'sender' => '[_site_title] <leocoders@gmail.com>',
						 'body' => 'From: [your-name] <[your-email]>
				 Subject: [your-subject]

				 Message Body:
				 [your-message]

				 --
				 This e-mail was sent from a contact form on [_site_title] ([_site_url])',
						 'recipient' => '[_site_admin_email]',
						 'additional_headers' => 'Reply-To: [your-email]',
						 'attachments' => '',
						 'use_html' => 0,
						 'exclude_blank' => 0 );

			 add_post_meta($cf7post_id, "_mail", $cf7mail_data);
			 // Gets term object from Tree in the database.
			 $cf7shortcode = '[contact-form-7 id="'.$cf7post_id.'" title="'.$contact_title.'"]';

			 if ($contact_content['set_id'] != ''){
				set_theme_mod( $contact_content['set_id'] , $cf7shortcode);
			 }

	 	}


		set_theme_mod('rp_leocdoers_pro_aboutus_page_secone_heading', 'Growth strategies to be effective & competitive');
		set_theme_mod('rp_leocdoers_pro_aboutus_page_secone_para', 'Everything we do and dream up has a solid design impact. We create human-centered enterprise software that has the polished, snappy feel of the best consumer apps. ');
		set_theme_mod('rp_leocdoers_pro_aboutus_page_secone_btn_text', 'Get Started');
		set_theme_mod('rp_leocdoers_pro_aboutus_page_secone_btn_url', '#');
		set_theme_mod('rp_leocdoers_pro_aboutus_page_secone_img', rj_bookmark_theme_path .'/assets/images/about-us-page/right-img.jpeg');

		set_theme_mod('rp_leocdoers_pro_hire_bgimage', rj_bookmark_theme_path .'/assets/images/about-us-page/openings.jpg');
		set_theme_mod('rp_leocdoers_pro_hire_heading', 'Current Openings');
		set_theme_mod('rp_leocdoers_pro_hire_para', '#Leocoders would love to hire your awesomeness! Join us to be a part of something exciting!');
		set_theme_mod('rp_leocdoers_pro_hire_btn_text', 'Become A Leocoders');
		set_theme_mod('rp_leocdoers_pro_hire_btn_url', get_permalink(get_page_by_title('Careers')));

		set_theme_mod('rp_leocdoers_pro_leocoders_life_bgimage', rj_bookmark_theme_path .'/assets/images/about-us-page/leocoders-life.png');
		set_theme_mod('rp_leocdoers_pro_leocoders_life_heading', 'Life @ Leocoders');
		set_theme_mod('rp_leocdoers_pro_leocoders_life_para', 'Here at Leocoders, we encourage a culture of openness that sparks creativity & innovation to solve business challenges collectively.');


		set_theme_mod('rp_leocdoers_pro_contactus_page_sectwo_main_heading', 'Have some questions?');
		set_theme_mod('rp_leocdoers_pro_contactus_page_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.5277483665323!2d79.11772577597887!3d21.131385884244445!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd4c1e22b43abfb%3A0x4ef0f31bdcb4af9b!2sLeo%20Coders%20Pvt.%20Ltd!5e0!3m2!1sen!2sin!4v1683178375280!5m2!1sen!2sin');

		set_theme_mod('rp_leocdoers_pro_contactus_address', 'Plot No 582 New Nandanvan Canal Road Nagpur, Maharashtra 440009');
		set_theme_mod('rp_leocdoers_pro_contactus_address_icon', 'fas fa-map-marker-alt');
		set_theme_mod('rp_leocdoers_pro_contactus_contact_no_one', '+91 8788308817');
		set_theme_mod('rp_leocdoers_pro_contactus_contact_no_two', '+91 9209092567');
		set_theme_mod('rp_leocdoers_pro_contactus_contact_icon', 'fas fa-phone');
		set_theme_mod('rp_leocdoers_pro_contactus_gmail_one', ' hr@leocoders.com');
		set_theme_mod('rp_leocdoers_pro_contactus_gmail_two', 'rahul@leocoders.com');
		set_theme_mod('rp_leocdoers_pro_contactus_gmail_icon', 'fas fa-envelope');

		wp_delete_post(1);

	$content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip';

		$all_post_types_post = array(

				'leocoders_services' => array(
												 array(
														'post_title' 	 => 'Research and Development',
														'post_content' => 'Research and Development (R&D) is a crucial process that involves investigating and exploring new ideas, technologies, and methodologies to drive innovation and create new products, services, or processes. R&D plays a significant role in enhancing competitiveness, improving efficiency, and fostering growth for businesses across various industries. Here are some key points to understand about research and development:
															<ul>
															<li>Purpose of R&D: The primary purpose of R&D is to generate new knowledge, insights, and technologies that can be applied to develop new products, improve existing products, or enhance operational processes. R&D efforts focus on creating innovative solutions that address market needs, improve efficiency, reduce costs, and stay ahead of competitors. </li>
															<li>Importance of R&D: R&D is critical for the long-term success and sustainability of businesses. It enables companies to adapt to evolving market trends, customer demands, and technological advancements. R&D investments can lead to the development of breakthrough products or services that differentiate a company from its competitors, open new revenue streams, and strengthen its position in the market. </li>
															<li>Types of R&D: R&D can be classified into different types based on the objectives and outcomes sought:
																<ul>
																<li>Basic Research: This type of research aims to expand scientific knowledge and understanding without immediate commercial application. It focuses on exploring fundamental principles and theories, often conducted in academic or research institutions. </li>
																<li>Applied Research: Applied research is focused on solving specific problems or developing new technologies or products with a clear practical application in mind. It bridges the gap between theoretical knowledge and practical implementation. </li>
																<li>Development Research: This type of research focuses on the practical application of existing knowledge and technologies to create new or improved products, processes, or services. Development research aims to optimize and refine existing ideas or technologies to meet specific requirements or market demands.</li>
																<ul>
															</li>
																<li>R&D Process: The R&D process typically involves several stages:
																	<ul>
																	<li>Idea Generation: Ideas for new products, technologies, or improvements are generated through brainstorming sessions, market research, and customer feedback. </li>
																	<li>Feasibility Analysis: Ideas are evaluated to determine their technical feasibility, market potential, and alignment with the company\'s strategic goals. </li>
																	<li>Concept Development: Promising ideas are further developed into concepts, outlining the proposed product\'s features, functionality, and value proposition. </li>
																	<li>Prototype Development: Prototypes or proof-of-concepts are created to test and validate the feasibility, functionality, and user experience of the concept. </li>
																	<li>Testing and Validation: Prototypes are subjected to rigorous testing and validation to gather feedback, identify potential issues, and make necessary refinements. </li>
																	<li>Scale-Up and Commercialization: Once the prototype is successfully validated, it moves into production, marketing, and commercialization phases, preparing the product for market launch. </li>
																	<ul>
																</li>
																<li>Collaboration and Investment: Successful R&D often involves collaboration with internal teams, external research organizations, academic institutions, and industry partners. Companies allocate significant resources, including financial investments, dedicated R&D teams, and specialized equipment, to drive innovation and ensure the success of their R&D efforts. </li>
															</ul>
															R&D is a dynamic and iterative process that requires continuous learning, experimentation, and adaptation. By investing in R&D, companies can stay ahead of the curve, bring innovative products to market, and achieve sustainable growth in a rapidly evolving business landscape.'
															,
														'post_excerpt' => 'Research and Development (R&D) is a crucial process that involves investigating and exploring new ideas, technologies, and methodologies to drive innovation and create new products, services, or processes.',
														'post_thumb'	 => 'scoping-session.png',
														'button_text'	 => 'Learn More'
													),
												 array(
														'post_title' 	 => 'Product Design Sprint',
														'post_content' => 'Welcome to the Product Design Sprint! This intensive and collaborative process is designed to help you rapidly develop, prototype, and validate innovative product ideas. During this sprint, our team will guide you through a structured framework to transform your concept into a tangible and user-centric solution. Here\'s an overview of the Product Design Sprint process:
														<ul>
															<li>Define: In the initial phase, we will work closely with you to gain a deep understanding of your product vision, target audience, and business goals. Through interactive discussions and brainstorming sessions, we will identify the core problem your product aims to solve and establish clear objectives for the sprint. </li>
															<li>Research: Next, we will conduct user research and gather valuable insights about your target audience. By empathizing with users, understanding their pain points, and analyzing market trends, we can ensure that the product we design aligns with user needs and preferences. This research will serve as the foundation for ideation and solution development. </li>
															<li>Ideate: In this creative phase, we will generate a multitude of ideas and potential solutions to address the identified problem. Through collaborative workshops and design exercises, we encourage diverse thinking and explore various possibilities. Our team will facilitate these sessions and guide you through the process of ideation, allowing for a wide range of perspectives and innovative concepts. </li>
															<li>Prototype: Once we have selected the most promising ideas, we will move into the prototyping stage. Our skilled designers will create interactive and realistic prototypes that represent the key features and functionalities of your product. These prototypes can be in the form of digital interfaces, physical mock-ups, or a combination of both. Prototypes serve as a visual representation of the product, enabling you to gather valuable feedback and make informed decisions. </li>
															<li>Test: The next step is to validate the effectiveness of the prototype through user testing. We will conduct usability testing sessions with real users, observing their interactions and gathering feedback. This valuable input will help us identify any usability issues, validate assumptions, and make data-driven refinements to enhance the user experience. </li>
															<li>Iterate: Based on the insights gathered during the testing phase, we will iterate on the design and refine the prototype. This iterative process allows us to address user feedback, optimize the product\'s usability, and make necessary improvements. We will collaborate closely with you throughout this process, ensuring that your vision is translated into a functional and user-friendly solution. </li>
															</ul>',
														'post_excerpt' => 'This intensive and collaborative process is designed to help you rapidly develop, prototype, and validate innovative product ideas.',
														'post_thumb'	 => 'fountain-pen.png',
														'button_text'	 => 'Learn More'
													),
													array(
														'post_title' 	 => 'Scoping Session',
														'post_content' => 'Welcome to the Scoping Session for your project! This session is a crucial step in defining the scope, objectives, and deliverables of your project. We are here to understand your requirements in detail and provide you with the best possible solutions. Let\'s dive into the key aspects of this session: <ul>
															<li>Project Overview: During this session, we will work closely with you to define the scope of your project. We will discuss your business goals, target audience, and any specific features or functionalities you want to incorporate into your project. This will help us gain a comprehensive understanding of your requirements and ensure that our solution aligns perfectly with your vision. </li>
															<li>Understanding Your Needs: Our team will actively listen to your requirements and ask relevant questions to gain deeper insights. We want to understand your business objectives, challenges, and the problems you are looking to solve through this project. This collaborative process allows us to tailor our solutions to your specific needs, ensuring that the end result exceeds your expectations. </li>
															<li>Identifying Key Deliverables: During the scoping session, we will identify the key deliverables for your project. This includes defining the specific functionalities, features, and design elements that need to be incorporated. By clearly defining the deliverables, we can provide you with accurate timelines, cost estimates, and project milestones, ensuring a smooth and transparent development process. </li>
															<li>Technical Feasibility Assessment: Our team will assess the technical feasibility of your project during the scoping session. We will evaluate the compatibility of your requirements with the available technologies and resources. If there are any limitations or challenges, we will discuss them with you and suggest alternative solutions to achieve your desired outcomes. </li>
															<li>Project Timeline and Milestones: Based on the scope of your project, we will work with you to establish a realistic project timeline. This timeline will include key milestones, such as design approval, development phases, testing, and deployment. Our goal is to ensure that your project is completed within the agreed-upon timeframe, while maintaining the highest quality standards.</li>
															<li>Communication and Collaboration: Effective communication and collaboration are vital for the success of any project. We will establish clear lines of communication and discuss the preferred methods of collaboration, such as regular meetings, progress updates, and feedback sessions. This ensures that you are actively involved throughout the project lifecycle and have full visibility into the progress being made. </li>
															</ul>',
														'post_excerpt' => 'This session is a crucial step in defining the scope, objectives, and deliverables of your project.',
														'post_thumb'	 => 'coffee.png',
														'button_text'	 => 'Learn More'
													)
											),


				'leocoders_projects' => array(
												array(
													 'post_title' 	 => 'Financial Services',
													 'post_content' => 'A workshop to answer critical questions, plan the features of your product and reduce the risk.',
													 'post_excerpt' => '',
													 'post_thumb'	 => 'financial-service.jpeg'
												 ),

												array(
													 'post_title' 	 => 'App Development',
													 'post_excerpt' => '',
													 'post_content' => 'A workshop to answer critical questions, plan the features of your product and reduce the risk.',
													 'post_thumb'	 => 'app-development.jpeg'
												 ),

												array(
													 'post_title' 	 => 'Software Development',
													 'post_excerpt' => '',
													 'post_content' => 'A workshop to answer critical questions, plan the features of your product and reduce the risk.',
													 'post_thumb'	 => 'software-development.jpeg'
												 ),

												array(
													 'post_title' 	 => 'Technology Consulting',
													 'post_excerpt' => '',
													 'post_content' => 'A workshop to answer critical questions, plan the features of your product and reduce the risk.',
													 'post_thumb'	 => 'technology-consulting.jpeg'
												 )
												),

				'rp_testimonial' => array(
															array(
																 'post_title' 	 => 'Build a WordPress ecommerce site with an online fundraising platform. Contract negotiable.',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content'  => 'The Leo Coders team is very professional, kind, and knowledgeable. They did a great job picking a platform that suited my needs. They were also very responsive to my requests, and committed to doing excellent work! After having bad experiences with other developers in the past, it was very refreshing to work with Leo Coders. They are a solid team that does good work! I am very satisfied with my website!',
																 // 'post_thumb'	 => 'stella-smith.webp'
															 ),

															array(
																 'post_title' 	 => 'Web portal - Card Management System ',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Mohini and her team did a fantastic job. She is professional and her technical experience was great.  I would highly recommend her to anyone for their business needs. The response was great, interpersonal skills fantastic. Easy to work with. Thank you Mohini and Thank you Leo Coders.'
															 ),

															array(
																 'post_title' 	 => 'Developer needed for creating a responsive auction website',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => ' enjoyed working with them. They are very responsive and willing to help you to solve the problems. I am continually impressed by the results they produce! I will use them again for my other projects.'
															 ),

															array(
																 'post_title' 	 => 'Build responsive WordPress site with booking/payment functionality',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Extremely satisfied with the results obtained. Leo Coders delivered good work on this Laravel development project and I enjoyed working with him. His communication was top-notch and his skills were reasonably strong. I enjoyed working with Rahul and will likely have additional jobs for him in the future.'
															 ),

															array(
																 'post_title' 	 => 'Build responsive WordPress site with booking/payment functionality',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Extremely satisfied with the results obtained. Leo Coders delivered good work on this Laravel development project and I enjoyed working with him. His communication was top-notch and his skills were reasonably strong. I enjoyed working with Rahul and will likely have additional jobs for him in the future.'
															 ),

															array(
																 'post_title' 	 => 'Simplified E-Commerce Food Ordering Website',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Very polite and professional. We look forward to working with them again in this next project.'
															 ),

															array(
																 'post_title' 	 => 'Develop a modern Mortgage origination platform in React',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Great experience. They worked hard to make sure they delivered what we needed.'
															 ),

															array(
																 'post_title' 	 => 'Reactjs Development for healthcare application',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Great supportive set of developers who are dedicated to the project and timeline. Recommended !'
															 ),

															array(
																 'post_title' 	 => 'Developer needed for creating PWA',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Very easy to work with and with skilled developers. They go that extra mile to solve the job.'
															 ),

															array(
																 'post_title' 	 => 'SEO',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Rahul has been helpful and very committed to his work as assigned. His is very good with communications. I will definitely hire him again In the future.'
															 ),

															array(
																 'post_title' 	 => 'Create real estate recommendation site',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Rahul and Leocoders were a pleasure to work! They really took every effort to adjust their schedule to meet my needs. I loved their ability to take my requirements and enhance and expand upon my vision.'
															 ),

															array(
																 'post_title' 	 => 'Custom property management web app',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Rahul delivered good work on my PHP development project and I enjoyed working with him. His communication was top-notch, he met all deadlines, and his skills were reasonably strong. At one point I asked for an additional help and he was very forthcoming that the additional work was outside his responsibilities. I enjoyed working with rahul and will likely have additional jobs for him in the future.'
															 ),

															array(
																 'post_title' 	 => 'Multi-Vendor Marketplace Development Project Manager ',
																 'post_excerpt' => '',
																 'designation'	 => 'CEO',
																 'ratings'			=> '5',
																 'post_content' => 'Rahul did a good job, but unfortunately, project launch was cancelled due to current situations.'
															 ),
												),
			'leocoders_partners' => array(
													array(
														'post_title' => 'Wdezia',
														'post_excerpt' => '',
														'post_content' => 'This is the demo partner',
														'post_thumb'	 => 'wdezia.png'
													),
													array(
														'post_title' => 'Platform No 1',
														'post_excerpt' => '',
														'post_content' => 'This is the demo partner',
														'post_thumb'	 => 'platform-no-1.png'
													),
													 array(
														 'post_title' => 'Aggradata',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'aggradata.png'
													 ),
													 array(
														 'post_title' => 'Beerple',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'beerple.png'
													 ),
													 array(
														 'post_title' => 'Bnutty',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'bnutty.png'
													 ),
													 array(
														 'post_title' => 'Btroo',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'btroo.png'
													 ),
													 array(
														 'post_title' => 'Catapolt',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'catapolt.png'
													 ),
													 array(
														 'post_title' => 'Duo Lingua',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'duo-lingua.png'
													 ),
													 array(
														 'post_title' => 'Enkore',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'enkore.png'
													 ),
													 array(
														 'post_title' => 'Etooth',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'etooth.png'
													 ),
													 array(
														 'post_title' => 'Jinn',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'jinn.png'
													 ),
													 array(
														 'post_title' => 'KidzCare',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'kidzcare.png'
													 ),
													 array(
														 'post_title' => 'Monitor Events',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'monitor-events.png'
													 ),
													 array(
														 'post_title' => 'Nananest',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'nananest.png'
													 ),
													 array(
														 'post_title' => 'Btroo',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'btroo.png'
													 ),
													 array(
														 'post_title' => 'Thrivelab',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'thrivelab.png'
													 ),
													 array(
														 'post_title' => 'Weconnect',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'weconect.png'
													 ),
													 array(
														 'post_title' => 'Avetiz',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'avetiz.png'
													 ),
													 array(
														 'post_title' => 'Afisado',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'afisado.png'
													 ),
													 array(
														 'post_title' => 'Partment',
														 'post_excerpt' => '',
														 'post_content' => 'This is the demo partner',
														 'post_thumb'	 => 'partment.png'
													 ),
												),

			'leocoders_jobs' => array(
													 array(
														 'post_title' 								=> 'Laravel Developer',
														 'post_excerpt' 							=> '',
														 'experience' 								=> '3-5 Years Experience',
														 'job_type' 									=> 'Full Time',
														 'post_content' 							=> 'This is the demo job',
														 'apply_now'									=> 'Apply Now',
														 'key_skills' 								=>  array(
															 																		'PHP',
															 																		'Laravel 8+',
															 																		'MYSQL',
															 																		'REST API',
													 																		),
														 'roles_and_reponsibilities' 	=> array(
															 																		array('name' => 'Designing and Developing web applications using Laravel 8 and above'),
															 																		array('name' => 'Closely working with front-end and back-end developers on projects' ),
															 																		array('name' => 'Hands on experience with handing MySQL queries' ),
															 																		array('name' => 'Excellent logical and analytics skill' ),
															 																		array('name' => 'Experience with Payment Gateway integrations and implementations' ),
															 																		array('name' => 'Knowledge on any Frontend Framework - Bootstrap / Foundation / Backbone (Secondary skills, added advantage)' ),
															 																		array('name' => 'Follow standard coding structure with Security first approach to avoid vulnerabilities such as SQL Injection and XSS' ),
																														),
													 ),

													 array(
														 'post_title' 								=> 'ReactJS Developer ',
														 'post_excerpt' 							=> '',
														 'experience' 								=> '3-5 Years Experience',
														 'job_type' 									=> 'Full Time',
														 'post_content' 							=> 'This is the demo job',
														 'apply_now'									=> 'Apply Now',
														 'key_skills' 								=>  array(
															 																		'ReactJS',
															 																		'Redux / React Query',
															 																		'Javascript',
															 																		'Jquery',
															 																		'HTML',
															 																		'CSS'
													 																		),
														 'roles_and_reponsibilities' 	=> array(
															 																		array('name' => 'Hands on experience with Front-end UI development in ReactJS '),
															 																		array('name' => 'Excellent knowledge in HTML/CSS/Bootstrap' ),
															 																		array('name' => '3+ years in developent in React.js/Redux/React-Query with Responsive website and web application development' ),
															 																		array('name' => 'Developing self/contained, reusable and testable modules and components' ),
															 																		array('name' => 'Experience in Java script, Jquery ' ),
															 																		array('name' => 'Good knowledge REST API Integration with web page and web application' )
																														),
													 ),

													 array(
														 'post_title' 								=> 'Sr. PHP Developer',
														 'post_excerpt' 							=> '',
														 'experience' 								=> '3-5 Years Experience',
														 'job_type' 									=> 'Full Time',
														 'post_content' 							=> 'This is the demo job',
														 'apply_now'									=> 'Apply Now',
														 'key_skills' 								=>  array(
															 																		'Hands-on experience in e-Commerce development ',
															 																		'Sound knowledge of Core PHP',
															 																		'Hands-on experience in Laravel / CodeIgniter',
															 																		'Good knowledge of MySQL Database',
															 																		'Good knowledge in handling HTML, CSS, JavaScript, Ajax, JQuery',
															 																		'Web Services Development / API Integration – JSON RESTFul API',
															 																		'Knowledge of any Front-end Framework - Bootstrap / Foundation / Backbone (Secondary skills, an added advantage)',
															 																		'Follow standard coding structure with a Security-First approach to avoid vulnerabilities such as SQL Injection and XSS',
															 																		'Sound knowledge of integrating multiple payment gateways'
													 																		),
														 'roles_and_reponsibilities' 	=> array(
															 																		array('name' => 'Must be able to develop and write code effectively, in a quick turnaround time'),
															 																		array('name' => 'Must be a good team player with excellent communication skills' ),
															 																		array('name' => 'Must allocate & assign tasks to team members to ensure project deadlines are met' ),
															 																		array('name' => 'Must be a good problem solver who can clear architectural disputes and other complex problems' ),
															 																		array('name' => 'Must be proficient in third-party APIs' ),
															 																		array('name' => 'Must be able to take up R&D activities and resolve issues' )
																														),
													 ),

													 array(
														 'post_title' 								=> 'Front-End Developer',
														 'post_excerpt' 							=> '',
														 'experience' 								=> '3-5 Years Experience',
														 'job_type' 									=> 'Full Time',
														 'post_content' 							=> 'This is the demo job',
														 'apply_now'									=> 'Apply Now',
														 'key_skills' 								=>  array(
															 																		'WordPress',
															 																		'Bootstrap',
															 																		'Angular',
															 																		'JavaScript',
															 																		'CSS',
															 																		'HTML'
													 																		),
														 'roles_and_reponsibilities' 	=> array(
															 																		array('name' => 'Proficient in Angular, JavaScript, jQuery, CSS & HTML with best practices'),
															 																		array('name' => 'Working knowledge in Photoshop, Dreamweaver and Bootstrap' ),
															 																		array('name' => 'Develop fully functional, resolution-free & fully accessible websites using CSS & Div' ),
															 																		array('name' => 'Ability to work under deadlines, taking decisions on complex technical & design requirements' ),
															 																		array('name' => 'Ensuring high performance & providing support' )
																														),
													 ),

													 array(
														 'post_title' 								=> 'Business Development Executive ',
														 'post_excerpt' 							=> '',
														 'experience' 								=> '3-5 Years Experience',
														 'job_type' 									=> 'Full Time',
														 'post_content' 							=> 'This is the demo job',
														 'apply_now'									=> 'Apply Now',
														 'key_skills' 								=>  array(
															 																		'The candidate should have an understanding of Software, Graphic designing, Websites and other Web technologies',
															 																		'Excellent verbal/ written communication and presentation skills',
															 																		'To coordinate with the internal team and get the deliverables for the client',
															 																		'Must have a deep desire to work and succeed in a growing organization with total commitment and a sense of job ownership',
															 																		'Ability to multi-task, prioritize, and manage time effectively',
															 																		'Hands-on experience in using MS office products like Word, Excel, PowerPoint, etc.'
													 																		),
														 'roles_and_reponsibilities' 	=> array(
															 																		array('name' => 'The desired candidate will be responsible for selling the services (Website design, SMO and SEO) provided by the company to the prospective clients and thereby acquiring new clients'),
															 																		array('name' => 'Must be analytical enough to understand project requirements' ),
															 																		array('name' => 'Will be required to interact with the client and get approval for designs' ),
															 																		array('name' => 'Gathering information to accurately identify the customers\' needs' ),
															 																		array('name' => 'Oversee design choices and possible external vendor selection' )
																														),
													 ),

												),

									'post' => array(
																				array(
																					 'post_title' 	 => 'The 5 Ways To Improve Your Credibility Working From Home',
																					 'post_excerpt'  => '',
																					 'post_content'  => 'As a small-business owner, it’s important to find high-quality information and educational resour-ces you can trust to help you overcome common obstacles and achieve success. These resources may include finance-focused resources such as magazines, podcasts or blogs that aim to educate business owners about how to make the right financial decisions.
																					 With the large number of online resources out there, it’s hard to know where to begin searching for the best ones for your needs. To help you get started, below, 15 Forbes Finance Council members share their go-to finance blogs, periodicals, podcasts and websites—resources that they believe all small-business owners should be accessing.',
																					 'button_text'	 => 'Learn More',
																					 'post_thumb'	 => 'post1.jpeg'
																				 ),

																				array(
																					 'post_title' 	 => 'Fintech Startup Will Finance The Women-Owned Businesses',
																					 'post_excerpt' => '',
																					 'post_content'  => 'As a small-business owner, it’s important to find high-quality information and educational resour-ces you can trust to help you overcome common obstacles and achieve success. These resources may include finance-focused resources such as magazines, podcasts or blogs that aim to educate business owners about how to make the right financial decisions.
																					 With the large number of online resources out there, it’s hard to know where to begin searching for the best ones for your needs. To help you get started, below, 15 Forbes Finance Council members share their go-to finance blogs, periodicals, podcasts and websites—resources that they believe all small-business owners should be accessing.',
																					 'button_text'	 => 'Learn More',
																					 'post_thumb'	 => 'post2.webp'
																				 ),

																				array(
																					 'post_title' 	 => '4 Ways Businesses Can Conduct Productive Time Management',
																					 'post_excerpt' => '',
																					 'post_content'  => 'As a small-business owner, it’s important to find high-quality information and educational resour-ces you can trust to help you overcome common obstacles and achieve success. These resources may include finance-focused resources such as magazines, podcasts or blogs that aim to educate business owners about how to make the right financial decisions.
																					 With the large number of online resources out there, it’s hard to know where to begin searching for the best ones for your needs. To help you get started, below, 15 Forbes Finance Council members share their go-to finance blogs, periodicals, podcasts and websites—resources that they believe all small-business owners should be accessing.',
																					 'button_text'	 => 'Learn More',
																					 'post_thumb'	 => 'post3.jpeg'
																				 )
																	),

									'leocoders_products' => array(
																				array(
																					'post_title' 	 => 'Lead Managment',
																					'post_excerpt' => '',
																					 'post_content'  => 'Our Lead Management Application helps real estate professionals track and nurture leads effectively. It provides a centralized platform for lead capture, tracking, and communication. With automated follow-ups, lead scoring, and analytics, our application enables agents to maximize their conversion rates and close more deals. ',
																					 'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
																					 'launching_date' => '2023-12-01',
																					 'form' => 'Products Form',
																					 'post_thumb'	 => 'lead-managment.png'
																				),
																				array(
																					 'post_title' 	 => 'Co-working Space Application',
																					 'post_excerpt' => '',
																					 'post_content'  => 'Our Co-working Space Application serves as a comprehensive management tool for co-working space providers. It offers features like desk booking, member management, invoicing, and access control. With our application, co-working space operators can efficiently manage their spaces, attract new members, and provide an exceptional working environment. ',
																					 'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
																					 	'launching_date' => '2023-12-01',
																					 	'form' => 'Products Form',
																					 'post_thumb'	 => 'co-working-space-application.png'
																				),
																				array(
																					'post_title' 	 => 'Rental',
																					'post_excerpt' => '',
																					'post_content'  => 'Our Rental Application caters to property owners and tenants, streamlining the rental process from start to finish. It facilitates property listings, tenant screening, lease agreement creation, and online rent payments. By automating key aspects of the rental process, our application saves time and reduces administrative burdens.',
																					'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
																						'launching_date' => '2023-12-01',
																						'form' => 'Products Form',
																					'post_thumb'	 => 'rental.png'
																				),
																				array(
																					'post_title' 	 => 'Construction And Internal Designer Quotation\'s',
																					'post_excerpt' => '',
																					'post_content'  => 'Our Quotation\'s Management Application simplifies the process of creating and managing quotations for property-related services. It automates the quotation generation, tracks revisions, and enables seamless communication with clients. With customizable templates and document storage, our application ensures accurate and professional quotations every time. ',
																					'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
																						'launching_date' => '2023-12-01',
																						'form' => 'Products Form',
																					'post_thumb'	 => 'construction.png'
																				),
																				array(
																					'post_title' 	 => 'Broker/Agent Application',
																					'post_excerpt' => '',
																					'post_content'  => 'Our Broker/Agent Application caters to real estate professionals, empowering them to efficiently handle their clients\' needs. The application offers client management features, document sharing, property tracking, and marketing tools. It streamlines the agent\'s workflow, allowing them to focus on providing exceptional service to their clients. ',
																					'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
																						'launching_date' => '2023-12-01',
																						'form' => 'Products Form',
																					'post_thumb'	 => 'broker-agent.png'
																				),
																				array(
																						'post_title' 	 => 'Developer Inventory Management',
																						'post_excerpt' => '',
																						'post_content'  => 'Our Inventory Management application assists property managers in efficiently managing their property portfolios. It provides centralized control over property information, lease agreements, maintenance schedules, and tenant communication. With automated reminders and reporting features, our application optimizes the property management workflow. ',
																						'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
 																					 	'launching_date' => '2023-12-01',
 																					 	'form' => 'Products Form',
																						'post_thumb'	 => 'lead-managment.png'
																				),
																				array(
																						'post_title' 	 => 'Booking System',
																						'post_excerpt' => '',
																						'post_content'  => 'Our Booking System application streamlines the rental process, enabling property owners to manage bookings efficiently. From vacation rentals to event spaces, our application handles reservations, availability tracking, and online payments. The intuitive interface ensures a hassle-free booking experience for both property owners and renters. ',
																						'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
 																					 	'launching_date' => '2023-12-01',
 																					 	'form' => 'Products Form',
																						'post_thumb'	 => 'booking-system.png'
																				),
																				array(
																						'post_title' 	 => 'Second Home',
																						'post_excerpt' => '',
 																					 	'post_content'  => 'Our Second Home Application focuses on helping individuals explore and invest in second homes. Whether it\'s a vacation property or a long-term investment, our application provides comprehensive listings, financing options, and expert advice. Users can easily compare properties, access market trends, and make informed decisions about their second home investments.',
																						'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
 																					 	'launching_date' => '2023-12-01',
 																					 	'form' => 'Products Form',
																						'post_thumb'	 => 'second-home.png'
																				),
																				array(
																						'post_title' 	 => 'Property Market Place',
																						'post_excerpt' => '',
																						'post_content'  => 'Our Property Market Place SAAS application serves as a centralized platform connecting property buyers, sellers, and agents. It offers a user-friendly interface with advanced search filters, enabling users to find their dream properties quickly. With secure transaction capabilities and real-time notifications, our application simplifies the property buying and selling process. ',
																						'offer_text' => 'On Pre Booking Grab 80% discount on boarding fee for first 100 customers',
 																					 	'launching_date' => '2023-12-01',
 																					 	'form' => 'Products Form',
																						'post_thumb'	 => 'property-market-place.png'
																				),
																	),

									'rp_case_study' => array(
																				array(
																					 'post_title' 	 => 'Building ThriveLab\'s Hormone Balance Web Application',
																					 'post_excerpt' => '',
																					 'post_content'  => 'Leo Coders, a leading software development company, was entrusted by ThriveLab (https://www.thrivelab.com/), a prominent healthcare service provider, to create a web application that would empower patients in maintaining hormone balance. The primary objective was to develop an intuitive platform where patients could select their symptoms, receive personalized treatment suggestions using machine learning and artificial intelligence algorithms, and conveniently schedule appointments with recommended doctors. Furthermore, the client requested seamless integration with Zoho for efficient lead management. This case study showcases Leo Coders\' approach to realizing the client\'s vision, including the selection of appropriate technologies to meet ThriveLab\'s requirements. ',
																					 'requirement'	 => 'ThriveLab articulated the following key requirements for the web application:
																						 									Symptom Selection: Patients should have the ability to choose their symptoms from a predefined list.
																															Treatment Suggestions: The application should leverage ML/AI algorithms to provide tailored treatment suggestions based on the selected symptoms.
																															Appointment Booking: Patients must be able to effortlessly schedule appointments with doctors recommended by the application.
																															Zoho Integration: Seamless communication with Zoho is vital for effective lead management within the application. ',
																					 'technology_selection'	 => 'After thorough consultations with ThriveLab, Leo Coders finalized the technology stack for the project:

																						 													Frontend: React, a flexible and component-based framework, was chosen for the frontend development. Its scalability and reusability make it ideal for creating dynamic and interactive web applications.

																																			Backend and API: Python, in conjunction with the Django framework, was selected for the backend development. Django\'s robustness, scalability, and extensive ecosystem of libraries and packages make it well-suited for constructing complex web applications.

																																			Database: Postgres, a reliable and secure open-source relational database, was chosen to store application data. ',
																					 'development_process'	 => 'The development process involved the following key stages:
																					 <ol>
																					  	<li><span> UI Design</span>
																					     <span>Leo Coders\' team initiated the project by designing the user interface for ThriveLab\'s web application (https://www.thrivelab.com/) using Adobe XD. Collaborating closely with ThriveLab, they crafted wireframes and mockups to ensure the UI aligned with their expectations. The design underwent multiple iterations and received valuable feedback from the client, ultimately resulting in the approval of the final UI design. </span></li>
																					  	<li><span>Backend Development</span>
																					 <span>Once the UI design was finalized, the development team commenced the backend development using Python and Django. They skillfully implemented the necessary APIs, integrated ML/AI algorithms for symptom analysis, and formulated the logic for generating personalized treatment suggestions. The team prioritized scalability, efficiency, and the ability to handle a substantial number of concurrent users when designing the backend architecture.</span></li>
																					  	<li><span>Frontend Development</span>
																					 <span>Simultaneously, a dedicated team focused on frontend development using React. They diligently translated the approved UI design into functional components, seamlessly integrating them with the backend APIs. The team dedicated efforts towards enhancing user experience, optimizing performance, and ensuring cross-browser compatibility.</span></li>
																					  	<li><span>Zoho  Integration</span>
																					     <span>Leo Coders\' adept developers seamlessly integrated the web application with Zoho to facilitate effective lead management. They skillfully implemented APIs and workflows to ensure seamless data synchronization between the application and Zoho, thereby enabling efficient communication and streamlined lead tracking.</span></li>
																					 </ol>',
																					 'project_duration'	 => 'The entire project was completed within a span of six months. Leo Coders allocated a team of eight skilled professionals, specializing in frontend development, backend development, UI/UX design, and integration. Adhering to Agile development methodologies, the team maintained consistent communication with ThriveLab, fostering regular feedback loops and iterative development cycles. ',
																					 'development_conclusion'	 => 'Leo Coders successfully developed and delivered a robust web application for hormone balance management at ThriveLab (https://www.thrivelab.com/). By leveraging the power of React for the frontend, Python (Django) for the backend, and Postgres for the database, the team created a user-friendly platform that empowers patients to select their symptoms, receive personalized treatment suggestions, and conveniently book appointments with recommended doctors. The seamless integration with Zoho ensures efficient lead management and enhances the application\'s overall functionality. Through effective collaboration, agile development methodologies, and a dedicated team, Leo Coders completed the project within the agreed timeframe, fulfilling ThriveLab\'s requirements and providing a valuable tool for patients seeking hormone balance.',
																					 'category'			 => 'Healthcare',
																					 'project_link'  => '#',
																					 'post_thumb'	 	 => 'thrivelab.png'
																				 ),

																				array(
																					 'post_title' 	 => 'Building a WordPress Website for Mizata by Antiresort',
																					 'post_excerpt' => '',
																					 'post_content'  => 'Leo Coders, a renowned web development agency, was approached by Mizata by Antiresort to create a WordPress website for their beachfront resort located at Playa Mizata, Teotepeque, El Salvador. The client desired a refreshing user interface (UI) that would effectively highlight their resort and its facilities. They also wanted to emphasize packages, rooms, experiences, food, and adventurous packages. This case study outlines Leo Coders\' approach to developing the website and achieving the client\'s objectives.',
																					 'requirement'	=> 'Client Requirements Mizata by Antiresort had the following key requirements for their WordPress website:
																						 									<ol class="">
																					 										<li>UI Refresh: The client sought a visually appealing and engaging UI that would capture the essence of their resort and provide an immersive experience for website visitors.</li>
																					 										<li>Resort Highlights: The website needed to prominently showcase the resort\'s amenities, facilities, and unique features to entice potential guests.</li>
																					 										<li>Package Highlighting: Mizata by Antiresort wanted to emphasize their diverse packages, including accommodation, adventure activities, culinary experiences, and more.</li>
																					 										<li>Room Showcase: The website should feature an intuitive and visually appealing room gallery, enabling visitors to explore the available accommodation options.</li>
																															<li>Experience, Food, and Adventure Sections: Separate sections were required to highlight the resort\'s experiential offerings, culinary delights, and adventurous packages.</li> </ol>',
																					 // 'technology_selection' => 'Ecommerce',
																					 'development_process' => 'Development Process To meet the client\'s requirements, Leo Coders undertook the following development process:
																					 <ol>
																					 <li>UI Design Leo Coders\' team initiated the project by collaborating closely with Mizata by Antiresort to understand their vision and target audience. They then designed a refreshing and captivating UI for the WordPress website, incorporating elements that showcased the resort\'s unique character and stunning location. The team paid attention to visual aesthetics, intuitive navigation, and mobile responsiveness to ensure an optimal user experience. </li>
																					 <li>WordPress Development Once the UI design was finalized, the development team began implementing the website using WordPress, a versatile and user-friendly content management system. They customized and extended WordPress functionalities to meet the specific needs of Mizata by Antiresort. This involved creating custom post types and taxonomies for packages, rooms, experiences, food, and adventure sections, enabling seamless content management and organization.</li>
																					 <li>Resort Highlights and Room Showcase Leo Coders\' developers integrated captivating visuals, engaging copy, and multimedia elements to showcase the resort\'s highlights and amenities effectively. They designed an intuitive room gallery, allowing visitors to explore different room types, view high-quality images, and access relevant information.</li>
																					 <li>Package, Experience, Food, and Adventure Sections To emphasize Mizata by Antiresort\'s offerings, dedicated sections were created to highlight packages, experiences, culinary delights, and adventurous activities. These sections were designed to provide detailed information, captivating images, and clear calls-to-action, encouraging visitors to explore and book their desired experiences. </li>
																					 </ol>
																					 ',
																					 'project_duration' => 'The development of the WordPress website for Mizata by Antiresort took approximately three months to complete. Leo Coders assigned a team of skilled professionals, including web designers, frontend and backend developers, content specialists, and quality assurance testers. The team followed an agile development approach, ensuring frequent communication with the client, iterative feedback loops, and timely project delivery.',
																					 'development_conclusion' => '<ol>
																					 	<li>Leo Coders accomplished the development of a visually captivating and user-friendly WordPress website for Mizata by Antiresort, perfectly aligning with the client\'s requirements. By prioritizing a refreshing UI design and emphasizing the resort\'s highlights, packages, rooms, experiences, food, and adventure offerings, the website effectively showcases the unique features and attractions of the Playa Mizata resort in Teotepeque, El Salvador. </li>
																					 	<li>Through close collaboration with the client, Leo Coders created a visually immersive experience that captures the essence of the resort and appeals to its target audience. The intuitive navigation and mobile responsiveness enhance the user experience, allowing visitors to seamlessly explore the resort\'s amenities and make informed decisions. </li>
																					 	<li>The dedicated sections highlighting packages, experiences, culinary delights, and adventurous activities engage visitors and encourage them to book their desired experiences. The custom post types and taxonomies implemented for content management ensure easy organization and updates, empowering the client to maintain the website efficiently. </li>
																					 	<li>The project was completed within a timeframe of approximately three months, thanks to the collaborative efforts of a skilled team of web designers, frontend and backend developers, content specialists, and quality assurance testers. Following an agile development approach, the team maintained regular communication with the client, enabling iterative feedback loops and ensuring timely project delivery. </li>
																					 	<li>Overall, the WordPress website developed by Leo Coders for Mizata by Antiresort successfully fulfills the client\'s vision and effectively highlights the resort\'s offerings. By combining captivating visuals, engaging content, and seamless functionality, the website serves as a powerful tool for attracting potential guests, showcasing the resort\'s unique features, and driving bookings. </li>
																					 </ol>',
																					 'category'			 => 'Wordpress',
																					 'project_link'  => '#',
																					 'post_thumb'	 	 => 'antiresort.png'
																				 ),

																				array(
																					 'post_title' 	 => 'Building a Food Delivery Web and Mobile Application for DrapShop',
																					 'post_excerpt' => '',
																					 'post_content'  => 'Leo Coders was approached by DrapShop (https://drapshop.com/), a food delivery service provider, to develop a comprehensive web and mobile application catering to customers, vendors, and drivers. The objective was to create a seamless platform that would facilitate food ordering, vendor management, and efficient delivery operations. This case study outlines Leo Coders\' approach to developing the application, utilizing Bootstrap and Laravel for the web application and native mobile development for Android and iOS.',
																					 'requirement'	=> 'Client Requirements DrapShop presented the following requirements for their food delivery application:
																						 <ol>
																						 	<li> Customer Application: A user-friendly interface allowing customers to browse menus, place orders, track deliveries, and provide feedback.</li>
																						 	<li>Vendor Application: An intuitive platform enabling vendors to manage menus, track orders, update availability, and communicate with customers.</li>
																						 	<li>Driver Application: A dedicated app for drivers to receive delivery requests, navigate to destinations, and update order status.</li>
																						 	<li>Seamless Integration: The applications should be interconnected, facilitating real-time updates between customers, vendors, and drivers.</li>
																						 	<li>Technology Preferences: Bootstrap and Laravel were requested for the web application, while native development for Android and iOS was preferred for the mobile application.</li>
																						 </ol>
																						 ',
																					 // 'technology_selection' => '',
																					 'development_process' => 'Leo Coders undertook the following development process to fulfill DrapShop\'s requirements:
																					 <ol>
																					 	<li>Web Application Development The development team utilized Laravel, a popular PHP framework, to build the web application. They leveraged Laravel\'s robust features to handle user authentication, manage menus, process orders, and facilitate smooth communication between customers, vendors, and drivers. Bootstrap, a responsive web design framework, was employed to ensure a visually appealing and user-friendly interface across devices. </li>
																					 	<li>Mobile Application Development For the customer, vendor, and driver applications, Leo Coders adopted native mobile development approaches. They utilized Java or Kotlin for Android development and Swift for iOS development, enabling the team to create performant and platform-specific applications. Native development ensured seamless integration with device features, enhanced performance, and a superior user experience. </li>
																					 	<li> Seamless Integration To achieve real-time communication and updates between the web and mobile applications, Leo Coders implemented APIs and web services. These APIs facilitated data synchronization, enabling customers to place orders, vendors to receive and process them, and drivers to track and deliver them efficiently. The integration ensured that all stakeholders remained updated on order status, ensuring smooth operations and improved customer satisfaction. </li>
																					 </ol>
																					 ',
																					 'project_duration' => 'The entire project was completed within a specified timeline, leveraging the expertise of a skilled development team. The team consisted of frontend and backend developers, mobile app developers, UI/UX designers, and quality assurance testers. Following an agile development methodology, regular communication with DrapShop facilitated iterative feedback and ensured timely project delivery',
																					 'development_conclusion' => 'Leo Coders successfully delivered a robust web and mobile application for DrapShop\'s food delivery service. By utilizing Bootstrap and Laravel for the web application and adopting native development for Android and iOS, the team created a seamless platform for customers, vendors, and drivers. The intuitive user interfaces, comprehensive features, and real-time communication capabilities facilitated a smooth and efficient food ordering and delivery process. With the successful implementation of the project requirements and seamless integration, DrapShop\'s food delivery service gained a competitive edge in the market, enhancing customer satisfaction and streamlining operations. ',
																					 'category'			 => 'Mobile App',
																					 'project_link'  => '#',
																					 'post_thumb'	 	 => 'drapshop.png'
																				 ),

																				array(
																					 'post_title' 	 => 'Building an E-Commerce Website for Sinten: A WordPress Case Study',
																					 'post_excerpt' => '',
																					 'post_content'  => 'Leo Coders was approached by Sinten to develop an e-commerce website using WordPress. The client required a robust platform that would facilitate online product sales and provide a personalized shopping experience for their customers. In addition to incorporating a Quiz plugin to suggest the best suitable products, Sinten also desired the integration of various plugins such as AutomateWoo, WooCommerce Smart Coupons, WooCommerce Ultimate Points And Rewards, YITH WooCommerce Quick View, YITH WooCommerce Wishlist, and Yoast SEO. Furthermore, Leo Coders developed a custom module that allowed users to select specific areas on a Singapore map, displaying all available play areas in the selected location. This case study highlights Leo Coders\' approach in meeting Sinten\'s requirements and implementing the desired features. ',
																					 'requirement'	=> 'Sinten outlined the following requirements for their e-commerce website:
																						 <ol>
																						 	<li>E-Commerce Functionality: A robust platform capable of showcasing and selling products online. </li>
																						 	<li>Quiz Plugin Integration: Incorporation of a Quiz plugin to assess customer preferences and suggest the most suitable products.</li>
																						 	<li>Plugin Integration: Integration of plugins such as AutomateWoo, WooCommerce Smart Coupons, WooCommerce Ultimate Points And Rewards, YITH WooCommerce Quick View, YITH WooCommerce Wishlist, and Yoast SEO. </li>
																						 	<li>Seamless Integration: The applications should be interconnected, facilitating real-time updates between customers, vendors, and drivers.</li>
																						 	<li>Custom Module: Development of a custom module allowing users to select specific areas on a Singapore map and displaying available play areas in the selected location. </li>
																						 </ol>
																						 ',
																					 // 'technology_selection' => '',
																					 'development_process' => 'Leo Coders followed a comprehensive development process to meet Sinten\'s requirements:
																					 <ol>
																					 	<li> <strong>WordPress Website Development</strong> <br> The development team utilized WordPress, a flexible and user-friendly content management system, to create the e-commerce website. They selected a suitable theme and customized it to match Sinten\'s brand identity. The website included essential e-commerce features such as product listings, shopping cart functionality, secure payment gateways, and a user-friendly checkout process.  </li>
																					 	<li><strong>Quiz Plugin Integration</strong> <br> Leo Coders integrated a Quiz plugin into the website to enable personalized product suggestions. Collaborating closely with Sinten, the team defined quiz questions and criteria for product recommendations based on customer responses. The plugin was seamlessly integrated, allowing users to complete the quiz and receive tailored product recommendations based on their preferences. </li>
																					 	<li><strong>Plugin Integration</strong><br> To enhance the website\'s functionality, Leo Coders integrated various plugins as requested by Sinten. These included AutomateWoo for automated marketing and customer retention, WooCommerce Smart Coupons for discount and coupon management, WooCommerce Ultimate Points And Rewards for loyalty programs, YITH WooCommerce Quick View for quick product previews, YITH WooCommerce Wishlist for user wishlists, and Yoast SEO for search engine optimization.  </li>
																					 	<li><strong>Custom Module Development</strong><br>Leo Coders developed a custom module to cater to the unique requirement of allowing users to select specific areas on a Singapore map. Leveraging JavaScript or other suitable technologies, the team implemented an interactive map feature. When a user selects an area, the website dynamically fetches and lists all available play areas in the chosen location, providing a seamless user experience. </li>
																					 </ol>
																					 ',
																					 'project_duration' => 'The entire project was completed within a specified timeline, leveraging the expertise of a skilled development team. The team consisted of frontend and backend developers, mobile app developers, UI/UX designers, and quality assurance testers. Following an agile development methodology, regular communication with DrapShop facilitated iterative feedback and ensured timely project delivery',
																					 'development_conclusion' => 'Leo Coders successfully developed a robust e-commerce website for Sinten, leveraging WordPress and incorporating essential features and requested plugins. The integration of the Quiz plugin enables a personalized shopping experience by suggesting the best suitable products to customers. The inclusion of plugins such as AutomateWoo, WooCommerce Smart Coupons, WooCommerce Ultimate Points And Rewards, YITH WooCommerce Quick View',
																					 'category'			 => 'Wordpress',
																					 'project_link'  => '#',
																					 'post_thumb'	 	 => 'sinten.png'
																				 ),

																				array(
																					 'post_title' 	 => 'Developing a Chatbot SaaS Application and Mobile Application for WeConnet.chat: A Case Study',
																					 'post_excerpt' => '',
																					 'post_content'  => 'WeConnet.chat approached Leo Coders with the requirement of developing a Chatbot SaaS Application and a Mobile Application for Agents. The goal was to create a robust platform that leveraged React and Websocket technology to provide seamless communication between users and the chatbot. The project involved a dedicated team of six members and presented challenges such as smooth Websocket integration, developing a global script compatible with various websites, and creating a custom chatbot with 21 different types of components. The application was divided into two parts, Raibu and Kawai. This case study outlines Leo Coders\' approach to successfully delivering the project. ',
																					 'requirement'	=> 'WeConnet.chat outlined the following requirements for their Chatbot SaaS Application and Mobile Application:
																						 <ol>
																						 	<li>Chatbot SaaS Application: A robust platform allowing users to deploy and manage chatbots. </li>
																						 	<li> Mobile Application for Agents: A mobile application that enables agents to interact with customers through the chatbot. </li>
																						 	<li>Technology Stack: Utilization of React and Websocket for real-time communication and an enhanced user experience.</li>
																						 	<li>Smooth Websocket Integration: Ensuring seamless integration of Websocket technology to facilitate uninterrupted communication between users and the chatbot. </li>
																						 	<li>Global Script Development: Creating a global script that is compatible with various types of websites and easy to install.</li>
																						 	<li>Custom Chatbot with 21 Components: Designing and implementing a chatbot that incorporates 21 different types of components to enhance its functionality and versatility.</li>
																						 </ol>
																						 ',
																					 // 'technology_selection' => '',
																					 'development_process' => 'Leo Coders followed a systematic development process to meet WeConnet.chat\'s requirements:
																					 <ol>
																					 	<li><strong>Technology Selection</strong> <br> Based on the client\'s requirements, Leo Coders chose React for building the user interfaces of the Chatbot SaaS Application and the Mobile Application for Agents. The team selected Websocket technology to enable real-time communication between users and the chatbot, ensuring a seamless and responsive user experience. </li>
																					 	<li><strong>Websocket Integration</strong> <br>Leo Coders meticulously integrated Websocket technology into the application, ensuring smooth communication flow between users and the chatbot. The team implemented error handling mechanisms and optimized the performance of the Websocket connection to provide a reliable and efficient communication channel. </li>
																					 	<li><strong>Global Script Development</strong><br> To meet the requirement of a global script compatible with various websites, Leo Coders developed a flexible and easily installable script. The team ensured that the script could be seamlessly integrated into different types of websites, providing consistent chatbot functionality and user experience across multiple platforms. </li>
																					 	<li><strong>Custom Chatbot Development</strong><br>Leo Coders designed and implemented a custom chatbot that incorporated 21 different types of components. The team developed these components to enhance the chatbot\'s capabilities, allowing it to handle a wide range of user queries, provide personalized responses, and facilitate smooth customer-agent interactions. </li>
																					 </ol>
																					 ',
																					 'project_duration' => 'The project was completed within the specified timeline, utilizing the expertise of a dedicated team consisting of frontend and backend developers, UI/UX designers, and quality assurance testers. Regular communication and collaboration with WeConnet.chat ensured iterative feedback loops, timely updates, and successful project delivery. ',
																					 'development_conclusion' => 'Leo Coders successfully developed the Chatbot SaaS Application and the Mobile Application for Agents for WeConnet.chat. By leveraging React and Websocket technology, the applications provided a seamless and interactive user experience. The integration of Websocket technology facilitated uninterrupted communication between users and the chatbot, ensuring efficient customer-agent interactions. The development of a global script enabled easy installation across various websites, while the custom chatbot with 21 different components enhanced its versatility and functionality. Overall, Leo Coders delivered a robust and user-friendly solution that met WeConnet.chat\'s requirements, empowering them to provide exceptional chatbot services to their customers. ',
																					 'category'			 => 'Saas Application',
																					 'project_link'  => '#',
																					 'post_thumb'	 	 => 'weconnect-app.png'
																				 ),

																				array(
																					 'post_title' 	 => 'Building a WordPress Website for WeConnect.chat: A Case Study',
																					 'post_excerpt' => '',
																					 'post_content'  => 'WeConnect.chat engaged Leo Coders to develop a WordPress website that showcased their chatbot services and allowed visitors to purchase subscription plans. The objective was to create an informative and user-friendly platform where visitors could access relevant information, explore available chatbot templates for plug-and-play use, and access a knowledge base to learn more about chatbots and their usage. This case study outlines Leo Coders approach to fulfilling WeConnect.chat\'s requirements and delivering a compelling WordPress website. ',
																					 'requirement'	=> 'WeConnect.chat specified the following requirements for their WordPress website:
																						 <ol>
																						 	<li>Informative Website: A platform where visitors could access information about WeConnect.chat\'s chatbot services, subscription plans, and features. </li>
																						 	<li>Subscription Plan Purchase: Integration of an e-commerce functionality to enable visitors to purchase subscription plans directly from the website. </li>
																						 	<li>Chatbot Template Showcase: A dedicated section showcasing available chatbot templates that visitors could utilize for plug-and-play implementation. </li>
																						 	<li>Knowledge Base: Inclusion of a knowledge base section providing resources and information about chatbots, including usage guidelines and best practices. </li>
																						 </ol>
																						 ',
																					 // 'technology_selection' => '',
																					 'development_process' => 'Leo Coders followed a systematic development process to meet WeConnect.chat\'s requirements:
																					 <ol>
																					 	<li><strong>WordPress Website Development</strong> <br>The development team utilized WordPress, a versatile and user-friendly content management system, to build the website. They selected a suitable theme and customized it to align with WeConnect.chat\'s branding and design preferences. The website design focused on delivering a clean and intuitive user interface to enhance the overall user experience. </li>
																					 	<li><strong>Subscription Plan Integration</strong><br>Leo Coders integrated an e-commerce functionality into the website to facilitate the purchase of subscription plans. They leveraged appropriate plugins and implemented secure payment gateways to ensure a seamless and secure transaction process for visitors.</li>
																					 	<li><strong>Chatbot Template Showcase</strong><br>To showcase the available chatbot templates, Leo Coders created a dedicated section where visitors could explore and preview different templates. The team developed a visually appealing and interactive layout that allowed users to view the features and benefits of each template, making it easier for them to select a suitable option for their needs.</li>
																					 	<li><strong>Knowledge Base Development</strong><br>Leo Coders developed a comprehensive knowledge base section where visitors could access information about chatbots. This section provided resources, guidelines, and best practices for using chatbots effectively. The team organized the content in a user-friendly manner, enabling visitors to find relevant information quickly and easily. </li>
																					 </ol>
																					 ',
																					 'project_duration' => 'The project was completed within the agreed timeline, with Leo Coders assigning a dedicated team consisting of web developers, designers, content creators, and quality assurance testers. Regular communication and collaboration with WeConnect.chat ensured that the website met their requirements and aligned with their branding guidelines.',
																					 'development_conclusion' => 'Leo Coders successfully developed a WordPress website for WeConnect.chat, incorporating essential features to meet the client\'s requirements. The informative platform provided visitors with detailed information about WeConnect.chat\'s chatbot services and subscription plans. The e-commerce functionality enabled seamless subscription plan purchases directly from the website. The chatbot template showcase section facilitated plug-and-play implementation, allowing users to select templates that suited their specific needs. Additionally, the knowledge base section provided valuable resources and information about chatbots, helping users understand their benefits and usage. By following a systematic development process and ensuring regular collaboration with the client, Leo Coders delivered a visually appealing and user-friendly WordPress website that effectively showcased WeConnect.chat\'s services and empowered visitors to explore and engage with their chatbot solutions. ',
																					 'category'			 => 'Wordpress',
																					 'project_link'  => '#',
																					 'post_thumb'	 	 => 'weconnect.png'
																				 ),
																	),
		);

		$all_post_types_post_cats = array(
							'leocoders_services' => array(
											'Digital Marketing' => array(
												'cat_desc' => 'Managing Social Media Marketing & Lead Generation',
												'cat_img' => 'digital_marketing.jpg',
											),
											'Digital Technology' => array(
												'cat_desc' => 'Enabling ECommerce, Web Apps & Mobile Apps',
												'cat_img' => 'digital_technology.jpg',
											),
											'Digital Design' => array(
												'cat_desc' => 'Delivering UI/UX, Website Design & Development',
												'cat_img' => 'digital_design.jpg',
											),
									)
								);

		foreach ($all_post_types_post as $post_types => $all_posts) {

			//  insert cats
			foreach ($all_post_types_post_cats as $cats_post_types => $all_cats) {
				foreach ($all_cats as $cat_name => $cat_details) {

					if ($cats_post_types == $post_types ) {

						$term = term_exists( $cat_name, $cats_post_types.'_category' );

						if (! $term) {
							$services_cats	=	wp_insert_term(
									 $cat_name, // the term
									 $cats_post_types.'_category', // the taxonomy
									 array(
										 'description'=> $cat_details['cat_desc'],
										 'slug' => strtolower(str_replace(' ', '_', $cat_name))
									 )
									);

									// add category image START
									$image_url = get_template_directory_uri().'/assets/images/leocoders_services/'.strtolower(str_replace(' ', '_', $cat_name)).'.jpg';

									$image_name= strtolower(str_replace(' ', '_', $cat_name)) . '.png';
									$upload_dir       = wp_upload_dir();
									$image_data       = file_get_contents($image_url);
									$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
									$filename= basename( $unique_file_name );
									if( wp_mkdir_p( $upload_dir['path'] ) ) {
										$file = $upload_dir['path'] . '/' . $filename;
									} else {
										$file = $upload_dir['basedir'] . '/' . $filename;
									}
									file_put_contents( $file, $image_data );
									$wp_filetype = wp_check_filetype( $filename, null );
									$attachment = array(
										'post_mime_type' => $wp_filetype['type'],
										'post_title'     => sanitize_file_name( $filename ),
										'post_content'   => '',
										'post_type'     => '',
										'post_status'    => 'inherit'
									);
									$attach_id = wp_insert_attachment( $attachment, $file );
									$attachment_url = wp_get_attachment_url( $attach_id );
									$term_meta_id = add_term_meta( $services_cats['term_id'], 'leocoders_services_category-image-id', $attach_id, true );

							}else {
								$services_cats = get_term_by('name', $cat_name, $cats_post_types.'_category');
							}
							// add category image END
						}
				}
			}


			foreach ($all_posts as $post_data) {

					$post_obj = array(
					'post_title'    => wp_strip_all_tags( $post_data['post_title'] ),
					'post_content'  => $post_data['post_content'],
					'post_excerpt'  => $post_data['post_excerpt'],
					'post_status'   => 'publish',
					'post_type'     => $post_types,
			 );

			 // Insert the post into the database
			 if (! post_exists( $post_data['post_title'] )) {
				 $rp_leocoders_post_id = wp_insert_post( $post_obj );
				 $meta_fields = array(
					 								'designation',
					 								'ratings',
					 								'button_text',
													'button_link',
													'experience',
													'job_type',
													'key_skills',
													'apply_now',
													'category',
													'requirement',
													'technology_selection',
													'development_process',
													'project_duration',
													'development_conclusion',
													'project_link',
													'offer_text',
													'launching_date',
				 							);
							foreach ($meta_fields as $meta_field ) {
								if($post_data[$meta_field] != '' ){
									update_post_meta( $rp_leocoders_post_id, $post_types.'_'. $meta_field, $post_data[$meta_field] );
								}
							}

				 if ($post_data['job_type'] !='') {
					 $job_type = sanitize_text_field( $post_data['job_type'] );
					 update_post_meta( $rp_leocoders_post_id, '_leocoders_jobs_job_type', $job_type );
				 }

				 if($post_data['roles_and_reponsibilities'] != '' ){
					 update_post_meta( $rp_leocoders_post_id, 'leocoders_jobs_roles_and_reponsibilities', $post_data['roles_and_reponsibilities']);
				 }

				 if($post_data['key_skills'] != '' ){
					 update_post_meta( $rp_leocoders_post_id, 'leocoders_jobs_key_skills', $post_data['key_skills']);
				 }

				 $post = get_page_by_title($post_data['form'], OBJECT, 'wpcf7_contact_form');
				 if($post_data['form'] != '' ){
					 update_post_meta( $rp_leocoders_post_id, 'leocoders_products_form', $post->ID);
				 }


				 // update_post_meta( $post_id, 'leocoders_jobs_roles_and_reponsibilities', $new );

				 if ($post_data['post_thumb'] !='') {
					 $image_url = rj_bookmark_theme_path .'/assets/images/'.$post_types.'/'.$post_data['post_thumb'];

					 $image_name       = $post_data['post_thumb'];
					 $upload_dir       = wp_upload_dir(); // Set upload folder
					 $image_data       = file_get_contents($image_url); // Get image data
					 $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
					 // Generate unique name
					 $filename         = basename( $unique_file_name ); // Create image file name

					 // Check folder permission and define file location
					 if( wp_mkdir_p( $upload_dir['path'] ) ) {
						 $file = $upload_dir['path'] . '/' . $filename;
					 } else {
						 $file = $upload_dir['basedir'] . '/' . $filename;
					 }

					 // Create the image  file on the server
					 file_put_contents( $file, $image_data );

					 // Check image file type
					 $wp_filetype = wp_check_filetype( $filename, null );

					 // Set attachment data
					 $attachment = array(
						 'post_mime_type' => $wp_filetype['type'],
						 'post_title'     => sanitize_file_name( $filename ),
						 'post_content'   => '',
						 'post_type'     => $post_types,
						 'post_status'    => 'inherit'
					 );

					 // Create the attachment
					 $attach_id = wp_insert_attachment( $attachment, $file, $rp_leocoders_post_id );
					 // Include image.php
					 require_once(ABSPATH . 'wp-admin/includes/image.php');
					 // Define attachment metadata
					 $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
					 // Assign metadata to attachment
					 wp_update_attachment_metadata( $attach_id, $attach_data );
					 // And finally assign featured image to post
					 set_post_thumbnail( $rp_leocoders_post_id, $attach_id );
				 }
		 		}
			}
		}

		//  career page
		set_theme_mod('rp_leocoders_pro_career_main_heading', 'Current Openings');
		set_theme_mod('rp_leocoders_pro_jobs_roles_responsibility_head', 'Roles & Responsibilities:');
		set_theme_mod('rp_leocoders_pro_jobs_key_skills_head', 'Key Skill:');
		set_theme_mod('rp_leocoders_pro_career_apply_form_heading', 'Apply Now:');

		// if( ! file_exists( $this->widget_file_url ) ) {
		// 	// If the file doesn't exist, this step will just complete
		// 	wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'File does not exist' ) ) );
		// }

		$this->theme_create_primary_nav_menu();
		$this->theme_create_prodcut_nav_menu();

		$Whizzie_Widget_Importer = new Whizzie_Widget_Importer;
		$results = $Whizzie_Widget_Importer->import_widgets( $this->widget_file_url );
		// wp_send_json( $results );


		exit;
	}

}
