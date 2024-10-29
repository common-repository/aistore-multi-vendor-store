<?php

 function aistore_product_edit()
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