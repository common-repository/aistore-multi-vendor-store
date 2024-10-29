<?php

class Aistore_multi_vendor_store
{

    public static function aistore_product_edit()
    {
        
    if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
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

        $pid = sanitize_text_field($_REQUEST['pid']);

        global $wpdb;
        $objProduct = new WC_Product($pid);
        $regular_price = $objProduct->get_regular_price();
        $amount = $objProduct->get_sale_price();

        if (isset($_POST['submit']) and $_POST['action'] == 'productdelete')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $product_id = sanitize_text_field($_REQUEST['product_id']);

            $product_id = $product_id;

            wp_delete_post($product_id);

            $actual_link = get_site_url();
            header('Location: ' . $actual_link . '/my-account/product-list/');

        }
        else
        {

?>

 <form method="POST" action="" name="productdelete" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
    <br>
      <input type="hidden" name="product_id" value="<?php echo esc_attr($pid); ?>" />
<input  type="submit"  name="submit" value="<?php _e('Delete Product', 'aistore') ?>">
  <input type="hidden" name="action" value="productdelete" />
</form>

<?php
        }

        if (isset($_POST['submit']) and $_POST['action'] == 'productedit')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $name = sanitize_text_field($_REQUEST['product_name']);

            $short_description = sanitize_text_field($_REQUEST['short_description']);
            $amount = intval($_REQUEST['amount']);
            $price = intval($_REQUEST['price']);
            $product_type = sanitize_text_field($_REQUEST['product_type']);
            $full_description = sanitize_text_field($_REQUEST['full_description']);
            $category = sanitize_text_field($_REQUEST['category']);

            $terms_condtion = sanitize_text_field($_REQUEST['terms_condtion']);
            $tags = sanitize_text_field($_REQUEST['tags']);

            $objProduct->set_name($name); //Set product name.
            $objProduct->set_status('publish'); //Set product status.
            

            $objProduct->set_description($full_description); //Set product description.
            $objProduct->set_short_description($short_description); //Set product short description.
            $objProduct->set_price($price); //Set the product's active price.
            $objProduct->set_regular_price($price); //Set the product's regular price.
            $objProduct->set_sale_price($amount); //Set the product's sale price.
            

            $objProduct->set_manage_stock(false); //Set if product manage stock.
            $objProduct->set_stock_status('instock'); //Set stock status.
            $objProduct->save(); //Saving the data to create new product, it w
            

            wp_set_object_terms($pid, array(
                $tags
            ) , 'product_tag');

            update_post_meta($pid, 'product_terms_condtion', $terms_condtion);
            update_post_meta($pid, 'product_tags', $tags);
            update_post_meta($pid, 'product_category', $category);
            update_post_meta($pid, 'product_type', $product_type);

            $product_details = get_permalink($pid);
            header('Location:' . $product_details);

        }
        else
        {

?>
       <div >
      <form method="POST" action="" name="productedit" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>


     <div class="mb-3">          
<label><?php _e('Title', 'aistore'); ?></label><br/>
  <input class="input" type="text" id="product_name" name="product_name" value="<?php echo esc_attr($objProduct->get_title()); ?>"></div><br>
  

  
  
     <div class="mb-3">          
<label><?php _e('Regular price ', 'aistore');
            echo "(" . get_woocommerce_currency_symbol() . ")"; ?></label><br/>
  <input class="input" type="text" id="price" name="price" value="<?php echo esc_attr($regular_price); ?>"></div><br>
  
  
       <div class="mb-3">          
<label><?php _e('Sale price ', 'aistore');
            echo "(" . get_woocommerce_currency_symbol() . ")"; ?></label><br/>
  <input class="input" type="text" id="amount" name="amount" value="<?php echo esc_attr($amount); ?>"></div><br>
  
     <div class="mb-3">   
 <label>  <?php _e('Product Type', 'aistore'); ?> </label><br>
 
 
 
<br>  

<?php
            $type = get_post_meta($pid, '_downloadable', true);
            if ($type == 'yes')
            {
?>
 <input checked type="checkbox" id="downloadable" name="product_type" value="downloadable"  onclick="myFunction()">
   <?php _e('Downloadable', 'aistore');

            }
            else
            {

?> 
  

   
       <input   checked type="checkbox" id="virtual" name="product_type" value="Virtual">
  <?php _e('Virtual', 'aistore');
            }

?></div><br><br>
   


  
  
  
  </div><br>
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

                if (get_post_meta($pid, 'product_category', true) == $category->name)
                {
                    echo '	<option selected value="' . $category->name . '">' . $category->name . '</option>';

                }
                else
                {

                    echo '	<option value="' . $category->name . '">' . $category->name . '</option>';

                } ?>
<?php
            }

