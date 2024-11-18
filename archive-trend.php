<?php get_header(); ?>
<section class="section_wrap breadcrumb_section">
	<div class="woocommerce-breadcrumb">
		<?php
		if ( function_exists( 'woocommerce_breadcrumb' ) ) {
			woocommerce_breadcrumb(array(
				'delimiter'   => '&nbsp;&bull;&nbsp;',
				'wrap_before' => '<nav class="w-breadcrumb">',
				'wrap_after'  => '</nav>',
				'before'      => '',
				'after'       => '',
				'home'        => _x( 'Home Page', 'breadcrumb', 'woocommerce' ),
			));
		}
		?>
	</div>
</section>