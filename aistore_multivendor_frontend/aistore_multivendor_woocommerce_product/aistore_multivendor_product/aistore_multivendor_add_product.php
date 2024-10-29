<?php
     function aistore_add_product()
    {
        
            if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
        $user_id = get_current_user_id();
        $user_role = get_user_meta($user_id, 'user_role', true);

        if ($user_role != 'Vendor')
        {
            _e('<div>You are not vendor please contact to admin</div>', 'aistore');
        }

        else
        {
            $editor = array(
                'tinymce' => array(
                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright, link,unlink,  ',
                    'toolbar2' => '',
                    'toolbar3' => ''

                ) ,
                'textarea_rows' => 1,
                'teeny' => true,
                'quicktags' => false,
                'media_buttons' => false
            );

?>
    
<h3><?php _e("Add a Product", "blank"); ?></h3>
<?php
            if (isset($_POST['submit']) and $_POST['action'] == 'product')
            {

                if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                {
                    return _e('Sorry, your nonce did not verify', 'aistore');

                }

                $name = sanitize_text_field($_REQUEST['product_name']);

                $short_description = sanitize_text_field($_REQUEST['short_description']);
                $amount = intval($_REQUEST['amount']);
                $price = intval($_REQUEST['price']);
                $full_description = sanitize_text_field($_REQUEST['full_description']);
                $category = sanitize_text_field($_REQUEST['category']);

                $terms_condtion = sanitize_text_field($_REQUEST['terms_condtion']);
                $tags = sanitize_text_field($_REQUEST['tags']);

                $product_type = sanitize_text_field($_REQUEST['product_type']);

                $objProduct = new WC_Product();

                $objProduct->set_name($name); //Set product name.
                $objProduct->set_status('publish'); //Set product status.
                

                $objProduct->set_description($full_description); //Set product description.
                $objProduct->set_short_description($short_description); //Set product short description.
                $objProduct->set_price($price); //Set the product's active price.
                $objProduct->set_regular_price($price); //Set the product's regular price.
                $objProduct->set_sale_price($amount); //Set the product's sale price.
                

                $objProduct->set_manage_stock(false); //Set if product manage stock.
                $objProduct->set_stock_status('instock'); //Set stock status.
                $pid = $objProduct->save(); //Saving the data to create new product, it will
                update_post_meta($pid, 'product_terms_condtion', $terms_condtion);

                wp_set_object_terms($pid, array(
                    $tags
                ) , 'product_tag');

                update_post_meta($pid, 'product_category', $category);
                wp_set_object_terms($pid, 'simple', 'product_type');
                // echo $product_type;
                if ($product_type == 'downloadable')
                {
                    update_post_meta($pid, '_downloadable', 'yes');
                    update_post_meta($pid, '_virtual', 'no');
                }
                else
                {
                    update_post_meta($pid, '_downloadable', 'no');
                    update_post_meta($pid, '_virtual', 'yes');
                }

                // Product Image
                $check = getimagesize(($_FILES["file"]["tmp_name"]));
                if ($check !== false)
                {

                    $post_id = $pid;

                    // Add Featured Image to Post
                    $image_url = sanitize_text_field($_FILES['file']['tmp_name']); // Define the image URL here
                    $image_name = sanitize_text_field($_FILES['file']['name']);
                    $upload_dir = wp_upload_dir(); // Set upload folder
                    $image_data = file_get_contents($image_url); // Get image data
                    $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
                    $filename = basename($unique_file_name); // Create image file name
                    // Check folder permission and define file location
                    if (wp_mkdir_p($upload_dir['path']))
                    {
                        $file = $upload_dir['path'] . '/' . $filename;
                    }
                    else
                    {
                        $file = $upload_dir['basedir'] . '/' . $filename;
                    }

                    // Create the image  file on the server
                    file_put_contents($file, $image_data);

                    // Check image file type
                    $wp_filetype = wp_check_filetype($filename, null);

                    // Set attachment data
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => sanitize_file_name($filename) ,
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    // Create the attachment
                    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

                    // Include image.php
                    require_once (ABSPATH . 'wp-admin/includes/image.php');

                    // Define attachment metadata
                    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

                    // Assign metadata to attachment
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // And finally assign featured image to post
                    set_post_thumbnail($post_id, $attach_id);

                }
                else
                {
                    _e('File is not an image', 'aistore');

                }

                ///gallary file
                $check = getimagesize(($_FILES["gallary_file"]["tmp_name"]));
                if ($check !== false)
                {

                    $post_id = $pid;

                    // Add Featured Image to Post
                    $image_url = sanitize_text_field($_FILES['gallary_file']['tmp_name']); // Define the image URL here
                    $image_name = sanitize_text_field($_FILES['gallary_file']['name']);
                    $upload_dir = wp_upload_dir(); // Set upload folder
                    $image_data = file_get_contents($image_url); // Get image data
                    $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
                    $filename = basename($unique_file_name); // Create image file name
                    // Check folder permission and define file location
                    if (wp_mkdir_p($upload_dir['path']))
                    {
                        $file = $upload_dir['path'] . '/' . $filename;
                    }
                    else
                    {
                        $file = $upload_dir['basedir'] . '/' . $filename;
                    }

                    // Create the image  file on the server
                    file_put_contents($file, $image_data);

                    // Check image file type
                    $wp_filetype = wp_check_filetype($filename, null);

                    // Set attachment data
                    $attachment = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => sanitize_file_name($filename) ,
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    // Create the attachment
                    $attach_id = wp_insert_attachment($attachment, $file, $post_id);

                    // Include image.php
                    require_once (ABSPATH . 'wp-admin/includes/image.php');

                    // Define attachment metadata
                    $attach_data = wp_generate_attachment_metadata($attach_id, $file);

                    // Assign metadata to attachment
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    // And finally assign featured image to post
                    update_post_meta($post_id, '_product_image_gallery', $attach_id);
                    // set_post_thumbnail( $post_id, $attach_id );
                    

                    
                }
                else
                {
                    _e('File is not an image', 'aistore');

                }

                ///downloadable file
                

                if ($product_type == 'downloadable')
                {

                    $check = getimagesize(($_FILES["product_download_file"]["tmp_name"]));
                    if ($check !== false)
                    {

                        $post_id = $pid;

                        // Add Featured Image to Post
                        $image_url = sanitize_text_field($_FILES['product_download_file']['tmp_name']); // Define the image URL here
                        $image_name = sanitize_text_field($_FILES['product_download_file']['name']);

                        echo $image_name;
                        $upload_dir = wp_upload_dir(); // Set upload folder
                        $image_data = file_get_contents($image_url); // Get image data
                        $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
                        $filename = basename($unique_file_name); // Create image file name
                        // Check folder permission and define file location
                        if (wp_mkdir_p($upload_dir['path']))
                        {
                            $file = $upload_dir['path'] . '/' . $filename;
                        }
                        else
                        {
                            $file = $upload_dir['basedir'] . '/' . $filename;
                        }

                        // Create the image  file on the server
                        file_put_contents($file, $image_data);

                        // Check image file type
                        $wp_filetype = wp_check_filetype($filename, null);

                        // Set attachment data
                        $attachment = array(
                            'post_mime_type' => $wp_filetype['type'],
                            'post_title' => sanitize_file_name($filename) ,
                            'post_content' => '',
                            'post_status' => 'inherit'
                        );

                        // Create the attachment
                        $attach_id = wp_insert_attachment($attachment, $file, $post_id);

                        // Include image.php
                        require_once (ABSPATH . 'wp-admin/includes/image.php');

                        // Define attachment metadata
                        $attach_data = wp_generate_attachment_metadata($attach_id, $file);

                        // Assign metadata to attachment
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        // And finally assign featured image to post
                        $post_thumbnail_id = get_post_thumbnail_id($post_id);
                        $year = date("Y");
                        $month = date("m");
                        $url = get_site_url() . "/wp-content/uploads/" . $year . "/" . $month . "/" . $filename;

                        // echo "url".$url;
                        update_post_meta($post_id, '_downloadable_files', $url);

                    }
                    else
                    {
                        _e('File is not an image', 'aistore');

                    }

                }

                $product_details = get_permalink(esc_attr($pid));
                header('Location: ' . $product_details);

            }
            else
            {

?>
      <div >
      <form method="POST" action="" name="product" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>


    <br>      
        <div class="mb-3">   
<label><?php _e('Title', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="product_name" name="product_name" required></div><br><br>
  
    
   <br>      
        <div class="mb-3">   
<label><?php _e('Regular price ($)', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="price" name="price" required></div><br><br>
    <br>      
        <div class="mb-3">   
<label><?php _e('Sale price ($)', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="amount" name="amount" required></div><br><br>


      <div class="mb-3">   
 <label for="downloadable">  <?php _e('Product Type', 'aistore'); ?> </label>
<br> <input  type="checkbox" id="virtual" name="product_type" value="Virtual">
  <?php _e('Virtual', 'aistore'); ?> 
 <input type="checkbox" id="downloadable" name="product_type" value="downloadable"  onclick="myFunction()">
   <?php _e('Downloadable', 'aistore'); ?></div><br><br>
   
 


<?php

                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false

                ));
?>

    <div class="mb-3">   
<label><?php _e('Category', 'aistore'); ?></label><br>
  <select name="category" id="category">
      <?php

                foreach ($categories as $category)
                {

                    echo ' <option value="' . esc_attr($category->name) . '">' . esc_attr($category->name) . '   </option>';

                }

?>
  </select></div><br><br>
  
  
      <div class="mb-3">   
  <label><?php _e('Tags', 'aistore'); ?></label><br>
  <textarea id="tags" name="tags" rows="3" cols="50">
</textarea></div><br>


 <br>     <div class="mb-3">   <label><?php _e('Short Description', 'aistore'); ?></label><br/>

   <?php
                $content = '';
                $editor_id = 'short_description';

                $short_description = $editor;

                wp_editor($content, $editor_id, $short_description);

?></div>
   
 <br>     <div class="mb-3">   <label><?php _e('Full Description', 'aistore'); ?></label>
 <br/>
   <?php
                $content = '';
                $editor_id = 'full_description';

                $full_description = $editor;

                wp_editor($content, $editor_id, $full_description);

?></div>
 

  
  <br>    <div class="mb-3">   <label><?php _e('Terms and conditions', 'aistore'); ?></label><br/>

   <?php
                $content = '';
                $editor_id = 'terms_condtion';

                $terms_condtion = $editor;

                wp_editor($content, $editor_id, $terms_condtion);

?></div>
<br>

    <div class="mb-3">   
  <label><?php _e('Product Image', 'aistore'); ?></label><br/>
  <input class="input" type="file" id="file" name="file" ></div><br><br>
  
    <div class="mb-3">   
  <label><?php _e('Product Gallary', 'aistore'); ?></label><br/>
  <input class="input" type="file" id="gallary_file" name="gallary_file" ></div><br><br>
  

      <div class="mb-3">   
        <span id="text" class="downloadfile">
   
  <label><?php _e('Product Downloadable  File', 'aistore'); ?></label><br/>
  <input class="input" type="file"  name="product_download_file" ><br><br></span>

  
  
  </div><br><br>
  
  <input  type="submit"  name="submit" value="<?php _e('Submit', 'aistore') ?>">
  <input type="hidden" name="action" value="product" />
</form></div>


      <?php
            }
        }
    }
?>