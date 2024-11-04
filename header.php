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
				<?php echo str_replace('<svg', '<svg class="arrow-up w-dir-up"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-right-md.svg')); ?>
			</button>
		</div>
	</div>
	<main id="main-site" >
		<header id="masthead" class="site-header">
			<nav class="nav-top-wrapper">
				<div class="search-site w-1-3">
					<button aria-label="button" type="button" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/menu.svg'); ?>
					</button>
					<div id="btn-header-search" class="search">
						<!-- <div class="visible-desktop"> -->
						<?php echo str_replace('<svg', '<svg class="search-svg-btn"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/search.svg')); ?>
						<!-- </div> -->
						<!-- <span><?php //esc_html_e( 'search...', 'kare' ); ?></span> -->
						<!-- <?php //echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/search.svg'); ?> -->
						<?php echo get_product_search_form(); ?>
					</div>
				</div>
				<div class="logo-site center-flex w-1-3">
					<a href="/" aria-current="page" title="KARE - Buy KARE furniture" class="logo-kare">
						<img src="<?php echo get_template_directory_uri();?>/dist/images/logo-kare.png" alt="KARE - Buy KARE furniture" width="169" height="30" class="text-red">
					</a>
				</div>
				<div class="justify-end w-1-3">
					<button aria-label="Wishlist" type="button" class="wishlist l-btn open_wishlist_popup">
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/heart.svg'); ?>
						<span><?php esc_html_e( 'Wishlist', 'kare' ); ?></span>
					</button>
					<button aria-label="My account" type="button" class="l-btn open_account_popup">
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/user.svg'); ?>
						<span><?php esc_html_e( 'Account', 'kare' ); ?></span>
					</button>
					<a href="<?php echo wc_get_cart_url(); ?>" aria-label="link" class="shopping-cart <?php echo is_cart() ? 'cart' : ''; ?>">
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/cart.svg'); ?>
						<span class="shopping-cart-btn"><?php esc_html_e( 'Shopping Cart', 'kare' ); ?></span> 
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

			<!-- Popup Wishlist -->
			<div id="wishlist_sidebar" class="wishlist_sidebar">
				<div class="sidebar-content">
					<div class="top_sidebar">
						<h2><?php echo (!is_user_logged_in()) ? __( 'Login', 'kare' ) :  __( 'Wishlist', 'kare' ); ?></h2>
						<button id="close_wishlist" class="close">
							<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/close.svg" alt="<?php esc_html_e( 'close whishlist sidebar', 'kare' ) ?>" width="18" height="18">
						</button>
					</div>			
					<?php 
					// Check if user is not logged in before showing the form
					if ( !is_user_logged_in() ) :  ?>
						<div class="bottom_sidebar">
							<p><?php echo get_field('txt_before_login','option'); ?></p>
							<?php 
							woocommerce_login_form();?>
							<a class="register_page" href="/register"><?php esc_html_e( 'Create new account', 'kare' ); ?></a>
							<div class="accordion_details_wrapper">
								<div class="accordion_item">
									<div class="accordion_title">
										<button type="button" aria-label="button" class="accordion_question">
											<span class="title"><?php echo get_field('why_register','option') ?></span>
											<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" width="24" height="24"><path fill="currentColor" d="M7 10l5 5 5-5z"/></svg>
										</button>
									</div>
									<div class="accordion_content">
										<div class="accordion_answer">
											<p><?php echo get_field('why_register_response','option'); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					<?php else : ?>
						<div class="bottom_sidebar">
							<div class="wishlist_popup_content"> 
								<button type="button" aria-label="button" class="share_wishlist_btn">
									<span><?php esc_html_e( 'Copy your wishlist URL and share it!', 'kare' ); ?></span>
									<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/share-alt.svg'); ?>
								</button>
								<div class="wishlist_content">
									<?php echo do_shortcode('[yith_wcwl_wishlist]'); ?>
								</div>
							</div>
						</div>

					<?php endif;
					?>
					

				</div>
				<div id="copy-notification" style="display: none;"><?php esc_html_e( 'The wishlist URL has been copied to the clipboard', 'kare' ); ?></div>
			</div>

			<!-- Popup Sidebar -->
			<div id="login_sidebar" class="login_sidebar">
				<div class="sidebar-content">
					<div class="top_sidebar">
						<h2><?php echo (!is_user_logged_in()) ? __( 'Login', 'kare' ) :  __( 'Your customer account', 'kare' ); ?></h2>
						<button id="close_sidebar" class="close">
							<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/close.svg" alt="<?php esc_html_e( 'close sidebar', 'kare' ) ?>" width="18" height="18">
						</button>
					</div>
					
					<?php 
					// Check if user is not logged in before showing the form
					if ( !is_user_logged_in() ) {  ?>
						<div class="bottom_sidebar">
							<p><?php echo get_field('txt_before_login','option'); ?></p>
							<?php 
							woocommerce_login_form();?>
							<a class="register_page" href="/register"><?php esc_html_e( 'Create new account', 'kare' ); ?></a>
							<div class="accordion_details_wrapper">
								<div class="accordion_item">
									<div class="accordion_title">
										<button type="button" aria-label="button" class="accordion_question">
											<span class="title"><?php echo get_field('why_register','option') ?></span>
											<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" width="24" height="24"><path fill="currentColor" d="M7 10l5 5 5-5z"/></svg>
										</button>
									</div>
									<div class="accordion_content">
										<div class="accordion_answer">
											<p><?php echo get_field('why_register_response','option'); ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					<?php } else {?>
						<div class="my_account_menu_wrapper">
							<?php do_action( 'woocommerce_account_navigation' ); ?>
						</div>
						<div class="bottom_sidebar">
							<p class="contact_title"><?php esc_html_e( "contact", "kare" ); ?></p>
							<button class="contact_mail">
								<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/mail.svg" alt="" width="20" height="20">
								<a href="mailto:<?php echo get_field('contact_email','option'); ?>"  target="_blank"><?php echo get_field('contact_email','option'); ?></a>
							</button>
						</div>
						<nav class="woocommerce-MyAccount-navigation">
							<ul>
								<li class="woocommerce-MyAccount-navigation-link custom_logout">
									<a href="javascript:void(0)">
										<?php esc_html_e( 'Logout', 'woocommerce' ); ?>
									</a>
								</li>
							</ul>
						</nav>
					<?php }
					?>
					

				</div>
			</div>

			<!-- Popup Mobile Menu -->
			<div id="mobile_menu_sidebar" class="mobile_menu_sidebar open_left">
				<div class="sidebar-content">
					<div class="top_sidebar">
						<h2><?php echo __( 'all categories', 'kare' ); ?></h2>
						<button id="close_menu" class="close">
							<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/close.svg" alt="<?php esc_html_e( 'close mobile menu', 'kare' ) ?>" width="18" height="18">
						</button>
					</div>			
					<div class="bottom_sidebar">
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
				</div>
			</div>
		</header><!-- #masthead -->


		<div class="logout_popup">
			<div class="logout_popup_content">
				<p><?php esc_html_e( 'Would you like to unsubscribe?', 'kare' ); ?></p>
				<div class="popup_btns">
					<button id="confirm_logout"><?php esc_html_e( 'OK', 'kare' ); ?></button>
					<button id="cancel_logout"><?php esc_html_e( 'CANCEL', 'kare' ); ?></button>
				</div>
				
			</div>
		</div>

		 <!-- Display the WooCommerce mini cart -->
		<div class="mini_cart_sidebar" id="modal_mini_cart">
			<div class="sidebar-content">
				<div class="top_sidebar">
					<div class="top_sidebar_title">
						<h2><?php echo __( 'Product overview', 'kare' ); ?></h2>
						<button id="close_mini_cart" class="close">
							<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/close.svg" alt="<?php esc_html_e( 'close mini cart', 'kare' ) ?>" width="18" height="18">
						</button>
						
					</div>
					<div id="wc-add-product-notices"></div>	
					<a href="<?php echo wc_get_cart_url(); ?>" aria-label="link" class="continue_shop_btn" alt="<?php esc_html_e( 'Continue to shopping cart', 'kare' ) ?>">
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/cart.svg'); ?>

						<span><?php esc_html_e( 'Continue to shopping cart', 'kare' ); ?></span>
					</a>
				</div>	
				<div class="bottom_sidebar widget_shopping_cart_content">
					<?php  woocommerce_mini_cart(); ?>
				</div>
			</div>
		</div>