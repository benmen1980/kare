<?php 

get_header(); 

?>

<?php $archive_title = post_type_archive_title('', false); ?>
<h1><?php echo $archive_title; ?></h1>
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
<div class="container">
	<section class="inspiration_box_wrapper">
		<?php 
		$args = array(
			'post_type' => 'inspiration',
			'posts_per_page' => -1, // Get all posts
		);
		
		$custom_posts_inspirations = new WP_Query($args);

		if ($custom_posts_inspirations->have_posts()) :
			while ($custom_posts_inspirations->have_posts()) : $custom_posts_inspirations->the_post();
				// Get the post ID
				$post_id = get_the_ID();
				$trend_img = get_field('trend_img', $post_id);
				// Check if the value is an ID and convert to URL if needed
				if (is_numeric($trend_img)) {
					$trend_img = wp_get_attachment_url($trend_img);
				}
				$trend_title = get_the_title();?>
				<div class="trend_box_wraper">
					<a href="<?php echo get_the_permalink();?>">
						<img src="<?php echo $trend_img;?>" alt="<?php echo $trend_title;?>" loading="lazy">
						<p><?php echo $trend_title;?></p>
						<button>
							<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/screen-full.svg" alt="" width="24" height="24">
						</button>
					</a>
				</div>
			<?php endwhile;
			wp_reset_postdata();
		else :
			echo 'No posts found';
		endif; ?>
	</section>
</div>

<?php

get_footer();

?>
