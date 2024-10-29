<?php
add_filter ( 'woocommerce_account_menu_items', 'aistore_misha_log_history_link', 40 );
function aistore_misha_log_history_link( $menu_links ){
	
	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	+ array( 'withdrawal-request' => 'Withdrawal Request' )
	+ array( 'bank-account' => 'Bank Account Details' )
	+ array_slice( $menu_links, 5, NULL, true );
	
	return $menu_links;

}
/*
 * Step 2. Register Permalink Endpoint
 */
add_action( 'init', 'aistore_misha_add_endpoint' );
function aistore_misha_add_endpoint() {

	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation
	add_rewrite_endpoint( 'withdrawal-request', EP_PAGES );
		add_rewrite_endpoint( 'bank-account', EP_PAGES );
	
}
/*
 * Step 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
 */
add_action( 'woocommerce_account_withdrawal-request_endpoint', 'aistore_misha_my_account_endpoint_content' );
function aistore_misha_my_account_endpoint_content() {

	// of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()

echo do_shortcode( '[aistore_saksh_withdrawal_system]' );
}


add_action( 'woocommerce_account_bank-account_endpoint', 'aistore_my_account_endpoint_content_bank_account' );
function aistore_my_account_endpoint_content_bank_account() {

	// of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()

echo do_shortcode( '[aistore_bank_account]' );
}



add_action( 'show_user_profile', 'aistore_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'aistore_extra_user_profile_fields' );

function aistore_extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Add Bank Details", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="bank_account_name"><?php _e("Account Name"); ?></label></th>
        <td>
            <input type="text" name="bank_account_name" id="bank_account_name" value="<?php echo esc_attr( get_the_author_meta( 'bank_account_name', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your bank acccount name."); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="bank_account"><?php _e("Account Number"); ?></label></th>
        <td>
            <input type="text" name="bank_account" id="bank_account" value="<?php echo esc_attr( get_the_author_meta( 'bank_account', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your bank account number."); ?></span>
        </td>
    </tr>
    <tr>
    <th><label for="name_of_bank"><?php _e("Name Of Bank"); ?></label></th>
        <td>
            <input type="text" name="name_of_bank" id="name_of_bank" value="<?php echo esc_attr( get_the_author_meta( 'name_of_bank', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your name of bank."); ?></span>
        </td>
    </tr>
    
    
      <tr>
    <th><label for="ifsc_code"><?php _e("IFSC Code"); ?></label></th>
        <td>
            <input type="text" name="ifsc_code" id="ifsc_code" value="<?php echo esc_attr( get_the_author_meta( 'ifsc_code', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your IFSC CodeY."); ?></span>
        </td>
    </tr>
    
      <tr>
    <th><label for="IBAN"><?php _e("IBAN"); ?></label></th>
        <td>
            <input type="text" name="IBAN" id="IBAN" value="<?php echo esc_attr( get_the_author_meta( 'IBAN', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your IBAN."); ?></span>
        </td>
    </tr>
     <tr>
    <th><label for="BIC"><?php _e("BIC"); ?></label></th>
        <td>
            <input type="text" name="BIC" id="BIC" value="<?php echo esc_attr( get_the_author_meta( 'BIC', $user->ID ) ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your BIC."); ?></span>
        </td>
    </tr>
    
     
    
    
    
         <tr>
    <th><label for="lock_bank_details"><?php _e(" Lock Bank Details"); ?></label></th>
        <td>
             <?php
    if( esc_attr( get_the_author_meta( 'lock_bank_details', $user->ID ) )==0){
        
    ?>
              <input type="checkbox" id="lock_bank_details" name="lock_bank_details" value="1"><br />
       <?php }
       else{
           ?>
           <input type="checkbox" id="lock_bank_details" name="lock_bank_details" value="1" checked><br />
           <?php }
           ?>
       
        </td>
    </tr>
    </table>
<?php }


