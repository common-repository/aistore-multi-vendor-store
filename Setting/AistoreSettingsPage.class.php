<?php




class AistoreSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'aistore_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'aistore_page_register_setting' ) );
        
    

	
    }

    /**
     * Add options page
     */
public function aistore_add_plugin_page()
{
    // This page will be under "Settings"
  
    
    
    
    
    add_menu_page(__( 'Aistore multi vendor store', 'aistore' ),  __('Aistore multi vendor store', 'aistore' ), 'administrator', 'vendor_store');
    

    add_submenu_page('vendor_store', __('Product List','aistore'), __('Product List','aistore'), 'administrator', 'vendor_store', array(
        $this,
        'aistore_product_list'
    ));
    
  add_submenu_page('vendor_store', __('Sells List','aistore'), __('Sells List','aistore'), 'administrator', 'aistore_sells_list', array(
        $this,
        'aistore_sells_list'
    ));
    
      add_submenu_page('vendor_store', __('Add To Cart Report','aistore'), __('Add To Cart Report','aistore'), 'administrator', 'aistore_cart_list', array(
        $this,
        'aistore_cart_list'
    ));
    
    
    
    add_submenu_page('vendor_store', __('Setting','aistore'), __('Setting','aistore'), 'administrator', 'aistore_page_setting', array(
        $this,
        'aistore_page_setting'
    ));
    
    
}
function aistore_cart_list(){
    $data=new AddToCartReport();
    
    $full_data =  $data->aistore_page_setting_cart();
  echo $full_data;
}

function aistore_sells_list()
{
  $data=new AistoreSellsReport();
    
    $full_data =  $data->aistore_page_setting_product_sells();
  echo $full_data;

}

// disputed escrow list

function aistore_product_list()
{
 
    $data=new AistoreProductPage();
    
    $full_data =  $data->aistore_page_setting_product();
  //echo $full_data;

}


// page Setting

function aistore_page_register_setting() {
	//register our settings
	register_setting( 'aistore_page', 'add_product_page_id' );
	register_setting( 'aistore_page', 'list_product_page_id' );
	register_setting( 'aistore_page', 'edit_product_page_id' );
    register_setting( 'aistore_page', 'list_order_page_id' );
	register_setting( 'aistore_page', 'edit_order_page_id' );
	register_setting( 'aistore_page', 'commission_fee' );
	register_setting( 'aistore_page', 'product_show' );




}

 function aistore_page_setting() {
     $pages = get_pages(); 
?>	 
	 <h1><?php   _e( 'Aistore multi vendor store', 'aistore' ); ?></h1>
  
  

 
<form method="post" action="options.php">
    
    <?php settings_fields( 'aistore_page' ); ?>
    <?php do_settings_sections( 'aistore_page' ); ?>
    
    <table class="form-table">
         <tr valign="top">
        <th scope="row"><?php  _e( 'Add Product', 'aistore' ) ?></th>
        <td>
		<select name="add_product_page_id"  >
		 
		 
     <?php 

                    foreach($pages as $page){ 
					
					if($page->ID==get_option('add_product_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
</select>


<p>Create a page add this shortcode <strong> [aistore_add_product] </strong> and then select that page here. </p>

</td>
        </tr>  
        
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Product List', 'aistore' ) ?></th>
        <td>
		<select name="list_product_page_id"  >
		 
		 
     <?php 

                    foreach($pages as $page){ 
					
					if($page->ID==get_option('list_product_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
</select>


<p>Create a page add this shortcode <strong> [aistore_product_list] </strong> and then select that page here. </p>

</td>
        </tr> 
        
        
            <tr valign="top">
        <th scope="row"><?php  _e( 'Edit Product', 'aistore' ) ?></th>
        <td>
		<select name="edit_product_page_id"  >
		 
		 
     <?php 

                    foreach($pages as $page){ 
					
					if($page->ID==get_option('edit_product_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
</select>


<p>Create a page add this shortcode <strong> [aistore_product_edit] </strong> and then select that page here. </p>

</td>
        </tr> 
        
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Order List', 'aistore' ) ?></th>
        <td>
		<select name="list_order_page_id"  >
		 
		 
     <?php 

                    foreach($pages as $page){ 
					
					if($page->ID==get_option('list_order_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
</select>


<p>Create a page add this shortcode <strong> [aistore_order_list] </strong> and then select that page here. </p>

</td>
        </tr> 
        <br><br><br>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Edit Order', 'aistore' ) ?></th>
        <td>
		<select name="edit_order_page_id"  >
		 
		 
     <?php 

                    foreach($pages as $page){ 
					
					if($page->ID==get_option('edit_order_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
</select>


<p>Create a page add this shortcode <strong> [aistore_order_edit] </strong> and then select that page here. </p>

</td>
        </tr> 
        
         <tr valign="top">
 <th scope="row"><?php  _e( 'Product show  or not in account menu', 'aistore' ) ?></th>
        <td>
            <?php $msg_value=get_option('product_show');?>
            
            <select name="product_show" id="product_show">
               
            <option selected value="yes" <?php selected(
                $msg_value,
                'yes'
            ); ?>>Yes</option>
            <option value="no" <?php selected(
                $msg_value,
                'no'
            ); ?>>No</option>
  
</select>
	
</td>
        </tr> 
        	
        <tr valign="top">
        <th scope="row"><?php   _e( 'Commission Fee', 'aistore' ); ?></th>
        <td><input type="text" name="commission_fee" value="<?php echo esc_attr( get_option('commission_fee') ); ?>" />%</td>
        </tr>
         
      
      	


    </table>
    
    <?php submit_button(); ?>

</form>
</div>
</div>
 <?php
	 
 }



}


    


if( is_admin() )
    $AistoreSettingsPage = new AistoreSettingsPage(); 
