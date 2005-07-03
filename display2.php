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

header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-control: No-Cache");

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
$psku = getparam('psku');
$zid = (int)getparam('zid');
$lid = (int)getparam('lid');
$cat = (int)getparam('cat');
$nlst = (int)getparam('nlst');
$olst = (int)getparam('olst');
$olimit = (int)getparam('olimit');
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

$webid = (int) $webid;
$scat = (int) $scat;

$fcz = new FC_SQL;
$fcz->query("select zonecurrsym,usescat,zflag1 from zone ".
			"where zoneid=$zid"); 
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $csym=trim($csym);
 $zscat=(int)$fcz->f("usescat");
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $csym="";
 $zscat=0;
 $zflag1=0;
}

$dn_line = 20;	// number of search result pages to show per line
$now=time();

$fca = new FC_SQL;
$fck = new FC_SQL;
$fcl = new FC_SQL;
$fco = new FC_SQL;
$fcp = new FC_SQL;
$fcs = new FC_SQL;
$fcv = new FC_SQL;
$fcw = new FC_SQL;
$fcrp = new FC_SQL;
$fcrpl = new FC_SQL;

function showerr() {
	global $zid,$lid,$cartid,$fcw;
	$mln=256;
?>
<html>
<head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title>Empty Search</title>
</head>
<body<?php
 if($fcw->f("webtext")){?> text="#<?php echo stripslashes($fcw->f("webtext"))?>"<?php }
 if($fcw->f("weblink")){?> link="#<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> vlink="#<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> alink="#<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="#<?php echo $fcw->f("webbg")?>"<?php }
 if($fcw->f("webback")) {?> background="<?php echo $fcw->f("webback")?>"<?php }
?>
marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">

<table border="0" cellpadding="0" width="500">
<tr><td>
<?php echo fc_text('emptysearch'); ?>
<p>
<a href="index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text("back2select"); ?></a>
</p>
</td></tr>
</table>
<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
<?php 
}

$cat=(int)$cat; // force to a number
$scat=(int)$scat;
$olimit=(int)$olimit;

// get the Web table
//$fcw->query("select * from web where webzid=$zid and weblid=$lid"); 
if ($aid > 0) {
  $fca->query("select ascwebid from associate where ascid='$aid'");
  $fca->next_record();
  $webid = (int)$fca->f("ascwebid");
if ($webid > 0) {
    $fcw->query("select * from web where webid=$webid AND webzid = $zid and weblid=$lid");
  } else {  //aid set, but no webid for some reason
    $fcw->query("select * from web where webzid=$zid and weblid=$lid");
  }
} else {
  $fcw->query("select * from web where webzid=$zid and weblid=$lid");
} 

$fcw->next_record();
$srt=$fcw->f("websort");
$dn=(int)$fcw->f("webprodpage");	// number of products per page

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	include('./pw.php');
}

// get the language templates
$fcl->Auto_free=1;
$fcl->query("select langtdsp,langshow,langterr from lang ".
	"where langid=$lid");
$fcl->next_record();
$show=$fcl->f("langshow");

