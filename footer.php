<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kare
 */

?>
	</main><!-- #main-site -->
	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<div class="footer-container background-gray">
				<div class="contain-footer min-p">
					<h4 class="text-center w-full"><?php esc_html_e( 'Good reasons for KARE Online Israel', 'kare' ); ?></h4>
					<div class="center-flex justify-around">
						<div class="text-center w-1-3">
							<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/truck.svg'); ?>
							<p class=""><?php esc_html_e( 'Free returns', 'kare' ); ?></p>
						</div>
						<div class="text-center w-1-3">
							<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/shield.svg'); ?>
							<p class=""><?php esc_html_e( 'Purchase on account', 'kare' ); ?></p>
						</div>
						<div class="text-center w-1-3">
							<?php echo file_get_contents(get_template_directory_uri() . '/dist/images/svg/return.svg'); ?>
							<p class=""><?php esc_html_e( '14 days cancellation', 'kare' ); ?></p>
						</div>
					</div>
				</div>

			</div>
			<div class="footer-container  first-container-footer background-gray">
				<div class="first-footer contain-footer container">
					<h3 class="text"><?php esc_html_e( 'POPULAR CATEGORIES', 'kare' ); ?></h3>
					<div class="bg-gray">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer_navigation1',
									'menu_id'        => 'footer-menu-1',
									'menu_class'     => 'first-section-footer w-btn',
								)
							);
						?>
					</div>
				</div>

			</div><!-- .first-container-footer -->
			<div class="footer-container second-container-footer background-dark-gray">
				<div class="second-footer container contain-footer">
					<div class="customer_service_footer_menu">
						<ul class="footer-menu"> 
							<li class="menu-item menu-item-has-children">
								<a href="#">
									<?php echo get_field('footer_title','option'); ?>
								</a>
								<ul class="sub-menu">
									<li>	
										<?php echo get_field('footer_desc','option'); ?>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer_navigation2',
								'menu_id'        => 'footer-menu-2',
								'menu_class'     => 'second-section-footer w-btn',
							)
						);
					?>
				</div>
			</div><!-- .second-container-footer -->
			<div class="footer-container  third-container-footer background-gray">
			<div class="third-footer contain-footer container">
					<h3 class="text"><?php esc_html_e( 'IMPORTANT LINKS', 'kare' ); ?></h3>
					<div class="">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer_navigation3',
									'menu_id'        => 'footer-menu-3',
									'menu_class'     => 'third-section-footer w-btn',
								)
							);
						?>
					</div>
				</div>

			</div><!-- .third-container-footer -->
			<div class="footer-container bottom-footer background-white">
				<img src="<?php echo get_template_directory_uri();?>/dist/images/logo-black.png" alt="KARE - Buy KARE furniture" width="83" height="14" class="text-black">
				<?php
				$text_area_content = get_field('regulations_service_text','option'); 
				if( $text_area_content ) {
					echo '<p class="regulations-service-text text-center">';
					echo wp_kses_post( $text_area_content ); 
					echo '</p>';
				}
				?>
			</div><!-- .fourth-container-footer -->

		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>

<!-- Start of Glassix Chat Widget -->

<script>

var widgetOptions = {

     apiKey: "99233337-8402-4bdc-81ab-4f317bdb843d",

     snippetId: "SKrn08k65cmti5T98yWE"

};

 

(function(n){var u=function(){GlassixWidgetClient&&typeof GlassixWidgetClient=="function"?(window.widgetClient=new GlassixWidgetClient(n),widgetClient.attach(),window.glassixWidgetScriptLoaded&&window.glassixWidgetScriptLoaded()):f()},f=function(){r.onload=u;r.src="https://cdn.glassix.net/clients/widget.1.2.min.js";i.parentNode.removeChild(t);i.parentNode.insertBefore(r,i)},i=document.getElementsByTagName("script")[0],t=document.createElement("script"),r;(t.async=!0,t.type="text/javascript",t.crossorigin="anonymous",t.id="glassix-widget-script",r=t.cloneNode(),t.onload=u,t.src="https://cdn.glassix.com/clients/widget.1.2.min.js",!document.getElementById(t.id)&&document.body)&&(i.parentNode.insertBefore(t,i),t.onerror=f)})(widgetOptions)

</script>

<!-- End of Glassix Chat Widget -->

</body>
</html>
