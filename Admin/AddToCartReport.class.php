<?php
class AddToCartReport{
  
function aistore_page_setting_cart() {


 global $wpdb;
 
 ?>

 	<h3><u><?php   _e( 'Cart Report', 'aistore' ); ?></u> </h3> <br>
 
    <table class="wp-list-table widefat fixed striped table-view-list ">
     
      
     
    <?php 



  $results_cart =$wpdb->get_results("SELECT * FROM {$wpdb->prefix}add_to_cart_report order by id desc");
 

    if($results_cart==null)
	{
	    _e( 'No data availale', 'aistore' );
	
	}
	else{
	    ?>
	       <tr>
      
     <th><?php   _e( 'Id', 'aistore' ); ?></th>
        
          <th><?php   _e( 'Product Id', 'aistore' ); ?></th> 
          <th><?php   _e( 'Product Title', 'aistore' ); ?></th>
           <th><?php   _e( 'Username', 'aistore' ); ?></th> 
          <th><?php   _e( 'Email', 'aistore' ); ?></th>
		  <th><?php   _e( 'Date', 'aistore' ); ?></th>
	
		    
</tr>
<?php
	    
	    
    foreach($results_cart as $row):
$user = get_user_by( 'id', $row->user_id ); 
	 $url=	 admin_url( 'post.php?post='.$row->product_id.'&action=edit'  );
	 ?>


<tr>
		   <td> 	<a href="<?php echo esc_url($url); ?>" >
		   <?php echo esc_attr($row->id) ; ?> </a> </td>
		   
		 
		  
		   <td> 		   <?php echo esc_attr($row->product_id) ; ?></td>
		     <td> 		   <?php 	 echo get_the_title( $row->product_id  ); ?> </td>
		<td><?php echo $user->user_login; ?></td>
		<td><?php echo $user->user_email; ?></td>
		   <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
		     
		         
		 
    

</td>
 
    </tr>
    <?php 

    endforeach;
	
}	?>



    </table>
    <?php 

}

}
