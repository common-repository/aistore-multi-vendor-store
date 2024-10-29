<?php
 function aistore_order_edit()
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