?>
  </select></div><br><br>





  <?php
            $downloadable_files = get_post_meta($pid, '_downloadable_files', true);

            $tags = get_post_meta($pid, 'product', true);

            $product_terms = wp_get_object_terms($pid, 'product_tag', $args = array());

            if ($type == 'yes')
            {
?>
     <div class="mb-3">  
<img src="<?php echo $downloadable_files; ?>" style="width:250px; height:250px;"></div><br>

<?php
            }
?>

     <div class="mb-3">   
     
  <label><?php _e('Tags', 'aistore'); ?></label><br>
  <textarea id="tags" name="tags" rows="3" cols="50" ><?php echo $product_terms[0]->name; ?>
</textarea>   </div>   <br>


   <div class="mb-3">    <label><?php _e('Short Description', 'aistore'); ?></label><br/>

   <?php
            $content = $objProduct->get_short_description();
            $editor_id = 'short_description';

            wp_editor($content, $editor_id, $editor);

?></div>
   
 <br>
 
    <div class="mb-3">   <label><?php _e('Full Description', 'aistore'); ?></label>
 <br/>
   <?php
            $content = $objProduct->get_description();

            $editor_id = 'full_description';

            wp_editor($content, $editor_id, $editor);

?>
 </div>

  
  <br>
     <div class="mb-3">   
  <label><?php _e('Terms and conditions', 'aistore'); ?></label><br/>

   <?php
            $content = get_post_meta($pid, 'product_terms_condtion', true);
            $editor_id = 'terms_condtion';

            wp_editor($content, $editor_id, $editor);

?></div>
  <br>             

     <div class="mb-3">   
  <input  type="submit"  name="submit" value="<?php _e('Submit', 'aistore') ?>">
  <input type="hidden" name="action" value="productedit" />
</form>

<br>


</div></div>
      <?php

        }

        ///sells report
        
?>
<div>
<?php
        $user_id = get_current_user_id();

        $orders1 = $wpdb->prepare("SELECT distinct order_id FROM {$wpdb->prefix}system_orders WHERE product_owner_user_id=%d and product_id = %d", $user_id, $pid);

        $orders = $wpdb->get_results($orders1);
?>
	<h3><u><?php _e('Sells Report', 'aistore'); ?></u> </h3><br>
	<?php
        if ($orders == null)
        {
            _e('No data availale', 'aistore');

        }
        else
        {
?>
<div>
 <table class="table">
         <tr>
      
     <th><?php _e('Order', 'aistore'); ?></th>
         <th><?php _e('Product Name', 'aistore'); ?></th>
         	  <th><?php _e('Amount', 'aistore'); ?></th>
          <th><?php _e('Status', 'aistore'); ?></th> 
	
		 
		   
</tr>

    <?php

            foreach ($orders as $row)
            {

                $edit_order_page_id_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('edit_order_page_id') ,
                    'oid' => $row->order_id,
                ) , home_url()));

                $order_details = new WC_Order($row->order_id);

                $order_data = $order_details->get_data();
                $status = $order_data['status'];

                $items = $order_details->get_items();

                foreach ($items as $item)
                {
                    $product_name = $item['name'];
                    $product_id = $item['product_id'];
                    $subtotal = $item['subtotal'];
                    $total = $item['total'];
                    $quantity = $item['quantity'];
                    $currency = $item['currency'];

                    // print_r($item);
                    
?> 
      <tr>
           
 
		   <td> 	<a href="<?php echo esc_url($edit_order_page_id_url); ?>" >
		   <?php echo esc_attr($row->order_id); ?> </a> </td>
		    <td><?php echo esc_attr($product_name); ?></td>
		   	   <td> 		 <?php echo esc_attr($currency); ?>  <?php echo esc_attr($total); ?> for <?php echo esc_attr($quantity); ?> </td>
		   	
		   	       <td> 		   <?php echo esc_attr($status); ?> </td>
    </tr>
    <?php

                }
            }
        }
