<?php 
global $product; 

if(!is_category() && !is_product_category()) : ?>
    <div class="swiper-slide">
<?php endif; ?>
        <div class="search_suggestions_product">
            <?php 
            $pdt_name = $product->get_name();
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
                        
            if(is_category() || is_product_category()) : ?>

                <button aria-label="link" type="button" title="Add to Wishlist" class="wishlist_btn">
                    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
                </button>
                
            <?php endif; ?>
            <div class="box_product flex card-product" id="<?php echo $product->get_id();?>">
                <div  class="product_img_wrapper">
                    <div class="thumbnail <?php echo !empty($hover_image_url) ? 'has-hover' :'' ?>">
                        <a href="<?php echo $pdt_permalink; ?>" title="<?php echo $pdt_name;?>">
                            <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" alt="<?php echo $product->get_title(); ?>">
                        </a>
                        <?php if(!empty($sale_price)){ ?>
                            <div class="sale_tag">
                                <span class="text_sale_percent"> <?php echo '-'.$percent.'%'; ?></span>
                            </div>
                        <?php } ?>
                        <?php if(has_term( '29', 'product_tag',$product->get_id() )){ 
                                $term_data = get_term_by('id', '29', 'product_tag');
                            ?>                       
                            <div class="wc_tag_bestseller">
                                <div class="text_tag_bestseller"> <?php echo $term_data->name; ?></div>
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
                        ?>
                        <div>
                            <?php if ($stock_status === 'instock') : ?>
                                <p class="stock instock"><?php esc_html_e( 'Available immediately', 'kare' ); ?></p>
                            <?php else : ?>
                                <p class="stock outofstock"><?php esc_html_e( '60 business days', 'kare' ); ?></p>
                            <?php endif ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>
<?php if(!is_category() && !is_product_category()) : ?>
    </div>
<?php endif; ?>