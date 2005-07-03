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
require_once( BITCART_PKG_PATH.'functions.php' );

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
$nlst = getparam('nlst');
$olst = getparam('olst');
$olimit = (int)getparam('olimit');

$mode = getparam('mode');
// ==========  end of variable loading  ==========

require('./public.php');
require('./flags.php');
require('./cartid.php');
require('./languages.php');

$webid = !empty($webid) ? (int) $webid : 0;

$fcz = new FC_SQL;
$fcz->query("select zonecurrsym,zflag1 from zone ".
			"where zoneid=$zid"); 
if($fcz->next_record()){
 $csym=stripslashes($fcz->f("zonecurrsym"));
 $csym=trim($csym);
 $zflag1=(int)$fcz->f("zflag1");
}else{
 $csym="";
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

$cat=(int)$cat; // force to a number
$olimit=(int)$olimit;

// get the Web table
//$fcw->query("select * from web where webzid=$zid and weblid=$lid"); 
if( $aid != '' ){
  $fca->query("select ascwebid from associate where ascid='$aid'");
  $fca->next_record();
  $webid = (int)$fca->f("ascwebid");
  if ($webid > 0) {
    $fcw->query("select * from web ".
		"where webid=$webid and webzid = $zid and weblid=$lid");
  } else {  //aid set, but no webid for some reason
    $fcw->query("select * from web where webzid=$zid and weblid=$lid");
  }
} else {
  $fcw->query("select * from web where webzid=$zid and weblid=$lid");
} 

$fcw->next_record();
$srt=$fcw->f("websort");
$wflag1=(int)$fcw->f('webflags1');
$dn=(int)$fcw->f("webprodpage");	// number of products per page

if( $zflag1 & $flag_zonepwcatalog ){
	// password controlled access
	include('./pw.php');
}

// get the language templates
$fcl->Auto_free=1;
$fcl->query("select langtdsp,langshow,langterr,langcopy,langterms from lang ".
	"where langid=$lid");
$fcl->next_record('langterms');
$show=$fcl->f("langshow");
$copy=$fcl->f("langcopy");
$lterms=$fcl->f("langterms");


// log the access only if zoneflag
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
//end logging access

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
    "catbanrh,catbanrw,catsku,catmast,cattmpl,catsort,catprodpage,catcols";
	
        $fcs->query("select $cf from cat where ".
        "catval=$cat and catzid=$zid and catlid=$lid");
		$fcs->next_record();
		$srt=$fcs->f("catsort");
		$dn=(int)$fcs->f("catprodpage");
		$cats_across=(int)$fcs->f("catcols");
		if( $cats_across <= 0 ){
			$cats_across = 1;
		}

	if(!empty($psku)){
	 // single product display
	   $pj.="pcatval=$cat and pcatzid=$zid and pcatzid=prodzid and ".
	    "prodzid=prodlzid and prodlid=$lid and pcatsku='$psku' and ".
		"prodsku='$psku' and prodlsku='$psku' and ".
		"catzid=$zid and catval=$cat and catact=1 and ".
		"(produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
	}else{ // from a category selection
	 $pj.="pcatval=$cat and pcatzid=$zid and pcatzid=prodzid and ".
	 	"prodzid=prodlzid and prodzid=$zid and prodlzid=$zid and ".
		"prodlid=$lid and pcatsku=prodsku and prodsku=prodlsku and ".
		"catzid=$zid and catval=$cat and catact=1 and ".
		"(produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
	}
	$tbs="prod,prodlang,prodcat,cat";
	if(!empty($key1)){
         if( $databaseeng=="pgsql" || $databaseeng=="mssql" ) {
	   $pj.=" and (lower(proddescr) like lower('%$key1%')".
			" or lower(prodname) like lower('%$key1%')".
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
			" or lcase(prodname) like lcase('%$key1%')".
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
         if($databaseeng=="pgsql" || $databaseeng=="mssql" ) {
		  $pj.="(lower(proddescr) like lower('%$key1%')".
		    " or lower(prodkeywords) like lower('%$key1%')".
	        " or lower(prodname) like lower('%$key1%')".
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
	        " or lcase(prodname) like lcase('%$key1%')".
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
$fds="prodname,proddescr,prodpic,prodpicw,prodpich,prodtpic,prodtpicw,prodtpich,".
 "prodsku,prodprice,prodrtlprice,prodinvqty,prodaudio,prodvideo,prodsalebeg,prodsaleend,".
 "prodsplash,prodsaleprice,prodstsalebeg,prodstsaleend,prodstsaleprice,".
 "produseinvq,prodseq,prodoffer,prodsdescr,proddload,prodsetup,prodpersvc,prodflag1,prodlflag1";

$pj.=" and (produseinvq=0 or (produseinvq=1 and prodinvqty>0))";
// echo "select count(*) as cnt from $tbs where $pj<br />";
if (!$tbs){
$count=0;
}else{
$fcp->query("select count(*) as cnt from $tbs where $pj");
$fcp->next_record();
$total=(int)$fcp->f("cnt");
$fcp->free_result();
$count=$total;	// this because the "got" count below is commented out
}

if( !empty($cat) ){
 // check to see whether subcats exist under this cat
 $check_sub = new FC_SQL;
 $check_sub->query("select count(*) as cnt from cat where catunder=$cat");
 $check_sub->next_record();
 $check_subc=(int)$check_sub->f("cnt");
 $check_sub->free_result();
}


 // echo "select $fds from $tbs where $pj order by $srt<br />";
if ($count){
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
 if(empty($cat)){
     $cf="webbg,webback,weblink,webvlink,webalink";

	$fcs->query("select $cf from web where ".
	"webzid=$zid and weblid=$lid");
	}
	
 // END OF ESSENTIAL CART DISPLAY CODE ?>

<html><head>
<link rel="stylesheet" ID href="style.css" type="text/css" />
<title><?php
if($cat){
 echo stripslashes($fcs->f("catdescr"));
}
?></title></head>
<body<?php 
if( !empty($cat) ){
 // if a category given
 if($fcs->f("cattext")){?> text="#<?php echo stripslashes($fcs->f("cattext"))?>"<?php }
 if($fcs->f("catlink")){?> link="#<?php echo $fcs->f("catlink")?>"<?php }
 if($fcs->f("catvlink")){?> vlink="#<?php echo $fcs->f("catvlink")?>"<?php }
 if($fcs->f("catalink")){?> alink="#<?php echo $fcs->f("catalink")?>"<?php }
 if($fcs->f("catbg")){?> bgcolor="#<?php echo $fcs->f("catbg")?>"<?php }
 if($fcs->f("catback")){?> background="<?php echo $fcs->f("catback")?>"<?php }
 else{ if($fcw->f("webback")){?> background="<?php echo $fcw->f("webback")?>"<?php } }
}else{
 if($fcw->f("webtext")){?> text="#<?php echo stripslashes($fcw->f("webtext"))?>"<?php }
 if($fcw->f("weblink")){?> link="#<?php echo $fcw->f("weblink")?>"<?php }
 if($fcw->f("webvlink")){?> vlink="#<?php echo $fcw->f("webvlink")?>"<?php }
 if($fcw->f("webalink")){?> alink="#<?php echo $fcw->f("webalink")?>"<?php }
 if($fcw->f("webbg")){?> bgcolor="#<?php echo $fcw->f("webbg")?>"<?php }
 if($fcw->f("webback")) {?> background="<?php echo $fcw->f("webback")?>"<?php }
}
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

<?php

// display the masthead graphic
if($cat && $fcs->f("catlogo")){?>


<table class="text" cellpadding="0" width="580" border="0">
<tr><td align="center">
<img src="<?php echo $fcs->f("catlogo")?>" width="<?php echo $fcs->f("catlogow")?>" height="<?php echo $fcs->f("catlogoh")?>" border="0" alt="" />
</td></tr>
</table>

<?php }elseif($fcw->f("weblogo")){?>

<table class="text" cellpadding="0" width="580" border="0">
<tr><td align="center">
<img src="<?php echo $fcw->f("weblogo")?>" width="<?php echo $fcw->f("weblogow")?>" height="<?php echo $fcw->f("weblogoh")?>" border="0" alt="" />
</td></tr>
</table>

<?php }?>

<?php // CATEGORY MASTHEAD TEXT (IF ANY) ?>

<?php
$catmast=stripslashes($fcs->f("catmast"));

if($cat && $catmast != ''){?>
<table class="text" cellpadding="0" width="580" border="0">
<tr><td valign="top">

<?php echo stripslashes($fcs->f("catmast"));?>

</td></tr>
</table>
<?php }?>

<?php // TOP OF PAGE PROMO ITEM (IF ONE) ?>

<?php
if($cat && $fcs->f("catsku")){ 
      // if a category and SKU given ?>
<table class="text" cellpadding="0" width="580" border="0">
<tr><td align="center" colspan="1">

<?php  // use the category level banner unless empty, else product banner

  $hsku=$fcs->f("catsku");
  $img=$fcs->f("catbanr");
  $wid=$fcs->f("catbanrw");
  $hgt=$fcs->f("catbanrh");

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
<b><?php echo fc_text("quantity"); ?></b><input type="text" size="5" name="quantity" value="1" />
<input type="submit" value="<?php echo fc_text("longadd"); ?>" />
</form>

<?php }else{ ?>
Temporarily out of stock
<?php } ?>

</td></tr>
</table>
<?php } 

 if( !empty($cat) ){
 print"<div class=\"header\">";

 // shows the path to the current category
 $showpath = new FC_SQL;

 $showpath->query(
  "select catpath from cat where catpath like '%:$cat:' order by catseq");
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
    print ": $subcatdesc";
    print "</div>";
   }
  }
 }

/* commented out because it might not be helpful for everyone
// bjh start of new quick product finder
print"<br />";
?>
<table class="text" align="center" border="0" cellspacing="0" cellpadding="5" bgcolor="#CCCCCC">
<tr bgcolor="#000000">
<td align="left">
<font face="Arial,Helvetica" color="#FFFFFF" size="2"><b>
Quick Product Finder:
<?php if($cat && $catmast != ''){?>
<?php echo stripslashes($fcs->f("catmast"));?>
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
  $subcats = new FC_SQL;
  $subcats->query("select catval,catdescr,catmast,catlogo,catbutton ".
 	 "from cat where catact=1 and catzid=$zid and catlid=$lid ".
	 "and catunder=$cat order by catseq");

  // don't display more columns than we need
  if( $subcattot < $cats_across ){
    $cats_across = $subcattot;
  }
  $across=0;  
?>

<br />
<table class="text" border="0" width="580" cellpadding="5">
<tr><td valign="top" colspan="<?php echo $cats_across; ?>">
<b><?php echo fc_text("subcats")." $subcattot"; ?></b>
</td></tr>
 
<?php
  while( $subcats->next_record() ){
  if( ($across % $cats_across) == 0 ){
 	 if( $across > 0 ){ //end of not first row
	   print '</td></tr>';
	 }
	 print '<tr><td valign="top">';
   }else{ //not first column
     print '</td><td valign="top">';
   } 

   $catvl = $subcats->f("catval");
   //$catlogo = $subcats->f("catlogo");
   $catbutton = $subcats->f("catbutton");
   /********************************
   If more cat detail is needed, add your detailed description
   to Category Masthead Text in the modify or add category section
   in maintenance section. Comment the line defining $subdescr
   as catdescr and uncomment the next line where it is defined as 
   catmast.
   *********************************/
   $subdescr = stripslashes($subcats->f("catdescr"));
   //$subdescr = stripslashes($subcats->f("catmast"));
   print "<a href=\"display.php?cat=$catvl&zid=$zid&lid=$lid&cartid=$cartid\">";
   if( $catbutton ){
    print "<img src=\"$catbutton\" alt=\"$subdescr\" border=\"0\">";
   }
   print "<br>$subdescr</a><br />\n";
   $across++;
  }
  // figure count of cells to close except the last
  $rc = $cats_across - ($across % $cats_across);
  // with special exception if current column = total column count
  if( $rc == $cats_across ){
   $rc = 0;
  }
  while($rc){
   print '</td><td valign="top">';
   $rc--;
  }
  print "</td></tr>\n";

  //close table
    print '</table>';
  if (!$count){
    print'<table class="text" width="580" border="0">';
  }
  $subcats->free_result();
 }
 // end are there any subcats to display? If not, don't do anything more
 // end get subcat level under $cat and spit them out in a table
