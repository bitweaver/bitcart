<?php

if (!$functions_inc){
require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape

$CookieCustID = getcookie("Cookie${instid}CustID");
$CookieCart   = getcookie("Cookie${instid}Cart");
$cartid=getparam('cartid');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$subz = (int)getparam('subz');
$cat = (int)getparam('cat');

// ==========  end of variable loading  ==========

if( !$pub_inc    ){ require('./public.php');    }
if( !$cartid_inc ){ require('./cartid.php');    }
if( !$flags_inc  ){ require('./flags.php');     }
if( !$lang_inc   ){ require('./languages.php'); }

} // end functions_inc

$pre1 = new FC_SQL;
$pre2 = new FC_SQL;
$pre3 = new FC_SQL;
$pre4 = new FC_SQL;
$pre5 = new FC_SQL;
$pre6 = new FC_SQL;	

// get the language templates
$pre7=new FC_SQL;
$pre7->Auto_free=1;
$pre7->query(
 "select langgeo,langshow,langordr from lang where langid=$lid");
// echo "select langgeo,langshow,langordr from lang where langid=$lid";
$pre7->next_record();
$pregeo=$pre7->f("langgeo");
$preshow=$pre7->f("langshow");
$preordr=$pre7->f("langordr");
$pre7->free_result();

$pre8 = new FC_SQL;	// order table
$pre8->Auto_commit = 1;

$now=time();

