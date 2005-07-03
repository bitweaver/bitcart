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

This file displays products and totals for both showcart.php and
orderform.php.  It is included by those to files; this file
should not be referenced except as a require().

Assumes functions.php has already been included.

*/

// avoid direct calls; only works if register_globals is off
if( empty($zid) ){ exit; }

$now=time();

$fao->query("select * from oline where orderid='$cartid'"); 
if($fao->next_record()){
?>
<tr><td class="subdivrow" align="center" valign="top" bgcolor="#CCCCCC" width="310">
<b><?php echo fc_text('proddesc'); ?></b>
</td><td class="subdivrow" align="center" valign="top" bgcolor="#CCCCCC" width="90">
<b><?php echo fc_text('quantity'); ?></b>
</td><td class="subdivrow" align="center" valign="top" bgcolor="#CCCCCC" width="90">
<b><?php echo fc_text('unitprice'); ?></b>
</td><td class="subdivrow" align="center" valign="top" bgcolor="#CCCCCC" width="90">
<b><?php echo fc_text('total'); ?></b>
</td></tr>

<?php 
$i=0;
$tqty=0;
$fcp = new FC_SQL;
$fcdel = new FC_SQL;
do{ // already have the first record

 $sku=stripslashes($fao->f("sku"));
 $csku=stripslashes($fao->f("compsku"));
 $qty=(double)$fao->f("qty");
 $invover=(int)$fao->f("invover");
 $olzone=(int)$fao->f("olzone");
 $ollang=(int)$fao->f("ollang");

 // get the short product description
 $fcp->query("select prodname from prodlang ".
	 "where prodlzid=$olzone and prodlid=$ollang and prodlsku='$sku'");
 if(!$fcp->next_record()){
  $fcdel->query("delete from oline where orderid='$cartid'");
  continue;
 }
 $sdescr=stripslashes(ereg_replace("<[^>]+>"," ",$fcp->f("prodname")));
 $fcp->free_result();

 $fcp->query("select prodprice,prodsetup,prodsaleprice,prodsalebeg,".
     "prodsaleend,prodstsalebeg,prodstsaleend,prodstsaleprice,".
	 "prodweight,prodflag1 from prod ".
	 "where prodzid=$olzone and prodsku='$sku'");
 if(!$fcp->next_record()){
  $fcdel->query("delete from oline where orderid='$cartid'");
  continue;
 }
	$stslb=(int)$fcp->f("prodstsalebeg");
	$stsle=(int)$fcp->f("prodstsaleend");
	if( $stslb < $now && $now < $stsle ){
		$prodsetup=(double)$fcp->f("prodstsaleprice");
		$stslprc=1;
	}else{
		$prodsetup=(double)$fcp->f("prodsetup");
		$stslprc=0;
	}
	$slb=(int)$fcp->f("prodsalebeg");
	$sle=(int)$fcp->f("prodsaleend");
	if( $slb < $now && $now < $sle ){
		$prodprice=(double)$fcp->f("prodsaleprice");
		$slprc=1;
	}else{
		$prodprice=(double)$fcp->f("prodprice");
		$slprc=0;
	}
 $prodweight=(double)$fcp->f("prodweight");
 $flag1=(int)$fcp->f("prodflag1");
 $fcp->free_result();
?>

<tr><td class="showcartcell" align="left" valign="top" bgcolor="#FFFFFF">

<?php echo $sdescr; ?><br />

<?php // retrieve product options, accumulate $popttotal, $poptsetttot
 if( $sku != $csku && $prodprice ){
  echo '&nbsp;&nbsp;&nbsp;'.fc_text('baseprice').' '.
 	sprintf("%s%.2f<br />\n",$csym,$prodprice);
  if( $prodsetup ){
   echo '&nbsp;&nbsp;&nbsp;'.fc_text('basesetup').' '.
 	sprintf("%s%.2f<br />\n",$csym,$prodsetup);
  }
 }
 while( get_prodopts($csku) ){
  echo '&nbsp;&nbsp;&nbsp;'.fc_text('option').'&nbsp;'.$poptname."<br />\n";
  echo '&nbsp;&nbsp;&nbsp;&nbsp;';
  if($poptqty){
	  echo fc_text('qty').'&nbsp;'.$poptqty;
	  if( $poptextension ){
	   echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text('total').
	   		'&nbsp;'.sprintf("%s%.2f",$csym,$poptextension);
	  }
  }else{
	  echo fc_text('total').'&nbsp;'.
	  sprintf("%s%.2f",$csym,$poptextension);
  }
  if($poptsetup){
    // for one time product setup regardless of qty, comment the line below
	$poptsetup=$poptsetup*$poptqty;
    echo '&nbsp;&nbsp;&nbsp;'.fc_text('setup').'&nbsp;'.
      sprintf("%s%.2f",$csym,$poptsetup);
  }
  echo "<br />\n";
 }
?>

</td><td class="showcartcell" align="center" valign="top" bgcolor="#FFFFFF">

<?php 
 if($invover>0){echo"*** ";$invshort=1;}
 if( $allowupdate ){
  echo "<input name=\"qty$i\" size=\"3\" value=\"$qty\" /><br />\n";
 }else{
  echo "$qty<br />\n";
 }
 echo "<input type=\"hidden\" name=\"sku$i\" value=\"$sku\" />\n";
 echo "<input type=\"hidden\" name=\"csku$i\" value=\"$csku\" />\n";
 ?>

</td><td class="showcartcell" align="right" valign="top" bgcolor="#FFFFFF">

<?php 
 	// figure options modified product price, accumulate $prodsetttot
	// sets various globals
	$prodprice = prod_price ( $sku );

 	if(!$prodprice){ $prodprice=$webfree; }

	printf("%s%.2f<br>",$csym,$prodprice);
	if( $prodsetup ){
  		// for one time product setup regardless of qty, comment the line below
  		$prodsetupext=$prodsetup*$qty;
		printf("%s&nbsp;%s%.2f<br />",fc_text('setuptotal'),$csym,$prodsetup);
		$prodsettot=rnd($prodsettot+$prodsetupext);
	}
?>

</td><td class="showcartcell" align="right" valign="top" bgcolor="#FFFFFF">

<?php 
	$ltotal=line_total( $qty, $prodprice );
	$tqty=rnd($tqty+$qty);
	$lweight=rnd($qty * $prodweight);
	printf("%s%.2f<br>",$csym,$ltotal);
	if( $prodsetup ){
	 printf("%s%.2f<br>",$csym,$prodsetupext);
	}
    if( $flag1 & $flag_persvc ){
	 $mstotal=rnd($mstotal+$ltotal);
	 $mtotal=rnd($mtotal+$ltotal);
	 $ptotal=rnd($ptotal+$ltotal);
	}else{
	 $wtotal=rnd($wtotal+$lweight);
	 $pstotal=rnd($pstotal+$ltotal);
	 $stotal=rnd($stotal+$ltotal);
	 $ttotal=rnd($ttotal+$ltotal);
	}
?>

</td></tr>
 <?php
 $i++;
} while($fao->next_record()); /* end of product display while loop */
?>

<?php if( $prodsettot ){ ?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('setupfees'); ?></b><br />
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php
printf("%s%.2f<br />",$csym,$prodsettot);
// accumulate setup into order total
$ttotal=rnd($prodsettot+$ttotal);
?>
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
  $ttotal=rnd($ttotal - $cpndisc);
?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('coupondisc'); ?></b><br />
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php printf("%s%.2f<br />",$csym,$cpndisc); ?>
</td>
<?php
 }
} // flag_zonecoupon ?>

