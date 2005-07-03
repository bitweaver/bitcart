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

$fc_functions=1;

if( empty($pub_inc) ){
 require('BITCART_PKG_PATHpublic.php');
}

function fc_getpricedescr ( $zid, $lid, $sku ) {
 // variables in which values are returned
 global $fc_sdescr, $fc_descr, $fc_price, $fc_retail, $fc_onsale; 
 $now=time();

 $fcp = new FC_SQL;
 // get the language independent pieces
 $fcp->query(
  "select prodsalebeg,prodsaleend,prodsaleprice,prodprice,prodrtlprice ".
  "from prod where prodsku='$sku' and prodzid=$zid");
 if ( $fcp->next_record() ) {
  $fc_retail=sprintf("%.2f",$fcp->f("prodrtlprice"));
  if( $fcp->f("prodsalebeg") < $now && $now < $fcp->f("prodsaleend") ){
   $fc_price=sprintf("%.2f",$fcp->f("prodsaleprice"));
   $fc_onsale=1;
  }else{
   $fc_price=sprintf("%.2f",$fcp->f("prodprice"));
   $fc_onsale=0;
  }
 } else {
  $fc_price=0; $fc_retail=0; $fc_onsale=0;
 }
 $fcp->free_result();

 $fcl = new FC_SQL;
 // get the language dependent pieces
 $fcl->query("select prodsdescr,proddescr from prodlang ".
  "where prodlzid=$zid and prodlid=$lid and prodlsku='$sku'");
 if ( $fcl->next_record() ) {
  $fc_descr=$fcl->f("proddescr");
  $fc_sdescr=$fcl->f("prodsdescr");
 } else {
  $fc_descr=""; $fc_sdescr="";
 }
 $fcl->free_result();
}

function fc_getprice ( $zid, $sku ) {
 // variables in which values are returned
 global $fc_price, $fc_retail, $fc_onsale; 
 $now=time();

 $fcp = new FC_SQL;
 // get the language independent pieces
 $fcp->query(
  "select prodsalebeg,prodsaleend,prodsaleprice,prodprice,prodrtlprice ".
  "from prod where prodsku='$sku' and prodzid=$zid");
 if ( $fcp->next_record() ) {
  $fc_retail=sprintf("%.2f",$fcp->f("prodrtlprice"));
  if( $fcp->f("prodsalebeg") < $now && $now < $fcp->f("prodsaleend") ){
   $fc_price=sprintf("%.2f",$fcp->f("prodsaleprice"));
   $fc_onsale=1;
  }else{
   $fc_price=sprintf("%.2f",$fcp->f("prodprice"));
   $fc_onsale=0;
  }
 }else{
  $fc_price=0; $fc_retail=0; $fc_onsale=0;
 }
 $fcp->free_result();
}

function fc_getpic ( $zid, $sku ) {
 // variables in which values are returned
 global $fc_pic; 

 $fcp = new FC_SQL;
 $fcp->query("select prodpic from prodlang ".
  "where prodlsku='$sku' and prodlzid=$zid");
 if ( $fcp->next_record() ) {
  $fc_pic=sprintf("%s",$fcp->f("prodpic"));
 }else{
  $fc_pic="";
 }
 $fcp->free_result();
}