$pre8->query("select * from oline where orderid='$cartid'"); 
if($pre8->next_record()){
?>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr><td>
<table class="text" align="left" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC" width="100%">
<tr><td class="subdivrow" colspan="2" align="center" valign="top" bgcolor="#CCCCCC">
<font face="Arial,Helvetica" size="1">
<b><?php echo fc_text('product'); ?></b>
</font>
</td><!--td class="subdivrow" align="center" valign="top" bgcolor="#CCCCCC">
<font face="Arial,Helvetica" size="1">
<b><?php echo fc_text('total'); ?></b>
</font>
</td--></tr>

<?php 
$i=0;
$tqty=0;
$pre10 = new FC_SQL;
$pre11 = new FC_SQL;
do{ // already have the first record

 $sku=$pre8->f("sku");
 $csku=$pre8->f("compsku");
 $qty=$pre8->f("qty");
 $invover=(int)$pre8->f("invover");
 $olzone=(int)$pre8->f("olzone");
 $ollang=(int)$pre8->f("ollang");

 // get the short product description
 $pre10->query("select prodname from prodlang ".
	 "where prodlzid=$olzone and prodlid=$ollang and prodlsku='$sku'");
 if(!$pre10->next_record()){
  $pre11->query("delete from oline where orderid='$cartid'");
  continue;
 }
 $sdescr=stripslashes(ereg_replace("<[^>]+>"," ",$pre10->f("prodname")));
 $pre10->free_result();

 $pre10->query(
	"select prodprice,prodsetup,prodweight,prodflag1 from prod ".
	"where prodzid=$olzone and prodsku='$sku'");
 if(!$pre10->next_record()){
  $pre11->query("delete from oline where orderid='$cartid'");
  continue;
 }
 $prodprice=(double)$pre10->f("prodprice");
 $prodsetup=(double)$pre10->f("prodsetup");
 $prodweight=(double)$pre10->f("prodweight");
 $flag1=(int)$pre10->f("prodflag1");
 $pre10->free_result();
?>

<tr><td class="previewcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<?php
echo "<b>".$sdescr."</b><br />\n".
 sprintf("%s&nbsp;%d<br />\n",fc_text('qty'),$qty);

 // retrieve product options, accumulate $popttotal, $poptsetttot
 if( $sku != $csku && $prodprice ){
  echo sprintf("%s&nbsp;%s%.2f<br />\n",fc_text('price'),$csym,$prodprice);
  if( $prodsetup ){
   echo sprintf("%s&nbsp;%s%.2f<br />\n",fc_text('setup'),$csym,$prodsetup);
  }
 }
 while( get_prodopts($csku) ){
  echo fc_text('option').'&nbsp;'.$poptname."<br />\n";
  if($poptqty){
	  echo '&nbsp;&nbsp;'.
	  	   fc_text('qty').'&nbsp;'.$poptqty."<br />\n";
	  if( $poptextension ){
	   echo '&nbsp;&nbsp;'.fc_text('total').
	   		'&nbsp;'.sprintf("%s%.2f",$csym,$poptextension)."<br />\n";
	  }
  }elseif($poptextension){
	  echo '&nbsp;&nbsp;'.fc_text('total').'&nbsp;'.
	  sprintf("%s%.2f",$csym,$poptextension)."<br />\n";
  }
  if($poptsetup){
    // for one time product setup regardless of qty, comment the line below
	$poptsetup=$poptsetup*$poptqty;
    echo "&nbsp;&nbsp;".fc_text('setup').'&nbsp;'.
      sprintf("%s%.2f",$csym,$poptsetup)."<br />\n";
  }
  // echo "<br />\n";
 }
?>
</font>
</td>
<!--td class="previewcell" align="right" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">

<?php 
 	// figure options modified product price, accumulate $prodsetttot
	// sets various globals
	$prodprice = prod_price ( $sku );

 	// if(!$prodprice){ $prodprice=$webfree; }

	$ltotal=line_total( $qty, $prodprice );
	// $ltotal=rnd($ltotal+$prodsetup);

	printf("%s %s%.2f<br>",fc_text('total'),$csym,$ltotal);

	if( $prodsetup ){
  		// for one time product setup regardless of qty, comment the line below
  		$prodsetup=$prodsetup*$qty;
		printf("%s&nbsp;%s%.2f<br />",fc_text('setuptotal'),$csym,$prodsetup);
		$prodsettot=rnd($prodsettot+$prodsetup);
	}

	$tqty=rnd($tqty+$qty);
	//$lweight=rnd($qty * $prodweight);
    if( $flag1 & $flag_persvc ){
	 //$mstotal=rnd($mstotal+$ltotal);
	 //$mtotal=rnd($mtotal+$ltotal);
	 $ptotal=rnd($ptotal+$ltotal);
	}else{
	 //$wtotal=rnd($wtotal+$lweight);
	 //$pstotal=rnd($pstotal+$ltotal);
	 $stotal=rnd($stotal+$ltotal);
	}
?>
</font>
</td--></tr>
 <?php
 $i++;
} while($pre8->next_record()); /* end of product display while loop */
?>

<?php if( $prodsettot ){ ?>
<tr><td class="previewcell" colspan="1" align="left" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<b><?php echo fc_text('setupfees'); ?></b><br />
</font>
</td><td class="previewcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<?php
printf("%s%.2f<br />",$csym,$prodsettot);
// accumulate setup into order total
$stotal=rnd($prodsettot+$stotal);
?>
</font>
</td></tr>
<?php
}

if( $zflag1 & $flag_zonecoupon ){
 // $stotal is the discounted product subtotal
 $cpndisc=coupon_discount($couponid,$stotal,$tqty);
 if( $cpndisc > 0 ){
  $shipsubtotal=rnd($shipsubtotal - $cpndisc);
  $taxsubtotal=rnd($taxsubtotal - $cpndisc);
  $stotal=rnd($stotal - $cpndisc);
  $ototal=rnd($ototal - $cpndisc);
?>
<tr><td class="previewcell" colspan="1" align="left" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<b><?php echo fc_text('coupondisc'); ?></b><br />
</font>
</td><td class="previewcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<?php printf("%s%.2f<br />",$csym,$cpndisc); ?>
</font>
</td><td class="previewcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<?php
 }
} // flag_zonecoupon ?>

<?php // if( $stotal ){ ?>
<tr><td class="previewcell" colspan="1" align="left" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<b><?php echo fc_text('subtotal'); ?></b>
</font>
</td><td class="previewcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<font face="Arial,Helvetica" size="1">
<?php printf("%s%.2f<br />",$csym,$stotal); ?>
</font>
</td></tr>
</table>
</td></tr></table>
<?php // } // if stotal 
} // if($olc>0)
?>
