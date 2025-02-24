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
	<?php 
	$text_banner_strip = get_field('text_strip','option');
	$link_banner_strip = get_field('url_strip','option');

	if ( $text_banner_strip && $link_banner_strip ) :
	?>
		<div class="banner-strip" style="background-color: <?php echo get_field('bg_color','option')?>;">
			<div class="text-banner">
				<a href="<?php echo $link_banner_strip; ?>" rel="noopener" aria-label="link" class="text-banner-strip" target="_blank" style="color: <?php echo get_field('text_color','option')?>;">
					<span><?php echo $text_banner_strip; ?></span> 
				</a>
			</div>
			<div id="close-banner" class="close-banner" style="color: <?php echo get_field('text_color','option')?>;">
				<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/close.svg'); ?>
			</div>
		</div>
	<?php endif; ?>
	<main id="main-site" >
		<header id="masthead" class="site-header">
			<nav class="nav-top-wrapper">
				<div class="search-site w-1-3">
					<button aria-label="button" type="button" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/menu.svg'); ?>
					</button>
					<div id="btn-header-search" class="search">
						<?php //echo str_replace('<svg', '<svg class="search-svg-btn"', file_get_contents(get_template_directory_uri() . '/dist/images/svg/search.svg')); ?>
						<?php echo do_shortcode('[fibosearch]'); ?>
					</div>
				</div>
				<div class="logo-site center-flex w-1-3">
					<a href="<?php echo esc_url( apply_filters( 'wpml_home_url', home_url() ) ); ?>" aria-current="page" title="KARE - Buy KARE furniture" class="logo-kare">
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
							<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/close.svg'); ?>
						</button>
					</div>			
					<?php 
					// Check if user is not logged in before showing the form
					if ( !is_user_logged_in() ) :  ?>
						<div class="bottom_sidebar">
							<p><?php echo get_field('txt_before_login','option'); ?></p>
							<?php 
							woocommerce_login_form();?>
							<a class="register_page" href="<?php echo apply_filters('wpml_permalink', site_url('/register')); ?>"><?php esc_html_e( 'Create new account', 'kare' ); ?></a>
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
								<?php /*if (function_exists('yith_wcwl_get_products')) :
									$wishlist_products = yith_wcwl_get_products();
									if (!empty($wishlist_products)) :*/ ?>
										<button type="button" aria-label="button" class="share_wishlist_btn">
											<span><?php esc_html_e( 'Copy your wishlist URL and share it!', 'kare' ); ?></span>
											<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/share-alt.svg'); ?>
										</button>
									<?php /*endif;
								endif; */?>
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
							<?php do_action( 'woocommerce_login_form_start' ); ?>
							<?php 
							woocommerce_login_form();?>
							<!-- Display WooCommerce notices (including login errors) -->
							<?php if ( function_exists( 'wc_print_notices' ) && isset($_POST['login']) ) { ?>
								<div class="woocommerce-notices-wrapper">
									<?php wc_print_notices(); ?>
								</div>
							<?php } ?>
							
							<a class="register_page" href="<?php echo apply_filters('wpml_permalink', site_url('/register')); ?>"><?php esc_html_e( 'Create new account', 'kare' ); ?></a>
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
						<div class="custom-reset-password-form">
							<?php  woocommerce_get_template( 'myaccount/form-lost-password.php' ); ?>
						</div>
						<div class="woocommerce-notices-wrapper">
							<?php if ( function_exists( 'wc_print_notices' ) && isset($_POST['wc_reset_password'])  && !isset($_GET['show-reset-form'])) { wc_print_notices(); } ?>
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

			<!-- Filter Sidebar -->
			<div class="filter_sidebar">
				<form id="filter_form" action="<?php echo esc_url($current_url); ?>" method="GET">
					<div class="sidebar-content">
						<div class="top_sidebar">
							<h2><?php echo  __( 'Categories', 'kare' ); ?></h2>
							<button type="button" class="close">
								<img src="<?php echo get_template_directory_uri();?>/dist/images/svg/close.svg" alt="<?php esc_html_e( 'close sidebar', 'kare' ) ?>" width="18" height="18">
							</button>
						</div>
						<div class="content_filter_wrapper">
							<button type="button" class="btn_mobile_filter" data-target="#cat_sidebar">
								<?php echo esc_html__('Categories', 'kare') ?>
								<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
							</button>
					
							<div class="content_sidebar" id="cat_sidebar" data-title="<?php echo __( 'Categories', 'kare' ); ?>">
								
									<?php 
									$current_category = get_queried_object();

									if ( $current_category ) {
										// Get the category ID
										$category_id = $current_category->term_id;
										// $child_categories = get_terms( array(
										// 	'taxonomy' => 'product_cat',
										// 	'parent' => $category_id,
										// 	'hide_empty' => false, // can be set to true if you want to hide empty
										// ) );
										//$child_categories = get_field('child_categories_list', 'product_cat_' . $category_id);
										$english_category_id = apply_filters('wpml_object_id', $category_id, 'product_cat', true, 'en');
										$child_categories = get_field('child_categories_list', 'product_cat_' . $english_category_id);
										if (function_exists('icl_object_id')) {
											$lang = apply_filters('wpml_current_language', NULL); // Get current language
										
											// If in Hebrew, get translated categories
											if ($lang == 'he' && !empty($child_categories)) {
												$translated_categories = [];
										
												foreach ($child_categories as $child_category) {
												
													$child_term = get_term_by('slug', $child_category['slug'], 'product_cat');
													
													if ($child_term) {
														$translated_term_id = apply_filters('wpml_object_id', $child_term->term_id, 'product_cat', true, 'he');
														
														if ($translated_term_id) {
															$translated_term = get_term($translated_term_id, 'product_cat');
															
															if ($translated_term) {
																$translated_categories[] = [
																	'slug' => $translated_term->slug,
																	'name' => $translated_term->name
																];
															}
														}
													}
												}
												
												// Use translated categories
												$child_categories = $translated_categories;
											}
										}
										if (!empty($child_categories)) :
											foreach ($child_categories as $child_category) :
												$cat_name = esc_html($child_category['name']);
												$cat_slug = esc_attr($child_category['slug']); ?>
												
												<div class="checkbox_wrapper">
													<label role="menuitem">
														<input class="kare-element" type="checkbox" name="product_category[]" value="<?php echo $cat_slug; ?>" 
															<?php checked(!empty($_REQUEST['product_category']) && in_array($cat_slug, $_REQUEST['product_category'])); ?>>	
														<p><?php echo $cat_name; ?></p>
													</label>
												</div>
								
											<?php endforeach;
										endif;
									}
									?>
							
							</div>
							
							<button type="button" class="btn_mobile_filter" data-target="#delivery_sidebar">
								<?php echo esc_html__('Delivery Time', 'kare') ?>
								<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
							</button> 
							<div class="content_sidebar" id="delivery_sidebar" data-title="<?php echo __( 'Delivery Time', 'kare' ); ?>">
								
								<?php  $selected_delivery_filter = isset($_REQUEST['product_delivery']) ? $_REQUEST['product_delivery'] : '';?>
								<div class="radio_wrapper">
									<label role="menuitem">
										<input type="radio" name="product_delivery" value="instock" <?php checked( $selected_delivery_filter, 'instock' ); ?>>  
										<p><?php echo __('Immediately Available', 'kare'); ?></p>
									</label>
								</div>
								<div class="radio_wrapper">
									<label role="menuitem">
										<input type="radio" name="product_delivery" value="general_stock"  <?php checked( $selected_delivery_filter, 'general_stock' ); ?>>  
										<p><?php echo __('60 business days', 'kare'); ?></p>
									</label>
								</div>
								<div class="radio_wrapper">
									<label role="menuitem">
										<input type="radio" name="product_delivery" value="coming_soon"  <?php checked( $selected_delivery_filter, 'coming_soon' ); ?>>  
										<p><?php echo __('coming soon', 'kare'); ?></p>
									</label>
								</div>
						
							</div>
							
							<button type="button" class="btn_mobile_filter" data-target="#color_sidebar">
								<?php echo esc_html__('Colors', 'kare') ?>
								<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/arrow-down.svg'); ?>
							</button>
							<div class="content_sidebar" id="color_sidebar" data-title="<?php echo __( 'Colors', 'kare' ); ?>">
								<?php 
								$current_category = get_queried_object();
								if ($current_category) {
									$category_id = $current_category->term_id;
									$english_category_id = apply_filters('wpml_object_id', $category_id, 'product_cat', true, 'en');
									$product_colors = get_field('product_colors_list', 'product_cat_' . $english_category_id);
									// Get colors from ACF
									//$product_colors = get_field('product_colors_list', 'product_cat_' . $category_id);
									//$product_colors = json_decode($product_colors, true);
									$lang = apply_filters('wpml_current_language', NULL); // Get current language
										
											// If in Hebrew, get translated categories
									if ($lang == 'he' && !empty($product_colors)) {
										$translated_colors = [];
								
										foreach ($product_colors as $color) {
											$color_term = get_term_by('slug', $color['slug'], 'pa_color');
											
											
											$translated_color_id = apply_filters('wpml_object_id', $color_term->term_id, 'pa_color', true, 'he');
							
											if ($translated_color_id) {
												$translated_color = get_term($translated_color_id, 'pa_color');
												if ($translated_color) {
													$translated_colors[] = [
														'slug' => $translated_color->slug,
														'name' => $translated_color->name
													];
												}
											} else {
												// If no translation exists, use original color
												$translated_colors[] = [
													'slug' => $color['slug'],
													'name' => $color['name']
												];
											}
											
										}
								
										$product_colors = $translated_colors;
									}
									
									
									if (!empty($product_colors)) : ?>
										<?php foreach ($product_colors as $color) :
											$color_name = esc_html($color['name']); 
											$color_slug = esc_attr($color['slug']); ?>
											
											<div class="checkbox_wrapper">
												<label>
													<input type="checkbox" name="product_color[]" value="<?php echo $color_slug; ?>"
														<?php checked(!empty($_REQUEST['product_color']) && in_array($color_slug, $_REQUEST['product_color'])); ?>>
													<p><?php echo $color_name; ?></p>
												</label>
											</div>

										<?php endforeach; 
									endif;
								}
								?>
							</div>
						</div>
						<div class="bottom_sidebar bottom_filter">
							<button type="button" class="close_filter"><?php echo  __( 'CLOSE', 'kare' ); ?></button>
							<button type="submit" class="confirm_filter"><?php echo  __( 'Confirm', 'kare' ); ?></button>
						</div>
					</div>
				</form>
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
						<?php if ( function_exists( 'icl_get_languages' ) ) {
							$languages = icl_get_languages('skip_missing=0');
							if (!empty($languages)) {
								echo '<select id="mobile-lang-switcher" onchange="window.location.href=this.value;" style="background-image: url(' . esc_url($languages[array_search(1, array_column($languages, 'active'))]['country_flag_url']) . '); background-repeat: no-repeat; background-position: left center; padding-left: 30px;">';
								
								echo '<option value="#" disabled selected>Language</option>'; // Display "Language" as the default text
								
								foreach ($languages as $lang) {
									echo '<option value="'.esc_url($lang['url']).'" data-flag="'.esc_url($lang['country_flag_url']).'">'.esc_html($lang['native_name']).'</option>';
								}
								
								echo '</select>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->


		<div class="logout_popup">
			<div class="logout_popup_content">
				<p><?php esc_html_e( 'Would you like to logout?', 'kare' ); ?></p>
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
		
		<?php if ( !is_user_logged_in() ) { ?>
			<div id="lost-password-confirmation">
				<?php woocommerce_get_template( 'myaccount/lost-password-confirmation.php' ); ?>
			</div>
		<?php } ?>
		