// if they didn't tell us to do anything
if(empty($cat) && empty($key1) && empty($psku) && empty($nlst) && empty($olst)){
 showerr();
}else{




// log the access
if ($zflag1 & $flag_zonelogaccess){
$fcl->Auto_commit = 1;
$fcl->query("select accesscnt from acc where accessip='$REMOTE_ADDR'");
if($fcl->next_record()==0){
 $fcl->query("insert into acc (accesshost,accessip,accesstime,accesscnt)".
  " values ('$REMOTE_HOST','$REMOTE_ADDR',$now,1)");
}else{
 $tmp=(int)$fcl->f("accesscnt") + 1;
 $fcl->query("update acc set accesscnt=$tmp,accesstime=$now ".
  "where accessip='$REMOTE_ADDR'");
}
}
//end logging the access

// set the product start/stop date search parameters
if( $zflag1 & $flag_zoneproddate ){
	$pj="((prodstart=0 or (prodstart <> 0 and $now > prodstart)) and ".
		" (prodstop =0 or (prodstop  <> 0 and $now < prodstop ))) and ";
}else{
	$pj='';
}

// display the new product list
if(!empty($nlst)){
 $cat=""; $key1="";
 $tbs="nprod,prod,prodlang";
 $pj.="nzid=$zid and nprodsku=prodsku and prodzid=$zid and ".
     "nprodsku=prodlsku and prodlzid=$zid and prodlid=$lid";
}

// display the closeout product list
if(!empty($olst)){
 $cat=""; $key1="";
 $tbs="oprod,prod,prodlang";
 $pj.="ozid=$zid and oprodsku=prodsku and prodzid=$zid and ".
     "oprodsku=prodlsku and prodlzid=$zid and prodlid=$lid";
}

// category and/or search terms 
if(!empty($cat)){
	$cf="catdescr,catbg,catback,cattext,catlink,catvlink,".
    "catalink,catlogo,catlogoh,catlogow,catfree,catbanr,".
    "catbanrh,catbanrw,catsku,catmast,cattmpl,catsort,catprodpage";
	if($zscat && $scat){
		// if subcats enabled and a subcat given
		$fcs->query("select usescat from cat ".
			"where catval=$cat and catzid=$zid and catlid=$lid");
		$fcs->next_record();
		$cscat=(int)$fcs->f("usescat");
		$fcs->free_result();
		if($cscat){
		 $sf="scatdescr,scatbg,scatback,scattext,scatlink,scatvlink,".
    	 "scatalink,scatlogo,scatlogoh,scatlogow,scatfree,scatbanr,".
    	 "scatbanrh,scatbanrw,scatsku,scatmast,scattmpl,scatsort";
		 $fcs->query("select $sf from subcat where scatcat=$cat and ".
		 "scatzid=$zid and scatlid=$lid and scatval=$scat");
		  $fcs->next_record();
		  $srt=$fcs->f("scatsort");
        }else{
          $fcs->query("select $cf from cat where ".
          "catval=$cat and catzid=$zid and catlid=$lid");
		  $fcs->next_record();
		  $srt=$fcs->f("catsort");
		  $dn=(int)$fcs->f("catprodpage");
        }
	}else{
        $fcs->query("select $cf from cat where ".
        "catval=$cat and catzid=$zid and catlid=$lid");
		$fcs->next_record();
		$srt=$fcs->f("catsort");
		$dn=(int)$fcs->f("catprodpage");
	}

	if(!empty($psku)){
	 // single product display
	 if($scat){
	  $pj.="pcatval=$cat and pscatval=$scat and pcatzid=$zid and ".
	 	"pcatzid=prodzid and prodzid=prodlzid and prodlid=$lid and ".
		"pcatsku='$psku' and prodsku='$psku' and prodlsku='$psku' and ".
		"catzid=$zid and catval=$cat and catact=1 and ".
		"(produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
	 }else{
	  $pj.="pcatval=$cat and pcatzid=$zid and pcatzid=prodzid and ".
	    "prodzid=prodlzid and prodlid=$lid and pcatsku='$psku' and ".
		"prodsku='$psku' and prodlsku='$psku' and ".
		"catzid=$zid and catval=$cat and catact=1 and ".
		"(produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
	 }
	}elseif($scat){ // join with subcategory
	 $pj.="pcatval=$cat and pcatzid=$zid and pcatzid=prodzid and prodzid=".
	 	"prodlzid and prodlid=$lid and pcatsku=prodsku and ".
	 	"prodzid=$zid and prodlzid=$zid and prodsku=prodlsku and ".
		"pscatval=$scat and catzid=$zid and catval=$cat and catact=1 and ".
		"(produseinvq=0 or (produseinvq=1 and prodinvqty>0)) ";
	}else{ // from a category selection, no subcategory
	 $pj.="pcatval=$cat and pcatzid=$zid and pcatzid=prodzid and ".
	 	"prodzid=prodlzid and prodzid=$zid and prodlzid=$zid and ".
		"prodlid=$lid and pcatsku=prodsku and prodsku=prodlsku and ".
		"catzid=$zid and catval=$cat and catact=1 and ".
		"(produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
	}
	$tbs="prod,prodlang,prodcat,cat";
	if(!empty($key1)){
         if( $databaseeng=="'postgres'" || $databaseeng=="mssql" ) {
	   $pj.=" and (lower(proddescr) like lower('%$key1%')".
			" or lower(prodsdescr) like lower('%$key1%')".
			" or lower(prodsku) like lower('%$key1%')".
			" or lower(prodkeywords) like lower('%$key1%'))";
         } elseif($databaseeng=="oracle") {
	   $pj.=" and (upper(proddescr) like upper('%$key1%')".
			" or upper(prodsdescr) like upper('%$key1%')".
			" or upper(prodsku) like upper('%$key1%')".
			" or upper(prodkeywords) like upper('%$key1%'))";
         } else {
	   $pj.=" and (lcase(proddescr) like lcase('%$key1%')".
			" or lcase(prodsdescr) like lcase('%$key1%')".
			" or lcase(prodsku) like lcase('%$key1%')".
			" or lcase(prodkeywords) like lcase('%$key1%'))";
         }
	}
}else{
	if(!empty($psku)){
		// single product display, category not selected
		$pj.="prodsku='$psku' and prodlsku='$psku' and prodzid=$zid ".
			"and prodzid=prodlzid and prodlid=$lid";
		$tbs="prod,prodlang";
	}elseif(!empty($key1)){
		// keyword search, no categories selected but
		// get only products in active categories and
		// sufficient inventory
         if( $databaseeng=="'postgres'" || $databaseeng=="mssql" ) {
		  $pj.="(lower(proddescr) like lower('%$key1%')".
		    " or lower(prodkeywords) like lower('%$key1%')".
	        " or lower(prodsdescr) like lower('%$key1%')".
	        " or lower(prodsku) like lower('%$key1%'))".
			" and prodsku=prodlsku and prodzid=$zid".
			" and prodlzid=$zid and prodlid=$lid".
			" and (produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
		  $tbs="prod,prodlang";
         } elseif($databaseeng=="oracle") {
		  $pj.="(upper(proddescr) like upper('%$key1%')".
		    " or upper(prodkeywords) like upper('%$key1%')".
	        " or upper(prodsdescr) like upper('%$key1%')".
	        " or upper(prodsku) like upper('%$key1%'))".
			" and prodsku=prodlsku and prodzid=$zid".
			" and prodlzid=$zid and prodlid=$lid".
			" and (produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
		  $tbs="prod,prodlang";
         } else {
		  $pj.="(lcase(proddescr) like lcase('%$key1%')".
		    " or lcase(prodkeywords) like lcase('%$key1%')".
	        " or lcase(prodsdescr) like lcase('%$key1%')".
	        " or lcase(prodsku) like lcase('%$key1%'))".
			" and prodsku=prodlsku and prodzid=$zid".
			" and prodlzid=$zid and prodlid=$lid".
			" and (produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
		  $tbs="prod,prodlang";
         }

	}
}

// same for all queries
$fds="proddescr,prodpic,prodpicw,prodpich,prodtpic,prodtpicw,prodtpich,".
 "prodsku,prodprice,prodinvqty,prodaudio,prodvideo,prodsalebeg,prodsaleend,".
 "prodsplash,prodsaleprice,produseinvq,prodseq,prodoffer,prodsdescr,proddload,".
 "prodsetup,prodpersvc,prodflag1";

$pj.=" and (produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
// echo "select count(*) as cnt from $tbs where $pj<br />";
$fcp->query("select count(*) as cnt from $tbs where $pj");
$fcp->next_record();
$total=(int)$fcp->f("cnt");
$fcp->free_result();
$count=$total;	// this because the "got" count below is commented out

if( !empty($cat) ){
 // check to see whether subcats exist under this cat
 $check_sub = new FC_SQL;
 $check_sub->query("select count(*) as cnt from cat where catunder=$cat");
 $check_sub->next_record();
 $check_subc=(int)$check_sub->f("cnt");
 $check_sub->free_result();
}

// show error if no products and no subcats
if( empty($count) and empty($check_subc) ){
 showerr();
}else{
// echo "select $fds from $tbs where $pj order by $srt<br />";
$fcp->query("select distinct $fds from $tbs where $pj order by $srt");
// THIS IS ONLY NEEDED DUE TO SOLID'S POOR LIMIT ABILITY...
// if olimit>0, read off the first olimit rows
if(!$psku && $olimit){
 $i=0;
 while($i<$olimit){
  $fcp->next_record();
  $i++;
 }
}

if(!empty($key1)){
 // log keyword search results
 $key1=strtolower($key1);
 $fck->Auto_free=1;
 $fck->query("select keycnt,keyres from keyword where keyval='$key1'");
 if( !$fck->next_record ){
  $fck->query(
   "insert into keyword (keyval,keycnt,keyres) values ('$key1',1,$total)");
 }else{
  $tmp=(int)$fck->f("keycnt") + 1;
  $kres=(int)$fck->f("keyres") + $count;
  $fck->query(
   "update keyword set keycnt=$tmp,keyres=$kres where keyval='$key1'");
 }
 $fck->commit();
}
// END OF ESSENTIAL CART DISPLAY CODE?>

<html><head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title><?php
if($cat && !$cscat){
 echo stripslashes($fcs->f("catdescr"));
}elseif($cat && $cscat){
 echo stripslashes($fcs->f("scatdescr"));
}
?></title></head>
<body<?php 
if( !empty($cat) && empty($cscat) ){
 // if a category given, no subcat
 if($fcs->f("cattext")){?> text="#<?php echo stripslashes($fcs->f("cattext"))?>"<?php }
 if($fcs->f("catlink")){?> link="#<?php echo $fcs->f("catlink")?>"<?php }
 if($fcs->f("catvlink")){?> vlink="#<?php echo $fcs->f("catvlink")?>"<?php }
 if($fcs->f("catalink")){?> alink="#<?php echo $fcs->f("catalink")?>"<?php }
 if($fcs->f("catbg")){?> bgcolor="#<?php echo $fcs->f("catbg")?>"<?php }
 if($fcs->f("catback")){?> background="<?php echo $fcs->f("catback")?>"<?php }
}elseif( !empty($cat) && !empty($cscat) ){
 // if subcategory values override
 if($fcs->f("scattext")){?> text="#<?php echo stripslashes($fcs->f("scattext"))?>"<?php }
 if($fcs->f("scatlink")){?> link="#<?php echo $fcs->f("scatlink")?>"<?php }
 if($fcs->f("scatvlink")){?> vlink="#<?php echo $fcs->f("scatvlink")?>"<?php }
 if($fcs->f("scatalink")){?> alink="#<?php echo $fcs->f("scatalink")?>"<?php }
 if($fcs->f("scatbg")){?> bgcolor="#<?php echo $fcs->f("scatbg")?>"<?php }
 if($fcs->f("scatback")){?> background="<?php echo $fcs->f("scatback")?>"<?php }
}else{
 if($fcw->f("webtext")){?> text="#<?php echo stripslashes($fcw->f("webtext"))?>"<?php }
 if($fcw->f("weblink")){?> link="#<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> vlink="#<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> alink="#<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="#<?php echo $fcw->f("webbg")?>"<?php }
 if($fcw->f("webback")) {?> background="<?php echo $fcw->f("webback")?>"<?php }
}
?>>

<?php // START OF ESSENTIAL CART DISPLAY CODE

// display the masthead graphic
if($cat && !$cscat && $fcs->f("catlogo")){?>


<table cellpadding="0" width="600" border="0">
<tr><td align="center">
<img src="<?php echo $fcs->f("catlogo")?>" width="<?php echo $fcs->f("catlogow")?>" height="<?php echo $fcs->f("catlogoh")?>" border="0" alt="" />
</td></tr>
</table>

<?php }elseif($cat && $cscat && $fcs->f("scatlogo")){?>

<table cellpadding="0" width="600" border="0">
<tr><td align="center">
<img src="<?php echo $fcs->f("scatlogo")?>" width="<?php echo $fcs->f("scatlogow")?>" height="<?php echo $fcs->f("scatlogoh")?>" border="0" alt="" />
</td></tr>
</table>

<?php }elseif($fcw->f("weblogo")){?>

<table cellpadding="0" width="600" border="0">
<tr><td align="center">
<img src="<?php echo $fcw->f("weblogo")?>" width="<?php echo $fcw->f("weblogow")?>" height="<?php echo $fcw->f("weblogoh")?>" border="0" alt="" />
</td></tr>
</table>

<?php }?>

<?php // CATEGORY MASTHEAD TEXT (IF ANY) ?>

<?php
$catmast=stripslashes($fcs->f("catmast"));
$scatmast=stripslashes($fcs->f("scatmast"));

if($cat && !$cscat && $catmast != ''){?>
<table cellpadding="0" width="360" border="0">
<tr><td valign="top">

<?php echo stripslashes($fcs->f("catmast"));?>

</td></tr>
</table>
<?php }elseif($cat && $cscat && $scatmast != ''){?>
<table cellpadding="0" width="360" border="0">
<tr><td valign="top">

<?php echo stripslashes($fcs->f("scatmast"));?>

</td></tr>
</table>
<?php }?>

<?php // TOP OF PAGE PROMO ITEM (IF ONE) ?>

<?php
if($cat && !$cscat && $fcs->f("catsku") || 
   $cat &&  $cscat && $fcs->f("scatsku") ){
   // if a category and SKU given ?>
<table cellpadding="0" width="600" border="0">
<tr><td align="center" colspan="1">

<?php  // use the category level banner unless empty, else product banner
 if($cat && !$cscat && $fcs->f("catsku") ){
  $hsku=$fcs->f("catsku");
  $img=$fcs->f("catbanr");
  $wid=$fcs->f("catbanrw");
  $hgt=$fcs->f("catbanrh");
 }elseif($cat && $cscat && $fcs->f("scatsku") ){
  $hsku=$fcs->f("scatsku");
  $img=$fcs->f("scatbanr");
  $wid=$fcs->f("scatbanrw");
  $hgt=$fcs->f("scatbanrh");
 }
 if(!$img){
  $fca->query("select * from prodlang ".
   "where prodlsku='".$hsku."' and prodlzid=$zid"); 
  $fca->next_record();
  $img=$fca->f("prodbanr");
  $wid=$fca->f("prodbanrw");
  $hgt=$fca->f("prodbanrh");
  $fca->free_result();
 }
?>
<a href="display.php?&cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=0&cat=&key1=&psku=<?php echo $hsku?>"><img src="<?php echo $img?>" width="<?php echo $wid?>" height="<?php echo $hgt?>" border="0" /></a>
<br />
<?php if( $fcp->f('produseinvq') == 0 || 
		  $fcp->f('produseinvq') && $fcp->f('prodinvqty') > 0
		){
?>

<form method="post" action="showcart.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&product=<?php echo $hsku?>&olimit=<?php echo $olimit?>&cat=<?php echo $cat?>&fname=<?php echo urlencode($REQUEST_URI)?>">
<b><?php echo fc_text("quantity"); ?></b>< /input type="text" size="5" name="quantity" value="1" />
<input type="submit" value="<?php echo fc_text("longadd"); ?>" />
</form>

<?php }else{ ?>
Temporarily out of stock
<?php } ?>

</td></tr>
</table>
<?php } ?>

<?php
if( !empty($cat) ){

 // shows the path to the current category
 $showpath = new FC_SQL;

 $showpath->query("select catpath from cat where catpath like '%:$cat:'");
 print "<a href=\"index.php?zid=$zid&lid=$lid&cartid=$cartid\">".
	fc_text("home")."</a> ";		
 $showpath->next_record();
 $paths=$showpath->f("catpath");
 $patharray = explode(":",$paths);
 // retrieve and spit out the cat path to the current cat
 $showpath->free_result();
 while (list($key, $val)=each($patharray)) {
// echo "<br>key: $key val: $val<br>";
  if ($val != ""){
   $sublevel = new FC_SQL;
   $sublevel->query(
   'select catval,catdescr from cat '.
   "where catval=$val order by catval asc");
   $sublevel->next_record();
   $subcatl = (int)$sublevel->f("catval");
   $subcatdesc = stripslashes($sublevel->f("catdescr"));
   $sublevel->free_result();

   if( $subcatl != $cat ){
    // don't make the last cat in the string a hyperlink
    print ":<a href=\"display.php?cat=$subcatl&zid=$zid&lid=$lid&cartid=$cartid\"> $subcatdesc</a> ";	
   }else{
    print ": $subcatdesc</a> ";
   }
  }
 }

/* commented out because it might not be helpful for everyone
// bjh start of new quick product finder
print"<br />";
?>
<table align="center" border="0" cellspacing="0" cellpadding="5" bgcolor="#CCCCCC">
<tr bgcolor="#000000">
<td align="left">
<font face="Arial,Helvetica" color="#FFFFFF" size="2"><b>
Quick Product Finder:
<?php if($cat && !$cscat && $catmast != ''){?>
<?php echo stripslashes($fcs->f("catmast"));?>
<?php }elseif($cat && $cscat && $scatmast != ''){?>
<?php echo stripslashes($fcs->f("scatmast"));?>
<?php }?>
</b></font>
<tr>
 <td align="center" height="20">
  <form name="subcatprods" method="post" action="display.php">
   <select name="psku" size="1" onChange="submit(); return false;">
   <option value="">Choose a Product</option>
  <?php // decide whether displaying a dropdown of current cat or under cats
  $getprods = new FC_SQL;
  $countcats = new FC_SQL;
  $countcats->query("select count(*) as cnt from cat where catunder=$cat");
  $countcats->next_record();
  $catcount=(int)$countcats->f('cnt');
  $countcats->free_result();
  if ($catcount!=0){

  $getprods->query("select prodlang.prodsdescr,".
  "prod.prodprice,prodlsku from cat,".
  "prod,prodlang,prodcat where ".
  "catunder=$cat and catval=pcatval and prodlsku=pcatsku and prodsku=prodlsku order by prodsdescr asc");

  }else{

  $getprods->query(
  "select prodsdescr,prodprice,prodlsku ".
  "from cat,prod,prodlang,prodcat ".
  "where ".
  "catval=$cat and ".
  "pcatval=$cat and ".
  "catval=pcatval and ".
  "prodlsku=pcatsku and ".
  "prodsku=pcatsku and ".
  "prodsku=prodlsku order by prodsdescr asc");

  }
  while ($getprods->next_record()){
echo "<option value=\"".$getprods->f("prodlsku") ."\">".stripslashes($getprods->f("prodsdescr")).":&nbsp;&nbsp;&nbsp;$".$getprods->f("prodprice")."</option>\n";
  }
  $getprods->free_result();
  ?>
   </select>
   <input type="submit" value="GO!" />
  </form>
 </td></tr>
</table>

<?php
end of commented out quick product finder bjh*/

 // end path to current category
 // get subcat level under $cat and spit them out in a table
 // how many cats across the page?
 $subcats1 = new FC_SQL;
 $subcats1->query('select count(*) as cnt from cat '.
 	"where catzid=$zid and catlid=$lid and catunder=$cat");
 // are there any subcats to display? If not, don't do anything more.
 $subcats1->next_record();
 $subcattot=(int)$subcats1->f("cnt");
 $subcats1->free_result();
}else{
 $subcattot = 0;
}

if ($subcattot) {
 // change this to increase or descrease the number of cats 
 // displayed across the page
 $cats_across="1";
 $subcats = new FC_SQL;
 $subcats->query("select catval,catdescr,catmast,catlogo from cat ".
	"where catact=1 and catzid=$zid and catlid=$lid and catunder=$cat order by catdescr");
 $across=0;  
?>
<br /><br />
<table border="0" width="500" cellpadding="5">
<tr><td valign="top" colspan="<?php echo $cats_across; ?>">
<b><?php echo fc_text("subcats"); ?></b>
</td></tr>
<?php
 while( $subcats->next_record() ){
  $across++;
  // starting printing cat results
  $catvl = $subcats->f("catval");
  $catlogo = $subcats->f("catlogo");
  $subdescr = stripslashes($subcats->f("catmast"));
  print "<tr><td><a href=\"display.php?cat=$catvl&zid=$zid&lid=$lid&cartid=$cartid\">";
  if($catlogo !=''){
  print "<img src=\"$catlogo\" alt=\"$subdescr\" border=\"1\">";
  }
  print "<br>$subdescr</a><br /><br /></td></tr>\n";
  // new line if across page has been reached
  if ($across==$cats_across) {
  // print "</tr>\n<tr>";
  // reset across page counter;
  $across=0;
  }
 }
 //close table
 print '</table><table width="500" border="0">';
 $subcats->free_result();
}
// end are there any subcats to display? IF not, don't do anything more
// end get subcat level under $cat and spit them out in a table
?>
 
<?php
// main product display table; only show if there are products
if($count) { ?>
<table cellpadding="0" width="500" border="0">

<?php  // show the clickable search results bar
if( empty($dn) ){ $dn=5; } // failsafe
$llmt = $olimit + 1;
$ulmt = $olimit + $dn;
if ($ulmt >= $count){
$ulmt = $count;
}

if($total>$dn){?>

<tr><td align="center" colspan="3">

<?php

 if( $olimit ){	// set the Previous button
?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $olimit - $dn ?>&cat=<?php echo $cat?>&scat=<?php echo $scat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("previous"); ?></a>&nbsp;&nbsp;&nbsp;
<?php
 }

 $tmp=""; $i=0; $j=1; $k=$total;
 while ($k>0) {
  if($i!=$olimit){?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $i?>&cat=<?php echo $cat?>&scat=<?php echo $scat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo $j?></a>
<?php 
  }else{
   echo $j.' ';
   $currj=$j;
  }
  $i+=$dn;
  $j++;
  $k-=$dn;
  if( !($j%$dn_line) ){
   echo '<br />';
  }
 }

 if( $j>1 && $j>$currj+1 ){	// set the Next button
?>
&nbsp;&nbsp;&nbsp;<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $currj * $dn ?>&cat=<?php echo $cat?>&scat=<?php echo $scat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("next"); ?></a>
<?php
 }
 
 echo "\n";
?>

</td></tr>
<?php } // end of the clickable search results bar ?>

<?php  // display the category description
if(!empty($cat)){ ?>

<?php
if( 0 && $cat && !$cscat ){?>
<tr><td align="center" colspan="1">
<b>
<?php
echo stripslashes($fcs->f("catdescr"))."<br />\n";
	//fc_text('optreqtext')."<br />\n";
?>
</b>
</td></tr>
<?php }elseif( 0 && $cat && $cscat ){?>
<tr><td align="center" colspan="1">
<b>
<?php
echo stripslashes($fcs->f("scatdescr"))."<br />\n";
	//fc_text('optreqtext')."<br />\n";
?>
</b>
</td></tr>
<?php }?>

<?php }elseif(!empty($key1)){ // DISPLAY THE SEARCH KEYWORD ?>

<tr><td align="center" colspan="1">

<b><?php echo fc_text("searchresult"); ?> <?php echo $key1?></b>

</td></tr>

<?php } // end of displaying the category header text ?>

<tr><td align="center" colspan="3">
<?php
if($count > 1){
echo "Displaying products <b>$llmt to $ulmt</b> of $count<br />";
}else{
echo "Displaying product <b>$llmt</b> of $count<br />";
} ?>
<br />
</td></tr>

<?php  // display the products
$j=0;
while( $fcp->next_record() ){
 $flag1=(int)$fcp->f('prodflag1');
?>

<tr><td align="left" valign="top" colspan="3">

 <table width="100%" cellpadding="0" cellspacing="0" border="0">
 <tr><td align="left" valign="top" colspan="3">
<br /><br />
 <?php
if ( $psku =='' ){

if ($fcp->f("prodtpic") !=''){ //show the product thumbnail (if defined)?>

 <a href="display.php?psku=<?php echo ($fcp->f("prodsku")) ?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><img src="<?php echo $fcp->f("prodtpic")?>" alt="<?php echo stripslashes($fcp->f("prodsdescr"))?>" align="left" /></a><b><?php echo stripslashes($fcp->f("prodsdescr"))?></b>
 <br><?php echo fc_text('click2select'); ?>
<?php 
 
 }else{ //no thumbnail was defined

if ($fcp->f("prodsdescr") !=''){ //show product short description if defined)?>
<a href="display.php?psku=<?php echo ($fcp->f("prodsku")) ?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><b><?php echo stripslashes($fcp->f("prodsdescr"))?></b></a>
<?php }else{ // no product short description was defined ?>
<a href="display.php?psku=<?php echo ($fcp->f("prodsku")) ?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text('click4more'); ?>
<?php } ?>
  <br><?php echo fc_text('click2prodname'); ?>
<?php
 } //end of thumbnail or no thumbnail display

}else{ //if psku is defined show the big pic display?>

<font size="2"><a href=javascript:history.back();><?php echo fc_text('back2cat'); ?></a></font><br /><br />
<?php  if(($fcp->f("prodpic")) && ($psku !='')){ // show the product picture (if defined)
 ?>
 <img src="<?php echo $fcp->f("prodpic")?>" alt="" align="left" />
 <?php }
 echo "<b>".stripslashes($fcp->f("prodsdescr"))."</b><br /><br />";
 echo stripslashes($fcp->f("proddescr"));
 echo "<br />";
 } // end
 ?>
</td></tr>
<tr><td align="left" valign="bottom" colspan="1">

 <?php if( $fcp->f("prodaudio") ){?>
  <a href="<?php echo $fcp->f("prodaudio")?>"><i><?php echo fc_text("audiosample"); ?></i></a><br />
   <?php }?> 

    </td><td align="center" valign="bottom" colspan="1">

	 <?php if( $fcp->f("prodvideo") ){?>
	  <a href="<?php echo $fcp->f("prodvideo")?>"><i><?php echo fc_text("videosample"); ?></i></a><br />
	   <?php }?>

	    </td><td align="right" valign="bottom" colspan="1">
 </td></tr>
 <tr><td align="left" valign="bottom" colspan="3">
<?php $prodsku=$fcp->f("prodsku"); ?>

<form method="post" action="showcart.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&product=<?php echo $fcp->f("prodsku")?>&cat=<?php echo $cat?>&olimit=<?php echo $olimit?>&key1=<?php echo $key1?>">
<?php // show the product options; see showcart for a detailed description

 $poptqty=0;
 $poptgrp=0;	// nmb
 $poptflag1=0;	// nmb
 $poptogrp=-1;		// -1 is initial value
 $poptgrpcnt=0; 	// # of options per group
 $poptgrplst='';	// : separated list of all represented groups

 $fco->query("select poptid,poptname,poptsdescr,poptsetup,poptprice,poptgrp,".
        "poptflag1 from prodopt where poptsku='$prodsku' and poptzid=$zid ".
        "and poptlid=$lid order by poptgrp,poptseq");
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
    if( $poptoflg & $flag_poptgrpqty ){	// qty is required
     echo '&nbsp;&nbsp;'.fc_text("qty").
      '<input name="'.$prodsku.'_'.$poptogrp.'_qty" size="3" /><br />'."\n";
    }
    if( $poptoflg & $flag_poptgrpreq ){	// option group is required
      echo '<input type="hidden" name="'.$prodsku.'_'.$poptogrp.'_req" value="1" />'."\n";
    }else{
      echo '<input type="hidden" name="'.$prodsku.'_'.$poptogrp.'_req" value="0" />'."\n";
	}
	echo "<br />\n<select name=\"${prodsku}_${poptgrp}_popt[]\">\n";

	if( $poptogrp >= 0 ){
      $poptgrplst .= "$poptogrp:";
	}
    $poptgrpcnt=0;		// zero the counter
  }elseif( !$i ){
	// nmb
	echo "<select name=\"${prodsku}_${poptgrp}_popt[]\">\n";
  }

  if( $poptflag1 & $flag_poptgrpexc ){
   $popttype = 'radio';
  }else{
   $popttype = 'checkbox';
  }

  // compose composite sku
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
  echo '<input type="'.$popttype.'" name="'.$prodsku.'_'.$poptgrp.
  		'_popt[]" value="'.$poptid.'" />'.
		$poptname . $poptsdescr .'&nbsp;'.fc_text('reqflag')."<br />\n";
   nmb */
  // nmb
  echo "<option value=\"${poptid}\"> $poptname $poptsdescr\n";?>
  </option>
 <?php

  if( $poptsetup ){
   echo '&nbsp;&nbsp;'.fc_text("setup").
		sprintf("%s%.2f\n",$csym,$poptsetup);
		// nmb sprintf("%s%.2f<br />\n",$csym,$poptsetup);
  }
  
  echo '&nbsp;&nbsp;'.fc_text("price");
  // nmb added if/else below
  if( ($poptflag1 & $flag_poptprcrel) && $poptprice ){
   $relflg='+';
  }else{
   $relflg='';
  }
  if( $poptprice ){
	// nmb echo sprintf("%s%.2f<br />\n",$csym,$poptprice);
	echo ' '.$relflg.sprintf("%s%.2f\n",$csym,$poptprice);
  }else{
	// nmb echo fc_text("nocharge")."<br />\n";
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
   echo '&nbsp;&nbsp;'.fc_text("qty").
    '<input name="'.$prodsku.$poptgrp.'qty" size="3" /><br />'."\n";
 }
 if( $poptflag1 & $flag_poptgrpreq ){	// option group is required
 	echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_req" value="1" />'."\n";
 }else{
    echo '<input type="hidden" name="'.$prodsku.'_'.$poptgrp.'_req" value="0" />'."\n";
 }

 if( $poptgrp >= 0 ){
   $poptgrplst .= "$poptgrp";
 }
 echo '<input type="hidden" name="'.$prodsku.'_grplst" value="'.
 		$poptgrplst.'" />'."\n";
 } // if product options
 ?>
 
</td></tr>
<tr bgcolor="#666666"><td align="left" colspan="1">
 <font color="#FFFFFF"><b>
 <i><?php echo fc_text("sku"); ?> <?php $prodsku=$fcp->f("prodsku"); echo $prodsku; ?></i>
</b></font>
</td><td align="left" colspan="1">
<font color="#FFFFFF"><b>
<?php  // show the product price
$setup=(double)$fcp->f("prodsetup");
if( $setup ){
  echo sprintf("%s %s%8.2f ", fc_text("setup"),$csym,$setup);
}
$prc='';
if($fcp->f("prodprice")==0){
 // free, show alternative text
 if(!empty($cat) && !$cscat){ $prc=$fcs->f("catfree"); }
 if(!empty($cat) &&  $cscat){ $prc=$fcs->f("scatfree"); }
 if( empty($prc)){ $prc=$fcw->f("webfree"); }
}else{ // not free, check for sale price
 if( $fcp->f("prodsalebeg")<$now && $now<$fcp->f("prodsaleend") ){
  // on sale
  $prc=sprintf(
   "<b>%s %s%8.2f</b>", fc_text("onsale"),$csym,$fcp->f("prodsaleprice"));
 }else{
  $prc=sprintf("%s %s%8.2f",fc_text("price"),$csym,$fcp->f("prodprice"));
 }
}
echo $prc;
if( $flag1 & $flag_persvc ){
 //echo ' '.fc_text('periodic');
 echo ' '.$fcp->f('prodpersvc');
}
?>
</b></font>
 </td><td align="right" colspan="1">
<font color="#FFFFFF"><b>
 <?php /* removed from active use since ESD was installed
 if( $fcp->f("proddload") ){ ?>

 <a href="<?php echo $fcp->f("proddload")?>"><i><?php echo fc_text("download"); ?></i></a><br />

 <?php } */ ?>
<?php 

// SHOW THE ADD TO ORDER BUTTON
// with product options, it is no longer feasible to show the qty
// on order, as we don't know which options have been chosen
if( $fcw->f("webflags1") & $flag_webshowqty ) {
  $qty="1";
}else{
  $qty="";
}
?>

<?php if( $fcp->f('produseinvq') == 0 || 
		  $fcp->f('produseinvq') && $fcp->f('prodinvqty') > 0
		){
?>
<input type="text" size="3" name="quantity" value="<?php echo $qty?>" />
<input type="submit" value="<?php echo fc_text('shortadd'); ?>" />
<?php }else{ ?>
Temporarily out of stock
<?php } ?>

</b></font>
</td></tr>
</form>
 
 <?php // show related products
 $fcrp->query(
  "select relprod from prodrel where relzone=$zid and relsku='$prodsku' order by relseq");
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
 ?>
 
 </table>

</td></tr>
<?php 
 $j++;
 if($j>=$dn){
  // check if max products per page has been reached per $dn variable above
  break;
 }
} // end of product display loop
echo "</td></tr>";
// end only show product table if some products
}

// show the clickable search results bar
if($total>$dn){?>

<tr><td align="center" colspan="3">

<?php

 if( $olimit ){	// set the Previous button
?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $olimit - $dn ?>&cat=<?php echo $cat?>&scat=<?php echo $scat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("previous"); ?></a>&nbsp;&nbsp;&nbsp;
<?php
 }

 $tmp=""; $i=0; $j=1; $k=$total;
 while ($k>0) {
  if($i!=$olimit){?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $i?>&cat=<?php echo $cat?>&scat=<?php echo $scat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo $j?></a>
<?php 
  }else{
   echo $j.' ';
   $currj=$j;
  }
  $i+=$dn;
  $j++;
  $k-=$dn;
  if( !($j%$dn_line) ){
   echo '<br />';
  }
 }

 if( $j>1 && $j>$currj+1 ){	// set the Next button
?>
&nbsp;&nbsp;&nbsp;<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $currj * $dn ?>&cat=<?php echo $cat?>&scat=<?php echo $scat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("next"); ?></a>
<?php
 }
 
 echo "\n";
?>

</td></tr>
<?php } // end of the clickable search results bar ?>
<tr><td align="center" colspan="3">
<table width="100%" height="36" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" colspan="1">

<a href="index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><img src="images/catalog_fp.gif" alt="<?php echo fc_text('zonehome'); ?>" target="_top" border="0"></a>

</td><td align="center" colspan="1">

<a href="<?php echo $show?>?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><img src="<?php echo $fcw->f("webviewlogo")?>" width="<?php echo $fcw->f("webviewlogow")?>" height="<?php echo $fcw->f("webviewlogoh")?>"
 alt="" border="0" /></a>

</td></tr>
</table>

</td></tr></table>

<?php
 }	// end of no products and no subcats
}	// end of else after the showerr() statements

