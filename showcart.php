<?php /*
FishCart: an online catalog management / shopping system
Copyright (C) 1997-2002  FishNet, Inc.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307,
USA.

   N. Michael Brennen
   FishNet(R), Inc.
   850 S. Greenville, Suite 102
   Richardson,  TX  75081
   http://www.fni.com/
   mbrennen@fni.com
   voice: 972.669.0041
   fax:   972.669.8972
*/

require_once( '../bit_setup_inc.php' );

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$CookieCustID = getcookie("Cookie${instid}CustID");
$CookieCart   = getcookie("Cookie${instid}Cart");
$cartid = getparam('cartid');
$key1 = getparam('key1');
$product = getparam('product');
$city = getparam('city');
$state = getparam('state');
$zip = getparam('zip');
$country = getparam('country');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$cat = (int)getparam('cat');
$szid = (int)getparam('szid');
$oszid = (int)getparam('oszid');
$nlst = (int)getparam('nlst');
$olst = (int)getparam('olst');
$olimit = (int)getparam('olimit');
$quantity = (int)getparam('quantity');
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

// SQL INJECTION AVOIDENCE
$olid = !empty( $olid ) ? (int) $olid : 0;
$poptid = !empty( $poptid ) ? (int) $poptid : 0;
$curshipid = !empty( $curshipid ) ? (int) $curshipid : 0;
$poptqty = !empty( $poptqty ) ? (int) $poptqty : 0;
$inv = !empty( $inv ) ? (int) $inv : 0;
$prc = !empty( $prc ) ? (double) $prc : 0;
$subz = !empty( $subz ) ? (int) $subz : 0;

// catch 2^31 32 bit integer overflow as well
if( $quantity < 0 || $quantity > 2147483647 ){
	$quantity=0;
}

// Causes preview.php to not be shown
$nukepreview=1;

$fcv=new FC_SQL;
$fcz=new FC_SQL;
$fcz->query("select zonecurrsym,zflag1 from zone where zoneid=$zid");
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $csym=trim($csym);
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $csym="";
 $zflag1=0;
}

$mln=256;
$now=time();

$stotal=0.0;		// product subtotal after discount
$pstotal=0.0;		// product subtotal prior to discount
$mtotal=0.0;		// periodic service after discount
$mstotal=0.0;		// periodic service prior to discount
$ototal=0.0;		// order total prior to contribution
$ttotal=0.0;		// product order total
$ptotal=0.0;		// periodic order total
$wtotal=0.0;		// weight total (for shipping calculation)

// get the Web table
$fcw=new FC_SQL;
$fcw->query("select * from web where webzid=$zid and weblid=$lid"); 
$fcw->next_record();

// see if this order exists
$fco=new FC_SQL;
$fco->query(
  "select subz,contrib,shipid,couponid,scity,sstate,szip,scountry from ohead where orderid='$cartid'");
if(!$fco->next_record()){?>
<?php echo fc_text('invalidorder'); ?>

<?php exit;}

// if subz=0, no products in the cart
$subz=(int)$fco->f("subz");
// get current shipping option; needed by shipcalc
$curshipid=(int)$fco->f("shipid");
$couponid=stripslashes($fco->f("couponid"));
if (!$state){ $state=$fco->f("sstate"); }
if (!$city){ $city=$fco->f("scity"); }
if (!$zip){ $zip=$fco->f("szip"); }
if (!$country){ $country=$fco->f("scountry"); }

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	include('./pw.php');
}

// get the language templates
$fcl=new FC_SQL;
$fcl->Auto_free=1;
$fcl->query(
 "select langgeo,langshow,langordr,langcopy,langterms from lang where langid=$lid");
$fcl->next_record('langterms');
$geo=stripslashes($fcl->f("langgeo"));
$show=stripslashes($fcl->f("langshow"));
$ordr=stripslashes($fcl->f("langordr"));
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fcl->free_result();