<?php if( $stotal ){ ?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('subtotal'); ?></b>
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php printf("%s%.2f<br />",$csym,$stotal); ?>
</td></tr>

<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">

<b><?php echo fc_text('shipfee'); ?></b><br />

<?php
if( $allowupdate ){
 if($scnt>1){ ?>
<select name="shipid" size="1" onChange="submit(); return false;">
<?php
  $fcmt = new FC_SQL;
  $fcmt->query("select ship.shipid,shipdef,shipdescr ".
 	"from ship,subzship ".
	"where shipzid=$zid ".
	"and shipszid=$subz ".
	"and ship.shiplid=$lid ".
	"and subzship.shiplid=$lid ".
	"and subzship.shipid=ship.shipid ".
	"and subzship.shiplid=ship.shiplid ".
	"order by ship.shipid");
  while( $fcmt->next_record() ){
   if( $curshipid==(int)$fcmt->f("shipid") ){ ?>
<option value="<?php echo $fcmt->f("shipid") ?>" selected><?php echo stripslashes($fcmt->f("shipdescr")) ?>
</option>
<?php }else{ ?>
<option value="<?php echo $fcmt->f("shipid") ?>"><?php echo stripslashes($fcmt->f("shipdescr")) ?>
</option>
<?php
   } // if shipdef
  } // while
?>
</select><br />
<?php
 $fcmt->free_result();
 }else{ ?>
<input type="hidden" name="shipid" value="<?php echo $curshipid ?>">
<?php
  echo $defshipdesc."<br />\n";
 }
}else{ ?>
<i><?php echo stripslashes($fct->f("shipdescr"))?></i><br />
<?php } ?>

</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">

<?php
if($shipcalc){
 include ( $shipcalc );
}else{
 $shamt=0.0;
}
$fct->free_result();
$ttotal=rnd($ttotal+$shamt);
printf("%s%.2f<br />",$csym,$shamt);
?>

</td></tr>
<?php } // if stotal ?>

