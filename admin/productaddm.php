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
header("Expires: 0");

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// addslashes() for non-numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
//$zid = (int)getparam('zid');
//$lid = (int)getparam('lid');
// Note. $zid and $lid are used in this script to hold optional values
// of $zoneid and $langid used in <select> elements.
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcc = new FC_SQL; //category
$fcl = new FC_SQL; //language
//$fcp = new FC_SQL;
//$fcs = new FC_SQL;
$fcw = new FC_SQL; //web
$fcz = new FC_SQL; //zone

if($zoneid==''||$langid==''){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}
$fcw->query("select webdesctmpl from web ".
	"where webzid=$zoneid and weblid=$langid"); 
$fcw->next_record();
?>

<h2 align="center">Add a Product to Multiple Zones</h2>
<hr />

<center>
<form name="prodform" method="post" action="productupdm.php">
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<b>
Add a New Product to Multiple Zones<br />
</b>

</td></tr>

<?php 
$fcz->query("select count(*) as zcnt from zone"); 
$fcz->next_record();
$zcnt=(int)$fcz->f('zcnt');
$fcz->free_result();
if( $zcnt > 1 ){
?>

<tr><td align="center" colspan="3" bgcolor="#ffffff"><i>
You can add this product to more than one zone.  To activate a zone, click
on the checkbox by its name.  Within each active zone, the appropriate fields
must be filled in for each language, and at least one category selected.
</i></td></tr>

<?php
}