function fc_addproduct($cartid, $product, $quantity) {
 global $zid;
 $fap = new FC_SQL;
 $fai = new FC_SQL;
 $fao = new FC_SQL;
 $fap->query("select qty from oline ".
  "where orderid='$cartid' and sku='$product'");
 if($fap->next_record()){
  // product is already on order, see if there is sufficient inventory
  $fai->query("select prodprice,prodinvqty,produseinvq,prodordmax,prodflag1 ".
   "from prod where prodsku='$product'");
  $fai->next_record();
  $use=(int)$fai->f("produseinvq");
  $inv=(int)$fai->f("prodinvqty");
  $ordmax=(int)$fai->f("prodordmax");
  $price=(double)$fai->f("prodprice");
  $fai->free_result();
  if($ordmax && $quantity > $ordmax){
   // can't order more than max
   $quantity = $ordmax;
  }
  if($use && $quantity>$inv){
   $fao->query("update oline set qty=$inv, invover=1 ".
   "where orderid='$cartid' and sku='$product'");
  }else{
   $fao->query("update oline set qty=$quantity, invover=0 ".
   "where orderid='$cartid' and sku='$product'");
  }
 }else{
  // product is not on order, see if there is sufficient inventory
  $fai->query("select prodprice,prodinvqty,produseinvq,prodordmax,prodflag1 ".
   "from prod where prodsku='$product'");
  $fai->next_record();
  $use=(int)$fai->f("produseinvq");
  $inv=(int)$fai->f("prodinvqty");
  $ordmax=(int)$fai->f("prodordmax");
  $price=(double)$fai->f("prodprice");
  $fai->free_result();
  if( $ordmax && $quantity>$ordmax ){
   // can't order more than max; check before inventory
   $quantity = $ordmax;
  }
  
  // get a unique ID for this order line
  srand((double)microtime()*1000000);
  $olid=rand();
  
  if($use && $quantity>0 && $quantity>$inv){
   $fao->query("insert into oline ".
   "(custid,olzone,ollang,orderid,sku,compsku,qty,invover,olprice,olid) ".
   "values ".
   "('$custid',$zid,$lid,'$cartid','$product','$product',$inv,1,".
   "$price,$olid)");
  }elseif($quantity>0){
   $fao->query("insert into oline ".
   "(custid,olzone,ollang,orderid,sku,compsku,qty,invover,olprice,olid) ".
   "values ".
   "('$custid',$zid,$lid,'$cartid','$product','$product',$quantity,0,".
   "$price,$olid)");
  }
 }
 $fao->commit();
 $fap->free_result();
}

function fc_open() {
 global $instid,$cartid,$zid,$lid,$aid,$CookieCart,$CookieCustID;
 global $fc_csym,$fc_webfree,$fc_webflags1;
 $custid="";
 if(isset($CookieCart)){
  list($cartid,$czid,$clid)=explode(":",$CookieCart);
  if(empty($zid)){ $zid=$czid; }
  if(empty($lid)){ $lid=$clid; }
 }
 if(isset($CookieCustID)){
  list($purchid,$purch_email)=explode(":",base64_decode($CookieCustID));
 }else{
  $purchid=0;
 }
 $fm = new FC_SQL;
 $fm->Auto_free =1;
 if(empty($zid)){ // get the default zone
  $fm->query("select zoneid from master");
  $fm->next_record();
  $zid=$fm->f("zoneid");
  $fm->free_result();
 }
 if(empty($lid)){	// get default language from zone record
  $fm->query("select zonedeflid from zone where zoneid=$zid");
  $fm->next_record();
  $lid=$fm->f("zonedeflid");
  $fm->free_result();
 }
 if(!empty($cartid)){ // see if this order is still valid 
  $fm->query("select aid from ohead where orderid='$cartid'");
  if( !$fm->next_record() ){
   // order no longer exists
   $cartid="";
   setcookie("Cookie${instid}Cart");
  }else{
   // the order still exists
   $tmp_aid=str_replace(" ","",$fm->f("aid"));
   $fm->free_result();
  }
 }
 if( empty($aid) ){
  $aid="";
 }
 if(empty($cartid)){
  $tstamp=time();
  if ( '' == 'solid' ){
   $fid = new FC_SQL;
   $fid->query("call order_ins");
   $fid->next_record();
   $cartid = date("Y",$tstamp) .
    sprintf("%02d",date("m",$tstamp)).
    sprintf("%02d",date("d",$tstamp)).
    sprintf("%07d",$fid->f("cartid_seq"));
  }else{
   $cartid=uniqid("");
  }
  $fm->query("insert into ohead ".
   "(custid,orderid,zone,subz,tstamp,contrib,trans1,aid,purchid,complete) ".
   " values ".
   "('$custid','$cartid',$zid,0,$tstamp,0,'$trans1','$aid',$purchid,0)");
  $fm->commit();
 }elseif( !$tmp_aid ){
  // order AID was null, update with the current one
  $fm->query("update ohead set aid='$aid' where orderid='$cartid'");
 }
 // keep the cookie 48 hours
 setcookie("Cookie${instid}Cart",$cartid.":".$zid.":".$lid,time()+172800);

 $fcw = new FC_SQL;
 $fcz = new FC_SQL;
 $fcw->query("select webfree,webflags1 from web ".
             "where webzid=$zid and weblid=$lid");
 $fcw->next_record();
 $fc_webfree=$fcw->f('webfree');
 $fc_webflags1=$fcw->f('webflags1');
 $fcw->free_result();

 $fcz->query("select zonecurrsym from zone ".
             "where zoneid=$zid");
 $fcz->next_record();
 $fc_csym=$fcz->f('zonecurrsym');
 $fcz->free_result();
}

