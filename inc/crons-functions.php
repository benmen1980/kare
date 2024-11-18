<?php

//Inventory synchronization from Kara file abroad

//write to the error log as a dedicated file
function custom_log_test( $message ) {
    $log_file = ABSPATH . 'wp-content/crons/custom-log-image.txt'; 
    $current_time = date( 'Y-m-d H:i:s' );
    $formatted_message = $current_time . " - " . print_r( $message, true ) . "\r\n";
    file_put_contents( $log_file, $formatted_message, FILE_APPEND );
}

// require_once ABSPATH . 'wp-content/lib/phpseclib/Net/SFTP.php';
// use phpseclib3\Net\SFTP;

function upload_file_sftp() 
{
    /*// SFTP server details
    $host = 'sftp.kare-design.com';
    $username = 'shop28144';
    $password = 'ri3aiSha';

    // Create an SFTP instance
    $sftp = new SFTP($host);

    // Connect and authenticate
    if (!$sftp->login($username, $password)) {
        die('Login Failed');
        error_log('no sucssesfull to login');
        return;
    } else {
        error_log('sucssesfully to login');
    }

    wp_die();*/

    // פרטי התחברות ל-SFTP
    $sftp_server = 'ftp.kare.de';
    $sftp_user = 'shop28144';
    $sftp_password = 'JaFFA1909';

    // נתיב הקובץ ב-FTP
    $remote_file = "shop28144/downloads/20241025_093000_item_masterdata_fn_il_xxx.csv.zip";
    $local_zip = ABSPATH . 'wp-content/uploads/tmp/item_masterdata.zip';
    custom_log_test($local_zip);
    $extract_path = ABSPATH . 'wp-content/uploads/tmp/extracted_files';
    custom_log_test($extract_path);

    $conn_id = ftp_connect($sftp_server);
    custom_log_test('$conn_id: ' . $conn_id);

    $login_result = ftp_login($conn_id, $sftp_user, $sftp_password);
    ftp_pasv($conn_id, true); // העברת החיבור למצב פסיבי

    if (!$login_result) {
        die("Could not log in to $sftp_server");
    }
    $size = ftp_size($conn_id, $remote_file);

    custom_log_test('size: ' . $size);


    $ftp_get = ftp_get($conn_id, $local_zip, $remote_file, FTP_BINARY);

    if ($ftp_get) {
        custom_log_test('ftp_get: Successfully');
    } else {
        custom_log_test('ftp_get: Error');
    }
    
    // סגירת החיבור ל-FTP
    ftp_close($conn_id);

    $zip = new ZipArchive;

    if ($zip->open($local_zip) === TRUE) {
        custom_log_test('zip open');
        $aaa = $zip->extractTo($extract_path);
        $zip->close();
    } else {
        die("Failed to extract the file.");
        custom_log_test('Failed to extract the file');
    }
    
    // קריאת קובץ ה-CSV
    $csv_masterdata_file = $extract_path . '/20241025_093000_item_masterdata_fn_il_xxx.csv'; 
    custom_log_test('open the file' . $csv_masterdata_file);
    if (file_exists($csv_masterdata_file)) {
        // open the file and update model, manufacturer meta fields

        custom_log_test('open the file');

        if (($handle = fopen($csv_masterdata_file, "r")) !== false) {

            $headers = fgetcsv($handle, 1000, ";");

            
            $row_count = 0;

            while (($row = fgetcsv($handle, 1000, ";")) !== false) {
                $data = [];
                // אם השורה לא ריקה
                if (trim(implode(';', $row)) !== '') {
                    // שילוב הכותרות עם הערכים
                    $data[] = array_combine($headers, $row);
                    if ( !empty ($data) ) {
                        update_available_test($data);
                    }
                    $row_count++;
                    
                    // Check if the product count is a multiple of 1500
                    if ($row_count % 1500 == 0) {
                        // Introduce a delay of 0.5 seconds using sleep
                        usleep(200000); // Sleep for 0.2 seconds
                        custom_log_test('update 1500 pdts');
                        $row_count = 0;
                    }
                }
            }

            // סגירת הקובץ
            fclose($handle);
            custom_log_test('complete sync all file');

        }
    }
 
}

function update_available_test($array) 
{
    foreach ($array as $data) {
                
        $sku = !empty($data['itemnumber']) ? $data['itemnumber'] : '';
        $stock = !empty($data['available_stock']) ? $data['available_stock'] : '';

        $product = get_posts(array(
            'post_type' => 'product',
            'post_status' => array( 'publish', 'draft' ),
            'meta_query'  => array(
                array(
                    'key'   =>'_sku',
                    'value' => $sku,
                )
            )
        ));
        if($product){
            $product_id = $product[0]->ID;
            custom_log_test('product id: '.  $product_id);
            if ( $stock && intval($stock) > 0 ) {
                update_post_meta($product_id, '_stock_status', 'instock');
                update_field('kare_general_stock', $stock, $product_id);  
            } else {
                $stock_available = get_post_meta($product_id, '_stock', true);
                if ( $stock_available && intval($stock_available <= 0) ) {
                    update_post_meta($product_id, '_stock_status', 'outofstock');
                }

            }             
        }
        // error_log('Now finish index number: ' . $index);
    }

}

// add_action('upload_file_sftp_hook', 'upload_file_sftp');
/*if (!wp_next_scheduled('upload_file_sftp_hook')) {
    $res = wp_schedule_event(time(), 'daily', 'upload_file_sftp_hook');
}*/