// set for inline t anc c first, override with inline contrib if set
// inline contrib will check for inline terms and conditions
if( $zflag1 & $flag_zonetcpage ){
	// set for inline terms and conditions
	$ordr='terms.php';
}
if( $zflag1 & $flag_zoneinlinecontrib ){
	// set for inline contribution
	$ordr='contribute_order.php';
}

// default to no subzones under this one
$subzparent = 0;
$subzpcount = 0;
if( $szid ){
 // if returning from showgeo see if the selected subzone has children
 // see if there are any subzones under this one
 $fcgeo = new FC_SQL;
 $fcgeo->query(
 	"select count(*) as cnt from subzone where subzparent=$szid");
 $fcgeo->next_record();
 $subzpcount=(int)$fcgeo->f('cnt');
 $fcgeo->free_result();
 if( $subzpcount ){
  $subzparent = $szid;
 }
}

$fcr = new FC_SQL;
$fcr->query("select count(*) as cnt from oline where orderid='$cartid'"); 
$fcr->next_record();
$olc=(int)$fcr->f('cnt');
$fcr->free_result();

if( (empty($subz) && empty($szid) && empty($product) && empty($olc)) ||
    ((!empty($subz) || !empty($szid)) && empty($subzpcount)) ){
 // if subz, geography has been selected
 // if szid, returning from showgeo, has been selected
 // if subzpcount is non-zero, there are child subzones still to select

if( !($zflag1 & $flag_zonereturn) || empty($product) ){

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE ?>

<html>
<head>
<title><?php echo fc_text('cartcontents'); ?></title>
<link rel="stylesheet" ID href="style.css" type="text/css" />
</head>
<body<?php
 if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
 if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
 if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> link="<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> link="<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }
?>
 marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<?php // START OF ESSENTIAL CART DISPLAY CODE ?>
<!--BEGIN CATEGORY LINKS TABLE--> 
<table border="0" cellpadding="5" cellspacing="0" width="780">
<tr><td class="navtext" align="left" valign="top" width="135">
<?php include('fc_leftnav.php');?>
</td><td align="left" valign="top">
<!--OPEN CELL FOR FISHCART CODE--> 
<table class="text" border="0" cellpadding="0" cellspacing="0" width="580">

<!-- FIRST COLUMN -->
<tr><td align="left" valign="top" width="10">
<img src="clearpixel.gif" width="10" height="1" /></td>
<!-- SECOND COLUMN -->
<td valign="top">
<table class="showcartmainbg" border="0" cellpadding="0" cellspacing="0" width="580" bgcolor="#FFFFFF" align="left">
<tr><td valign="top">
<table class="showcartdisplaybg" border="0" cellpadding="4" cellspacing="1" width="580" bgcolor="#666666" align="left">
<tr><td class="divrow" align="center" bgcolor="#CCCCCC" height="35" colspan="4">
<?php echo fc_text('cartcontents'); ?>

<?php
} // zonereturn

 if( empty($olc) && $quantity <= 0 ){?>
<tr><td class="showcartcell" colspan="4" bgcolor="#FFFFFF">
<center><p><?php echo fc_text('cartempty'); ?></p></center>
</td></tr>
<?php $noproducts=1;
 }else{
  $noproducts=0;
 }
}

$fao = new FC_SQL;	// order table
$fao->Auto_commit = 1;

$fasz = new FC_SQL;	// subzone table
$fasz->Auto_commit = 1;

$fai = new FC_SQL;
$fap = new FC_SQL; // product
$fpo = new FC_SQL; // product options
$fps = new FC_SQL; // product options

$option_violation = 0;
$return_product = !empty( $return_product ) ? (int)$return_product : 0;