// END OF ESSENTIAL CART DISPLAY CODE ?>

<?php // VENDOR INFORMATION  ?>

<table width="600" cellpadding="0" border="0">
<tr><td align="center">

<table width="80%" border="0" cellpadding="6" cellspacing="0">
<tr><td align="left" valign="top">

<b><i><?php echo fc_text('contactinfo'); ?></i></b><br />
<?php  // display the vendor contact information
$fcv->query("select * from vend where vendzid=$zid"); 
$fcv->next_record();

if($fcv->f("vendname")){ echo stripslashes($fcv->f("vendname"))?><br /><?php }
if($fcv->f("vendaddr1")){ echo stripslashes($fcv->f("vendaddr1"))?><br /><?php }
if($fcv->f("vendaddr2")){ echo stripslashes($fcv->f("vendaddr2"))?><br /><?php }
if($fcv->f("vendcity")){ echo stripslashes($fcv->f("vendcity"))?>, <?php echo stripslashes($fcv->f("vendstate"))?> <?php echo stripslashes($fcv->f("vendzip"))?>  <?php echo stripslashes($fcv->f("vendnatl"))?><br /><?php }
if($fcv->f("vendphone")){ echo stripslashes($fcv->f("vendphone"))?><br /><?php }
if($fcv->f("vendfax")){ echo stripslashes($fcv->f("vendfax"))?><br /><?php }
if($fcv->f("vendemail")){?><a href="mailto:<?php echo stripslashes($fcv->f("vendemail"))?>"><?php echo stripslashes($fcv->f("vendemail"))?></a><br /><?php }?>

