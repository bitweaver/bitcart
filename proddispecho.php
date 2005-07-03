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

   This file echos product info in fixed space format; it is used
   typically for email orders and confirmations.
*/

// SQL INJECTION AVOIDENCE

$zid=(int) $zid;
$lid=(int) $lid;
$subz=(int) $subz;
$qty=(int) $qty;

if( empty($contrib_only) && empty($payment_only) ){

echo "\n";
// echo "ITEM         QTY      PRICE     PRODUCT\n";
echo fc_text('itemcapfix').fc_text('qtycapfix').fc_text('pricecapfix').fc_text('productcapfix')."\n";

$fco = new FC_SQL;
$fcp = new FC_SQL;

$fco->query("select subz from ohead where orderid='$cartid'");
$fco->next_record();
$subz=$fco->f("subz");
$fco->free_result();

if( !empty($subz) ){
 $fco->query("select subzdescr from subzone where subzsid=$subz");
 $fco->next_record();
 $subzdescr=$fco->f("subzdescr");
 $fco->free_result();
}else{
 $subzdescr="";
}

$tqty=0;
$fco->query("select sku,compsku,qty,olid from oline where orderid='$cartid'"); 
while($fco->next_record()){

 $qty=$fco->f("qty");
 $sku=$fco->f("sku");
 $csku=$fco->f("compsku");
 $olid=(int)$fco->f("olid");

 $fcp->query("select prodname from prodlang ".
  "where prodlzid=$zid and prodlid=$lid and prodlsku='$sku'");
 $fcp->next_record();
 // strip any HTML tags
 $dsc=ereg_replace("<[^>]+>"," ",$fcp->f("prodname"));
 $dsc=substr($dsc,0,35);
 $fcp->free_result();

 // accumulate $popttotal, $poptsettot
 while( get_prodopts($csku) ){
 }

 // figure options modified product price, accumulate $prodsetttot
 // sets various globals
 $prodprice = prod_price ( $sku );

 $ltotal = line_total( $qty, $prodprice );
 $tqty=rnd($tqty+$qty);

 if( $csku ){
  $tmp =sprintf("\n%10s",$csku);	// ProductNumber
 }else{
  $tmp =sprintf("\n%10s",$sku);		// ProductNumber
 }
 $tmp.='   ';
 $tmp.=sprintf("%3d",$qty);			// Quantity
 $tmp.='  ';
 $tmp.=sprintf("%8.2f",$ltotal);	// ExtPrice
 $tmp.='   ';
 $tmp.=sprintf("%s",$dsc);			// Description
 $tmp.="\n";
 echo $tmp;

 // now display the product options
 if( $sku != $csku ){
  echo fc_text('basepricefix').sprintf("%s%.2f\n",$csym,$prodbaseprice);
 }
 while( get_prodopts($csku) ){
   echo fc_text('optionfix').$poptname."\n";
   if($poptqty){
    echo fc_text('qtyfix').$poptqty."\n".
	     fc_text('totalfix').sprintf("%s%.2f\n",$csym,$poptextension);
   }else{
    echo fc_text('totalfix').sprintf("%s%.2f\n",$csym,$poptextension);
   }
   if($poptsetup){
    echo fc_text('setupfix').sprintf("%s%.2f\n",$csym,$poptsetup);
   }
 }
}
$fco->free_result();

echo "\n";
if( $prodsettot ){
 echo fc_text('setuptotalfix').sprintf("%8.2f\n",$prodsettot);
}
if( $zflag1 & $flag_zonecoupon ){
 if( $cpndisc > 0 ){
  echo fc_text('discountfix').sprintf("%8.2f\n",$cpndisc);
 }
}
if($stotal){
echo fc_text('subtotalfix').sprintf("%8.2f\n",$stotal);
echo fc_text('shippingfix').sprintf("%8.2f   %s\n",$shamt,$shipdescr);
} // if stotal

$staxn=(double)$staxn;
$staxs=(double)$staxs;
if($staxn){
 if( $staxcmtn ){
  echo sprintf("   %9s:     %8.2f\n",$staxcmtn,$staxn);
 }else{
  echo fc_text('salestaxfix').sprintf("%8.2f\n",$staxn);
 }
}
if($staxs){
 if( $staxcmts ){
  echo sprintf("   %9s:     %8.2f\n",$staxcmts,$staxs);
 }else{
  echo fc_text('salestaxfix').sprintf("%8.2f\n",$staxs);
 }
}
} // end of contrib_only

if($contamt>0){
echo fc_text('contributefix').sprintf("%8.2f\n",$contamt);
}

if( !empty($payment_only) ){
echo fc_text('paymentfix').sprintf("%8.2f\n",$payment);
}

if( empty($contrib_only) && empty($payment_only) && $ttotal ){
echo fc_text('ordertotalfix').sprintf("%8.2f\n",$ttotal);
}

if( $ttotal && $ptotal){
echo "\n";
}
if($mtotal){
echo fc_text('psubtotalfix').sprintf("%8.2f\n",$mtotal);
}
$pstaxn=(double)$pstaxn;
$pstaxs=(double)$pstaxs;
if($pstaxn){
 if( $staxcmtn ){
  echo sprintf("   %9s:     %8.2f\n",$staxcmtn,$pstaxn);
 }else{
  echo fc_text('psalestaxfix').sprintf("%8.2f\n",$pstaxn);
 }
}
if($pstaxs){
 if( $staxcmts ){
  echo sprintf("   %9s:     %8.2f\n",$staxcmts,$pstaxs);
 }else{
  echo fc_text('psalestaxfix').sprintf("%8.2f\n",$pstaxs);
 }
}

if( empty($contrib_only) && empty($payment_only) && $ptotal ){
echo fc_text('ptotalfix').sprintf("%8.2f\n",$ptotal);
}
?>