function fc_close() {
}

function fc_active_categories( $cid=0 ){
 // pass the category in which to fetch subcats, 0 for the top level
 // re-enterable function to fetch a list of categories in the cart
 // returns the category number as $fc_catid, category name as $fc_catname
 global $fcc,$fc_catid,$fc_catname;
 static $ccnt=-1;
 if($ccnt==-1){
  if( $cid ){ $cwid="and catunder=$cid"; }else{ $cwid=''; }
  $fcc = new FC_SQL;
  // get the number of active categories
  $fcc->query(
   "select count(*) as ccnt from cat where catact=1 $cwid");
  $fcc->next_record();
  $ccnt=$fcc->f("ccnt");
  // now get the categories themselves
  $fcc->query(
   "select catval,catdescr from cat where catact=1 $cwid order by catdescr");
 }elseif($ccnt==0){
  $fcc->free_result();
  $ccnt=-1;
  return 0;
 }
 $fcc->next_record();
 $fc_catid=$fcc->f("catval");
 $fc_catname=$fcc->f("catdescr");
 return $ccnt--;
} 

function fc_get_cat( $cid=0 ){
 // pass the category from which to fetch information
 // returns the category number as $fc_catid, category name as $fc_catname
 global $fc_catid,$fc_catname;
 $fcc = new FC_SQL;
 $fcc->query("select catval,catdescr from cat where catval=$cid");
 $fcc->next_record();
 $fc_catid=$fcc->f("catval");
 $fc_catname=$fcc->f("catdescr");
 $fcc->free_result();
 return $fc_catid;
}

function fc_products_by_category($zid,$lid,$cat){
 // re-enterable function to fetch a list of products within a category
 // pass in zone, language and numeric category and subcategory (or 0)
 // if subcat=0, returns all products across all subcats within cat
 // returns the product SKU and short description
 global $fcp,$fc_catid,$fc_sku,$fc_sdescr,$fc_descr,$fc_retail,$fc_price;
 static $pcnt=-1;
 $now=time();
 if($pcnt==-1){
  $fcp = new FC_SQL;
  
   $fcp->query(
   "select count(*) as pcnt from prod,prodlang,prodcat where ".
   "pcatval=$cat and pcatzid=$zid and pcatzid=prodlzid and ".
   "prodlid=$lid and pcatsku=prodlsku and prodsku=prodlsku ".
   "and (produseinvq=0 or (produseinvq=1 and prodinvqty>0))");
  
  $fcp->next_record();
  $pcnt=$fcp->f("pcnt");
  // now get the products themselves
  
   $fcp->query(
   "select prodlsku,prodsdescr,proddescr,".
   "prodsalebeg,prodsaleend,prodsaleprice,prodprice,prodrtlprice ".
   "from prod,prodlang,prodcat where ".
   "pcatval=$cat and pcatzid=$zid and pcatzid=prodlzid and ".
   "prodlid=$lid and pcatsku=prodlsku and prodsku=prodlsku ".
   "and (produseinvq=0 or (produseinvq=1 and prodinvqty>0)) ".
   "order by pcatseq");
  
 }elseif($pcnt==0){
  $fcp->free_result();
  $pcnt=-1;
  return 0;
 }
 $fcp->next_record();
 $fc_sku=$fcp->f("prodlsku");
 $fc_descr=stripslashes($fcp->f("proddescr"));
 $fc_sdescr=stripslashes($fcp->f("prodsdescr"));
 $fc_retail=sprintf("%.2f",$fcp->f("prodrtlprice"));
 if( $fcp->f("prodsalebeg") < $now && $now < $fcp->f("prodsaleend") ){
  $fc_price=sprintf("%.2f",$fcp->f("prodsaleprice"));
  $fc_onsale=1;
 }else{
  $fc_price=sprintf("%.2f",$fcp->f("prodprice"));
  $fc_onsale=0;
 }
 $fc_catid=$cat;
 return $pcnt--;
} 

