<?php


  class AistoreSellsReport
  {
      
  
function aistore_page_setting_product_sells() {
    
   global  $wpdb;
?>
 <div class="wrap">
<h3><u><?php   _e( 'Order List', 'aistore' ); ?></u> </h3> 
    <table class="wp-list-table widefat fixed striped table-view-list  ">
<?php
   
   
         if(isset($_REQUEST['id'])  )
{
      $id =sanitize_text_field($_REQUEST['id']);
      
         if($id>0){
 
 
$system_orders = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}system_orders WHERE product_owner_user_id=%d", $id);
$orders=$wpdb->get_results( $system_orders);
}
 else
 
{   $user=0;
   $orders=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}system_orders ");
}

}
 else
{   $id=0;
    
 $orders=$wpdb->get_results( "SELECT * FROM {$wpdb->prefix}system_orders ");
    
}


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
</div>
	 
<?php }

}