?>
   </table>
    </div></div>
    <?php
        return ob_get_clean();

    }

    public static function aistore_product_list()
    {
        
            if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
        global $wpdb;
        $user_id = get_current_user_id();

        $user_role = get_user_meta($user_id, 'user_role', true);
        // echo "ssdf".$user_role;
        if ($user_role != 'Vendor')
        {
            _e('<div>You are not vendor please contact to admin</div>', 'aistore');
        }

        else
        {

            if (isset($_POST['submit']) and $_POST['action'] == 'product_status')
            {

                if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                {
                    return _e('Sorry, your nonce did not verify', 'aistore');

                }

                $product_id = sanitize_text_field($_REQUEST['product_id']);

                $product_status = sanitize_text_field($_REQUEST['product_status']);

                $my_post = array(
                    'ID' => $product_id,
                    'post_status' => $product_status,
                );

                wp_update_post($my_post);
            }

?>
	<h3><u><?php _e('Product List', 'aistore'); ?></u> </h3> 
 
    <table class="table">
     
      
    <?php

            $results_product = get_posts(array(
                'post_status' => array(
                    'publish',
                    'pending',
                    'draft',
                    'trash'
                ) ,
                'post_type' => 'product',
                'post_author' => $user_id

            ));

            if ($results_product == null)
            {
                _e('Product List Not Found', 'aistore');

            }
            else
            {
?>
	    
        <tr>
      
     <th><?php _e('Id', 'aistore'); ?></th>
        
          <th><?php _e('Title', 'aistore'); ?></th> 
          <th><?php _e('Amount', 'aistore'); ?></th>
		  <th><?php _e('Date', 'aistore'); ?></th>
		   <th><?php _e('Status', 'aistore'); ?></th>
		    <th><?php _e('Total Sells', 'aistore'); ?></th>
		     <th><?php _e('Total add to cart', 'aistore'); ?></th>
		    <th colspan="2"><?php _e('Action', 'aistore'); ?></th>
		 
		    
</tr>
<?php
                foreach ($results_product as $row):

                    $edit_product_page_id_url = esc_url(add_query_arg(array(
                        'page_id' => get_option('edit_product_page_id') ,
                        'pid' => $row->ID,
                    ) , home_url()));

                    $actual_link = get_site_url();

                    $view_product_page_id_url = get_post_permalink($row->ID, $leavename = false, $sample = false);
                    $product = wc_get_product($row->ID);

                    $post_author = $row->post_author;
                      $amount = $product->get_price();

                    $order2 = $wpdb->get_row($wpdb->prepare("SELECT count(*) as c FROM {$wpdb->prefix}system_orders WHERE product_owner_user_id=%d and product_id=%d ", $user_id, $row->ID));

                    $cart = $wpdb->get_row($wpdb->prepare("SELECT count(*) as cart FROM {$wpdb->prefix}add_to_cart_report WHERE user_id=%d and product_id=%d ", $user_id, $row->ID));

                    echo '<tr>';

                    if ($post_author == $user_id)
                    {
?>
  
		   <td> 	<a href="<?php echo esc_url($edit_product_page_id_url); ?>" >
		   <?php echo esc_attr($row->ID); ?> </a> </td>
		   
		 
		  
		
		  

		   <td> 		   <?php echo esc_attr($row->post_title); ?></td>
		    <td>    <?php echo esc_attr($amount) . " " . get_woocommerce_currency_symbol(); ?> </td>
		   <td> 		   <?php echo esc_attr($row->post_date); ?> </td>
		   
		     <td> 		   <?php echo esc_attr($row->post_status); ?> </td>
		        <td><?php echo esc_attr($order2->c); ?></td>
		          <td><?php echo esc_attr($cart->cart); ?></td>
		          
 <td><a href="<?php echo esc_url($view_product_page_id_url); ?>" >
		   View</a></td>
		   
		    
		          <td> 
		          
		       
		 
      <form method="POST" action="" name="product_status" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input type="hidden" name="product_id" value="<?php echo $row->ID; ?>" />
  
  <?php
                        if ($row->post_status == 'publish')
                        {
?>
 <input  type="submit"  name="submit" value="<?php _e('Unpublish', 'aistore') ?>">
   
  <input type="hidden" name="product_status" value="draft" />
 <?php
                        } ?>
 
  <?php
                        if ($row->post_status == 'draft')
                        {
?>
 <input  type="submit"  name="submit" value="<?php _e('Publish', 'aistore') ?>">
   
  <input type="hidden" name="product_status" value="publish" />
 <?php
                        } ?>
 
  <input type="hidden" name="action" value="product_status" />

  
</form>
 

</td>

<?php
                    } ?>
 
    </tr>
    <?php

                endforeach;

            } ?>



    </table>
    <?php
            return ob_get_clean();
        }
    }

    public static function aistore_add_product()
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

    static function aistore_order_list()
    {
        if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
        global $wpdb;

        $user_id = get_current_user_id();
        $user_role = get_user_meta($user_id, 'user_role', true);

        if ($user_role != 'Vendor')
        {
            _e('<div>You are not vendor please contact to admin</div>', 'aistore');
        }

        else
        {
            $orders1 = $wpdb->prepare("SELECT distinct order_id FROM {$wpdb->prefix}system_orders WHERE product_owner_user_id=%d", $user_id);
            //echo $orders1;
            $orders = $wpdb->get_results($orders1);

            if ($orders == null)
            {
                _e('No data availale', 'aistore');

            }
            else
            {

?>
	<h3><u><?php _e('Order List', 'aistore'); ?></u> </h3><br>

  
    <table class="table">
     
      
        <tr>
      
     <th><?php _e('Order', 'aistore'); ?></th>
         <th><?php _e('Product Name', 'aistore'); ?></th>
         	  <th><?php _e('Amount', 'aistore'); ?></th>
          <th><?php _e('Status', 'aistore'); ?></th> 
	
		 
		   
</tr>

    <?php

                foreach ($orders as $row)
                {

                    $edit_order_page_id_url = esc_url(add_query_arg(array(
                        'page_id' => get_option('edit_order_page_id') ,
                        'oid' => $row->order_id,
                    ) , home_url()));

                    $order_details = new WC_Order($row->order_id);

                    $order_data = $order_details->get_data();
                    $status = $order_data['status'];

                    $items = $order_details->get_items();

                    foreach ($items as $item)
                    {
                        $product_name = $item['name'];
                        $product_id = $item['product_id'];
                        $subtotal = $item['subtotal'];
                        $total = $item['total'];
                        $quantity = $item['quantity'];
                        $currency = $item['currency'];

?> 
      <tr>
           
 
		   <td> 	<a href="<?php echo esc_url($edit_order_page_id_url); ?>" >
		   <?php echo esc_attr($row->order_id); ?> </a> </td>
		    <td><?php echo esc_attr($product_name); ?></td>
		   	   <td> 		 <?php echo esc_attr($currency); ?>  <?php echo esc_attr($total); ?> for <?php echo esc_attr($quantity); ?> </td>
		   	
		   	       <td> 		   <?php echo esc_attr($status); ?> </td>
	
	 
		   
		 
		          
		  
    </tr>
    <?php
                    }

                }
            }

?>



    </table>
    <?php
            return ob_get_clean();

        }
    }

    public static function aistore_order_edit()
    {
        
            if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
    
        global $wpdb;

        $oid = sanitize_text_field($_REQUEST['oid']);

        $order = new WC_Order($oid);

        if (isset($_POST['submit']) and $_POST['action'] == 'order_status')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $order_id = sanitize_text_field($_REQUEST['order_id']);
            $status = sanitize_text_field($_REQUEST['status']);

            if (!empty($order))
            {
                $order->update_status($status);

            }

        }

