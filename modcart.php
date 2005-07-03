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
$CookieCustid = getcookie("Cookie${instid}Custid");
$CookieCart   = getcookie("Cookie${instid}Cart");
$cartid = getparam('cartid');
$city = getparam('city');
$state = getparam('state');
$zip = getparam('zip');
$country = getparam('country');
$product = getparam('product');
$couponid = getparam('couponid');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$cat = (int)getparam('cat');
$subz = (int)getparam('subz');
$szid = (int)getparam('szid');
$nlst = (int)getparam('nlst');
$olst = (int)getparam('olst');
$itot = (int)getparam('itot');
$zflag1 = (int)getparam('zflag1');
$olimit = (int)getparam('olimit');
$contrib = (int)getparam('contrib');
$shipid = (int)getparam('shipid');
// ==========  end of variable loading  ==========

if(empty($pub_inc)){
 require('./public.php');
}
if(empty($zflag1)){
 $fcz=new FC_SQL;
 $fcz->query("select zflag1 from zone where zoneid=$zid");
 if($fcz->next_record()){
  $zflag1=$fcz->f("zflag1");
 }else{
  $zflag1=0;
 }
 require('./flags.php');
}

require_once( '../bit_setup_inc.php' );
$shipid=(int)$shipid;
$c=(double)$contrib;
if( $c < 0 ){ $c=0.0; }

$fch = new FC_SQL;
$fcl = new FC_SQL;
$fcp = new FC_SQL;

$fch->query(
 "update ohead set contrib=$c,shipid=$shipid,scity='$city',sstate='$state',".
 "szip='$zip',scountry='$country',couponid='$couponid' where orderid='$cartid'");
$i=0;
while( $i < $itot ){

	$tqty =(int)getparam("qty$i");
    $tsku = getparam("sku$i");
    $tcsku = getparam("csku$i");

	// catch 2^31 32 bit integer overflow as well
	if( $tqty < 0 || $tqty > 2147483647 ){
		$tqty=0;
	}
	if( $tqty == 0 ){
	 $fch->query(
	  "delete from oline where orderid='$cartid' and compsku='$tcsku'"); 
	 $fch->query(
	  "delete from olineopt where orderid='$cartid' and compsku='$tcsku'");
	}else{
		// see if there is sufficient inventory or if over max
		$fcp->query("select prodinvqty,produseinvq,prodordmax from prod ".
			"where prodsku='$tsku'");
		$fcp->next_record();
		$useq=(int)$fcp->f("produseinvq");
		$inv=(int)$fcp->f("prodinvqty");
		$ordmax=(int)$fcp->f("prodordmax");
		$fcp->free_result();
		if( $ordmax && $tqty > $ordmax ){
			// can't order more than max; check before inventory
			$tqty = $ordmax;
		}
		if( $useq > 0 && $tqty > $inv ){
			$fch->query("update oline set qty=$inv, invover=1 ".
				"where orderid='$cartid' and compsku='$tcsku'"); 
		}else{
			$fch->query("update oline set qty=$tqty, invover=0 ".
				"where orderid='$cartid' and compsku='$tcsku'"); 
		}
	}
	$i++;
}
$fcp->commit();
$product=urlencode($product);
if($zflag1 & $flag_zonezipshowgeo){
 $showgeo="&szid=$szid&city=$city&state=$state&zip=$zip&country=$country";
}else{
 $showgeo="&szid=$szid";
}
Header("Location: $nsecurl$cartdir/showcart.php?cartid=$cartid&zid=$zid&lid=$lid&olimit=$olimit&nlst=$nlst&olst=$olst&cat=$cat&key1=$key1&product=$product".$showgeo);
exit;
?>
