<?php
/*
Template Name: Register Page

*/

// if ( is_user_logged_in() ){
//     wp_redirect( home() );
//     exit;
// }

get_header();

?>

<div class="register_page entry-content">
    <div class="woocommerce">
        <div>
            <h1><?php esc_html_e( 'Register', 'kare' ); ?></h1>
            <p class="desc_before_field">
                <?php echo get_field('txt_before_my_account','option'); ?>
            </p>
            <?php echo get_field('register_title','option'); ?>
            <?php do_action( 'woocommerce_before_customer_login_form' );?>
            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
                
                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <div class="form-row">
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="full_name" id="reg_full_name"  placeholder="" autocomplete="full_name" value="<?php echo ( ! empty( $_POST['full_name'] ) ) ? esc_attr( wp_unslash( $_POST['full_name'] ) ) : ''; ?>" />
                    <label for="reg_full_name"><?php esc_html_e( 'First name Last Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                </div> 
                <!-- <div class="form_group">
                   
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="reg_last_name" autocomplete="last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" />
                    <label for="reg_last_name"><?//php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                </div> -->

                <div class="form-row">
                    
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" placeholder="" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                    <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                </div>
                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                <div class="form-row">
                    
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" value="<?php echo ( ! empty( $_POST['password'] ) ) ? esc_attr( wp_unslash( $_POST['password'] ) ) : ''; ?>" />
                    <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                </div>

                <?php else : ?>

                    <p class="send_pswd_msg"><?php esc_html_e( 'A password will be sent to your email address.', 'kare' ); ?></p>

                <?php endif; ?>

                <!-- Adding a date of birth field -->
                <div class="form-row">
                    <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="birth_date" id="reg_birth_date" value="<?php echo ( ! empty( $_POST['birth_date'] ) ) ? esc_attr( wp_unslash( $_POST['birth_date'] ) ) : ''; ?>" />
                    <label for="reg_birth_date" class="birth_date"><?php esc_html_e('Date of Birth', 'woocommerce'); ?></label>
                </div>

                <!-- Added a 'where did you come to us from' field -->
                 <?php if (have_rows('user_arrived_choice', 'option')) : ?>
                    <div class="form-row form-select">
                        <label for="reg_user_arrived_choice" class="user_arrived_choice">
                            <?php esc_html_e('Tell us how you came to us:', 'woocommerce'); ?>
                        </label>
                        <select name="user_arrived_choice" id="reg_user_arrived_choice" class="woocommerce-Input woocommerce-Input--select input-select">
                            <option value=""><?php esc_html_e('Select an Option', 'woocommerce'); ?></option>

                            <?php while (have_rows('user_arrived_choice', 'option')) : the_row();
                                $tab_choice = get_sub_field('user_arrived'); ?>
                                <option value="<?php echo esc_attr($tab_choice); ?>" >
                                    <?php echo esc_html($tab_choice); ?>
                                </option>                            
                            <?php endwhile; ?>

                        </select>
                    </div>
                <?php endif; ?>

                <div class="checkbox_wrapper">
                    <label for="checkbox_privacy">
                        <input id="checkbox_privacy" class="kare-element" type="checkbox" name="checkbox_privacy" value="<?php echo ( ! empty( $_POST['checkbox_privacy'] ) ) ? $_POST['checkbox_privacy'] : ''; ?>">	
                        <?php echo get_field('checkbox_privacy','option');?>
                    </label>
                </div>

                <div class="checkbox_wrapper">
                    <label for="read_site_condition">
                        <input id="read_site_condition" class="kare-element" type="checkbox" name="read_site_condition" required>	
                        <?php echo get_field('checkbox_read_site_condition','option');?>
                    </label>
                </div>

                <?php do_action( 'woocommerce_register_form' ); ?>
                
                <div class="form-row">
                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <button type="submit" class="register_btn" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
                        <?php esc_html_e( 'Create a free customer account', 'kare' ); ?>
                    </button>      
                </div>
                <?php do_action( 'woocommerce_register_form_end' ); ?>
            </form>
        </div>
    </div>
</div>




<?php get_footer(); 

?>
