<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package kare
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<h2 class="page-title"><?php esc_html_e( '404', 'kare' ); ?></h2>
			<h3 class="more-page-title"><?php esc_html_e( 'Page not found', 'kare' ); ?></h3>
			<a href="<?php echo esc_url( apply_filters( 'wpml_home_url', home_url() ) ); ?>" aria-current="page" aria-label="link" class="link-home-page" title="Back to home page">
				<span><?php esc_html_e( 'Back to home page', 'kare' ); ?></span>  
			</a>
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
