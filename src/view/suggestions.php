<style>
	@media screen and (max-width: 2299px) and (min-width: 1600px) {
		#the-list {
			display: flex;
			flex-wrap: wrap;
		}
		.plugin-card {
			margin: 8px !important;
			width: calc(50% - 4px - 16px) !important;
		}
		.wrap {
			position: relative;
			margin: 25px 40px 0 20px;
			max-width: 1200px;
		}
	}
</style>
<div class="wrap about-wrap full-width-layout">
	<h1>
		<?php esc_html_e( 'Suggestions', 'wp-plugin-suggestions' ); ?>
	</h1>
	<p class="about-text">
		<?php printf( esc_html__( 'Thanks for using our product! We recommend these extensions that will add new features to stand out your business and improve your sales.', 'wp-plugin-suggestions' ), esc_html( PWB_PLUGIN_NAME ) ); ?>
	</p>
	<p class="about-text">
		<?php printf( '<a href="%s" target="_blank">%s</a>', esc_html( PWB_PURCHASE_URL ), esc_html__( 'Purchase', 'wp-plugin-suggestions' ) ); ?></a> |
		<?php printf( '<a href="%s" target="_blank">%s</a>', esc_html( PWB_DOCUMENTATION_URL ), esc_html__( 'Documentation', 'wp-plugin-suggestions' ) ); ?></a>
	</p>
	<?php
		printf(
			'<a href="%s" target="_blank"><div style="
				background: #006bff url(%s) no-repeat;
				background-position: top center;
				background-size: 130px 130px;
				color: #fff;
				font-size: 14px;
				text-align: center;
				font-weight: 600;
				margin: 5px 0 0;
				padding-top: 120px;
				height: 40px;
				display: inline-block;
				width: 140px;
				" class="wp-badge">%s</div></a>',
			'https://quadlayers.com/?utm_source=pwb_admin',
			esc_url( plugins_url( '/assets/img/logo.jpg', PWB_PLUGIN_FILE ) ),
			esc_html__( 'QuadLayers', 'wp-plugin-suggestions' )
		);
		?>
</div>
<div class="wrap">
	<?php
		$wp_list_table->prepare_items();
	?>
	<form id="plugin-filter" method="post" class="importer-item">
		<?php $wp_list_table->display(); ?>
	</form>
</div>
