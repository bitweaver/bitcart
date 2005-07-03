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

require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// addslashes() for non-numbers, no exceptions

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

$zid        =   (int)getparam('zid');
$lid        =   (int)getparam('lid');
$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
// Product Modify/Delete
$ssku       =   getparam('ssku');
$srch       =   getparam('srch');
//Category Association
$val        =   (int)getparam('val');
//CC Checksum
$cctype     =   getparam('cctype');
$cc_number  =   getparam('cc_number');
// ==========  end of variable loading  ==========

require('./admin.php');
require_once( BITCART_PKG_PATH.'flags.php');

$fct = new FC_SQL;
$fclang = new FC_SQL;

// see if all has been set up yet; need at least one entry
// in each of the following tables.

$fct->query("select zoneid,zonedeflid,zflag1 from zone"); 
if( !$fct->next_record() ){
 header("Location: $nsecurl/$maintdir/zoneadd.php");
 exit;
}
$zflag1 = (int)$fct->f('zflag1');
$zonedeflid = (int)$fct->f('zonedeflid');
$fct->free_result();

if( !empty($zid) ){
	$zoneid = $zid;
}
if( !empty($lid) ){
	$langid = $lid;
}

// get the default zone if one has not been set so far
if( empty($zoneid) ){
 $fct->query("select zoneid from master");
 if( $fct->next_record() ){
  $zoneid=$fct->f("zoneid");
  $fct->free_result();
 }else{
  // force a default entry
  $fct->query("insert into master ".
  	"(zoneid,numzone,numlang,maxzone,maxlang) values (1,1,1,1,1)");
 }
}

// at least one language profile must exist for this zone
$fclang->query("select count(*) as cnt from lang where langzid=$zoneid"); 
$fclang->next_record();
if( $fclang->f('cnt') == 0 ){
 // langupd.php will force this new language as default for this zone
 // it will also define the countrytable and countrylang entries if needed
 header("Location: $nsecurl/$maintdir/langadd.php?zoneid=$zid");
 exit;
}

// get the default language
if( empty($langid) ){
 $langid=$zonedeflid;
 $langiso='';
}

// if for some reason we still don't have a language, get the default
if( !$langid ){
 $fct->query(
  "select langid,langiso from lang where langzid=$zoneid order by langid");
 $fct->next_record();
 $langid=(int)$fct->f("langid");
 $langiso=$fct->f("langiso");
 $fct->free_result();
}

// make sure we have the 3 char ISO 639/2 language code
if( $langid && empty( $langiso ) ){
 $fct->query(
  "select langiso from lang where langzid=$zoneid and ".
  "langid=$langid order by langid");
 $fct->next_record();
 $langiso=$fct->f("langiso");
 $fct->free_result();
}

// We can have a mismatch between zone and language if the language id
// is not valid for this zone.  If the language ID does not match the
// current zone ID, take the first one and make it the default.
$fct->query(
	"select count(*) as cnt from lang ".
	"where langzid=$zoneid and langid=$langid");
$fct->next_record();
if( $fct->f("cnt")==0 ){
 $fct->free_result();
 $fct->query(
  "select langid from lang where langzid=$zoneid order by langid"); 
 if( $fct->next_record() ){
  $langid=(int)$fct->f("langid");
 }
}
$fct->free_result();

// now check for overall consistency in the database
$fctmp = new FC_SQL;
$fctmp1 = new FC_SQL;

