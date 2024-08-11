<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kare
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<div class="channel-kare">
		<div class="center-flex">
			<button aria-label="button" type="button" id="btnSkipArrow" class="w-btn skip-arrow">
				<img src="<?php echo get_template_directory_uri();?>/dist/images/up-arrow-white.png" alt="Skip arrow to the top of the page" width="24" height="24" class="arrow-up">
			</button>
		</div>
	</div>
	<main id="main-site" >
		<header id="masthead" class="site-header">
			<nav class="nav-top-wrapper">
				<div class="search-site w-1-3">
					<button aria-label="button" type="button" id="btn-header-search" class="w-btn search">
						<span>search...</span>
					</button>
				</div>
				<div class="logo-site center-flex w-1-3">
					<a href="/" aria-current="page" title="KARE - Buy KARE furniture" class="logo-kare">
						<img src="<?php echo get_template_directory_uri();?>/dist/images/logo-kare.png" alt="KARE - Buy KARE furniture" width="169" height="30" class="text-red">
					</a>
				</div>
				<div class="justify-end w-1-3">
					<button aria-label="Wishlist" type="button" class="l-btn">
						<span>Wishlist</span>
					</button>
					<button aria-label="My account" type="button" class="l-btn open_account_popup">
						<span><?php esc_html_e( 'My account', 'kare' ); ?></span>
					</button>
					<a href="/cart" aria-label="link" class="w-btn">
						<!-- <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" class="-m-1 svg-icon sprite-icons"><use href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-cart" xlink:href="/_nuxt/41a1314fa8f2980ef26b9b83aa0c0cd1.svg#i-cart"></use></svg>  -->
						<span class="shopping-cart-btn">Shopping Cart</span> 
					</a>
				</div>
			</nav>
			<!-- <div class="site-btn">
				<button href="https://www.kare.de/" class="btn-test">btn more</button>
			</div> -->
			<div id="site-navigation" class="main-navigation header-menu header-menu-hovered">
				<div class="header-menu-tabs-wrapper">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'header-menu-button w-btn',
						)
					);
					?>
				</div>
			</div><!-- #site-navigation -->

			<!-- Popup Sidebar -->
			<div id="login_sidebar" class="login_sidebar">
				<div class="sidebar-content">
					<div class="top_sidebar">
						<button id="close_sidebar" class="close">&times;</button>
						<h2><?php esc_html_e( 'Login', 'kare' ); ?></h2>
					</div>
					<?php 
					// Check if user is not logged in before showing the form
					if ( !is_user_logged_in() ) { 
						// Display WooCommerce login form
						woocommerce_login_form(); 
					} else {
						// Optionally, you can display a message or redirect if the user is already logged in
						echo '<p>You are already logged in.</p>';
					}
					?>
				</div>
			</div>
		</header><!-- #masthead -->