<?php
add_filter('single_template', 'SCS_aistore_template_test');

function SCS_aistore_template_test($single)
{
    global $post;

    $dir = plugin_dir_path(__FILE__);
    // /* Checks for single template by post type */
    
}

function SCS_aistore_save_meta_box_data_test($post_id)
{
    // Check if our nonce is set.
    if (!isset($_POST['aistore_nonce']))
    {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce'))
    {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
    {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type'])
    {
        if (!current_user_can('edit_page', $post_id))
        {
            return;
        }
    }
    else
    {
        if (!current_user_can('edit_post', $post_id))
        {
            return;
        }
    }

   echo "sdsd";
}

add_action('save_post', 'SCS_aistore_save_meta_box_data_test');

function SCS_aistore_meta_box_callback_transaction($post)
{
    // Add a nonce field so we can check for it later. SCS_aistore_meta_box_callback
    wp_nonce_field('aistore_nonce', 'aistore_nonce');
?>
 <br>
  
  <h1>Transaction  Report</h1>
  
  
  
<?php

$transactions = get_wallet_transactions();

// print_r($transactions);

do_action( 'woo_wallet_before_transaction_details_content' );
?>
<p><?php _e( 'Current balance :', 'woo-wallet' ); ?> <?php echo woo_wallet()->wallet->get_wallet_balance( get_current_user_id() ); ?>

<a href="<?php echo is_account_page() ? esc_url( wc_get_account_endpoint_url( get_option( 'woocommerce_woo_wallet_endpoint', 'woo-wallet' ) ) ) : get_permalink(); ?>"><span class="dashicons dashicons-editor-break"></span></a></p>

<table id="wc-wallet-transaction-details" class="table"></table>
<?php do_action( 'woo_wallet_after_transaction_details_content' );
}

function SCS_aistore_meta_box_callback_selected_sells($post)
{
    wp_nonce_field('aistore_nonce', 'aistore_nonce');

?>
<br>
    <h1>Sells  Report</h1><br>
      <table class="wp-list-table widefat fixed striped table-view-list  ">
<?php
       
   global  $wpdb;
   
       $author_id = $post->post_author;
          $id = $post->ID;
 
//  echo $id;
$system_orders = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}system_orders WHERE product_owner_user_id=%d and product_id = %d", $author_id,$id);
$orders=$wpdb->get_results( $system_orders);

 

  


 if($orders==null)
	{
	    _e( 'No data availale', 'aistore' );
	
	}
	else{
?>

     
      <thead>
        <tr>
      
     <th><?php   _e( 'Order', 'aistore' ); ?></th>
         <th><?php   _e( 'Product Name', 'aistore' ); ?></th>
         	  <th><?php   _e( 'Amount', 'aistore' ); ?></th>
          <th><?php   _e( 'Status', 'aistore' ); ?></th> 
	
		 
		   
</tr>
</thead>
<tbody>
    <?php 
    
     

    foreach($orders as $row):
	
	 $url=	 admin_url( 'post.php?post='.$row->order_id.'&action=edit'  );
	 
	 

$order_details = new WC_Order($row->order_id);
$order_data = $order_details->get_data();
$status = $order_data['status'];


  
   $product_order = $order_details->get_items();

foreach ( $product_order as $item ) {
    $product_name = $item['name'];
    $product_id = $item['product_id'];
    $subtotal = $item['subtotal'];
      $total = $item['total'];
     $quantity = $item['quantity'];
            $currency = $item['currency'];
   


$amount=$currency." ".$total." for ".$quantity;
//USD 1 for 1 

    ?> 
    
      <tr>
           
 
		   <td> 	 	<a href="<?php echo esc_url($url); ?>" >
		   <?php echo esc_attr($row->order_id) ; ?> </a> </td>
		   
		   
		    <td><?php echo esc_attr($product_name); ?></td>
		   	   <td> 		 <?php echo esc_attr($amount); ?> </td>
		   	
		   	       <td> 		   <?php echo esc_attr($status) ; ?> </td>
	
	 
		   
		 
		          
		  
    </tr>
 
    <?php 
}
    
    endforeach;
	}	
	
	?>
   </tbody>


    </table>
<?php

}

function SCS_aistore_meta_box_test()
{
    $screens = ['product'];

    foreach ($screens as $screen)
    {
        add_meta_box('aistore_test', __('Transaction Report', 'aistore') , 'SCS_aistore_meta_box_callback_transaction', $screen);

        add_meta_box('aistore_selected_test', __('Sells Report', 'aistore') , 'SCS_aistore_meta_box_callback_selected_sells', $screen);

    }
}

add_action('add_meta_boxes', 'SCS_aistore_meta_box_test');


?>
