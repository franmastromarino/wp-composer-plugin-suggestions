<?php

namespace QuadLayers\WP_Plugin_Suggestions;

use QuadLayers\WP_Plugin_Suggestions\Page;

class Load {

	/**
	 * @var array
	 */
	private $plugin_data = array(
		'author'           => 'quadlayers',
		'per_page'         => 36,
		'exclude'          => array(),
		'parent_menu_slug' => null,
		'promote_links'    => array(),
	);

	/**
	 * Page model
	 *
	 * @var Page
	 */
	public $page = null;

	public function __construct( array $plugin_data = array() ) {
		/**
		 * Merge plugin data with default data
		 */
		$this->plugin_data = wp_parse_args(
			$plugin_data,
			$this->plugin_data
		);

		$this->page = new Page( $this->plugin_data );
	}

}
