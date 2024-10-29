<?php

add_shortcode('aistore_add_product',
    'aistore_add_product');


add_shortcode('aistore_product_list',
    'aistore_product_list');


add_shortcode('aistore_product_edit',
    'aistore_product_edit');

add_shortcode('aistore_order_list',
    'aistore_order_list');


add_shortcode('aistore_order_edit',
    'aistore_order_edit');



add_filter( 'woocommerce_account_menu_items', 'aistore_account_menu_link', 40 );

function aistore_account_menu_link( $menu_links ){
	


	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	
		+ array( 'cart-report' => 'Add to Cart Report' )
	+ array( 'order-list' => 'Sells List' )
	+ array( 'product-list' => 'Product List' )
		+ array( 'product-add' => 'Add Product' )
			+ array( 'product-details' => '' )
				+ array( 'order-details' => '' )
				

	+ array_slice( $menu_links, 5, NULL, true );
	
	return $menu_links;

}

add_action( 'init', 'aistore_add_endpoint' );
function aistore_add_endpoint() {

add_rewrite_endpoint( 'cart-report', EP_PAGES );
	add_rewrite_endpoint( 'order-list', EP_PAGES );
		add_rewrite_endpoint( 'product-list', EP_PAGES );
		add_rewrite_endpoint( 'product-add', EP_PAGES );
		
			add_rewrite_endpoint( 'product-details', EP_PAGES );
	add_rewrite_endpoint( 'order-details', EP_PAGES );

}


add_action( 'woocommerce_account_cart-report_endpoint', 'aistore_my_account_cart_endpoint_content' );

function aistore_my_account_cart_endpoint_content() {
    
  $object=new Aistore_multi_vendor_store();
echo $object->aistore_cart_report();


}

add_action( 'woocommerce_account_order-list_endpoint', 'aistore_my_account_endpoint_content' );
function aistore_my_account_endpoint_content() {
    
  $object=new Aistore_multi_vendor_store();
echo $object->aistore_order_list();


}

add_action( 'woocommerce_account_product-list_endpoint', 'aistore_my_account_endpoint_content1' );
function aistore_my_account_endpoint_content1() {

  $object=new Aistore_multi_vendor_store();
echo $object->aistore_product_list();

}

add_action( 'woocommerce_account_product-add_endpoint', 'aistore_my_account_endpoint_content_product_add' );
function aistore_my_account_endpoint_content_product_add() {


  $object=new Aistore_multi_vendor_store();
echo $object->aistore_add_product();

}

add_action( 'woocommerce_account_product-details_endpoint', 'aistore_my_account_endpoint_content_product_details' );
function aistore_my_account_endpoint_content_product_details() {


  $object=new Aistore_multi_vendor_store();
echo $object->aistore_product_edit();

}


add_action( 'woocommerce_account_order-details_endpoint', 'aistore_my_account_endpoint_content_order_details' );
function aistore_my_account_endpoint_content_order_details() {


  $object=new Aistore_multi_vendor_store();
echo $object->aistore_order_edit();

}

?>