// see if every zone/language combination has the basics set up
$fct->query("select zoneid from zone"); 
while( $fct->next_record() ){
 $zid=(int)$fct->f("zoneid");

 // check for a language profile for this zone
 $fclang->query("select langid from lang where langzid=$zid"); 
 if( !$fclang->next_record() ){
  header("Location: $nsecurl/$maintdir/langadd.php?zoneid=$zid");
  exit;
 }else{

  // now check for valid entries in language dependent tables in this zone
  // already have first record
  do {
   $lid=(int)$fclang->f("langid");

   // check for a vendor profile for this zone
   $fctmp->query("select vendid from vend where vendzid=$zid"); 
   if( !$fctmp->next_record() ){
    header("Location: $nsecurl/$maintdir/vendoradd.php?zoneid=$zid&langid=$lid");
    exit;
   }
   $fctmp->free_result();

   // take care of subzones; there should be at least one
   $fctmp->query("select count(*) as cnt from subzone where subzid=$zid"); 
   $fctmp->next_record();
   if( $fctmp->f('cnt') == 0 ){
    header("Location: $nsecurl/$maintdir/subzoneadd.php?zoneid=$zid&langid=$lid");
    exit;
   }
   $fctmp->free_result();

   $fctmp1->query("select webid from web ".
    "where webzid=$zid and weblid=$lid"); 
   if( !$fctmp1->next_record() ){
    header("Location: $nsecurl/$maintdir/webadd.php?zoneid=$zid&langid=$lid");
    exit;
   }
   $fctmp1->free_result();

   $fctmp1->query("select catval from cat ".
    "where catzid=$zid and catlid=$lid"); 
   if( !$fctmp1->next_record() ){
    header("Location: $nsecurl/$maintdir/categoryadd.php?zoneid=$zid&langid=$lid");
    exit;
   }
   $fctmp1->free_result();

   // check the next language for this zone
  } while( $fclang->next_record() ); // end of language loop
  $fclang->free_result();
 }
}
$fct->free_result();

// *NOW* we can show them the maintenance page...

require('./header.php');
?>
<table border="0" bgcolor="#FFFFFF" width="650" align="center" class="text">
<tr><td align="left" valign="middle" bgcolor="#FFFFFF">
<img src="../images/fclogo12.gif" align="left" />
</td>
<td align="left" valign="middle" bgcolor="#FFFFFF"><font="arial,helvetica" size="3" align="center"><b>
</font></b><br />
<font=arial,helvetica size="3" align="center"><b>FishCart Database Maintenance (version <?php require('version');?>)</b></font>
</td></tr></table>

<hr>

<center>
<table border="0" cellspacing="0" cellpadding="3" bgcolor="#666666" width="650" class="text">
<tr><td align="center" valign="top" bgcolor="#FFFFFF" height="25">

<form method="post" name="prodform" action="productndx.php">

<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#666666" width="600" class="text">
<tr><td class="divrow" colspan="1" align="center" bgcolor="#FFFFFF" height="35">
Select a Catalog (Zone):
</td><td class="divrow" colspan="1" align="center" bgcolor="#FFFFFF" height="35">
Select a Language:
</td></tr>
<tr><td colspan="1" align="center" valign="top" bgcolor="#FFFFFF" height="35">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>">
<input type="hidden" name="langid" value="<?php echo $langid?>">

<?php 
$fct->query("select count(*) as cnt from zone");
$fct->next_record();
$zt=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select zoneid,zonedescr from zone order by zoneid"); 
?>
<select name="zid" size="<?php echo $zt+1?>"
 onFocus="currfield='zoneid';"
 onChange="closehelp();document.prodform.action='index.php';submit();">
<option value="">[select a zone]
<?php 
while( $fct->next_record() ){
	$zi=$fct->f("zoneid");
	if(isset($zoneid)){
		if($zi==$zoneid){
			echo "<option value=\"$zi\" selected>";
		}else{
			echo "<option value=\"$zi\">";
		}
	}else{
		echo "<option value=\"$zi\">";
	}
	echo substr($fct->f("zonedescr"),0,20) . "\n";
}
$fct->free_result();
?>
</select><br>

</td><td colspan="1" bgcolor="#FFFFFF" align="center">

<?php 
$fct->query("select count(*) as cnt from lang where langzid=$zoneid");
$fct->next_record();
$zt=(int)$fct->f("cnt");
$fct->free_result();