$fcz->query("select zoneid,zonedescr from zone order by zoneid"); 
while( $fcz->next_record() ){
 $zid=(int)$fcz->f('zoneid');
 $zdesc=$fcz->f('zonedescr');
 if( $zid == $zoneid ){
  $zstate=' checked="checked"';
 }else{
  $zstate='';
 }
?>
 <tr><td align="center" colspan="3" bgcolor="#ffffff">
 <font size="+1"><b>Zone: <?php echo $zdesc ?></b></font><br />
 <input type="checkbox" name="addzone<?php echo $zid ?>" value="1"<?php echo $zstate ?> />
 Add to this zone<br />

<input type="hidden" name="act<?php echo $zid ?>" value="insert" />
<input type="hidden" name="show<?php echo $zid ?>" value="<?php echo $show?>" />
<input type="hidden" name="ssku<?php echo $zid ?>" value="<?php echo $ssku?>" />
<input type="hidden" name="srch<?php echo $zid ?>" value="<?php echo $srch?>" />
<p>Use Inventory Quantity Field?<br />
<input type="radio" name="useinv<?php echo $zid ?>" value="1" />YES
<input type="radio" name="useinv<?php echo $zid ?>" value="0" checked="checked" />NO<br />
Downloadable Product?<br />
<input type="radio" name="useesd<?php echo $zid ?>" value="1" 
 toggleESD('on') />YES
<input type="radio" name="useesd<?php echo $zid ?>" value="0" checked="checked"
 toggleESD('off') />NO<br />
</p>
<p>
<input type="radio" name="genesd" value="1" /> Use FishCart Download System<br />
<input type="radio" name="genesd" value="0" /> Use External Download System<br />
</p>

<p>
Charge shipping?<br />
<input type="radio" name="noship<?php echo $zid ?>" value="0" checked="checked" />YES
<input type="radio" name="noship<?php echo $zid ?>" value="1" />NO<br />
Charge tax?<br />
<input type="radio" name="notax<?php echo $zid ?>" value="0" checked="checked" />YES
<input type="radio" name="notax<?php echo $zid ?>" value="1" />NO<br />

Charge VAT?<br />
<input type="radio" name="novat<?php echo $zid ?>" value="0" checked="checked" />YES
<input type="radio" name="novat<?php echo $zid ?>" value="1" />NO<br />

Processing Method?<br />
<select name="prodcharge"> 
<option value="0">Online Clearing</option>
<option value="1">Authorize CC Only</option>
<option value="2">Invoice Item</option>
</select> 
</p>

</td></tr>
<tr><td valign="middle" bgcolor="#ffffff">

Inventory Quantity:<br />
<input name="invqty<?php echo $zid ?>" size="10" onfocus="currfield='invqty'" /><br />

</td><td valign="top" bgcolor="#ffffff" colspan="2">

Setup Fee:<br />
<input name="setup<?php echo $zid ?>" size="10" onfocus="currfield='setup'" /><br />

Product Retail:<br />
<input name="rtlprice<?php echo $zid ?>" size="10" onfocus="currfield='price'" /><br />

Product Price:<br />
<input name="price<?php echo $zid ?>" size="10" onfocus="currfield='price'" /><br />

VAT Percent: (format: 0.nn)<br />
<input name="prodvat<?php echo $zid ?>" size="10" onfocus="currfield='prodvat'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

<table width="100%" border="0" cellspacing="1" cellpadding="4" bgcolor="#666666" class="text">
<tr><td bgcolor="#ffffff">
Product SKU: <i>required; must be unique</i><br />
<input name="sku<?php echo $zid ?>" size="10" onfocus="currfield='sku'" /><br />
</td><td bgcolor="#ffffff">
Max Order Qty:<br />
<input name="ordmax<?php echo $zid ?>" size="10" onfocus="currfield='prodordmax'" /><br />
</td></tr></table>

</td><td valign="top" bgcolor="#ffffff">

Product Sequence #: <i>optional for alternate product sort ordering</i><br />
<input name="prodseq<?php echo $zid ?>" size="10" onfocus="currfield='prodseq'" /><br />

</td><td valign="top" bgcolor="#ffffff">

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Product Weight (format: 0.0000)<br />
<input name="prodweight<?php echo $zid ?>" size="10" onfocus="currfield='prodweight'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Product Activate Date:<br />
<i>YY MM DD; may be empty</i><br />
<input name="psy<?php echo $zid ?>" size="5" onfocus="currfield='proddate'" />
<input name="psm<?php echo $zid ?>" size="2" onfocus="currfield='proddate'" />
<input name="psd<?php echo $zid ?>" size="2" onfocus="currfield='proddate'" />
<br />

</td><td valign="top" bgcolor="#ffffff">

Product Deactivate Date:<br />
<i>YY MM DD; may be empty</i><br />
<input name="pey<?php echo $zid ?>" size="5" onfocus="currfield='proddate'" />
<input name="pem<?php echo $zid ?>" size="2" onfocus="currfield='proddate'" />
<input name="ped<?php echo $zid ?>" size="2" onfocus="currfield='proddate'" />
<br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Sale Price:<br />
<input name="saleprice<?php echo $zid ?>" size="10" onfocus="currfield='saleprice'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Sale Start Date:<br /><i>YY MM DD</i><br />
<input name="ssy<?php echo $zid ?>" size="5" onfocus="currfield='saledate'" />
<input name="ssm<?php echo $zid ?>" size="2" onfocus="currfield='saledate'" />
<input name="ssd<?php echo $zid ?>" size="2" onfocus="currfield='saledate'" />
<br />

</td><td valign="top" bgcolor="#ffffff">

Sale End Date:<br /><i>YY MM DD</i><br />
<input name="sey<?php echo $zid ?>" size="5" onfocus="currfield='saledate'" />
<input name="sem<?php echo $zid ?>" size="2" onfocus="currfield='saledate'" />
<input name="sed<?php echo $zid ?>" size="2" onfocus="currfield='saledate'" />
<br />

</td></tr>
<?php
 $fcl->query("select count(*) as cnt from lang where langzid=$zid");
 $fcl->next_record();
 $lt=(int)$fcl->f('cnt');
 $fcl->free_result();
 $fcl->query("select langdescr,langid from lang ".
	"where langzid=$zid order by langid"); 
 $i=0;
 while($i<$lt){
	$fcl->next_record();
	$ld=$fcl->f('langdescr');
	$lid=$fcl->f('langid');
?>
	<tr><td align="center" colspan="3" bgcolor="#ffffff">
	<b>Language: <?php echo $ld ?></b><br />
	</td></tr>

	<tr><td valign="top" colspan="3" bgcolor="#ffffff">

	Product Name:<br />
	<input name="prodname<?php echo $i?>" size="40"
 	 onfocus="currfield='prodname'" /><br />

	Short Product Description:<br />
	<textarea name="sdescr<?php echo $i?>" rows="2" cols="70"
	 onfocus="currfield='descr'"></textarea><br />

	Product Description:<br />
	<textarea name="descr<?php echo $i?>" rows="5" cols="70"
	 onfocus="currfield='descr'"><?php echo $fcw->f('webdesctmpl')?></textarea><br />

	Product Keywords:<br />
	<textarea name="keyword<?php echo $i?>" rows="5" cols="70"
	 onfocus="currfield='keywords'"></textarea><br />

	Product Download URI:<br />
	<input name="proddload<?php echo $i?>" size="40"
	 onfocus="currfield='proddload'" /><br />

	Product Offer # / Motivation Code:<br />
	<input name="prodoffer<?php echo $i?>" size="40"
 	 onfocus="currfield='prodoffer'" /><br />

<?php /* ?>
	Product ISBN:<br />
	<input name="prodisbn" size="40"
	 onfocus="currfield='prodisbn'" /><br />

	Author:<br />
	<input name="prodauth<?php echo $i?>" size="40"
	 onfocus="currfield='prodauth'" /><br />

	Author URL:<br />
	<input name="prodauthurl<?php echo $i?>" size="40"
	 onfocus="currfield='prodauthurl'" /><br />

	Product Lead Time Comments:<br />
	<input name="prodleadtime<?php echo $i?>" size="40"
	 onfocus="currfield='prodleadtime'" /><br />

	Product Material Code:<br />
	<input name="prodmcode" size="40"
	 onfocus="currfield='prodmcode'" /><br />
<?php */ ?>

	Audio Clip URI:<br />
	<input name="audio<?php echo $i?>" size="40"
	 onfocus="currfield='av'" /><br />

	Video Clip URI:<br />
	<input name="video<?php echo $i?>" size="40"
	 onfocus="currfield='av'" />

	<p><i>
	Graphic paths should be either relative to the installed cart (<b>./...</b>)<br />
	or absolute with respect to the top of the Web site (<b>//fishcart/...</b>).</i>
	</p><p>

	Web Page Graphic URI:<br />
	<input name="pic<?php echo $i?>" size="40"
	 onfocus="currfield='pic'" /><br />
    </p>
	Thumbnail Graphic URI:<br />
	<input name="tpic<?php echo $i?>" size="40"
	 onfocus="currfield='tpic'" /><br />

	Banner Graphic URI:<br />
	<input name="banr<?php echo $i?>" size="40"
	 onfocus="currfield='banr'" /><br />

	Sale Graphic URI:<br />
	<input name="splash<?php echo $i?>" size="40"
	 onfocus="currfield='splash'" /><br />

	</td></tr>
	<tr><td align="left" colspan="3" bgcolor="#ffffff">

<?php
$ccount=0;
$fcc->query("select catval,catdescr from cat ".
	"where catzid=$zid"); 
while( $fcc->next_record() ){
	// pull all category info into arrays
	$car[]=$fcc->f('catval');
	$card[]=$fcc->f('catdescr');
	$ccount++;
}
$fcc->free_result();
?>

	Select up to three categories for this product:<br />

<?php
  $h=0;		// iteration count
  $cloop=3;	// number of categories to show
  while($h<$cloop){
?>
    <p>
	<select name="pc<?php echo $zid.$h.$i ?>" size="1" onfocus="currfield='newcat'">
	<option value="0">Select A Category</option>
<?php 
	$j=0;
	$ccount=1;
	while($j<$ccount){
	// get the list of categories
		$get_cats = new FC_SQL;
		$get_scats = new FC_SQL;
		$get_cats->query("select catval,catpath from cat ".
			"where catzid=$zid and catlid=$lid order by catpath");
		while ( $get_cats->next_record() ){
			$patharray = explode(':',$get_cats->f('catpath'));
			$catlst=$get_cats->f('catval');
			print '<option value="'.$catlst.'">';
			while (list($key, $val)=each($patharray)) {
				if ($val != ''){
					$get_scats->query("select catdescr from cat ".
						"where catzid=$zid and catlid=$lid and catval=$val");
					if( $get_scats->next_record() ){
						print '/'.$get_scats->f('catdescr');
						$get_scats->free_result();
					}
				}
			}
			print  "</option>\n";
		}
		$get_cats->free_result();	
?>
</select>
<?php
		$j++;
	}
?>
	<br />

	Category Sequence Code: 
	<input name="pcatseq<?php echo $zid.$h.$i?>" size="10"
 	onfocus="currfield='pcatseq'" /></p>
<?php
	$h++;
  }
?>
	<input type="hidden" name="prodlid<?php echo $zid.$i ?>" value="<?php echo $lid?>" />
	</td></tr>
<?php
	$i++;
 } // end of lang loop
} // end of zone loop
$fcz->free_result();
?>

<tr><td align="center" colspan="3" valign="middle" bgcolor="#ffffff">

<input type="hidden" name="zcnt" value="<?php echo $zcnt?>" />
<input type="hidden" name="cloop" value="<?php echo $cloop?>" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add Product" onclick="closehelp()" />
<input type="reset" value="Clear Field" />


</td></tr>
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</form><br />
</center>

<?php  require('./footer.php'); ?>
