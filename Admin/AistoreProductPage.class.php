<?php

  class AistoreProductPage
  {
      
function aistore_page_setting_product() {

 global $wpdb;
 ?>
 <div class="wrap">
	<h3><u><?php   _e( 'Product List', 'aistore' ); ?></u> </h3>
    <table class="wp-list-table widefat fixed striped table-view-list  ">
        
        
      <?php
        if(isset($_REQUEST['id'])  )
{ 
      $user_id =sanitize_text_field($_REQUEST['id']);
      
      if($user_id>0){
 
 $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts where post_type='product' and post_author='%d' order by id desc",$user_id) 
                 );
      }
      
       else
 
{   $user_id=0;
  $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts where post_type='product' order by id desc" );

    
}
}
else{
$user_id=0;
$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}posts where post_type='product' order by id desc" );


}

    if($results==null)
	{
	    _e( 'Product List Not Found', 'aistore' );
	
	}
	else{
     
  ?>
 
        <tr>
      
     <th><?php   _e( 'Id', 'aistore' ); ?></th>
        
          <th><?php   _e( 'Title', 'aistore' ); ?></th> 
          <th><?php   _e( 'Amount', 'aistore' ); ?></th>
		  <th><?php   _e( 'Date', 'aistore' ); ?></th>
		   <th><?php   _e( 'Status', 'aistore' ); ?></th>
		    
</tr>

    <?php 
    
     $results_product_list = get_posts( array(
     'posts_per_page' => 8,
     'post_type'      => 'product',
     'post_author' => $user_id
) );

    foreach($results_product_list as $row):
	

	 
	 $url=	 admin_url( 'post.php?post='.$row->ID.'&action=edit'  );
	 
	 
 
	 
	 
	 
	 
$product =new WC_Product($row->ID ); 


$amount=$product->get_price();
    ?> 
      <tr>
           
 
		   <td> 	<a href="<?php echo esc_url($url); ?>" >
		   <?php echo esc_attr($row->ID) ; ?> </a> </td>
		  
		   <td> 		   <?php echo esc_attr($row->post_title) ; ?></td>
		    <td>    <?php echo esc_attr($amount) ; ?> </td>
		   <td> 		   <?php echo esc_attr($row->post_date) ; ?> </td>
		   
		     <td> 		   <?php echo esc_attr($row->post_status);?> </td>
		        
		          
		  
    </tr>
    <?php endforeach;
	
	}?>



    </table>

</div>
<?php
}
}