$fct->query("select langid,langdescr from lang ".
 "where langzid=$zoneid order by langid"); 
?>
<select name="lid" size="<?php echo $zt+1?>"
 onFocus="currfield='langid';"
 onChange="closehelp();document.prodform.action='index.php';submit();">
<option value="">[select a language]
<?php 
while( $fct->next_record() ){
	$li=$fct->f("langid");
	if(isset($langid)){
		if($li==$langid){
			echo "<option value=\"$li\" selected>";
		}else{
			echo "<option value=\"$li\">";
		}
	}else{
		echo "<option value=\"$li\">";
	}
	echo substr($fct->f("langdescr"),0,20) . "\n";
}
$fct->free_result();
?>
</select><br>


</td></tr>
<?php if( $zflag1 & $flag_zonepwcatalog ){ ?>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type="button" name="pwmod" value="Catalog Password Maintenance"
 onClick="closehelp();document.prodform.action='pwndx.php';submit();"><br>

</td></tr>
<?php } ?>
<tr><td class="divrow" align="center" colspan="2" bgcolor="#FFFFFF" height="35">
Products:
</td></tr>
<tr><td align="center" bgcolor="#FFFFFF" height="35">

Search SKU:<br>
<input name="ssku" size="10" maxsize="20"
 onFocus="currfield='skusearch'"><br>

</td><td align="left" bgcolor="#FFFFFF" height="35">

<input type="radio" name="srch" value="b"
 onFocus="currfield='skusearch'">
SKU Begins with<br>
<input type="radio" name="srch" value="c"
 onFocus="currfield='skusearch'">
SKU Contains<br>
<input type="radio" name="srch" value="e"
 onFocus="currfield='skusearch'">
SKU Ends with<br>

</td></tr>
<tr><td colspan="1" align="center" bgcolor="#FFFFFF" height="35">

<input type="button" name="prodmod" value="Modify/Delete Products"
 onClick="closehelp();document.prodform.action='productndx.php';submit();"><br>

</td>
<td colspan="1" align="center" bgcolor="#FFFFFF" height="35">

<input type=button name=prodadd value="Add A New Product"
 onClick="closehelp();document.prodform.action='productadd.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=prodaddm value="Multizone New Product"
 onClick="closehelp();document.prodform.action='productaddm.php';submit();"><br>

</td></tr>

<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=nlist value="Modify New Item List"
 onClick="closehelp();document.prodform.action='newprodndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=nprodadd value="Add To New Item List"
 onClick="closehelp();document.prodform.action='newprodadd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=addprodrel value="Modify Related Products"
 onClick="closehelp();document.prodform.action='prodrelndx.php';submit();"><br>

</td>

<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=addprodrel value="Add Related Products"
 onClick="closehelp();document.prodform.action='prodreladd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=olist value="Modify Closeout Item List"
 onClick="closehelp();document.prodform.action='oldprodndx.php';submit();"><br>
</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=oprodadd value="Add To Closeout Item List"
 onClick="closehelp();document.prodform.action='oldprodadd.php';submit();"><br>

</td></tr>
<tr>
	<th>Categories:</th>
</tr>
<tr>
	<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=pcat value="Modify Product Categories" onClick="closehelp();document.prodform.action='categoryndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=pcat value="Add Product Categories"
 onClick="closehelp();document.prodform.action='categoryadd.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=subzseq value="Modify Category Sequences"
onClick="closehelp();document.prodform.action='categoryseq.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=pcat value="Modify Product Sequences within Category"
 onClick="closehelp();document.prodform.action='prodcatmod.php';submit();"><br>

</td></tr>
<tr><td align=center colspan=2 bgcolor=#ffffff height=35>

Product/Category Association<br>

<select name=val size="1"
 onChange="closehelp();document.prodform.action='prodcatndx.php';submit();"
 onFocus="currfield='prodcat'"><br>