function upload_file_image() 
{
    $file_name = 'webcatalog-other18.csv';
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['path'] . '/' . $file_name;   

    // Ensure media functions are available
    if (!function_exists('media_handle_sideload')) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    // Check if the file exists before processing
    if (file_exists($file_path)) {
        // open the file and update model, manufacturer meta fields
        if (($handle = fopen($file_path, "r")) !== false) {

            custom_log_test('open file: '.$file_path);

            $row_count = 0;
            $preferred_size = '1400x1400';
            $product_images = [];

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                list($pdt_sku, $image_sku, $image_url) = $data;

                // Check if the URL matches the preferred size
                if (strpos($image_url, $preferred_size) === false) {
                    continue;
                }

                // Organize images under SKU
                if (!isset($product_images[$pdt_sku])) {
                    $product_images[$pdt_sku] = [
                        'main_image' => null,
                        'gallery_images' => ['mood' => [], 'detail' => [], 'master' => []],
                        'img_dimensions' => null,
                    ];
                }

                 // Sort images based on naming conventions
                if (preg_match('/master-m-/', $image_url)) {
                    $product_images[$pdt_sku]['img_dimensions'] = $image_url;
                } elseif (preg_match('/master-mood/', $image_url)) {
                    $product_images[$pdt_sku]['gallery_images']['mood'][] = ['url' => $image_url, 'order' => parse_order($image_url)];
                } elseif (preg_match('/detail/', $image_url)) {
                    $product_images[$pdt_sku]['gallery_images']['detail'][] = $image_url;
                } elseif (preg_match('/master/', $image_url)) {
                    $product_images[$pdt_sku]['gallery_images']['master'][] = $image_url;
                } else {
                    $product_images[$pdt_sku]['main_image'] = $product_images[$pdt_sku]['main_image'] ?: $image_url;
                }

                // Add delay every 1500 rows
                $row_count++;            
                if ($row_count % 1500 == 0) {
                    custom_log_test('finish 1500 rows');
                    usleep(200000); // Sleep for 0.2 seconds
                    $row_count = 0;
                }
            }
            fclose($handle);

            // Process each SKU’s images and update product data
            foreach ($product_images as $sku => $images) {

                // Find the product by SKU
                $product = get_posts([
                    'post_type'  => 'product',
                    'post_status'=> ['publish', 'draft'],
                    'meta_query' => [
                        ['key' => '_sku', 'value' => $sku],
                    ],
                    'numberposts' => 1
                ]);

                if (!$product) continue;

                $product_id = $product[0]->ID;
                custom_log_test('id: '.$product_id);

                // Download images and build the attachment array
                $main_image_id = $images['main_image'] ? download_external_image($images['main_image'], $sku) : null;
                $gallery_ids = [];
            
                 // Sort and download mood images by order
                usort($images['gallery_images']['mood'], function($a, $b) {
                    return $a['order'] - $b['order'];
                });
                foreach ($images['gallery_images']['mood'] as $mood_image) {
                    $gallery_ids[] = download_external_image($mood_image['url'], $sku);
                }
            
                // Download detail and master images
                foreach ($images['gallery_images']['detail'] as $detail_image_url) {
                    $gallery_ids[] = download_external_image($detail_image_url, $sku);
                }
                foreach ($images['gallery_images']['master'] as $master_image_url) {
                    $gallery_ids[] = download_external_image($master_image_url, $sku);
                }

                // Update the product image gallery and main image
                update_post_meta($product_id, '_thumbnail_id', $main_image_id);
                update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_ids));

                // Update the ACF field img_master_link with the URL
                $img_master_id = $images['img_dimensions'] ? download_external_image($images['img_dimensions'], $sku) : null;
                $dimensions_link = wp_get_attachment_url($img_master_id);
                if ($dimensions_link) {
                    update_field('product_details_img_dimensions_link', $dimensions_link, $product_id);
                }

                $_product = wc_get_product( $product_id );
                $_product->set_status( 'publish' );
                $_product->save();

                usleep(200000); // Sleep      
            }
                     
        }
        custom_log_test('finish file');
    }
 
}

// Helper function to download and attach images to WordPress
function download_external_image($image_url, $description) {

    $filename_without_extension = pathinfo($image_url, PATHINFO_FILENAME) ."-1";

    // Check if image already exists in media library
    $existing_image = get_posts([
        'post_type'  => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'title'       => $filename_without_extension,
    ]);

    if ($existing_image) {
        // Return existing image ID if found
        return $existing_image[0]->ID;
    }
    
    $temp_file = download_url($image_url);
    if (is_wp_error($temp_file)) return false;

    $file = [
        'name'     => basename($image_url),
        'type'     => mime_content_type($temp_file),
        'tmp_name' => $temp_file,
        'size'     => filesize($temp_file),
    ];

    // Upload image and attach to the media library
    $attachment_id = media_handle_sideload($file, 0);
    custom_log_test('attachment id: '.$attachment_id);

    if (is_wp_error($attachment_id)) {
        @unlink($temp_file);
        return false;
    }
    return $attachment_id;
}

// Extract the order for sorting mood images (e.g., 'a', 'b')
function parse_order($url) {
    preg_match('/-mood-(a|b)/', $url, $matches);
    return $matches[1] === 'a' ? 1 : 2;
}

// Schedule the upload_file_image function daily using wp_schedule_event
add_action('init', function() {
    // Only schedule the event if it hasn’t been scheduled already
    if (!wp_next_scheduled('upload_file_image_hook')) {
        wp_schedule_event(time(), 'daily', 'upload_file_image_hook');
    }
});

// Register the actual hook for the scheduled event
// add_action('upload_file_image_hook', 'upload_file_image');

?>