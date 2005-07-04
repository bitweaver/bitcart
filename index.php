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

header("Expires: 0");
header("Pragma: no-cache");
header("Cache-control: No-Cache");

require_once( '../bit_setup_inc.php' );

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
$langchange = (int)getparam('langchange');
// ==========  end of variable loading  ==========

require('./public.php');
require('./cartid.php');
require('./languages.php');
require('./flags.php');

// required for related products
$fcrp = new FC_SQL;
$fcrpl = new FC_SQL;

$fcz = new FC_SQL;
$fcz->query("select zflag1,zonecurrsym from zone where zoneid=$zid");
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $csym='';
 $zflag1=0;
}

$fcw=new FC_SQL;
$fcl=new FC_SQL;
$fcg=new FC_SQL;
$fcc=new FC_SQL;
$fcal=new FC_SQL;
$fcn=new FC_SQL;
$fcs=new FC_SQL;
$fcv=new FC_SQL;
$fcsc=new FC_SQL;

$showscat=!empty( $showscat ) ? (int)$showscat : 0;

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	include('./pw.php');
}

// get the Web table
$fcw->query("select * from web where webzid=$zid and weblid=$lid");
$fcw->next_record();
$srt=$fcw->f("websort");
$wflag1=(int)$fcw->f('webflags1');
$dn=(int)$fcw->f("webprodpage");	// number of products per page

// get the language templates
$fcl->query(
	"select langtmpl,langshow,langstmpl,langwelcome,langcopy,".
	"langterms,langfppromo from lang where langid=$lid");
$fcl->next_record('langterms');
if($showscat){
	$tmpl=stripslashes($fcl->f("langstmpl"));
}else{
	$tmpl=stripslashes($fcl->f("langtmpl"));
}
$show=$fcl->f("langshow");
$lwelcome=$fcl->f("langwelcome");
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");
$fp_cat=(int)$fcl->f("langfppromo");
$fcl->free_result();

// if the language has changed, force new default shipping profile
if( $langchange ){
	// get the current subzone
	$fcl->query("select subz from ohead where orderid='$cartid'");
	$fcl->next_record();
	$subz=(int)$fcl->f("subz");
	$fcl->free_result();

	// get the default profile
	$fcl->query(
		"select ship.shipid from ship,subzship ".
		"where shipzid=$zid ".
		"and shipszid=$subz ".
		"and shipdef=1 ".
		"and ship.shiplid=$lid ".
		"and subzship.shiplid=$lid ".
		"and subzship.shipid=ship.shipid ".
		"and subzship.shiplid=ship.shiplid");
	if( $fcl ){
		$fcl->next_record();
		$shipid=(int)$fcl->f('shipid');
		$fcl->free_result();
	}else{
		$shipid=0;
	}
	$fcl->query("update ohead set shipid=$shipid where orderid='$cartid'");
}

// END OF ESSENTIAL CART DISPLAY CODE FROM LINE 1 TO HERE?>

<html><head>
<link rel=stylesheet ID href="style.css" type="text/css" />
<title> <?php echo fc_text("titletag"); ?></title></head>

<body<?php
if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php }
if($fcw->f("webtext")){?> text="<?php echo $fcw->f("webtext")?>"<?php }
if($fcw->f("weblink")){?> link="<?php echo $fcw->f("weblink")?>"<?php }
if($fcw->f("webvlink")){?> vlink="<?php echo $fcw->f("webvlink")?>"<?php }
if($fcw->f("webalink")){?> alink="<?php echo $fcw->f("webalink")?>"<?php }
if($fcw->f("webbg")){?> bgcolor="<?php echo $fcw->f("webbg")?>"<?php }?>
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
<table class="text" cellpadding="0" width="100%" border="0">
<!-- BODY TEXT GOES HERE -->
<tr><td align="left" colspan="3">
<p>
<?php
//echo fc_text("welcome");
echo $lwelcome;
?>
</p>
</td></tr>
<!--spacer between welcome and front page promotions-->
<tr><td align="left" valign="top" colspan="3"><img src="clearpixel.gif" width="1" height="15" /></td></tr>
<?php
$now = time();
$fcp = new FC_SQL;
$fco = new FC_SQL;


