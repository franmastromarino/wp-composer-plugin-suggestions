<?php

namespace QuadLayers\WP_Plugin_Suggestions;

require_once ABSPATH . 'wp-admin/includes/class-wp-plugin-install-list-table.php';

class Table extends \WP_Plugin_Install_List_Table {

	/**
	 * @var array
	 */
	private $plugins_data = array();

	public function __construct( array $plugins_data = array() ) {
		$this->plugins_data = $plugins_data;
		parent::__construct();
	}

	public function self_admin_url( $url, $path ) {
		if ( strpos( $url, 'tab=plugin-information' ) !== false ) {
			$url = network_admin_url( $path );
		}

		return $url;
	}

	public function network_admin_url( $url, $path ) {
		if ( strpos( $url, 'plugins.php' ) !== false ) {
			$url = self_admin_url( $path );
		}
		return $url;
	}

	public function display_rows() {
		add_filter( 'self_admin_url', array( $this, 'self_admin_url' ), 10, 2 );
		add_filter( 'network_admin_url', array( $this, 'network_admin_url' ), 10, 2 );
		parent::display_rows();
	}

	private function get_transient_key() {

		$key = md5( serialize( $this->plugins_data ) );

		return 'quadlayers_plugin_suggestions_' . $key;
	}

	private function remove_excluded_plugins( $plugins ) {

		if ( empty( $this->plugins_data['exclude'] ) ) {
			return $plugins;
		}

		foreach ( $plugins as $key => $plugin ) {
			if ( in_array( $plugin['slug'], $this->plugins_data['exclude'] ) ) {
				unset( $plugins[ $key ] );
			}
		}

		return $plugins;
	}

	public function get_plugins() {

		$tk = $this->get_transient_key();

		$plugins = get_transient( $tk );

		if ( $plugins === false ) {

			$args = array(
				'per_page' => $this->plugins_data['per_page'],
				'author'   => $this->plugins_data['author'],
				'locale'   => get_user_locale(),
			);

			$api = plugins_api( 'query_plugins', $args );

			if ( ! is_wp_error( $api ) ) {

				$plugins = $this->remove_excluded_plugins( $api->plugins );

				set_transient( $tk, $plugins, 24 * HOUR_IN_SECONDS );
			}
		}

		return $plugins;
	}

	public function prepare_items() {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		global $tabs, $tab;

		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'plugin-install' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'updates' );
		wp_reset_vars( array( 'tab' ) );

		$tabs = array();

		if ( 'search' === $tab ) {
			$tabs['search'] = esc_html__( 'Search Results' );
		}
		if ( $tab === 'beta' || false !== strpos( get_bloginfo( 'version' ), '-' ) ) {
			$tabs['beta'] = _x( 'Beta Testing', 'Plugin Installer' );
		}
		$tabs['featured']    = _x( 'Featured', 'Plugin Installer' );
		$tabs['popular']     = _x( 'Popular', 'Plugin Installer' );
		$tabs['recommended'] = _x( 'Recommended', 'Plugin Installer' );
		$tabs['favorites']   = _x( 'Favorites', 'Plugin Installer' );

		$nonmenu_tabs = array( 'plugin-information' ); // Valid actions to perform which do not have a Menu item.

		$tabs = apply_filters( 'install_plugins_tabs', $tabs );

		$nonmenu_tabs = apply_filters( 'install_plugins_nonmenu_tabs', $nonmenu_tabs );

		// If a non-valid menu tab has been selected, And it's not a non-menu action.
		if ( empty( $tab ) || ( ! isset( $tabs[ $tab ] ) && ! in_array( $tab, (array) $nonmenu_tabs ) ) ) {
			$tab = key( $tabs );
		}

		$this->items = $this->get_plugins();

		wp_localize_script(
			'updates',
			'_wpUpdatesItemCounts',
			array(
				'totals' => wp_get_update_data(),
			)
		);
	}
}