?>
 
<?php
// main product display table; only show if there are products
// show error if no products and no subcats or if they didn't tell us to do anything
if( (empty($count) and empty($check_subc)) ||
    (empty($cat) && empty($key1) && empty($psku) && empty($nlst) && empty($olst)) ){
   showerr();
}
if($count) { ?>
<table class="text" cellpadding="0" width="580" border="0">

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
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $olimit - $dn ?>&cat=<?php echo $cat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("previous"); ?></a>&nbsp;&nbsp;&nbsp;
<?php
 }

 $tmp=""; $i=0; $j=1; $k=$total;
 while ($k>0) {
  if($i!=$olimit){?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $i?>&cat=<?php echo $cat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo $j?></a>
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
&nbsp;&nbsp;&nbsp;<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $currj * $dn ?>&cat=<?php echo $cat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("next"); ?></a>
<?php
 }
 
 echo "\n";
?>

</td></tr>
<?php } // end of the clickable search results bar ?>

<?php  // display the category description
if(!empty($cat)){ ?>

<?php
if( 0 && $cat ){?>
<tr><td align="center" colspan="1">
<b>
<?php
echo stripslashes($fcs->f("catdescr"))."<br />\n";
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
echo fc_text('dispmultiple').' <b>'.$llmt.' '.fc_text('dispto').' '.$ulmt.'</b> '.fc_text('dispof').' '.$count.'<br />';
}else{
echo fc_text('dispsingle').' <b>'.$llmt.' '.fc_text('dispof').' '.$ulmt.'</b><br />';
} ?>
<br />
</td></tr>

<?php  // display the products
$j=0;
while( $fcp->next_record() ){
 $flag1=(int)$fcp->f('prodflag1');
 $prodlflag1=(int)$fcp->f('prodlflag1');
 $retailprice=(double)$fcp->f('prodrtlprice');
?>

<tr><td align="left" valign="top" colspan="3">

 <table class="text" width="100%" cellpadding="0" cellspacing="0" border="0">
 <tr><td align="left" valign="top" colspan="3">
<br /><br />
<?php

 //bvo
 if ($mode=="mp"||$mode==""){
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
if ($mode=="sp"){ //begin showing product detail
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

	    </td><td align="right" valign="bottom" colspan="1">
 </td></tr>
 <tr><td align="left" valign="bottom" colspan="3">
<?php $prodsku=$fcp->f("prodsku"); ?>

<form method="post" action="showcart.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&product=<?php echo $fcp->f("prodsku")?>&cat=<?php echo $cat?>&olimit=<?php echo $olimit?>&key1=<?php echo $key1?>">
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
<tr><td align="left" colspan="1">
 
 <i><?php echo fc_text("sku"); ?> <?php $prodsku=$fcp->f("prodsku"); echo $prodsku; ?></i>

</td><td align="left" colspan="1">
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
 if(!empty($cat)){ $prc=$fcs->f("catfree"); }
 if( empty($prc)){ $prc=$fcw->f("webfree"); }
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
 //echo ' '.fc_text('periodic');
 echo ' '.$fcp->f('prodpersvc');
}
?>
 </td><td align="right" colspan="1">
 <?php /* removed from active use since ESD was installed
 if( $fcp->f("proddload") ){ ?>

 <a href="<?php echo $fcp->f("proddload")?>"><i><?php echo fc_text("download"); ?></i></a><br />

 <?php } */ ?>
<?php 

// SHOW THE ADD TO ORDER BUTTON
// with product options, it is no longer feasible to show the qty
// on order, as we don't know which options have been chosen
if( $wflag1 & $flag_webshowqty ) {
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
<tr><td align="left" valign="top" colspan="3"><hr /></td></tr> 
 </table>

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
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $olimit - $dn ?>&cat=<?php echo $cat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("previous"); ?></a>&nbsp;&nbsp;&nbsp;
<?php
 }

 $tmp=""; $i=0; $j=1; $k=$total;
 while ($k>0) {
  if($i!=$olimit){?>
<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $i?>&cat=<?php echo $cat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo $j?></a>
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
&nbsp;&nbsp;&nbsp;<a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olimit=<?php echo $currj * $dn ?>&cat=<?php echo $cat?>&key1=<?php echo $key1?>&nlst=<?php echo $nlst?>&olst=<?php echo $olst?>"><?php echo fc_text("next"); ?></a>
<?php
 }
 
 echo "\n";
?>

</td></tr>
<?php } // end of the clickable search results bar ?>
<tr><td align="center" colspan="3">
<table class="text" width="100%" height="36" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" colspan="1">
<div id="button">
<ul>
<li><a href="index.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text("zonehome"); ?></a></li>
</ul>
</div>
</td><td align="center" colspan="1">
<div id="button">
<ul>
<li><a href="<?php echo $show?>?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text("viewcart"); ?></a></li>
</ul>
</div>
</td></tr>
</table>

</td></tr></table>
</td></tr></table>
<!--CLOSE CELL/ROW/TABLE OF THE MAIN 100% WRAPPER TABLE-->
</td></tr></table>

<?php // VENDOR INFORMATION 
include ('vendinfo.php');
//END OF VENDOR INFORMATION ?>

<?php // END OF ESSENTIAL CART DISPLAY CODE ?>

<?php require_once( FISHCART_PKG_PATH.'bitcart_footer_inc.php' ); ?>
