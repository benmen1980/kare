<?php 
global $product; 
$is_slider = get_query_var('is_archive_product_inspiration', true); 

if (!is_category() && !is_product_category() && !is_shop() && !is_search() && $is_slider !== false) : ?>
    <div class="swiper-slide">
<?php endif; ?>
    <div class="search_suggestions_product">
        <?php 
        //$pdt_name = $product->get_name();
        $translated_product_id = apply_filters('wpml_object_id', $product->get_id(), 'product', true);
        // Get the product name and permalink for the translated product
        $pdt_name = get_the_title($translated_product_id);
        $pdt_permalink = get_permalink( $product->get_id() );
        if ( $product->is_type( 'variable' ) ) {
            $regular_price = $product->get_variation_regular_price();
            $sale_price = ($regular_price != $product->get_variation_sale_price())? $product->get_variation_sale_price() : '';
        }
        else{
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
        }
        if(!empty($sale_price)){
            $percent = round((($regular_price - $sale_price)*100) / $regular_price) ;
        }

        //get_gallery_image 
        $attachment_ids = $product->get_gallery_image_ids();
    
        if ( is_array( $attachment_ids ) && !empty($attachment_ids) ) {
            //check if image has hover image    
            if(strpos(wp_get_attachment_url( $attachment_ids[0] ), 'master-mood') !== false){
                $image_hover_url = wp_get_attachment_url( $attachment_ids[0] );
            }                
        }
                    
        if(is_category() || is_product_category()) : ?>

            <button aria-label="link" type="button" title="Add to Wishlist" class="wishlist_btn">
                <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
            </button>
            
        <?php endif; ?>
        <div class="box_product flex card-product" id="<?php echo $product->get_id();?>">
            <div  class="product_img_wrapper">
                <div class="image_with_tags">
                    <div class="image-pdts thumbnail <?php echo !empty($image_hover_url) ? 'has-hover' :'' ?>">
                        <a href="<?php echo $pdt_permalink; ?>" title="<?php echo $pdt_name;?>">
                            <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" alt="<?php echo $product->get_title(); ?>">
                        </a>
                    </div>
                    <?php if(!empty($image_hover_url)): ?>
                        <div class="image-pdts thumbnail-hover">
                            <a href="<?php echo $pdt_permalink; ?>" title="<?php echo $pdt_name;?>">
                                <img src="<?php echo  $image_hover_url; ?>" alt="<?php echo $product->get_title(); ?>">
                            </a>
                        </div> 
                    <?php endif; ?>

                    <?php if(!empty($sale_price)){ ?>
                        <div class="sale_tag">
                            <span class="text_sale_percent"> <?php echo '-'.$percent.'%'; ?></span>
                        </div>
                    <?php } ?>

                    <?php 
                    $product_tags = get_the_terms( $product->get_id(), 'product_tag' );

                    if ( $product_tags && !is_wp_error( $product_tags ) ) { ?>
                        <div class="wc_tag">
                            <?php foreach ( $product_tags as $tag ) { ?>
                                <div class="text_tag"> <?php echo esc_html( $tag->name ); ?> </div>
                            <?php } ?>
                        </div>
                    <?php } ?>    
                </div>
            </div>
            <div class="product_details_wrapper">
                <a class="product_details" href="<?php echo $pdt_permalink?>" title="<?php echo $pdt_name;?>">
                    <p class="name-product"><?php echo $pdt_name;?></p>  
                    <div class="pdt_price_wrapper">
                        <?php if(!empty($sale_price)): ?>
                            <p class="sale_price"> 
                                <span>RRP*: </span>
                                <span> <?php echo wc_price($sale_price); ?> </span>
                            </p>
                            <p class="regular_price <?php  echo (!empty($sale_price)) ? 'line-through' : '' ?>"><?php echo wc_price($regular_price); ?></p>
                        <?php else : ?>
                            <p class="regular_price <?php  echo (!empty($sale_price)) ? 'line-through' : '' ?>"><?php echo wc_price($regular_price); ?></p>
                            <p class="transparent">&nbsp;</p>
                        <?php endif; ?> 
                    </div>
                    <?php 
                        $availability = $product->get_availability(); 
                        $stock_status = $product->get_stock_status();
                        $stock_qty = $product->get_stock_quantity();
                        $kare_stock = get_post_meta($product->get_id(), 'kare_general_stock', true);
                    ?>
                    <div class="stock_status">
                        <?php if ( !empty($kare_stock) || $kare_stock > 0) :  ?>
                            <?php if ( $stock_qty > 0 ) : ?>
                                <p class="stock instock"><?php esc_html_e( 'Immediately available', 'kare' ); ?></p>
                            <?php else : ?>
                                <p class="stock outofstock"><?php esc_html_e( '60 business days', 'kare' ); ?></p>
                            <?php endif ?>
                        <?php else : ?>
                            <p class="stock coming_soon"><?php esc_html_e( 'coming soon', 'kare' ); ?></p>
                        <?php endif ?>
                    </div>                    
                </a>
            </div>
        </div>
    </div>
<?php if (!is_category() && !is_product_category() && !is_shop() && !is_search() && $is_slider !== false) : ?>
    </div>
<?php endif; ?>