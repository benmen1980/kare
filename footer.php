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
					<h4 class="text-center w-full">Gute Gründe für KARE Online GmbH</h4>
					<div class="center-flex justify-around">
						<div class="text-center  w-1-3">
							<svg></svg>
							<img src="<?php echo get_template_directory_uri();?>/dist/images/delivery-truck.png" alt="delivery truck" width="32" height="32" class="svg-icon sprite-icons">
							<p class="pt-3">Kostenlose Rücksendung</p>
						</div>
						<div class="text-center  w-1-3">
							<svg></svg>
							<img src="<?php echo get_template_directory_uri();?>/dist/images/delivery-truck.png" alt="delivery truck" width="32" height="32" class="svg-icon sprite-icons">
							<p class="pt-3">Kostenlose Rücksendung</p>
						</div>
						<div class="text-center  w-1-3">
							<svg></svg>
							<img src="<?php echo get_template_directory_uri();?>/dist/images/delivery-truck.png" alt="delivery truck" width="32" height="32" class="svg-icon sprite-icons">
							<p class="pt-3">Kostenlose Rücksendung</p>
						</div>
					</div>
				</div>

			</div>
			<div class="footer-container  first-container-footer background-gray">
				<div class="contain-footer container">
					<h3 class="text">POPULAR CATEGORIES</h3>
					<div class="">
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
			<div class="contain-footer container">
					<h3 class="text">IMPORTANT LINKS</h3>
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
				$text_area_content = get_field('regulations_service_text'); 
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

</body>
</html>