// product is set when someone adds something to their order
if( !empty($product) && $quantity > 0 ){

	// flag to show return to product link
	$return_product = 1;

	// start building composite SKU; if no options, same as sku
	$csku=$product;
	// get a unique ID for this order line
	srand((double)microtime()*1000000);
	$olid=rand();
/*
Product options are passed to us in three pieces.

A : separated list of all possible option groups for a given SKU
is passed in a field named SKU + '_grplst'.  This is the primary
point for parsing what options are passed.

Options without specifically chosen groups are in group zero.

* Option SKUs

The product options are passed in arrays per option group; the name 
is composed of the SKU + _ + the option group + _ + the string 'popt';
the values in this array are the product option IDs.  Only those
options selected are passed in this array by the browser.

* Required Options

Required option groups are so flagged in a variable with a name
composed of the SKU + _ + the option group value + _ + 'req'.  If
the value is non zero the option group is required.  Required options
are flagged as an error if:

  * the product quantity is > 0 and option is not selected;
  * the option quantity field is required and is zero
    ($poptflag1 & $flag_poptgrpqty)  &&  prodsku+poptgrp+qty == 0

* Option Quantities

Any quantities for this option group are passed in a variable with
a name composed of the SKU + _ + the option group value + _ + 'qty'.

Thus, options for the SKU 'PART001', group 3, are passed in an array
called 'PART001_3_popt[]'.  The quantity for option group 2 for this
SKU is in the variable called 'PART001_2_qty'.

Process options first, accumulate the composite SKU, then process
the order line record after the composite sku is built.
*/
	$grplst = getparam($product.'_grplst');
	if( $grplst ){
		// poptgrps is an array of all option group numbers
		$poptgrps = explode( ':', $grplst );
		$npoptgrps = count( $poptgrps );
	}else{
		$npoptgrps = 0;
	}
	$popttotal = 0;
	$poptsettot = 0;

	// accumulate composite sku first; this is what ties together all
	// product options for a particular product/option configuration
	$i=0;
	while( $i < $npoptgrps ){
		$gn = $poptgrps[$i];	// gn is the option group number
		$grp = getparam($product.'_'.$gn.'_popt');
		$ngn = count( $grp );

		// get the option required flag
		$poptreq = (int)getparam($product.'_'.$gn.'_req');
				
		// get the option quantity
		$poptqty = (int)getparam($product.'_'.$gn.'_qty');
		if( $poptqty < 0 ){
			$poptqty = 0;
		}

		// error if:
		// prod qty > 0 && option required && option not selected

		if( $quantity && $poptreq && ( empty($ngn) || $grp[0] == '' ) ){
			$option_violation = 1;	// flag for later action
		}

		if( $ngn >0 ){
			$j=0;
			while( $j < $ngn ){
			  $poptid=(int)$grp[$j];
			  if( $poptid ){
				// make up the composite sku
				$fps->query(
				 "select poptskumod,poptskusub,poptflag1,poptsetup,poptprice,".
				 "poptssalebeg,poptssaleend,poptssaleprice,poptsalebeg,poptsaleend,".
				 "poptsaleprice from prodopt where poptid=$poptid");

				$fps->next_record();
				$poptflag1=(int)$fps->f("poptflag1");
				$poptskumod=stripslashes($fps->f("poptskumod"));
				$poptskusub=stripslashes($fps->f("poptskusub"));

   				if( $fps->f("poptssalebeg")<$now && $now<$fps->f("poptssaleend") ){
				$poptsetup=(double)$fps->f("poptssaleprice");
				}else{
				$poptsetup=(double)$fps->f("poptsetup");
				}
   				if( $fps->f("poptsalebeg")<$now && $now<$fps->f("poptsaleend") ){
				$poptprice=(double)$fps->f("poptsaleprice");
				}else{
				$poptprice=(double)$fps->f("poptprice");
				}

				// option quantity field is shown && optqty == 0
				if( ($poptflag1 & $flag_poptgrpqty) && empty($poptqty) ){
					$option_violation = 1;	// flag for later action
				}

				// accumulate option setup, price
				$poptsettot=rnd($poptsettot+$poptsetup);
				if( $poptqty ){
					$poptextension = rnd($poptprice * $poptqty);
				}else{
					$poptextension = (double)$poptprice;
				}
				if( $poptflag1 & $flag_poptprcrel ){
					$popttotal+=$poptextension;
				}else{
					$popttotal =$poptextension;
				}

				// accumulate composite SKU
				if( $poptflag1 & $flag_poptskupre ){
	 			  $csku=stripslashes($fps->f("poptskumod")) . $csku;
				}elseif( $poptflag1 & $flag_poptskusuf ){
	 			  $csku=$csku . stripslashes($fps->f("poptskumod"));
				}elseif( $poptflag1 & $flag_poptskumod ){
	 			  $csku=ereg_replace(stripslashes($fps->f("poptskusub")),
				  			stripslashes($fps->f("poptskumod")),$csku);
				}elseif( $poptflag1 & $flag_poptskusub ){
	 			  $csku=stripslashes($fps->f("poptskumod"));
				}

				$fps->free_result();
			  }
			  $j++;
			}
		}
		$i++;
	}

	// we have composite sku, now get the quantities and update the order
	if( empty($option_violation) ){
	  $i=0;
	  while( $i < $npoptgrps ){
		$gn = $poptgrps[$i];	// gn is the option group number
		$grp = getparam($product.'_'.$gn.'_popt');
		$ngn = count( $grp );
		if( $ngn >0 ){
			$j=0;
			while( $j < $ngn ){
			  $poptid=(int)$grp[$j];
			  if( $poptid ){
				// get the option quantity
				$poptqty = (int)getparam($product.'_'.$gn.'_qty');
				if( $poptqty < 0 ){
					$poptqty = 0;
				}
				$fpo->query("select qty from olineopt where ".
					"orderid='$cartid' and compsku='$csku' and poptid=$poptid");
				if( $fpo->next_record() ){ // option is already on the order
	 			  $fpo->free_result();
	 			  $fpo->query(
				    "update olineopt set qty=$poptqty ".
					"where orderid='$cartid' and compsku='$csku' ".
					"and poptid=$poptid");
				}else{
	 			  $fpo->query("insert into olineopt ".
				  	"(orderid,olzone,ollang,sku,compsku,poptid,qty) ".
	  				"values ('$cartid',$zid,$lid,'$product','$csku',".
					"$poptid,$poptqty)");
				}
			  }
			  $j++;
			}
		}
		$i++;
	  }
	
	  // include setup in product line total
	  $popttotal = rnd($popttotal + $poptsettot);
	  $fap->query("select olid,qty,olprice from oline ".
		"where orderid='$cartid' and compsku='$csku'");
	  if( $fap->next_record() ){ // product is already on the order
		$fai->query(
			"select prodinvqty,produseinvq,prodordmax,prodflag1 ".
			"from prod where prodsku='$product'");
		$fai->next_record();
		$use=(int)$fai->f("produseinvq");
		$inv=(int)$fai->f("prodinvqty");
		$ordmax=(int)$fai->f("prodordmax");
		$fai->free_result();
		if($ordmax && $quantity>$ordmax){
			// can't order more than max; check before inventory
			$quantity = $ordmax;
		}
		// compsku is not updated on existing products; the product
		// must be deleted and readded with a different option mix
		if($use && $quantity>$inv){
		 $fao->query(
		 "update oline set qty=$inv,invover=1 ".
		 "where orderid='$cartid' and compsku='$csku'");
		}else{
		 $fao->query(
		 "update oline set qty=$quantity,invover=0 ".
		 "where orderid='$cartid' and compsku='$csku'");
		}
	  }else{
	  	// this is a new product on the order
		// see if there is sufficient inventory
		$fai->query(
			"select prodinvqty,produseinvq,prodordmax,prodsetup,prodprice,".
			"prodsaleprice,prodsalebeg,prodsaleend,prodstsalebeg,prodstsaleend,".
			"prodstsaleprice,prodflag1 ".
			"from prod where prodsku='$product'");
		$fai->next_record();
		$use=(int)$fai->f("produseinvq");
		$inv=(int)$fai->f("prodinvqty");
		$stslb=(int)$fai->f("prodstsalebeg");
		$stsle=(int)$fai->f("prodstsaleend");
		if($stslb<$now&&$now<$stsle){
		$setup=(double)$fai->f("prodstsaleprice");
			$stslprc=1;
		}else{
		$setup=(double)$fai->f("prodsetup");
			$stslprc=0;
		}
  		// for one time product setup regardless of qty, comment the line below
  		$setup=$setup*$quantity;
		// put price in order record to track discounts
		$slb=(int)$fai->f("prodsalebeg");
		$sle=(int)$fai->f("prodsaleend");
		$flag1=(int)$fai->f("prodflag1");
		if($slb<$now&&$now<$sle){
			$prc=(double)$fai->f("prodsaleprice");
			$slprc=1;
		}else{
			$prc=(double)$fai->f("prodprice");
		}
		if( $npoptgrps ){	// if product options
			if( $poptflag1 & $flag_poptprcrel ){
				$prc=rnd( $prc + $popttotal );
			}else{
				$prc=$popttotal;
			}
		}
		$ordmax=(int)$fai->f("prodordmax");
		$fai->free_result();
		if($ordmax && $quantity>$ordmax){
			// can't order more than max; check before inventory
			$quantity = $ordmax;
		}
		if($use && $quantity>0 && $quantity>$inv){
		 $fao->query("insert into oline ".
		 "(olid,olzone,ollang,orderid,sku,compsku,qty,invover,olprice) ".
		 "values ".
		 "($olid,$zid,$lid,'$cartid','$product','$csku',$inv,0,$prc)");
		}elseif($quantity>0){
		 $fao->query("insert into oline ".
		 "(olid,olzone,ollang,orderid,sku,compsku,qty,invover,olprice) ".
		 "values ".
		 "($olid,$zid,$lid,'$cartid','$product','$csku',$quantity,0,$prc)");
		}
	  }
	  $fap->free_result();
	} // option_violation
}