?>
      
      <div>
      <?php

        $order_data = $order->get_data();
        $order_status = $order_data['status'];
        $order_billing_first_name = $order_data['billing']['first_name'];
        $order_billing_last_name = $order_data['billing']['last_name'];
        $order_billing_company = $order_data['billing']['company'];
        $order_billing_address_1 = $order_data['billing']['address_1'];
        $order_billing_address_2 = $order_data['billing']['address_2'];
        $order_billing_city = $order_data['billing']['city'];
        $order_billing_state = $order_data['billing']['state'];
        $order_billing_postcode = $order_data['billing']['postcode'];
        $order_billing_country = $order_data['billing']['country'];
        $order_billing_email = $order_data['billing']['email'];
        $order_billing_phone = $order_data['billing']['phone'];

        $items = $order->get_items();

        foreach ($items as $item)
        {
            $product_name = $item['name'];
            $product_id = $item['product_id'];
            $subtotal = $item['subtotal'];
            $total = $item['total'];
            $quantity = $item['quantity'];
            $currency = $item['currency'];
            $status = $item['status'];
            $order_billing_first_name = $order_data['billing']['first_name'];

            echo "<h4>Order # " . esc_attr($oid) . " was placed on " . esc_attr($product_name) . " and is currently " .

            esc_attr($order_status) . "</h4><br>";

?>

	
	<h2><?php _e('Order details', 'aistore'); ?></h2>

	<table class="table">

		<thead>
			<tr>
				<th ><?php _e('Product', 'aistore'); ?></th>
				<th ><?php _e('Total', 'aistore'); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr>

	<td>
		<?php echo esc_attr($product_name); ?> <strong >×&nbsp;<?php echo esc_attr($quantity) ?></strong>	</td>

	<td >	<span ><span><?php echo esc_attr($currency); ?></span><?php echo esc_attr($subtotal); ?></span>	</td>

</tr>
	<tr>

	<td>
<?php _e('Subtotal :', 'aistore'); ?>		</td>

	<td >
	<span ><?php echo esc_attr($currency); ?></span><?php echo esc_attr($subtotal); ?></span></td>

</tr>
	
	<tr>

	<td>
<?php _e('Payment method:', 'aistore'); ?>		</td>

	<td >
	<span ><?php echo esc_attr($currency); ?></span><?php echo esc_attr($total); ?></span></td>

</tr>

	<tr>

	<td>
<?php _e('Total:', 'aistore'); ?> 	</td>

	<td >
	<span ><?php echo esc_attr($currency) ?></span><?php echo esc_attr($total) ?></span></td>

</tr>
					
					</tbody>
	</table>
	
	<br>


	
	<h2><?php _e('Billing address', 'aistore'); ?></h2>

	<address>
		<?php echo esc_attr($order_billing_company) ?><br><?php echo esc_attr($order_billing_first_name) . " " . esc_attr($order_billing_last_name); ?><br>
		<?php echo esc_attr($order_billing_address_1) ?><br>	<?php echo esc_attr($order_billing_address_2) ?><br>	<?php echo esc_attr($order_billing_city) . " " . esc_attr($order_billing_postcode); ?><br>
		<?php echo esc_attr($order_billing_state) . " " . esc_attr($order_billing_country); ?>
		<br>	<br>
		 
			</address>

	
	<br>


	<?php
        }