add_action( 'personal_options_update', 'aistore_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'aistore_save_extra_user_profile_fields' );

function aistore_save_extra_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    
 update_user_meta( $user_id, 'bank_account_name', sanitize_text_field($_POST['bank_account_name']) );
 update_user_meta( $user_id, 'bank_account', intval($_POST['bank_account']) );
 update_user_meta( $user_id, 'name_of_bank', sanitize_text_field($_POST['name_of_bank'] ));
  update_user_meta( $user_id, 'ifsc_code', sanitize_text_field($_POST['ifsc_code']) );
 update_user_meta( $user_id, 'IBAN', sanitize_text_field($_POST['IBAN']) );
 update_user_meta( $user_id, 'BIC', sanitize_text_field($_POST['BIC'] ));
  update_user_meta( $user_id, 'lock_bank_details', sanitize_text_field($_POST['lock_bank_details'] ));
}

function aistore_saksh_add_plugin_page() {
    add_menu_page(
        __( 'Withdrawal', 'aistore' ),
        'Withdrawal',
        'administrator',
        'withdrawal',
        'aistore_saksh_page_setting'
    );
}

add_action( 'admin_menu', 'aistore_saksh_add_plugin_page' );
  


function aistore_saksh_page_setting() {
    
       ?>
      
			
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
       <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>




       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<br>
       <div class="container">


		
<?php



       
global  $wpdb;


  


 $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}widthdrawal_requests");
 
         if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('No Withdraw Found', 'aistore');
            echo "</div>";
        }
        else
        {

     foreach($results as $row):
    
    
    
             

   $users = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}users WHERE user_email=%s ",$row->username));

$bank_account_name= esc_attr( get_the_author_meta( 'bank_account_name', $users->ID ) );
$bank_account= esc_attr( get_the_author_meta( 'bank_account', $users->ID ) );

$name_of_bank= esc_attr( get_the_author_meta( 'name_of_bank', $users->ID ) );
$ifsc_code= esc_attr( get_the_author_meta( 'ifsc_code', $users->ID ) );

$IBAN= esc_attr( get_the_author_meta( 'IBAN', $users->ID ) );
$BIC= esc_attr( get_the_author_meta( 'BIC', $users->ID ) );

    ?>
 
          <div class="accordion" id="accordionExample">
              
  <div class="accordion-item">
    <h2 class="accordion-header" id="heading<?php echo $row->id ; ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo esc_attr($row->id) ; ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_attr($row->id ); ?>">
        #<?php echo esc_attr($row->id) ; ?> :  <?php echo esc_attr($row->username) ; ?>
      </button>
    </h2>
    <div id="collapse<?php echo esc_attr($row->id) ; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?php echo esc_attr($row->id) ; ?>" data-bs-parent="#accordionExample">
      <div class="accordion-body">
      <table class="widefat fixed striped">
        
   
<table>
    
      <tr>
      <th scope="row"><?php _e( 'Username', 'aistore' ); ?> :</th>
      <td>   <?php echo esc_attr($row->username) ; ?></td>
      
    </tr>
    
      <tr>
      <th scope="row"><?php _e( 'Amount', 'aistore' ); ?> :    </th>
      <td>       <?php echo esc_attr($row->amount)." " ; ?></td>
      
    </tr>
    
     <tr>
      <th scope="row"><?php _e( 'Status', 'aistore' ); ?> :</th>
      <td>   <?php echo esc_attr($row->status) ; ?></td>
      
    </tr>
    
   
    
     <tr>
      <th scope="row"><?php _e( 'Date', 'aistore' ); ?> :</th>
      <td>   <?php echo esc_attr($row->created_at) ; ?></td>
      
    </tr>
    
    
