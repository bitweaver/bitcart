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

require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// addslashes() for non-numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$ssku   = getparam('ssku');
$srch   = getparam('srch');
$sku    = getparam('sku');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcc = new FC_SQL;
$fcd = new FC_SQL;
$fcl = new FC_SQL;
$fcp = new FC_SQL;
$fcpo= new FC_SQL;
$fcw = new FC_SQL;
$fcz = new FC_SQL;
?>

<h2 align="center">Modify A Product</h2>
<hr />

<center>
<form name="prodform" method="post" action="productupd.php"
 onSubmit="
  if( document.prodform.sku.value == '' ){
   alert('Error: please enter a product SKU.');
   return false;
  }
  if( document.prodform.price.value == 0 ){
   if( confirm('Warning: the product price is zero.  '+
               'To accept click OK, otherwise click Cancel.')){
    return true;
   }else{
    return false;
   }
  }
  if( document.prodform.ssy.value != '' &&
      document.prodform.ssm.value != '' &&
      document.prodform.ssd.value != '' &&
	  document.prodform.saleprice.value == 0 ){
   if( confirm('Warning: a product sale date has been set and the sale '+
               'price is zero.  '+
               'To accept click OK, otherwise click Cancel.')){
    return true;
   }else{
    return false;
   }
  }
  if( document.prodform.stssy.value != '' &&
      document.prodform.stssm.value != '' &&
      document.prodform.stssd.value != '' &&
	  document.prodform.stsaleprice.value == 0 ){
   if( confirm('Warning: a product setup sale date has been set and the '+
               'setup price is zero.  '+
               'To accept click OK, otherwise click Cancel.')){
    return true;
   }else{
    return false;
   }
  }
 ">
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" bgcolor="#FFFFFF" colspan="3">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF" colspan="3">

<input type="hidden" name="act" value="update" />
<input type="hidden" name="ssku" value="<?php echo $ssku?>" />
<input type="hidden" name="srch" value="<?php echo $srch?>" />
<input type="hidden" name="show" value="<?php echo $show?>" />

<b>
Modify An Existing Product</b><br />

</td></tr>
<tr><td bgcolor="#FFFFFF" colspan="3">

Listed in Product Categories:<br />

<?php
$fcc->query("select pcatval from prodcat ".
	"where pcatsku='$sku' and pcatzid=$zoneid");
while ($fcc->next_record()) {
    $fcd->query("select catdescr from cat where catval=".$fcc->f('pcatval'));
    $fcd->next_record();
    echo $fcd->f('catdescr')."<br />\n";
    $fcd->free_result();
}
$fcc->free_result();
?>
<p>
To modify the associated categories, use
&quot;<a href="">Product/Category Association</a>&quot;
from the central maintenance page.
</p>
</td></tr>
<tr><td bgcolor="#FFFFFF" colspan="3">

<a href="prodoptadd.php?ssku=<?php echo $sku ?>&amp;zoneid=<?php echo $zoneid ?>&amp;langid=<?php echo $langid ?>">Add Product Options</a><br />
<?php
$fcpo->query("select count(*) as cnt from prodopt where poptsku='$sku' and ".
             "poptlid=$langid and poptzid=$zoneid");
$fcpo->next_record();

if ( $fcpo->f('cnt') ) {
    echo '<a href="prodoptndx.php?ssku='.$sku.'&amp;zoneid='.$zoneid.
        '&amp;langid='.$langid.'">Update Product Options</a><br />';
    $fcpo->free_result();
}
?>

</td></tr>

<?php 
$i = 0;
$fcp->query("select * from prod ".
	"where prodsku='$sku' and prodzid=$zoneid");
if ( !$fcp->next_record() ) {
  echo '<tr><td bgcolor="#FFFFFF" colspan="3">';
  echo 'The selected product could not be found.  This may reflect an inconsistent database. ';
  echo 'Please check with your system administrator.';
  echo '</td></tr></table></center>';
  $fcp->free_result();
  exit;
}