// szid defined means we are coming back from showgeo
if( $szid ){
 $subz=$szid; // set the regular subzone variable
 $state=strtoupper($state);
 if( $oszid && ($szid != $oszid) ){
  // Revert to default shipping if they changed subzones and a subzone
  // was previously defined.
  // if oszid == 0, this was the first time into showgeo
  // if oszid == szid, the subzone did not change.
  $curshipid=0;
  $shpstr=',shipid=0';
 }else{
  $shpstr='';
 }
 $fao->query(
  "update ohead set
  subz=$szid$shpstr,scity='$city',sstate='$state',szip='$zip',scountry='$country',complete=0 where orderid='$cartid'");
 $fao->commit();
}

// see if a subzone has been chosen yet
// or if there are child subzones to this one
// if subzparent is non-zero, they selected a subzone with children,
// must now show the child subzones
if( ($subz==0 && !$noproducts) || $subzparent ){
 include("./$geo");
 exit;
}

if(!empty($subz)){
 $fasz->query("select subztaxpern,subztaxpers,subztaxcmtn,subztaxcmts ".
  "from subzone where subzid=$zid and subzsid=$subz");
 if( !$fasz->next_record() ){
   // not found in subzone table; reset and go back to geo selection
  $fao->query("update ohead set subz=0,shipid=0 where orderid='$cartid'");
  $fao->commit();
  header("Location: $nsecurl$cartdir/$geo?cartid=$cartid&zid=$zid&lid=$lid&olimit=$olimit&nlst=$nlst&olst=$olst&cat=$cat&key1=$key1&szid=$szid");
  exit;
 }
}

if( $product && ($zflag1 & $flag_zonereturn) ){
 if( $fname ){
   header("Location: $nsecurl$cartdir/".urldecode($fname).
 		"?cartid=$cartid&zid=$zid&lid=$lid&olimit=$olimit".
		"&nlst=$nlst&olst=$olst&cat=$cat&key1=$key1&szid=$szid");
 }else{
   $referer = ereg("(^[^?]+)?",$HTTP_REFERER,$ref);
   $referer = $ref[1];
   header("Location: ".$referer.
   // header("Location: ".$HTTP_REFERER.
		"?cartid=$cartid&zid=$zid&lid=$lid&olimit=$olimit".
		"&nlst=$nlst&olst=$olst&cat=$cat&key1=$key1&szid=$szid");
 }
 exit;
}

$fct = new FC_SQL;

// get the number of shipping options for this subzone
$fct->query("select count(*) as scnt from ship, subzship ".
	"where shipzid=$zid ".
	"and shipszid=$subz ".
	"and ship.shiplid=$lid ".
	"and subzship.shiplid=$lid ".
	"and subzship.shipid=ship.shipid ".
	"and subzship.shiplid=ship.shiplid");
$fct->next_record();
$scnt=(int)$fct->f("scnt");
$fct->free_result();

// get the current or default shipping option
if($scnt){
 if($curshipid){
  // a shipping profile is already selected
  $fct->query(
   "select ship.shipid,shipcalc,shipdescr,shippercent,shipitem,shipitem2,".
   "shipsvccode from ship,subzship ".
   "where shipzid=$zid ".
   "and shipszid=$subz ".
   "and ship.shiplid=$lid ".
   "and subzship.shiplid=$lid ".
   "and subzship.shipid=ship.shipid ".
   "and subzship.shiplid=ship.shiplid ".
   "and ship.shipid=$curshipid");
 }else{
  // get the default profile
  $fct->query(
   "select ship.shipid,shipcalc,shipdescr,shippercent,shipitem,".
   "shipitem2,shipsvccode,active from ship,subzship ".
   "where shipzid=$zid ".
   "and shipszid=$subz ".
   "and shipdef=1 ".
   "and ship.shiplid=$lid ".
   "and subzship.shiplid=$lid ".
   "and subzship.shipid=ship.shipid ".
   "and subzship.shiplid=ship.shiplid");
 }
 if( $fct->next_record() ){
  $curshipid=(int)$fct->f("shipid");
  $fap->query("update ohead set shipid=$curshipid where orderid='$cartid'");
  $fap->commit();
  $defshipdesc=stripslashes($fct->f("shipdescr"));
  $tmp=stripslashes($fct->f("shipcalc"));
  $shipcalc = './' . $tmp;
  if( empty($tmp) || !file_exists($shipcalc) ){
   $shipcalc="";
  }
 }else{
  $shipcalc="";
 }
}else{
  $shipcalc="";
}
?>
</td></tr>
<form name="showcart" method="post" action="modcart.php">

<?php if( !empty($option_violation) ){ ?>
<tr><td class="showcartcell" align="center" valign="top" colspan="4" bgcolor="#FFFFFF">
<?php echo fc_text('optviolation'); ?>
</td></tr>
<?php } ?>

<?php
$fao->query("select * from oline where orderid='$cartid'"); 
if($fao->next_record()){
?>

<?php // now show the product display table
	$allowupdate=1;
	include('proddisp.php');
?>

<?php if( $zflag1 & $flag_zonecoupon ){ ?>
<tr><td class="showcartcell" valign="middle" align="left" colspan="1" bgcolor="#FFFFFF">
<b><?php echo fc_text('couponid'); ?></b>
</td><td class="showcartcell" valign="middle" align="left" colspan="3" bgcolor="#FFFFFF">
<input name="couponid" size="20" value="<?php echo $couponid; ?>" />
</td></tr>
<?php } ?>

<tr><td class="subdivrow" valign="top" align="center" colspan="4" bgcolor="#CCCCCC">
<p align="center"><font size="-1"><?php echo fc_text('cartmodify'); ?></font></p>

<input type="hidden" name="itot" value="<?php echo $i?>" />
<input type="hidden" name="subz" value="<?php echo $subz?>" />
<input type="hidden" name="szid" value="<?php echo $subz?>" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="olimit" value="<?php echo $olimit?>" />
<input type="hidden" name="nlst" value="<?php echo $nlst?>" />
<input type="hidden" name="olst" value="<?php echo $olst?>" />
<input type="hidden" name="key1" value="<?php echo $key1?>" />
<input type="hidden" name="cat" value="<?php echo $cat?>" />
<input type="hidden" name="city" value="<?php echo $city?>" />
<input type="hidden" name="state" value="<?php echo $state?>" />
<input type="hidden" name="zip" value="<?php echo $zip?>" />
<input type="hidden" name="country" value="<?php echo $country?>" />
<input type="hidden" name="fname" value="<?php echo urlencode($fname);?>" />

<input type="submit" value="<?php echo fc_text('cartsubmit'); ?>" />

</td></tr>

<?php
 if( !empty( $invshort ) ){?>
<tr><td class="showcartcell" valign="top" align="center" colspan="4" bgcolor="#FFFFFF">
<?php echo fc_text('cartinvmax'); ?>
</td></tr>
<?php
 }
?>

<?php
} // order line count > 0
?>
</form>


<?php

//auxilliary links. jheg
echo "<br />";
$fcal=new FC_SQL;
$fcal->query("select count(*) as cnt from auxlinks where loc=2");
$fcal->next_record();
$auxlinkcnt=(int)$fcal->f('cnt');
$fcal->free_result();
$fcal->query("select title, url from auxlinks where loc=2 order by seq");
if($auxlinkcnt){?>
<tr><td class="showcartcell" align="center" valign="top" colspan="4" bgcolor="#FFFFFF">
<?php while ($fcal->next_record()){
 $url = stripslashes($fcal->f("url"));
 eval("\$url = \"$url\";");
 echo '| <a href="'.$url.'"><b>'.stripslashes($fcal->f("title"))."</b></a> |\n";
}
$fcal->free_result();?>
</td></tr>
<?php }
$fcal->query("select text from auxtext where loc=2 order by seq");
while ($fcal->next_record()){?>
<tr><td class="showcartcell" align="center" valign="top" colspan="4" bgcolor="#FFFFFF">
<?php
 $text = stripslashes($fcal->f("text"));
 echo $text;
?>
</td></tr>
<?php
}
$fcal->free_result();
?>
</table>
</td></tr>
<tr><td align="center">
<table border="0" align="center" cellspacing="0" cellpadding="0">
<tr>
<?php echo "<td><div id=\"button\"><ul><li><a href=\"index.php?cartid=$cartid&zid=$zid&lid=$lid\">";?><?php echo fc_text("zonehome");?><?php echo "</a></li></ul></div></td>\n";

if( !empty( $fname ) ){ // works with the fc_functions library
 echo "<td><div id=\"button\"><ul><li><a href=\"$nsecurl".urldecode($fname)."?cartid=$cartid&zid=$zid&lid=$lid\">";?><?php echo fc_text("returnpage");?><?php echo "</a></li></ul></div></td>\n";
}

if($noproducts==0){
if(!empty($return_product) && ($cat!=0 || $key1)){
 echo "<td><div id=\"button\"><ul><li><a href=\"display.php?cartid=$cartid&zid=$zid&lid=$lid&olimit=$olimit&nlst=$nlst&olst=$olst&cat=$cat&key1=$key1\">";?><?php echo fc_text("returnprod");?><?php echo "</a></li></ul></div></td>\n";
}

echo "<td><div id=\"button\"><ul><li><a href=\"$geo?cartid=$cartid&zid=$zid&lid=$lid&olimit=$olimit&nlst=$nlst&olst=$olst&cat=$cat&key1=$key1&szid=$subz&return_product=$return_product\">";?><?php echo fc_text("shiploc");?><?php echo "</a></li></ul></div></td>\n";

echo "<td><div id=\"button\"><ul><li><a href=\"$securl$secdir/$ordr?cartid=$cartid&zid=$zid&lid=$lid&itot=$i&subz=$subz\">";?><?php echo fc_text("checkout");?><?php echo "</a></li></ul></div></td>\n";
}
?>
</tr></table>
</td></tr></table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>
</td></tr></table>
<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
