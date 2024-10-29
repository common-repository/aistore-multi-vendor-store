<?php

 function aistore_order_list()
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
            $orders1 = $wpdb->prepare("SELECT distinct order_id FROM {$wpdb->prefix}aistore_multivendor_system_orders WHERE product_owner_user_id=%d", $user_id);
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