$fcl->query("select * from prodlang ".
	"where prodlzid=$zoneid and prodlid=$langid and prodlsku='$sku' ".
	"order by prodlsku");
if ( !$fcl->next_record() ) {
    echo '<tr><td bgcolor="#FFFFFF" colspan="3">';
    echo '<b>The selected language for this product could not be found. '.
		 'Please fill in the values below for this language.</b><br>';
    echo '</td></tr>';
	$yes_fcl = 0;
	$add_lang_only = 1;
}else{
	$yes_fcl = 1;
	$add_lang_only = 0;
}
?>

<input type="hidden" name="oldsku" value="<?php echo $fcp->f('prodsku')?>" />

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
<?php if($yes_fcl){ $tmp = stripslashes($fcl->f('prodname')); } ?>
Product Name:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodname<?php echo $i?>" size="40" value="<?php echo $tmp ?>"
 onfocus="currfield='prodname'" /><br />
</td></tr>

<tr><td valign="top" colspan="3" bgcolor="#ffffff">
<?php if($yes_fcl){ $tmp = stripslashes($fcl->f('prodsdescr'));  } ?>
Short Product Description:<br />
<textarea name="sdescr0" rows="2" cols="70" onfocus="currfield='sdescr'">
<?php echo $tmp?></textarea><br />

<?php if($yes_fcl){ $tmp = stripslashes($fcl->f('proddescr')); } ?>
Product Description:<br />
<textarea name="descr0" rows="5" cols="70" onfocus="currfield='descr'">
<?php echo $tmp?></textarea><br />

<?php if($yes_fcl){ $tmp=stripslashes($fcl->f('installinst')); } ?>
Post Order Comments:<br />
<textarea name="installinst0" rows="5" cols="70" onfocus="currfield='installinst'">
<?php echo $tmp?></textarea><br />

Product Keywords:<br />
<textarea name="keyword0" rows="5" cols="70" onfocus="currfield='keywords'">
<?php if($yes_fcl){ echo $fcl->f('prodkeywords'); } ?></textarea><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Product Download URI:<br />
<i>should be out of the public web tree</i><br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="proddload<?php echo $i?>" size="40" value="<?php if($yes_fcl){ echo $fcl->f('proddload'); } ?>"
    onfocus="currfield='proddload'" /><br />
</td></tr>

<?php /* ?>
<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Product ISBN:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodisbn" size="40" value="<?php echo $fcp->f('prodisbn')?>"
    onfocus="currfield='prodisbn'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Author:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodauth0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodauth'); } ?>"
    onfocus="currfield='prodauth'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Author URL:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodauthurl0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodauthurl'); } ?>"
    onfocus="currfield='prodauthurl'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Product Lead Time Comments:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodleadtime0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodleadtime'); } ?>"
    onfocus="currfield='prodleadtime'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Product Material Code:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodmcode" size="40" value="<?php echo $fcp->f('prodmcode')?>"
    onfocus="currfield='prodmcode'" /><br />
</td></tr>
<?php */ ?>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Audio Clip URI:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="audio0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodaudio'); } ?>"
    onfocus="currfield='av'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Video Clip URI:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="video0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodvideo'); } ?>"
    onfocus="currfield='av'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Product Periodic Service Description:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="prodpersvc0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodpersvc'); } ?>"
    onfocus="currfield='prodpersvc'" />
</td></tr>

<tr><td valign="top" colspan="3" bgcolor="#ffffff">
<i>Graphic paths should be either relative to the installed cart (<b>./...</b>)
or absolute with respect to the top of the Web site (<b>/fishcart/...</b>).</i>
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Web Page Graphic URI:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="pic0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodpic'); } ?>"
    onfocus="currfield='pic'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Thumbnail Graphic URI:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="tpic0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodtpic'); } ?>"
    onfocus="currfield='tpic'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Banner Graphic URI:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="banr0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodbanr'); } ?>"
    onfocus="currfield='banr'" /><br />
</td></tr>

