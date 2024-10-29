<?php
 function aistore_product_list()
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

                    $order2 = $wpdb->get_row($wpdb->prepare("SELECT count(*) as c FROM {$wpdb->prefix}aistore_multivendor_system_orders WHERE product_owner_user_id=%d and product_id=%d ", $user_id, $row->ID));

                    $cart = $wpdb->get_row($wpdb->prepare("SELECT count(*) as cart FROM {$wpdb->prefix}aistore_multivendor_add_to_cart_report WHERE user_id=%d and product_id=%d ", $user_id, $row->ID));

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

 ?>