function fc_display_product($fc_sku='',$zid=1,$lid=1,$cat=0){
// display one product with options
// doesn't return anything
 global $fc_cartid,$flag_persvc,$flag_webshowqty;
 global $fc_csym,$fc_webfree,$fc_webflags1;
 global $flag_poptskusub,$flag_poptskumod,$flag_poptskusuf,$flag_poptskupre;
 global $flag_poptgrpexc,$flag_poptprcrel,$flag_poptgrpqty,$flag_poptgrpreq;

 $now=time();

 $fco = new FC_SQL;
 $fcp = new FC_SQL;
 $fcrp = new FC_SQL;
 $fcrpl = new FC_SQL;

  $fcp->query(
   'select prodsdescr,proddescr,prodaudio,prodvideo,prodsetup,prodprice,'.
   'prodsalebeg,prodsaleend,prodpic,prodpicw,prodpich,prodpersvc,prodflag1 '.
   'from prod,prodlang '.
   "where prodsku='$fc_sku' and prodlsku='$fc_sku' and prodsku=prodlsku");
 $fcp->next_record();

 $flag1=(int)$fcp->f('prodflag1');
?>
 
<tr><td align=left valign=top colspan=1>

 <table width="100%" cellpadding=0 cellspacing=0 border=0>
 <tr><td align=left valign=top colspan=3>
 <br>

<?php
 if($fcp->f("prodpic")){ // show the product picture (if defined)
?>

 <img src="<?php echo $fcp->f("prodpic")?>"
  width="<?php echo $fcp->f("prodpicw")?>"
  height="<?php echo $fcp->f("prodpich")?>"
  alt="" align=left>

<?php } // end of the product picture ?>

  <b><?php echo stripslashes($fcp->f("prodsdescr"))?>:</b>
     <?php echo stripslashes($fcp->f("proddescr"))?><br>

 </td></tr>
<tr><td align=left valign=bottom colspan="1">

<?php if( $fcp->f("prodaudio") ){?>
  <a href="<?php echo $fcp->f("prodaudio")?>"><i><?php echo fc_text("audiosample"); ?></i></a><br>
<?php }?> 

    </td><td align=center valign=bottom colspan="1">

<?php if( $fcp->f("prodvideo") ){?>
	  <a href="<?php echo $fcp->f("prodvideo")?>"><i><?php echo fc_text("videosample"); ?></i></a><br>
<?php }?>

	    </td><td colspan=1 align=right valign=middle>
		 </td></tr>
 <tr><td align=left valign=middle colspan=3>
<form method=post action="//fishcart/showcart.php?cartid=<?php echo $fc_cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&product=<?php echo $fc_sku ?>&cat=<?php echo $cat?>">
<?php // show the product options; see showcart for a detailed description

 $poptqty=0;
 $poptgrp=0;	// nmb
 $poptflag1=0;	// nmb
 $poptogrp=-1;		// -1 is initial value
 $poptgrpcnt=0; 	// # of options per group
 $poptgrplst='';	// : separated list of all represented groups
 
 $fco->query("select poptid,poptname,poptsdescr,poptsetup,poptprice,poptgrp,poptskumod,".
 	"poptflag1 from prodopt where poptsku='$fc_sku' order by poptgrp,poptseq");
 if( $fco->next_record() ){
 $i=0;
 do{

  $poptid =(int)$fco->f("poptid");
  $poptgrp=(int)$fco->f("poptgrp");
  $poptflag1=(int)$fco->f("poptflag1");
  $poptsetup=(double)$fco->f("poptsetup");
  $poptprice=(double)$fco->f("poptprice");
  $poptname=stripslashes($fco->f("poptname"));
  $poptsdescr=stripslashes($fco->f("poptsdescr"));

  if( $poptogrp != -1 && $poptogrp != $poptgrp ){	// group rollover check
	echo "</select>";
    if( $poptflag1 & $flag_poptgrpqty ){	// qty is required
     echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("qty").
      '<input name="'.$fc_sku.'_'.$poptogrp.'_qty" size=3>'."\n";
    }
    if( $poptoflg & $flag_poptgrpreq ){	// option group is required
      echo '<input type=hidden name="'.$fc_sku.'_'.$poptogrp.'_req" value=1>'."\n";
    }else{
      echo '<input type=hidden name="'.$fc_sku.'_'.$poptogrp.'_req" value=0>'."\n";
	}
	echo "<br>\n<select name=\"${fc_sku}_${poptgrp}_popt[]\">\n";

	if( $poptogrp >= 0 ){
      $poptgrplst .= "$poptogrp:";
	}
    $poptgrpcnt=0;		// zero the counter
  }elseif( !$i ){
	// nmb
	echo "<select name=\"${fc_sku}_${poptgrp}_popt[]\">\n";
  }

  if( $poptflag1 & $flag_poptgrpexc ){
   $popttype = 'radio';
  }else{
   $popttype = 'checkbox';
  }

  // compose composite sku
  $csku='';
  if( $poptflag1 & $flag_poptskupre ){
    $csku=$fco->f("poptskumod") . $csku;
  }elseif( $poptflag1 & $flag_poptskusuf ){
    $csku=$csku . $fco->f("poptskumod");
  }elseif( $poptflag1 & $flag_poptskumod ){
    $csku=ereg_replace($fco->f("poptskusub"),$fco->f("poptskumod"),$csku);
  }elseif( $poptflag1 & $flag_poptskusub ){
    $csku=$fco->f("poptskumod");
  }             

  /* nmb
  echo '<input type='.$popttype.' name="'.$fc_sku.'_'.$poptgrp.
  		'_popt[]" value='.$poptid.'>'.
		$poptname . $poptsdescr .'&nbsp;'.fc_text('reqflag')."<br>\n";
   nmb */
  // nmb
  echo "<option value=\"${poptid}\"> $poptname\n";

  if( $poptsetup ){
   echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("setup").
		sprintf("%s%.2f\n",$fc_csym,$poptsetup);
		// nmb sprintf("%s%.2f<br>\n",$fc_csym,$poptsetup);
  }
  
  echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("price");
  // nmb added if/else below
  if( ($poptflag1 & $flag_poptprcrel) && $poptprice ){
   $relflg='+';
  }else{
   $relflg='';
  }
  if( $poptprice ){
	// nmb echo sprintf("%s%.2f<br>\n",$fc_csym,$poptprice);
	echo ' '.$relflg.sprintf("%s%.2f\n",$fc_csym,$poptprice);
  }else{
	// nmb echo fc_text("nocharge")."<br>\n";
	echo ' '.$relflg.fc_text("nocharge")."\n";
  }
  
  $poptgrpcnt++;		// incr count of options per group
  $poptogrp=$poptgrp;	// keep the current group ID
  $poptoflg=$poptflag1;	// keep the current group flag set
  
  $i++;
 } while( $fco->next_record() );
 $fco->free_result();

 // nmb
 if( $i ){
  echo "</select>";
 }

 // always do this stuff for last option group rollover check
 if( $poptflag1 & $flag_poptgrpqty ){	// qty is required
   echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("qty").
    '<input name="'.$fc_sku.$poptgrp.'qty" size=3><br>'."\n";
 }
 if( $poptflag1 & $flag_poptgrpreq ){	// option group is required
 	echo '<input type=hidden name="'.$fc_sku.'_'.$poptgrp.'_req" value=1>'."\n";
 }else{
    echo '<input type=hidden name="'.$fc_sku.'_'.$poptgrp.'_req" value=0>'."\n";
 }

 if( $poptgrp >= 0 ){
   $poptgrplst .= "$poptgrp";
 }
 echo '<input type=hidden name="'.$fc_sku.'_grplst" value="'.
 		$poptgrplst.'">'."\n";
 } // if product options
 ?>
 
 
 </td></tr>
<tr><td align=left valign=middle colspan=1>
 <i><?php echo fc_text("sku"); ?> <?php echo $fc_sku; ?></i>
</td><td align=left valign=middle colspan=1>
<?php  // show the product price
$setup=(double)$fcp->f("prodsetup");
if( $setup ){
  echo sprintf("%s %s%8.2f ", fc_text("setup"),$fc_csym,$setup);
}
$prc='';
if($fcp->f("prodprice")==0){
 // free, show alternative text
 if(!empty($cat)){ $prc=$fcs->f("catfree"); }
 if( empty($prc)){ $prc=$fc_webfree; }
}else{ // not free, check for sale price
 if( $fcp->f("prodsalebeg")<$now && $now<$fcp->f("prodsaleend") ){
  // on sale
  $prc=sprintf(
   "<b>%s %s%8.2f</b>", fc_text("onsale"),$fc_csym,$fcp->f("prodsaleprice"));
 }else{
  $prc=sprintf("%s %s%8.2f",fc_text("price"),$fc_csym,$fcp->f("prodprice"));
 }
}
echo $prc;
if( $flag1 & $flag_persvc ){
 //echo ' '.fc_text('periodic');
 echo ' '.$fcp->f('prodpersvc');
}
?>

 </td><td align=right valign=middle colspan=1>

<?php
// SHOW THE ADD TO ORDER BUTTON
// with product options, it is no longer feasible to show the qty
// on order, as we don't know which options have been chosen
if( $fc_webflags1 & $flag_webshowqty ) {
  $qty="1";
}else{
  $qty="";
}
?>

<input type=text size=3 name=quantity value=<?php echo $qty?>><input type=submit value="<?php echo fc_text('shortadd'); ?>">
</td></tr>
</form>

 <tr><td align=left valign=bottom colspan=3>

 </td></tr>
 
 <?php // show related products
 $fcrp->query(
  "select relprod from prodrel where relsku='$fc_sku' order by relseq");
 while( $fcrp->next_record() ){
  $rsku=$fcrp->f('relprod');
  $fcrpl->query("select prodname from prodlang where prodlsku='$rsku' ".
  "and prodlzid=$zid and prodlid=$lid");
  $fcrpl->next_record();
  $pname = strip_tags($fcrpl->f("prodname"));
 ?>
 <tr><td align=left valign=top colspan=3>
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&psku=<?php echo $rsku ?>"><?php echo $pname ?></a><br>
 </td></tr>
 <?
 }
 $fcrp->free_result();
?>
 </table>

 </td></tr>
<?php
} // fc_product_display

// now initialize an order if one is not already open
fc_open();
include ('BITCART_PKG_PATHlanguages.php');
include ('BITCART_PKG_PATHflags.php');
?>