<tr><td valign="top" colspan="1" bgcolor="#ffffff">
Splash Graphic URI:<br />
</td><td colspan="2" bgcolor="#ffffff">
<input name="splash0" size="40" value="<?php if($yes_fcl){ echo $fcl->f('prodsplash'); } ?>"
    onfocus="currfield='splash'" /><br />
</td></tr>

<?php if( !$add_lang_only ){ ?>

<tr><td width="33%" valign="top" bgcolor="#ffffff">

<?php
$tmp = (int)$fcp->f('produseinvq');
if ($tmp == 1) {?>
<input type="radio" name="useinv" value="0" />NO
<input type="radio" name="useinv" value="1" checked="checked" />YES<br />
<?php } else {?>
<input type="radio" name="useinv" value="0" checked="checked" />NO
<input type="radio" name="useinv" value="1" />YES
<?php }?>
Use Inventory Qty?

<br /><br />

<?php
$tmp = (int)$fcp->f('prodflag1');
if ($tmp & $flag_persvc) {?>
<input type="radio" name="persvc" value="0" />NO
<input type="radio" name="persvc" value="1" checked="checked" />YES<br />
<?php } else {?>
<input type="radio" name="persvc" value="0" checked="checked" />NO
<input type="radio" name="persvc" value="1" />YES
<?php }?>
Periodic Service Product?

<br /><br />

<?php
$dl_checked = '';
$tmp = (int)$fcp->f('prodflag1');
if ($tmp & $flag_useesd) {
 $dl_checked = 'checked="checked"';
?>
<input type="radio" name="useesd" value="0" 
 onClick="toggleESD('off')" />NO
<input type="radio" name="useesd" value="1" checked="checked" 
 onClick="toggleESD('on')" />YES<br />
<?php } else {?>
<input type="radio" name="useesd" value="0" checked="checked" 
 onClick="toggleESD('off')" />NO
<input type="radio" name="useesd" value="1" 
 onClick="toggleESD('on')" />YES
<?php }?>
Downloadable Product?

<br /><br />

<?php
$tmp = (int)$fcp->f('prodflag1');
if ($tmp & $flag_genesd) {
?>
<input type="radio" name="genesd" value="1" <?php echo $dl_checked; ?> /> Use FishCart Download System<br />
<input type="radio" name="genesd" value="0" /> Use External Download System<br />
<?php } else {?>
<input type="radio" name="genesd" value="1" /> Use FishCart Download System<br />
<input type="radio" name="genesd" value="0" <?php echo $dl_checked; ?> /> Use External Download System
<?php }?>

<br /><br />

Charge shipping?<br />
<?php
$tmp=(int)$fcp->f('prodflag1');
if($tmp & $flag_noship){?>
<input type="radio" name="noship" value="1" checked="checked" />NO
<input type="radio" name="noship" value="0" />YES<br />
<?php } else {?>
<input type="radio" name="noship" value="1" />NO
<input type="radio" name="noship" value="0" checked="checked" />YES
<?php }?>

<br /><br />

Charge tax?<br />
<?php
$tmp = (int)$fcp->f('prodflag1');
if ($tmp & $flag_notax) {?>
<input type="radio" name="notax" value="1" checked="checked" />NO
<input type="radio" name="notax" value="0" />YES<br />
<?php } else {?>
<input type="radio" name="notax" value="1" />NO
<input type="radio" name="notax" value="0" checked="checked" />YES
<?php }?>

<br /><br />

Charge VAT?<br />
<?php
$tmp = (int)$fcp->f('prodflag1');
if ($tmp & $flag_novat) {?>
<input type="radio" name="novat" value="1" checked="checked" />NO
<input type="radio" name="novat" value="0" />YES<br />
<?php } else {?>
<input type="radio" name="novat" value="1" />NO
<input type="radio" name="novat" value="0" checked="checked" />YES
<?php }?>

<br /><br />

VAT Percent:<br /><i>(format 0.nn)</i><br />
<input name="prodvat" size="10" value="<?php echo $fcp->f('prodvat')?>"
    onfocus="currfield='prodvat'" />

<?php /* currently not used

<br /><br />

Processing Method?<br />
<?php 
if($yes_fcl){ $tmp = (int)$fcl->f('prodcharge'); }else{ $tmp = 0; }
if ($tmp == 0 || $tmp == 1 || $tmp == 2) {
    echo '<select name="prodcharge">'."\n"; 
    echo '<option value="0"'.(($tmp == 0) ? ' selected="selected"' :'').'>';
    echo 'Online Clearing</option>'."\n";
    echo '<option value="1"'.(($tmp == 1) ? ' selected="selected"' :'').'>';
    echo 'Authorize CC Only</option>'."\n";
    echo '<option value="2"'.(($tmp == 2) ? ' selected="selected"' :'').'>';
    echo 'Invoice Item</option>'."\n";
    echo '</select>'."\n"; 
}
*/
?>

</td>
<td width="33%" valign="top" bgcolor="#ffffff">

Max Order Qty:<br />
<input name="ordmax" size="10" value="<?php echo $fcp->f('prodordmax')?>"
    onfocus="currfield='prodordmax'" /><br />

Product Sequence #: <i>optional for alternate product sort ordering</i><br />
<input name="prodseq" size="10" value="<?php echo $fcp->f('prodseq')?>"
    onfocus="currfield='prodseq'" /><br />

Inventory Quantity:<br />
<input name="invqty" size="10" value="<?php echo $fcp->f('prodinvqty')?>"
    onfocus="currfield='invqty'" />

<br /><br />

Product Weight (format: 0.0000)<br />
<input name="prodweight" size="10" value="<?php echo $fcp->f('prodweight')?>"
    onfocus="currfield='prodweight'" />

<br /><br />

Product Width (format: 0.0000)<br />
<input name="prodwidth" size="10" value="<?php echo $fcp->f('prodwidth')?>"
    onfocus="currfield='prodwidth'" />

<br /><br />

Product Height (format: 0.0000)<br />
<input name="prodheight" size="10" value="<?php echo $fcp->f('prodheight')?>"
    onfocus="currfield='prodheight'" />

<br /><br />

Product Length (format: 0.0000)<br />
<input name="prodlength" size="10" value="<?php echo $fcp->f('prodlength')?>"
    onfocus="currfield='prodlength'" />

<br /><br />

<?php $tmp = (int)$fcp->f('prodflag1');
echo '<input type="checkbox" name="prodpackage" value="1"';
echo 'onfocus="currfield=\'prodpackage\'"';
if ($tmp & $flag_package) { echo ' checked="checked"'; }
echo ' />' ?>
&nbsp;Separate shipping package?<br />

</td><td width="33%" valign="top" bgcolor="#ffffff">

Setup Fee:<br />
<input name="setup" size="10" value="<?php echo $fcp->f('prodsetup')?>"
    onfocus="currfield='setup'" /><br />

Suggested Retail Price:<br /><i>up to 11 places</i><br />
<input name="rtlprice" size="10" value="<?php echo $fcp->f('prodrtlprice')?>"
    onfocus="currfield='rtlprice'" /><br />

Product Price:<br /><i>up to 11 places</i><br />
<input name="price" size="10" value="<?php echo $fcp->f('prodprice')?>"
    onfocus="currfield='price'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Product SKU: <i>required; must be unique</i><br />
<input name="sku" size="15" value="<?php echo $fcp->f('prodsku')?>"
    onfocus="currfield='sku'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Product Activate Date:<br />
<i>YY MM DD; may be empty</i><br />
<?php $sd = $fcp->f('prodstart');
if ($sd > 0) {?>
<input name="psy" size="5" value="<?php echo date('Y',$sd);?>"
    onfocus="currfield='proddate'" />
<input name="psm" size="2" value="<?php echo date('m',$sd);?>"
    onfocus="currfield='proddate'" />
<input name="psd" size="2" value="<?php echo date('d',$sd);?>"
    onfocus="currfield='proddate'" />
<?php } else {?>
<input name="psy" size="5" value="" onfocus="currfield='proddate'" />
<input name="psm" size="2" value="" onfocus="currfield='proddate'" />
<input name="psd" size="2" value="" onfocus="currfield='proddate'" />
<?php }?>
<br />

</td><td valign="top" bgcolor="#ffffff">

Product Deactivate Date:<br />
<i>YY MM DD; may be empty</i><br />
<?php $sd = $fcp->f('prodstop');
if ($sd > 0) {?>
<input name="pey" size="5" value="<?php echo date('Y',$sd);?>"
    onfocus="currfield='proddate'" />
<input name="pem" size="2" value="<?php echo date('m',$sd);?>"
    onfocus="currfield='proddate'" />
<input name="ped" size="2" value="<?php echo date('d',$sd);?>"
    onfocus="currfield='proddate'" />
<?php } else {?>
<input name="pey" size="5" value="" onfocus="currfield='proddate'" />
<input name="pem" size="2" value="" onfocus="currfield='proddate'" />
<input name="ped" size="2" value="" onfocus="currfield='proddate'" />
<?php }?>
<br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Sale Price:<br /><i>up to 11 places</i><br />
<input name="saleprice" size="10" value="<?php echo $fcp->f('prodsaleprice')?>"
    onfocus="currfield='saleprice'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Sale Start Date:<br /><i>YYYY MM DD</i><br />
<?php $sd = $fcp->f('prodsalebeg');
if ($sd > 0) {?>
<input name="ssy" size="5" value="<?php echo date('Y',$sd);?>"
    onfocus="currfield='saledate'" />
<input name="ssm" size="2" value="<?php echo date('m',$sd);?>"
    onfocus="currfield='saledate'" />
<input name="ssd" size="2" value="<?php echo date('d',$sd);?>"
    onfocus="currfield='saledate'" />
<?php } else {?>
<input name="ssy" size="5" value="" onfocus="currfield='saledate'" />
<input name="ssm" size="2" value="" onfocus="currfield='saledate'" />
<input name="ssd" size="2" value="" onfocus="currfield='saledate'" />
<?php }?>
<br />

</td><td valign="top" bgcolor="#ffffff">

Sale End Date:<br /><i>YYYY MM DD</i><br />
<?php $sd = $fcp->f('prodsaleend');
if ($sd > 0) {?>
<input name="sey" size="5" value="<?php echo date('Y',$sd);?>"
    onfocus="currfield='saledate'" />
<input name="sem" size="2" value="<?php echo date('m',$sd);?>"
    onfocus="currfield='saledate'" />
<input name="sed" size="2" value="<?php echo date('d',$sd);?>"
    onfocus="currfield='saledate'" />
<?php } else {?>
<input name="sey" size="5" value="" onfocus="currfield='saledate'" />
<input name="sem" size="2" value="" onfocus="currfield='saledate'" />
<input name="sed" size="2" value="" onfocus="currfield='saledate'" />
<?php }?>
<br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Setup Fee Sale Price:<br /><i>up to 11 places</i><br />
<input name="stsaleprice" size="10" value="<?php echo $fcp->f('prodstsaleprice')?>"
    onfocus="currfield='stsaleprice'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Setup Fee Sale Start Date:<br /><i>YYYY MM DD</i><br />
<?php $osd = $fcp->f('prodstsalebeg');
if ($osd > 0) {?>
<input name="stssy" size="5" value="<?php echo date('Y',$osd);?>"
    onfocus="currfield='stsaledate'" />
<input name="stssm" size="2" value="<?php echo date('m',$osd);?>"
    onfocus="currfield='stsaledate'" />
<input name="stssd" size="2" value="<?php echo date('d',$osd);?>"
    onfocus="currfield='stsaledate'" />
<?php } else {?>
<input name="stssy" size="5" value="" onfocus="currfield='stsaledate'" />
<input name="stssm" size="2" value="" onfocus="currfield='stsaledate'" />
<input name="stssd" size="2" value="" onfocus="currfield='stsaledate'" />
<?php }?>
<br />

</td><td valign="top" bgcolor="#ffffff">

Setup Fee Sale End Date:<br /><i>YYYY MM DD</i><br />
<?php $osd = $fcp->f('prodstsaleend');
if ($osd > 0) {?>
<input name="stsey" size="5" value="<?php echo date('Y',$osd);?>"
    onfocus="currfield='stsaledate'" />
<input name="stsem" size="2" value="<?php echo date('m',$osd);?>"
    onfocus="currfield='stsaledate'" />
<input name="stsed" size="2" value="<?php echo date('d',$osd);?>"
    onfocus="currfield='stsaledate'" />
<?php } else {?>
<input name="stsey" size="5" value="" onfocus="currfield='stsaledate'" />
<input name="stsem" size="2" value="" onfocus="currfield='stsaledate'" />
<input name="stsed" size="2" value="" onfocus="currfield='stsaledate'" />
<?php }?>
<br />

</td></tr>

<?php }else{ // if $add_lang_only ?>

<tr><td colspan="3" valign="top" bgcolor="#ffffff">
Select up to three categories for this product:<br />
</td></tr>

<?php
    $h = 0;       // iteration count
    $cat_count = 3;   // number of categories to show
    while ( $h<$cat_count ) {
?>
	<tr><td colspan="1" bgcolor="#ffffff">
    <select name="pc<?php echo $h.$i?>" size="1"
        onfocus="currfield='newcat'">
    <option value="0">Select A Category</option>
<?php 
        $j = 0;
        $ccount = 1;
        while ($j< $ccount) {
            // get the list of categories
            $get_cats  = new FC_SQL;
            $get_scats = new FC_SQL;
            $get_cats->query("select catval,catpath from cat ".
                "where catzid=$zoneid and catlid=$langid order by catpath");
            while ( $get_cats->next_record() ) {
                $patharray = explode(':',$get_cats->f('catpath'));
                $catlst = $get_cats->f('catval');
                print '<option value="'.$catlst.'">';
                while (list($key, $val) = each($patharray)) {
                    if ($val != '') {
                        $get_scats->query("select catdescr from cat ".
                            "where catzid=$zoneid and catlid=$langid and catval=$val");
                        if( $get_scats->next_record() ) {
                            print '/'.$get_scats->f('catdescr');
                            $get_scats->free_result();
                        } // if( $get_scats->next_record() )
                    } // if ($val != '')
                } // while (list($key, $val)=each($patharray))
                print  '</option>'."\n";
            } // while ( $get_cats->next_record() ) 
            $get_cats->free_result();   
?>
	</select>
<?php
            $j++;
        }  // while ($j<$ccount) 
?>
	</td><td colspan="2" bgcolor="#ffffff">
    Category Sequence Code: 
    <input name="pcatseq<?php echo $h.$i?>" size="5" onfocus="currfield='pcatseq'" />
	</td></tr>
<?php
        $h++;
    } // while ( $h<$cat_count ) 
?>

</td></tr>

<?php } // if $add_lang_only ?>

<tr><td colspan="3" align="center" valign="top" bgcolor="#FFFFFF">

<?php if( $add_lang_only ){ ?>
<input type="hidden" name="sku" value="<?php echo $fcp->f('prodsku')?>" />
<?php } ?>

<input type="hidden" name="cat_count" value="<?php echo $cat_count?>" />
<input type="hidden" name="add_lang_only" value="<?php echo $add_lang_only?>" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="hidden" name="prodlid0" value="<?php echo $langid?>" />
<input type="submit" value="Modify Product" onclick="closehelp()" />
<input type="reset" value="Clear Field" />


</td></tr>
<tr><td align="center" bgcolor="#FFFFFF" colspan="3">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
 onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
</table>
</form>
</center>

<?php
$fcp->free_result();
require('./footer.php');
?>