?>
         
         <form method="POST" action="" name="order_status" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

          <div class="mb-3">             
  <label for="status"><?php _e('Status', 'aistore'); ?></label><br>
 <select class="form-select" aria-label="Default select example" name="status">
  <option selected><?php _e('select product', 'aistore') ?></option>
  <option value="wc-pending"><?php _e('Pending Payment', 'aistore') ?></option>
  <option value="wc-processing"><?php _e('Processing', 'aistore') ?></option>
  <option value="wc-on-hold"><?php _e('On hold', 'aistore') ?></option>
  <option value="wc-completed"><?php _e('Completed', 'aistore') ?></option>
   <option value="wc-cancelled"><?php _e('Cancelled', 'aistore') ?></option>
  <option value="wc-refunded"><?php _e('Refunded', 'aistore') ?></option>
   <option value="wc-failed"><?php _e('Failed', 'aistore') ?></option>
</select></div><br><br>

<input type="hidden" value="<?php echo esc_attr($oid); ?>" name="order_id">

  <input  type="submit"  name="submit" value="<?php _e('Submit', 'aistore') ?>">
  <input type="hidden" name="action" value="order_status" />
</form>

<br>
  
  </div>
  <?php
    }

    function aistore_cart_report()
    {
            if (!is_user_logged_in())
    {
        return "<div class='no-login'>Kindly login and then visit this page </div>";
    }
    
        global $wpdb;
        $current_user_id = get_current_user_id();
?>
	<h3><u><?php _e('Cart Report', 'aistore'); ?></u> </h3> <br>
 
    <table class="table">
     
      
  
    <?php

        $results_cart = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}add_to_cart_report WHERE user_id=%s  order by id desc", $current_user_id)
);

        if ($results_cart == null)
        {
            _e('No data availale', 'aistore');

        }
        else
        {

?>
	          <tr>
      
     <th><?php _e('Id', 'aistore'); ?></th>
        
          <th><?php _e('Product Id', 'aistore'); ?></th> 
          <th><?php _e('Product Title', 'aistore'); ?></th>
          
		  <th><?php _e('Date', 'aistore'); ?></th>
	
		    
</tr>
<?php
            foreach ($results_cart as $row):

?>


<tr>
		   <td> 
		   <?php echo esc_attr($row->id); ?></td>
		   
		 
		  
		   <td> 		   <?php echo esc_attr($row->product_id); ?></td>
		     <td> 		   <?php echo get_the_title($row->product_id); ?> </td>
		
		   <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
		     
		         
		 
    

</td>
 
    </tr>
    <?php

            endforeach;

        } ?>



    </table>
    <?php
        return ob_get_clean();

    }

}

