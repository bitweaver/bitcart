<?php require('BITCART_PKG_PATHfc_functions/fc_functions.php'); ?>

<html><head><title>FishCart PHP Functions Example</title></head>
<body bgcolor="#ffffff">

Active Category List:<br>
<?php while (fc_active_categories(0)){ ?>
<a href="//fishcart/display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&cat=<?php echo $cat_id?>"><?php echo $cat_name?></a><br>
<?php } ?>
<p>

Products Within Category 1:<br>
<?php while (fc_products_by_category($zid,$lid,1,0)){ ?>
Product SKU/Description: <?php echo $fc_sku ?> / <?php echo $fc_sdescr ?><br>
<?php } ?>

<hr>

<?php

$sku='YOUR_SKU';

if($sku=='YOUR_SKU'){?>
You must edit this file to define the SKU in your product database that
you want to use as an example.  Edit the definition $sku=&quot;YOUR_SKU&quot;.
<?php
 exit;
}

// 1 is the zone ID
// $sku is the SKU to retrieve.
// If returns fc_onsale = 1, the product is on sale.

fc_getprice(1,"$sku");
?>

Demonstration of fc_getprice() with SKU <?php echo $sku ?><br>
fc_retail: <?php echo $fc_retail ?><br>
fc_price: <?php echo $fc_price ?><br>
fc_onsale: <?php echo $fc_onsale ?>

<hr>

<?php
// 1 is the zone ID,
// 1 is the language ID,
// $sku is the SKU to retrieve.
fc_getpricedescr(1,1,"$sku");
?>
Demonstration of fc_getpricedescr() with SKU <?php echo $sku ?><br>
fc_retail: <?php echo $fc_retail ?><br>
fc_price: <?php echo $fc_price ?><br>
fc_onsale: <?php echo $fc_onsale ?>
<p>
short description: fc_sdescr:<br><?php echo $fc_sdescr ?>
<p>
long description: fc_descr:<br><?php echo $fc_descr ?>

<hr>

Add a product to the cart:<br>
<form method=post action="<?php echo $nsecurl.$cartdir ?>/fc_functions/fc_post.php">
SKU: <?php echo $sku ?> Quantity:
<input type=text name=fc_quantity size=3>
<input type=hidden name=fc_zid value=1>
<input type=hidden name=fc_lid value=1>
<input type=hidden name=fc_sku value="<?php echo $sku ?>">
<input type=hidden name=fc_cartid value="<?php echo $cartid ?>">
<input type=hidden name=fc_fname value="<?php echo urlencode($REQUEST_URI); ?>">
<input type=submit value=Add to your order>
</form>

<hr>

<a href="<?php echo $nsecurl.$cartdir ?>/showcart.php?zid=1&lid=1&cartid=<?php echo $cartid ?>&fname=<?php echo urlencode($REQUEST_URI); ?>">VIEW CART</a>

<hr>

<?php fc_close(); ?>
<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
