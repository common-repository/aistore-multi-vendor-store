<?php
/*
Plugin Name: Aistore Multi Vendor Store
Version:  1.0
Plugin URI: #
Author: susheelhbti
Author URI: http://www.aistore2030.com/
Description: Aistore Multi Vendor Store is a plugin which allow visitores to sell their digital products from your woo commerce store.  
Contributors: susheelhbti
Donate link: http://www.aistore2030.com/

Tags: Wordpress Products
License: GPLv2
Requires at least:  5.5
Tested up to: 5.8
Stable tag: 1.0.0
 

*/ 

add_action('init', 'aistore_product_load_textdomain');



function aistore_product_load_textdomain()
{
    load_plugin_textdomain('aistore', FALSE, basename(dirname(__FILE__)) . '/languages/');
}

include_once dirname(__FILE__) . '/aistore_multivendor_admin/AddToCartReport.class.php';
include_once dirname(__FILE__) . '/aistore_multivendor_admin/AistoreProductPage.class.php';
include_once dirname(__FILE__) . '/aistore_multivendor_admin/AistoreSellsReport.class.php';
include_once dirname(__FILE__) . '/aistore_multivendor_admin/AistoreSettingsPage.class.php';
include_once dirname(__FILE__) . '/aistore_multivendor_admin/users_setting.php';


include_once dirname(__FILE__) . '/aistore_multivendor_frontend/aistore_multivendor_woocommerce_product/aistore_multivendor_product/aistore_multivendor_add_product.php';
include_once dirname(__FILE__) . '/aistore_multivendor_frontend/aistore_multivendor_woocommerce_product/aistore_multivendor_product/aistore_multivendor_edit_product.php';
include_once dirname(__FILE__) . '/aistore_multivendor_frontend/aistore_multivendor_woocommerce_product/aistore_multivendor_product/aistore_multivendor_list_product.php';


include_once dirname(__FILE__) . '/aistore_multivendor_frontend/aistore_multivendor_woocommerce_product/aistore_multivendor_order/aistore_multivendor_edit_order.php';
include_once dirname(__FILE__) . '/aistore_multivendor_frontend/aistore_multivendor_woocommerce_product/aistore_multivendor_order/aistore_multivendor_list_order.php';

include_once dirname(__FILE__) . '/aistore_multivendor_frontend/aistore_multivendor_woocommerce_product/aistore_multivendor_cart_report.php';

	    if(get_option('product_show') === 'yes'){
include_once dirname(__FILE__) . '/aistore_multivendor_wp_hooks/aistore_multivendor_hooks.php';
}

