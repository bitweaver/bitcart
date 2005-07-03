<?php
// NOTE: you may have to modify the require path below to make
// it work for your installation.
require('BITCART_PKG_PATHfc_functions/fc_functions.php'); 

fc_addproduct($fc_cartid, $fc_sku, $fc_quantity);

// USE ONE OR THE OTHER OF THE LINES BELOW

// Use the line below to show the cart contents
header("Location: $nsecurl$cartdir/showcart.php?cartid=$fc_cartid&zid=$fc_zid&lid=$fc_lid&aid=$fc_aid&product=$fc_sku&quantity=$fc_quantity&fname=$fc_fname");

// Use the line below to return directly to the product page
// header("Location: $HTTP_REFERER");
?>