<hr>
<h4><?php _e( 'Bank account details', 'aistore' ); ?></h4>


    <tr>
      <th scope="row"><?php _e( 'Bank Account Number', 'aistore' ); ?></th>
    <td>   <?php echo esc_attr($bank_account) ; ?></td>
       
      
    </tr>
    <tr>
      <th scope="row"><?php _e( 'Bank Account Name', 'aistore' ); ?></th>
     <td>   <?php echo esc_attr($bank_account_name) ; ?></td>
    
   
    </tr>
    
       </tr>
    <tr>
      <th scope="row"><?php _e( 'Name Of Bank', 'aistore' ); ?></th>
     <td>   <?php echo esc_attr($name_of_bank) ; ?></td>
    
   
    </tr>
    
    
     <tr>
      <th scope="row"><?php _e( 'IFSC Code', 'aistore' ); ?></th>
     <td>   <?php echo esc_attr($ifsc_code) ; ?></td>
    
   
    </tr>
    
      <tr>
      <th scope="row"><?php _e( 'IBAN', 'aistore' ); ?></th>
     <td>   <?php echo esc_attr($IBAN) ; ?></td>
    
   
    </tr>
    
    <tr>
      <th scope="row"><?php _e( 'BIC', 'aistore' ); ?></th>
      <td>   <?php echo esc_attr($BIC) ; ?></td>
    
   
    </tr>
 
    <tr>
        
  
  <td>


<?php
if(isset($_POST['submit']) and $_POST['action']=='approve_withdrawal' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$withdrawal_id=sanitize_text_field($_REQUEST['withdrawal_id']);


$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}widthdrawal_requests
    SET status = '%s'  WHERE id = '%d'", 
   'Approved' , $withdrawal_id   ) );
   
   $widthdrawal = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}widthdrawal_requests WHERE id=%s ",$withdrawal_id));
   
   
   $to = $widthdrawal->username;
$subject ="Withdrawal Approved";




 $body="Hello, <br>
 
     <h2> withdraw approved  successfully for the withdraw ID ".$withdrawal_id." </h2>".
     
     "<br>Withdraw ID is: ".$withdrawal_id.
     "<br>Approved Withdraw system to :<br>";
    
  
  
  //$body.=__( 'Your Recevier Email'.$receiver_email, 'aistore' );
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );

 
}
else{
?>
 <form method="POST" action="" name="approve_withdrawal" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  
  <input class="input" type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo esc_attr($row->id) ; ?> ">
<input 
 type="submit" class="btn btn-primary btn-sm" name="submit" value="<?php  _e( 'Approve', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="approve_withdrawal" />
</form>

<?php
}
?>

</td>

<td>

<?php
if(isset($_POST['submit']) and $_POST['action']=='reject_withdrawal' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$withdrawal_id=sanitize_text_field($_REQUEST['withdrawal_id']);


$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}widthdrawal_requests
    SET status = '%s'  WHERE id = '%d'", 
   'Rejected' , $withdrawal_id   ) );
   
   
   
   $widthdrawal = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}widthdrawal_requests WHERE id=%s ",$withdrawal_id));
   
   
   $to = $widthdrawal->username;
$subject ="Withdrawal Request Rejected";


	

 $body="Hello, <br>
 
     <h2>Your  withdrawal request is Rejected for the withdraw ID ".$withdrawal_id." </h2>".
     
     "<br>Withdraw ID is: ".$withdrawal_id.
     "<br>Rejected Withdraw system to :<br>";
    
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );
 
}
else{
?>
 <form method="POST" action="" name="reject_withdrawal" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  
  <input class="input" type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo esc_attr($row->id) ; ?> ">
<input 
 type="submit" class="btn btn-primary btn-sm" name="submit" value="<?php  _e( 'Reject', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="reject_withdrawal" />
</form>

<?php
}
?>
</td>

<td>
<?php
if(isset($_POST['submit']) and $_POST['action']=='delete_withdrawal' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$withdrawal_id=sanitize_text_field($_REQUEST['withdrawal_id']);



$table =$wpdb->prefix.'widthdrawal_requests' ;
$wpdb->delete( $table, array( 'id' => $withdrawal_id ) );
 
}
else{
?>
 <form method="POST" action="" name="delete_withdrawal" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  
  <input class="input" type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo esc_attr($row->id) ; ?> ">
<input 
 type="submit" class="btn btn-primary btn-sm" name="submit" value="<?php  _e( 'Delete', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="delete_withdrawal" />
</form>

<?php
}
?>

		   </td>
    </tr>

</table>
      </div>
    </div>
  </div>
    <?php endforeach;
    
    }
    ?>
    
  </div>
  
  

<?php
     
 }


