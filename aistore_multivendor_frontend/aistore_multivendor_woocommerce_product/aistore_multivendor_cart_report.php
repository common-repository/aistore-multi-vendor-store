<?php



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

        $results_cart = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_multivendor_add_to_cart_report WHERE user_id=%s  order by id desc", $current_user_id)
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
?>
