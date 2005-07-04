<?php

if( empty( $mid ) ) {
	$smarty->assign_by_ref( 'bitcartCenter', ob_get_contents() );
	ob_end_clean();

	global $gBitSystem;
	//$gTikiSystem->mPrefs['feature_left_column'] = 'n';
	//$gTikiSystem->mPrefs['feature_right_column'] = 'n';
	$gBitSystem->display( 'bitpackage:bitcart/view_bitcart.tpl' );
} else {
	$gBitSystem->display( $mid, $browserTitle );
}
?>