function aistore_vendor_scripts_method()
{
    wp_enqueue_style('aistore', plugins_url('/aistore_multivendor_lib/aistore_multivendor_css/custom.css', __FILE__), array());
    wp_enqueue_script('aistore', plugins_url('/aistore_multivendor_lib/aistore_multivendor_js/custom.js', __FILE__), array(
    ));
    
    wp_enqueue_style( 'aistore', '//stackpath.bootstrapcdn.com/bootstrap/5.0.1/css/bootstrap.min.css' );
wp_enqueue_script( 'aistore', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js');
wp_enqueue_style( 'aistore', get_template_directory_uri() . '/style.css');
  
   
}
add_action('wp_enqueue_scripts', 'aistore_vendor_scripts_method');


  


function aistore_product_plugin_table_install()
{
    global $wpdb;

      $table_add_to_cart = "CREATE TABLE IF NOT EXISTS  " . $wpdb->prefix . "aistore_multivendor_add_to_cart_report  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  product_id int(100) NOT NULL,
   user_id  int(100)   NOT NULL,
   ip_address  varchar(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";
    
     $table_system_orders = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_multivendor_system_orders  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  product_id int(100) NOT NULL,
     ip_address  varchar(100)   NOT NULL,
   order_id  int(100)   NOT NULL,
   product_owner_user_id  int(100)   NOT NULL,
  order_owner_user_id  int(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";
    
    
    
 
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    dbDelta($table_system_orders);
        dbDelta($table_add_to_cart);

     
        
 
}

register_activation_hook(__FILE__, 'aistore_product_plugin_table_install');


 

function aistore_order_status_update($order_id) {
 
$order = new WC_Order( $order_id );
 global $wpdb;
// $wpdb->query('DELETE  FROM '.$wpdb->prefix.'system_orders WHERE order_id = %d',$order_id);

$wpdb->delete(
        $wpdb->prefix . 'aistore_multivendor_system_orders',    
        ['order_id' => $order_id],                    
        ['%d'] );

$items = $order->get_items(); 

$order_owner_user_id = $order->get_user_id();
    
    foreach ( $items as $item ) {
    $product_name = $item->get_name();
    $product_id = $item->get_product_id();
     $total_amount = $item['total'];

 
$product_owner_user_id = get_post_field( 'post_author', $product_id );
$ip_server = $_SERVER['SERVER_ADDR'];

 
$wpdb->insert($wpdb->prefix."aistore_multivendor_system_orders", array(
   "product_id" => $product_id,
   "order_id" => $order_id ,
   "product_owner_user_id" => $product_owner_user_id,
   "order_owner_user_id" => $order_owner_user_id ,
   "ip_address" =>$ip_server
   
));

}

} 

function aistore_order_status_completed($order_id) {
    
    $order = new WC_Order($order_id);
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
               $total_amount = $item['total'];
            
            
        }
     global $wpdb;
     $orders = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}aistore_multivendor_system_orders where order_id=%d ", $order_id));
     
     $order_owner_user_id = $orders->order_owner_user_id;
   $product_owner_user_id = $orders->product_owner_user_id;
      $product_id = $orders->product_id;
   
 $description="Order completed for order id #". $order_id ."and product name is ".$product_name;
      
      $wallet = new Woo_Wallet_Wallet();

$balance = $wallet->get_wallet_balance($order_owner_user_id, '');
$amount=$total_amount;


if($balance>=$amount){
   $commission=  get_option('commission_fee') ;
   $commission_fee = ($commission / 100) * $amount;
   
   $new_amount = $amount - $commission_fee;
   
     $wallet = new Woo_Wallet_Wallet();
       transaction($amount,$description,'debit',$order_owner_user_id,$product_id,$order_id);
     $res=$wallet->credit($product_owner_user_id, $amount,$description);
    transaction($amount,$description,'credit',$product_owner_user_id,$product_id,$order_id);
       
   $description="Order complete fee for order id #". $order_id."and product name is ".$product_name;
       
   $res=$wallet->debit($product_owner_user_id, $commission_fee,$description);
   
transaction($amount,$description,'debit',$product_owner_user_id,$product_id,$order_id);
    
 $res=$wallet->credit(1, $commission_fee,$description);
 transaction($amount,$description,'credit',1,$product_id,$order_id);
         
         
         
// email to product_owner_user_id 

    $user = get_user_by( 'id', $product_owner_user_id);

$to = $user->user_email;
$subject ="Order complete for order id #". $order_id;

  $user1 = get_user_by( 'id', $order_owner_user_id);

 $body="Hello, <br>
 <h4>Order # " . esc_attr($order_id) . " was placed on " . esc_attr($product_name) . " and is currently " .

            esc_attr($order_status) . "</h4><br>";

    
       $body.=" <br><h2> Product Details </h2><br>
   <table>
   
    <tr><td> Product ID :</td><td>".$product_id."</td></tr>
    <tr><td>Product Name :</td><td>".$product_name."</td></tr>
    <tr><td>Amount :</td><td>". $amount."</td></tr>
    
</table><br><br> ";
  
  $body.=" <br><h2> Customer Details </h2><br>
   <table>
   
    <tr><td> Name :</td><td>".$user1->user_nicename."</td></tr>
    <tr><td>Email :</td><td>".$user1->user_email."</td></tr>
</table><br><br>

   ";
   
   	
  $body.="<h2>Billing address</h2><br>
  <address>
   ".$order_billing_phone."<br>
    ".$order_billing_email."<br>
  ".$order_billing_company."<br>"
  .$order_billing_first_name."  ".$order_billing_last_name
  ."  ".$order_billing_address_1."<br>".$order_billing_address_2
  ."  ".$order_billing_city."  ".$order_billing_postcode."<br>"
  .$order_billing_state."  ".$order_billing_country."
		<br><br></address>";

  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );

    
}

else{
    _e( 'Insufficient Balance', 'aistore' ); 
}
}

add_action( 'woocommerce_order_status_processing', 'aistore_order_status_update', 10, 1);
add_action( 'woocommerce_order_status_failed', 'aistore_order_status_update', 10, 1);
add_action( 'woocommerce_order_status_on-hold', 'aistore_order_status_update', 10, 1);
add_action( 'woocommerce_order_status_completed', 'aistore_order_status_completed', 10, 1);
add_action( 'woocommerce_order_status_refunded', 'aistore_order_status_update', 10, 1);
add_action( 'woocommerce_order_status_cancelled', 'aistore_order_status_update', 10, 1);
 



function action_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
  global $wpdb;
    
  $user_id=get_current_user_id();
  $ip_server =$_SERVER['SERVER_ADDR'];
 



$wpdb->insert($wpdb->prefix."aistore_multivendor_add_to_cart_report", array(
   "product_id" =>$product_id,
   "user_id" => $user_id ,
   "ip_address" => $ip_server
  
));
  
 
};

// add the action
add_action( 'woocommerce_add_to_cart', 'action_woocommerce_add_to_cart', 10, 6 );


   function transaction($amount,$description,$type,$user_id,$product_id,$order_id){
      global $wpdb;
      $q1 = $wpdb->prepare("INSERT INTO {$wpdb->prefix}aistore_wallet_transactions  (amount,description,type, user_id,product_id,order_id ) VALUES (%s,%s, %s,%s,%s,%s )", array(
            $amount,
            $description,
            $type,
            $user_id,
            $product_id,
            $order_id
        ));

        $wpdb->query($q1);
        $transaction_id = $wpdb->insert_id;
}