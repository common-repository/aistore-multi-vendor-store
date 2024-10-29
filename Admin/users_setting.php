<?php
//Total Orders


 
 function aistore_new_modify_user_table_total_orders( $column ) {

       
    $column['total_orders'] =  _e( 'Total Orders', 'aistore' ); 
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_new_modify_user_table_total_orders' );




function aistore_new_modify_user_table_row_total_orders( $val, $column_name, $user_id ) {



    switch ($column_name) {

 
        case 'total_orders':
             global $wpdb;
    $system_orders = $wpdb->get_row($wpdb->prepare( "SELECT count(*) as c FROM {$wpdb->prefix}system_orders WHERE product_owner_user_id=%d ",$user_id ));

 return $system_orders->c; 

   
        default:
    }


    return $val;

}


add_filter( 'manage_users_custom_column', 'aistore_new_modify_user_table_row_total_orders', 10, 3 );


//Total Products


 
 function aistore_new_modify_user_table_total_products( $column ) {

       
    $column['total_products'] =   _e( 'Total Products', 'aistore' ); 
    
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_new_modify_user_table_total_products' );




function aistore_new_modify_user_table_row_total_products( $val, $column_name, $user_id ) {


 $total=0;
    switch ($column_name) {

        case 'total_products':
                   global $wpdb;
     $product = $wpdb->get_row($wpdb->prepare( "SELECT count(*) as total_product FROM {$wpdb->prefix}posts WHERE post_author =%d and post_type=%s ",
     $user_id ,'product'));

 return $product->total_product; 

        default:
    }


    return $val;

}


add_filter( 'manage_users_custom_column', 'aistore_new_modify_user_table_row_total_products', 10, 3 );





 
 function aistore_new_modify_user_table_user_sells( $column ) {

       
    $column['user_sells'] = _e( 'Sells', 'aistore' );
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_new_modify_user_table_user_sells' );




function aistore_new_modify_user_table_row_user_sells( $val, $column_name, $user_id ) {



    switch ($column_name) {

 
        case 'user_sells':
  $url = admin_url('admin.php'); 
 
         $link= '<a href="'.$url.'?page=aistore_sells_list&id='.$user_id.'">User Sells</a>';
         
 $link_products= '<a href="'.$url.'?page=vendor_store&id='.$user_id.'">User Products</a>';
   
       
 return $link." <br><br> ".$link_products;

   
        default:
    }


    return $val;

}


add_filter( 'manage_users_custom_column', 'aistore_new_modify_user_table_row_user_sells', 10, 3 );


function aistore_users( $contactmethods ) {
    $contactmethods['approve_user'] = 'Approve User';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'aistore_users', 10, 1 );

 function aistore_new_modify_user_table_approve_user( $column ) {

       
    $column['approve_user'] =  _e( 'Approve User', 'aistore' ); 
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_new_modify_user_table_approve_user' );




function aistore_new_modify_user_table_row_approve_user( $val, $column_name, $user_id ) {



    switch ($column_name) {

 
        case 'approve_user':
             global $wpdb;
             
            if( get_the_author_meta('user_role', $user_id)!='Vendor'){
                
            
 if(isset($_POST['submit']) and $_POST['action']=='approve' )
{

if ( ! isset( $_POST["aistore_nonce"] ) 
    || ! wp_verify_nonce( $_POST["aistore_nonce"], "aistore_nonce_action" ) 
) {
   return  _e( "Sorry, your nonce did not verify", "aistore");
   exit;
} 
//echo get_the_author_meta('user_role', $user_id);

 $userid=sanitize_text_field($_REQUEST['userid']);
 //echo "xvxv".$userid;
  update_user_meta( $userid, 'user_role', 'Vendor'  ); 
}
else{
   $btn='
 
   <form method="POST" action="" name="approve" enctype="multipart/form-data">'.

 wp_nonce_field('aistore_nonce_action', 'aistore_nonce').'
 
 <input type="hidden" name="userid" value="'.$user_id.'">
<button type="submit"  name="submit" >Approve User for merchant</button>
<input type="hidden" name="action" value="approve" />
</form>
';
      
 return get_the_author_meta('user_role', $user_id).$btn;
 
}
            }
            
            else{
               return get_the_author_meta('user_role', $user_id);  
            }
        default:
    }


    return $val;




}
add_filter( 'manage_users_custom_column', 'aistore_new_modify_user_table_row_approve_user', 10, 3 );


?>