<option value="">[select a category]</option>
<?php 
$get_cats = new FC_SQL;
$get_scats = new FC_SQL;
$get_cats->query("select catval,catpath from cat ".
	"where catzid=$zoneid and catlid=$langid order by catpath");
while ($get_cats->next_record()){
	$patharray = explode(":",$get_cats->f("catpath"));
	$catlst=$get_cats->f("catval");
	print "<option value=\"$catlst\">";
	while (list($key, $val)=each($patharray))
	{
		if ($val != ""){
			$get_scats->query("select catdescr from cat ".
				"where catzid=$zoneid and catlid=$langid and catval=$val");
			if( $get_scats->next_record() ){
				print '/'.$get_scats->f("catdescr");
				$get_scats->free_result();
			}
		}
	}
	print  "</option>\n";
}
$get_cats->free_result();	
?>
</select>
<br>
<input type=button name=pcatmod value="Modify Association"
 onClick="closehelp();document.prodform.action='prodcatndx.php';submit();"><br>

</td></tr>
<tr><th>
Prompts/Profiles:
</th></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=mastermod value="Modify Master Profile"
 onClick="closehelp();document.prodform.action='mastermod.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=promptmod value="Modify Prompts"
 onClick="closehelp();document.prodform.action='promptndx.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=vendormod value="Modify A Vendor Profile"
 onClick="closehelp();document.prodform.action='vendorndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=vendoradd value="Add A Vendor Profile"
 onClick="closehelp();document.prodform.action='vendoradd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=cpnmod value="Modify A Coupon Profile"
 onClick="closehelp();document.prodform.action='couponndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=cpnadd value="Add A Coupon Profile"
 onClick="closehelp();document.prodform.action='couponadd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=webmaint value="Modify A Web Profile"
 onClick="closehelp();document.prodform.action='webndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=webadd value="Add A Web Profile"
 onClick="closehelp();document.prodform.action='webadd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=langmaint value="Modify A Language Profile"
 onClick="closehelp();document.prodform.action='langndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=langmaint value="Add A Language Profile"
 onClick="closehelp();document.prodform.action='langadd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=shipmod value="Modify A Shipping Profile"
 onClick="closehelp();document.prodform.action='shipndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=shipadd value="Add A Shipping Profile"
 onClick="closehelp();document.prodform.action='shipadd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=zonemod value="Modify A Zone Profile"
 onClick="closehelp();document.prodform.action='zonendx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=zoneadd value="Add A Zone Profile"
 onClick="closehelp();document.prodform.action='zoneadd.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=subzmod value="Modify A SubZone Profile"
 onClick="closehelp();document.prodform.action='subzonendx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=subzmod value="Add A SubZone Profile"
onClick="closehelp();document.prodform.action='subzoneadd.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=subzseq value="Modify SubZone Sequences"
onClick="closehelp();document.prodform.action='subzoneseq.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=subzseq value="Modify Country List"
onClick="closehelp();document.prodform.action='countrymod.php';submit();"><br>

</td></tr>
<tr><th>
Dynamic Links/Text:
</th></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=auxlink value="Modify Dynamic Links"
 onClick="closehelp();document.prodform.action='auxlinkndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=auxlink value="Add Dynamic Links"
 onClick="closehelp();document.prodform.action='auxlinkadd.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=auxlink value="Modify Dynamic Link Sequence"
 onClick="closehelp();document.prodform.action='auxlinkseq.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=auxtext value="Modify Dynamic Text"
 onClick="closehelp();document.prodform.action='auxtextndx.php';submit();"><br>

</td>
<td colspan=1 align=center bgcolor=#ffffff height=35>

<input type=button name=auxtext value="Add Dynamic Text"
 onClick="closehelp();document.prodform.action='auxtextadd.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=center bgcolor=#ffffff height=35>