</td><td align="left" valign="top">

<b><i><?php echo fc_text('supportinfo'); ?></i></b><br />
<?php  // display the vendor service information
if($fcv->f("vsvcname")){ echo stripslashes($fcv->f("vsvcname"))?><br /><?php }
if($fcv->f("vsvcaddr1")){ echo stripslashes($fcv->f("vsvcaddr1"))?><br /><?php }
if($fcv->f("vsvcaddr2")){ echo stripslashes($fcv->f("vsvcaddr2"))?><br /><?php }
if($fcv->f("vsvccity")){ echo stripslashes($fcv->f("vsvccity")).', '.stripslashes($fcv->f("vsvcstate")).' '.stripslashes($fcv->f("vsvczip")).'  '.stripslashes($fcv->f("vsvcnatl"))?><br /><?php }
if($fcv->f("vsvcphone")){ echo stripslashes($fcv->f("vsvcphone"))?><br /><?php }
if($fcv->f("vsvcfax")){ echo stripslashes($fcv->f("vsvcfax"))?><br /><?php }
if($fcv->f("vsvcemail")){?><a href="mailto:<?php echo stripslashes($fcv->f("vsvcemail"))?>"><?php echo stripslashes($fcv->f("vsvcemail"))?></a><br /><?php }?>

</td></tr></table>

</td></tr></table>
<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