<?php
$taxpern=(double)$fasz->f("subztaxpern"); // shipping not taxed
$taxpers=(double)$fasz->f("subztaxpers"); // shipping taxed
$taxcmtn=stripslashes($fasz->f("subztaxcmtn")); // shipping not taxed text
$taxcmts=stripslashes($fasz->f("subztaxcmts")); // shipping taxed text

if( ($taxpern || $taxpers) && $ttotal ){
?>
<tr><td class="showcartcell" colspan="1" bgcolor="#ffffff"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('salestax'); ?></b><br />
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php 
$tpn=0.0;
$tps=0.0;
if( $taxpern ){ // nontaxable shipping
 $tpn=rnd($taxpern*$taxsubtotal);
 if( $taxcmtn ){
  printf("%s: %s%.2f<br />",$taxcmtn,$csym,$tpn);
 }else{
  printf("%s%.2f<br />",$csym,$tpn);
 }
 $ttotal=rnd($ttotal+$tpn);
}
if( $taxpers ){ // taxable shipping
 $tps=rnd($taxpers*($taxsubtotal+$shamt));
 if( $taxcmts ){
  printf("%s: %s%.2f<br />",$taxcmts,$csym,$tps);
 }else{
  printf("%s%.2f<br />",$csym,$tps);
 }
 $ttotal=rnd($ttotal+$tps);
}
?>

</td></tr>

<?php
} // taxper>0

$contamt=(double)$fco->f("contrib");
// check 0 to disable donate box in showcart.php, use inline
// contribution instead
if( (0 && $allowupdate && !($zflag1 & $flag_zoneinlinecontrib)) ||
	(!$allowupdate && $contamt > 0) ){
?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('voluntary'); ?></b><br />
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php 
if( $allowupdate ){
 printf("<input name=\"contrib\" size=\"6\" value=\"%.2f\" onChange=\"submit(); return false;\" /><br />\n",$contamt);
}else{
 printf("%s%.2f<br />",$csym,$contamt);
}
$ttotal=rnd($contamt+$ttotal);
?>

</td></tr>
<?php
}	// end of contribution
?>

<?php if( $ttotal ){ ?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('total'); ?></b>
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php printf("%s%.2f<br />",$csym,$ttotal); ?>
</td></tr>
<?php } ?>

<?php
if( ($taxpern || $taxpers) && $mtotal ){
?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('psalestax'); ?></b><br />
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php 
$tpn=0.0;
$tps=0.0;
if( $taxpern ){ // nontaxable shipping
 $tpn=rnd($taxpern*$ptaxsubtotal);
 if( $taxcmtn ){
  printf("%s: %s%.2f<br />",$taxcmtn,$csym,$tpn);
 }else{
  printf("%s%.2f<br />",$csym,$tpn);
 }
 $mtotal=rnd($mtotal+$tpn);
}
if( $taxpers ){ // taxable shipping
 $tps=rnd($taxpers*($ptaxsubtotal+$shamt));
 if( $taxcmts ){
  printf("%s: %s%.2f<br />",$taxcmts,$csym,$tps);
 }else{
  printf("%s%.2f<br />",$csym,$tps);
 }
 $mtotal=rnd($mtotal+$tps);
}
?>

</td></tr>

<?php
} // taxper>0

if( $mtotal ){ ?>
<tr><td class="showcartcell" colspan="1" bgcolor="#FFFFFF"></td>
<td class="showcartcell" colspan="2" align="left" valign="top" bgcolor="#FFFFFF">
<b><?php echo fc_text('psubtotal'); ?></b>
</td><td class="showcartcell" colspan="1" align="right" valign="top" bgcolor="#FFFFFF">
<?php printf("%s%.2f<br />",$csym,$mtotal); ?>
</td></tr>
<?php } ?>

<?php
} // if($olc>0)
?>