<input type=button name=auxtext value="Modify Dynamic Text Sequence"
 onClick="closehelp();document.prodform.action='auxtextseq.php';submit();"><br>

</td></tr>
<tr><th>
Reporting:

</th></tr>
<tr><td colspan=2 align=center valign=top bgcolor=#ffffff height=35>

<input type=button name=splitcc value="Collect CC Numbers"
 onClick="closehelp();document.prodform.action='splitcc.php';submit();"><br>

</td></tr>

<?php if( !($zflag1 & $flag_zonesqldel) ) { ?>
<tr><td align=center valign=top colspan=2 bgcolor=#ffffff height=35>

<input type=button name=ordertoday value="Order Summary Today"
 onClick="closehelp();document.prodform.action='ordertoday.php';submit();"><br>

</td></tr>
<?php } ?>
<tr><td colspan=1 align=center valign=top bgcolor=#ffffff height=35>

<input type=button name=tmplmod value="File Upload Maintenance"
 onClick="closehelp();document.prodform.action='uploadmaint.php';submit();"><br>

</td>
<td colspan=1 align=center valign=top bgcolor=#ffffff height=35>

<!-- check the height and width for all pics in the cart -->
<input type=button name=rebuildpix value="Rebuild Picture Index"
 onClick="
  closehelp();
  rbldwin=window.open('rebuildpics.php?rehash=1&zoneid=<?php echo zoneid ?>&langid=<?php echo langid ?>','Rebuild_Picture_Sizes','scrollbars,resizable');
  rbldwin.focus();
 "><br>
</td></tr>
<tr><td colspan=1 align=center valign=top bgcolor=#ffffff height=35>

<input type=button name=keyquery value="Keyword Summary Query"
 onClick="closehelp();document.prodform.action='keyquery.php';submit();"><br>

</td>
<td colspan=1 align=center valign=top bgcolor=#ffffff height=35>

<input type=button name=pricereport value="Product Price Report"
 onClick="closehelp();document.prodform.action='printquery.php';submit();"><br>

</td></tr>
<tr><td colspan=1 align=center valign=top bgcolor=#ffffff height=35>

<input type=button name=orderquery value="Order Detail Query"
 onClick="closehelp();document.prodform.action='orderdetail.php';submit();"><br>

</td>
<td colspan=1 align=center valign=top bgcolor=#ffffff height=35>

<input type=button name=orderquery value="Order Summary Query"
 onClick="closehelp();document.prodform.action='orderquery.php';submit();"><br>

</td></tr>
<tr><td colspan=2 align=left valign=top bgcolor=#ffffff height=35>

Verify a CC Number Checksum:<br>
<input type=radio name=cctype value="Visa">VISA<br>
<input type=radio name=cctype value="Mastercard">Mastercard<br>
<input type=radio name=cctype value="Discover">Discover<br>
<input type=radio name=cctype value="American Express">AmEx<br>
<input type=text name=cc_number size=19><br>
<input type=button name=shipadd value="Verify CC Number Checksum"
 onClick="closehelp();document.prodform.action='ccverify.php';submit();"><br>

<p>

Test Numbers:<br>
Visa: 4111-1111-1111-1111<br>
Mast: 5555-5555-5555-4444<br>
Disc: 6011-1111-1111-1117<br>
AmEx: 3782-8224-6310-005<br>

</td></tr>
</table>
</center>

</td></tr>
<?php /*
<tr><td align=center bgcolor=#ffffff>

<input type=button name=tmplmod value="Web Template Maintenance"
 onClick="closehelp();document.prodform.action='templateadd.php';submit();"><br>

</td></tr>
*/ ?>

<?php /* 
<tr><td align=center bgcolor=#ffffff>

<a href="orderproc.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Send Pending Orders
</a><br>
<font color="#ff0000"><i>preproduction testing only!</i></font>

</td></tr>
*/?>

</form>
</td></tr>
</table>
</center>

<?php  require('./footer.php');?>
