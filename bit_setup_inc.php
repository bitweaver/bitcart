<?php
global $gBitSystem;


$registerHash = array(
	'package_name' => 'bitcart',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if( $gBitSystem->isPackageActive( 'bitcart' ) ) {
	$menuHash = array(
		'package_name'  => BITCART_PKG_NAME,
		'index_url'     => BITCART_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:bitcart/menu_bitcart.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
}
?>