// set the product start/stop date search parameters
if( $zflag1 & $flag_zoneproddate ){
	$pj="((prodstart=0 or (prodstart <> 0 and $now > prodstart)) and ".
		" (prodstop =0 or (prodstop  <> 0 and $now < prodstop ))) and ";
}else{
	$pj='';
}

// look for Front Page Promotion products

if( $fp_cat ){
	$pj.="catval=$fp_cat and catval=pcatval and pcatsku=prodsku and ".
	"pcatsku=prodlsku and catlid=$lid and prodlid=$lid and prodzid=$zid ".
	"and prodlzid=$zid";

// same for all queries
$fds="prodname,proddescr,prodpic,prodpicw,prodpich,prodtpic,prodtpicw,prodtpich,".
 "prodsku,prodprice,prodrtlprice,prodinvqty,prodaudio,prodvideo,prodsalebeg,prodsaleend,".
 "prodsplash,prodsaleprice,prodstsalebeg,prodstsaleend,prodstsaleprice,".
 "produseinvq,prodseq,prodoffer,prodsdescr,proddload,prodsetup,prodflag1,prodlflag1";

$tbs="cat,prodcat,prod,prodlang";
$fcp->query("select count(*) as cnt from $tbs where $pj");
$fcp->next_record();
$total=(int)$fcp->f("cnt");
$fcp->free_result();
$count=$total;
//echo "<b>count: $count</b><br>\n";

$fcp->query("select distinct $fds from $tbs where $pj order by $srt");

// main product display table; only show if there are products

// display the products
$j=0;
while( $fcp->next_record() ){
 $flag1=(int)$fcp->f('prodflag1');
 $prodlflag1=(int)$fcp->f('prodlflag1');
 $retailprice=(double)$fcp->f("prodrtlprice");
?>

<tr><td align="left" valign="top" colspan="3">

<?php
 //bvo
 if( empty( $mode ) || $mode=="mp" ){
 //if mode multiple products or if it's empty
 if($fcp->f("prodtpic")){ // show the product thumbnail picture (if defined)
 if($fcp->f("prodpic")||$fcp->f("proddescr")){
  //only build link if either prodpic or proddescr is there otherwise only show prodtpic
 ?>
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php
 echo $lid?>&psku=<?php echo ($fcp->f("prodsku"))?>&mode=sp">
<?php
}
?>
<img src="<?php echo $fcp->f("prodtpic")?>"
 width="<?php echo $fcp->f("prodtpicw")?>"
 height="<?php echo $fcp->f("prodtpich")?>"
 alt="" align="left" border="0"/>
<?php
if($fcp->f("prodpic")||$fcp->f("proddescr")){
echo '</a>';
}} // end of showing thumbnail picture
?>
  <b><?php echo stripslashes($fcp->f("prodname"))?>:</b>
<?php
if( $wflag1 & $flag_webusenlbr ){
echo stripslashes(nl2br($fcp->f("prodsdescr"))).'<br />';
//echo '<br />';
}else{
echo stripslashes($fcp->f("prodsdescr")).'<br />';
//echo '<br />';
}
if($fcp->f("prodpic") && $fcp->f("prodtpic")){echo fc_text('click2select');
}elseif(!$fcp->f("prodpic") && $fcp->f("prodtpic") && $fcp->f("proddescr")){
echo fc_text('click2select');
}elseif(!$fcp->f("prodtpic") && $fcp->f("proddescr")){
?>
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php
 echo $lid?>&psku=<?php echo ($fcp->f("prodsku"))?>&mode=sp"><?php echo fc_text("click2select2");?></a>
<?php
}elseif(!$fcp->f("prodpic") && $fcp->f("proddescr")){
?>
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php
 echo $lid?>&psku=<?php echo ($fcp->f("prodsku"))?>&mode=sp"><?php echo fc_text("click2select2");?></a>
<?php
}elseif(!$fcp->f("prodtpic") && $fcp->f("proddescr")){
?>
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php
 echo $lid?>&psku=<?php echo ($fcp->f("prodsku"))?>&mode=sp"><?php echo fc_text("click2select2");?></a>
<?php
}elseif(!$fcp->f("prodtpic") && $fcp->f("prodpic")){
?>
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php
 echo $lid?>&psku=<?php echo ($fcp->f("prodsku"))?>&mode=sp"><?php echo fc_text("click2select2");?></a>
<?php
}
} //end mode=mp or empty
if( isset( $mode ) && $mode=="sp"){ //begin showing product detail
 echo '<font size="2"><a href=javascript:history.back();>'.fc_text("back2cat").'</a></font><br /><br />';
 if(($fcp->f("prodpic")) && ($psku !='')){ // show the product picture (if defined)
 ?>
 <img src="<?php echo $fcp->f("prodpic")?>" alt="" align="left" />
 <?php  //end showing prodpic now show prodtpic if prodpic is not defined
 }elseif($fcp->f("prodtpic")){
?>
 <img src="<?php echo $fcp->f("prodtpic")?>" alt="" align="left" />
<?php } //end showing prodtpic ?>
 <b><?php echo stripslashes($fcp->f("prodname"))?>:</b>
<?php
if( $wflag1 & $flag_webusenlbr ){
echo stripslashes(nl2br($fcp->f("proddescr"))).'<br />';
}else{
echo stripslashes($fcp->f("proddescr")).'<br />';
//echo '<br />';
}

} //end showing product detail   bvo?>
 </td></tr>
 <tr><td align="left" valign="bottom" colspan="1">

 <?php if( $fcp->f("prodaudio") ){?>
 <a href="<?php echo $fcp->f("prodaudio")?>"><i><?php echo fc_text("audiosample"); ?></i></a><br />
 <?php }?>

 </td><td align="center" valign="bottom" colspan="1">

 <?php if( $fcp->f("prodvideo") ){?>
 <a href="<?php echo $fcp->f("prodvideo")?>"><i><?php echo fc_text("videosample"); ?></i></a><br />
 <?php }?>

 </td><td align="right" valign="bottom">
 </td></tr>
<tr><td align="left" valign="top" colspan="3">
<?php $prodsku=$fcp->f("prodsku"); ?>

<form method="post" action="showcart.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&product=<?php echo $fcp->f("prodsku")?>&cat=<?php echo $cat?>&olimit=<?php echo $olimit?>">


<?php // show the product options; see showcart for a detailed description

 if($prodlflag1 & $flag_hasoption){
 //if product has option defined  (bvo)
 $poptqty=0;
 $poptgrp=0;	// nmb
 $poptflag1=0;	// nmb
 $poptogrp=-1;		// -1 is initial value
 $poptgrpcnt=0; 	// # of options per group
 $poptgrplst='';	// : separated list of all represented groups

 $fco->query("select poptid,poptname,poptsdescr,poptsetup,poptprice,poptssalebeg,".
 	"poptssaleend,poptssaleprice,poptsalebeg,poptsaleend,poptsaleprice,poptgrp,".
	"poptflag1,pgrpname from prodopt,prodoptgrp ".
	"where poptsku='$prodsku' and poptzid=$zid and poptlid=$lid ".
	"and pgrpzid=$zid and pgrplid=$lid and pgrpgrp=poptgrp ".
	"order by poptgrp,poptseq");
 if( $fco->next_record() ){
 $i=0;
 do{

  $poptid =(int)$fco->f("poptid");
  $poptgrp=(int)$fco->f("poptgrp");
  $poptflag1=(int)$fco->f("poptflag1");
   if( $fco->f("poptssalebeg")<$now && $now<$fco->f("poptssaleend") ){
	$poptsetup=(double)$fco->f("poptssaleprice");
    $stpprc=sprintf(
    "<b>%s %s%8.2f</b>", fc_text("onsale"),$csym,$fcp->f("poptssaleprice"));
	}else{
	$poptsetup=(double)$fco->f("poptsetup");
	}
   if( $fco->f("poptsalebeg")<$now && $now<$fco->f("poptsaleend") ){
	$poptprice=(double)$fco->f("poptsaleprice");
	}else{
	$poptprice=(double)$fco->f("poptprice");
	}
  $poptname=stripslashes($fco->f("poptname"));
  $poptsdescr=stripslashes($fco->f("poptsdescr"));
  $pgrpname=stripslashes($fco->f("pgrpname"));
  if( empty($pgrpname) ){
    $pgrpname = fc_text('emptyopt');
  }

  if( $poptogrp != -1 && $poptogrp != $poptgrp ){	// group rollover check
	echo '</select>';
    if( $poptoflg & $flag_poptgrpqty ){	// qty is required
     echo '&nbsp;&nbsp;'.fc_text("qty").
      '<input name="'.$prodsku.'_'.$poptogrp.'_qty" size="3" />'."\n";
 	}else{
     echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_qty" value="1" />'."\n";
    }
    if( $poptoflg & $flag_poptgrpreq ){	// option group is required
      echo '<input type="hidden" name="'.$prodsku.'_'.$poptogrp.'_req" value="1" />'.fc_text('reqflag')."<br />\n";
    }else{
      echo '<input type="hidden" name="'.$prodsku.'_'.$poptogrp.'_req" value="0" /><br />'."\n";
	}
	echo "<br />\n<select name=\"${prodsku}_${poptgrp}_popt[]\">\n".
		 "<option value=\"\">$pgrpname</option>\n";

	if( $poptogrp >= 0 ){
      $poptgrplst .= "$poptogrp:";
	}
    $poptgrpcnt=0;		// zero the counter
  }elseif( !$i ){
	echo "<select name=\"${prodsku}_${poptgrp}_popt[]\">\n".
		 "<option value=\"\">$pgrpname</option>\n";
  }

  if( $poptflag1 & $flag_poptgrpexc ){
   $popttype = 'radio';
  }else{
   $popttype = 'checkbox';
  }

  // compose composite sku
  if( $poptflag1 & $flag_poptskupre ){
    $csku=stripslashes($fco->f("poptskumod")) . $csku;
  }elseif( $poptflag1 & $flag_poptskusuf ){
    $csku=$csku . stripslashes($fco->f("poptskumod"));
  }elseif( $poptflag1 & $flag_poptskumod ){
    $csku=ereg_replace(stripslashes($fco->f("poptskusub")),stripslashes($fco->f("poptskumod")),$csku);
  }elseif( $poptflag1 & $flag_poptskusub ){
    $csku=stripslashes($fco->f("poptskumod"));
  }

  echo "<option value=\"${poptid}\"> $poptname $poptsdescr\n";

  if( $poptsetup ){
   echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("setup").
		sprintf("%s%.2f\n",$csym,$poptsetup);
  }

  echo '&nbsp;&nbsp;&nbsp;&nbsp;'.fc_text("price");
  if( ($poptflag1 & $flag_poptprcrel) && $poptprice ){
   $relflg='+';
  }else{
   $relflg='';
  }
  if( $poptprice ){
	echo ' '.$relflg.sprintf("%s%.2f\n",$csym,$poptprice);
  }else{
	echo ' '.$relflg.fc_text("nocharge")."\n";
  }
  echo "</option>\n";

  $poptgrpcnt++;		// incr count of options per group
  $poptogrp=$poptgrp;	// keep the current group ID
  $poptoflg=$poptflag1;	// keep the current group flag set

  $i++;
 } while( $fco->next_record() );
 $fco->free_result();

 // nmb
 if( $i ){
  echo '</select>';
 }

 // always do this stuff for last option group rollover check
 if( $poptflag1 & $flag_poptgrpqty ){	// qty is required
   echo '&nbsp;&nbsp;'.fc_text("qty").
    '<input name="'.$prodsku.'_'.$poptgrp.'_qty" size="3" />'."\n";
 }else{
   echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_qty" value="1" />'."\n";
 }
 if( $poptflag1 & $flag_poptgrpreq ){	// option group is required
 	echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_req" value="1" />'.fc_text('reqflag')."<br />\n";
 }else{
    echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_req" value="0" /><br />'."\n";
 }

 if( $poptgrp >= 0 ){
   $poptgrplst .= "$poptgrp";
 }
 echo '<input type="hidden" name="'.$prodsku.'_grplst" value="'.
 		$poptgrplst.'" />'."\n";
 } // if product options
} // end if product has option defined  (bvo)
 ?>

</td></tr>
<tr><td align="left" valign="bottom" colspan="1">
<i><?php echo fc_text("sku"); ?> <?php $prodsku=$fcp->f("prodsku"); echo $prodsku; ?></i>
</td><td align="left" valign="bottom" colspan="1">

<?php  // show the product price
   if( $fcp->f("prodstsalebeg")<$now && $now<$fcp->f("prodstsaleend") ){
	// on sale
	$stprc=sprintf(
	"<b>%s</b> %s %s%8.2f", fc_text("onsale"), fc_text("setup"),$csym,$fcp->f("prodstsaleprice"));
	$setup=(double)$fcp->f("prodstsaleprice");
	}else{
	$stprc=sprintf(
	"%s %s%8.2f", fc_text("setup"),$csym,$fcp->f("prodsetup"));
	$setup=(double)$fcp->f("prodsetup");
	}
	  $prc='';
	  if($fcp->f("prodprice")==0){
    // free, show alternative text
    if(!empty($cat)){ $prc=stripslashes($fcs->f("catfree")); }
	 if( empty($prc)){ $prc=stripslashes($fcw->f("webfree")); }
  }else{ // not free, check for sale price
   if( $fcp->f("prodsalebeg")<$now && $now<$fcp->f("prodsaleend") ){
    // on sale
    $prc=sprintf(
    "<b>%s %s%8.2f</b>", fc_text("onsale"),$csym,$fcp->f("prodsaleprice"));
	$finalprc=$fcp->f("prodsaleprice");
   }else{
    $prc=sprintf("<b>%s %s%8.2f</b>",fc_text("price"),$csym,$fcp->f("prodprice"));
	$finalprc=$fcp->f("prodprice");
   }
  }
  if( $flag1 & $flag_persvc ){
    echo ' <i>'.fc_text('periodic').'</i>';
  }
?>

</td><td align="right" valign="bottom" colspan="1">
<?php

// SHOW THE ADD TO ORDER BUTTON
// with product options, it is no longer feasible to show the qty
// on order, as we don't know which options have been chosen
if( $fcw->f("webflags1") & $flag_webshowqty ) {
  $qty="1";
}else{
  $qty="";
}

	if( $fcp->f('produseinvq') == 0 ||
		  $fcp->f('produseinvq') && $fcp->f('prodinvqty') > 0
		){
?>
<input type="text" size="3" name="quantity" value="<?php echo $qty?>" />
<input type="submit" value="<?php echo fc_text('shortadd'); ?>" />
<?php }else{ ?>
Temporarily out of stock
<?php } ?>

</td></tr>
<tr><td align="left">
<?php
	if( $setup ){
	  echo $stprc;
	  }
?>
</td><td align="center">
<?php
if ($retailprice > $finalprc){
$retailprc=sprintf("%s %s%8.2f",fc_text("retailprice"),$csym,$retailprice);
echo "<i>$retailprc</i>";
}
?>
</td><td align="right">
<?php echo $prc; ?>
</td></tr>
</form>
<?php // show related products
//start flag_hasrel (bvo)
  if($flag1 & $flag_hasrel){

 $fcrp->query(
  "select relprod from prodrel where relzone=$zid and relsku='$prodsku' ".
  "order by relseq");
 while( $fcrp->next_record() ){
  $rsku=$fcrp->f('relprod');
  $fcrpl->query("select prodname from prodlang where prodlsku='$rsku' ".
  "and prodlzid=$zid and prodlid=$lid");
  $fcrpl->next_record();
  $pname = strip_tags($fcrpl->f("prodname"));
 ?>
 <tr><td align="left" valign="top" colspan="3">
 <a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&psku=<?php echo $rsku ?>"><?php echo $pname ?></a><br />
</td></tr>
 <?php
 }
 $fcrp->free_result();
} // end flag_hasrel (bvo)

 ?>
<tr><td align="left" valign="top" colspan="3">
<hr />
</td></tr>
<?php
} // end of product display loop
} // end of fp_cat check
?>
</table>

<?php
// if count
?>

</td></tr></table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>

<?php // VENDOR INFORMATION
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( BITCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
