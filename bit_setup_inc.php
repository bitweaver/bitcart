<?php
global $gBitSystem;

$gBitSystem->registerPackage( 'bitcart', dirname( __FILE__ ).'/' );
if( $gBitSystem->isPackageActive( 'bitcart' ) ) {
	$gBitSystem->registerAppMenu( 'bitcart', 'Shopping', BITCART_PKG_URL.'index.php', 'bitpackage:bitcart/menu_bitcart.tpl' );
}


?>
