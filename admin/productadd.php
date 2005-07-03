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
require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.
// Note. $lid is used in this script in while-loop, though
// not as a global variable.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$ssku   = getparam('ssku');
$srch   = getparam('srch');

// ==========  end of variable loading  ==========

$fcc = new FC_SQL;  // category
$fcl = new FC_SQL;  // language
$fcp = new FC_SQL;  // unused
$fcs = new FC_SQL;  // unused
$fcw = new FC_SQL;  // web
$fcz = new FC_SQL;  // zone, language

if ($zoneid==''||$langid=='') {
    echo 'Please click Back and select a zone and/or language.  Thank you.';
    exit;
}

$fcw->query("select webdesctmpl from web ".
    "where webzid=$zoneid and weblid=$langid"); 
$fcw->next_record();

?>

<h2 align="center">Add A Product</h2>
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
   if( confirm('Warning: a product sale date has been set and '+
               'the sale price is zero.  '+
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
   if( confirm('Warning: a product setup sale date has been set and '+
               'the setup price is zero.  '+
               'To accept click OK, otherwise click Cancel.')){
    return true;
   }else{
    return false;
   }
  }
 ">
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="3" bgcolor="#ffffff">
<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
   onclick="closehelp();">
Return to Central Maintenance Page</a>

</td></tr>

<tr><td align="center" colspan="3" bgcolor="#ffffff">
<b>Add A New Product</b>
</td></tr>

<tr><td valign="top" align="center" colspan="3" bgcolor="#ffffff">


<input type="hidden" name="act" value="insert" />
<input type="hidden" name="show" value="<?php echo $show?>" />
<input type="hidden" name="ssku" value="<?php echo $ssku?>" />
<input type="hidden" name="srch" value="<?php echo $srch?>" />

Selected Zone: 
<?php 
$fcz->query("select zonedescr from zone ".
    "where zoneid=$zoneid order by zoneid"); 
$fcz->next_record();
echo $fcz->f('zonedescr');
$fcz->free_result();
?>
<br />

Selected Language: 
<?php 
$fcz->query("select langdescr from lang ".
    "where langzid=$zoneid and langid=$langid order by langid"); 
$fcz->next_record();
echo $fcz->f('langdescr');
$fcz->free_result();
?>
<br />

</td></tr>

<?php 
$fcl->query("select count(*) as cnt from lang where langzid=$zoneid");
$fcl->next_record();
$lt = (int)$fcl->f("cnt");
$fcl->free_result();
$fcl->query("select langdescr,langid from lang ".
    "where langzid=$zoneid order by langid"); 
$i = 0;
while ( $i < $lt) {
    $fcl->next_record();
    $ld  = $fcl->f('langdescr');
    $lid = $fcl->f('langid');
    echo '<tr><td align="center" colspan="3" bgcolor="#ffffff"><b>Language: '.$ld.'</b><br /></td></tr>';?>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Product Name:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodname<?php echo $i?>" size="40"
        onfocus="currfield='prodname'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="3" bgcolor="#ffffff">
    Short Product Description:<br />
    <textarea name="sdescr<?php echo $i?>" rows="2" cols="70"
        onfocus="currfield='descr'"></textarea><br />

    Product Description:<br />
    <textarea name="descr<?php echo $i?>" rows="5" cols="70"
        onfocus="currfield='descr'"><?php echo $fcw->f("webdesctmpl")?></textarea><br />

    Post Order Comments:<br />
    <textarea name="installinst<?php echo $i?>" rows="5" cols="70"
        onfocus="currfield='installinst'"><?php echo $fcw->f("webdesctmpl")?>
    </textarea><br />
    
    Product Keywords:<br />
    <textarea name="keyword<?php echo $i?>" rows="5" cols="70"
      onfocus="currfield='keywords'"></textarea><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Product Download URI:<br />
    <i>should be out of the public web tree</i><br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="proddload<?php echo $i?>" size="40"
        onfocus="currfield='proddload'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Product Offer # / Motivation Code:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodoffer<?php echo $i?>" size="40"
         onfocus="currfield='prodoffer'" /><br />
	</td></tr>

<?php /* ?>
    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Product ISBN:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodisbn" size="40"
        onfocus="currfield='prodisbn'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Author:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodauth<?php echo $i?>" size="40"
         onfocus="currfield='prodauth'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Author URL:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodauthurl<?php echo $i?>" size="40"
         onfocus="currfield='prodauthurl'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Product Lead Time Comments:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodleadtime<?php echo $i?>" size="40"
         onfocus="currfield='prodleadtime'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Product Material Code:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="prodmcode" size="40"
         onfocus="currfield='prodmcode'" /><br />
	</td></tr>
<?php */ ?>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Audio Clip URI:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="audio<?php echo $i?>" size="40"
         onfocus="currfield='av'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Video Clip URI:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="video<?php echo $i?>" size="40"
         onfocus="currfield='av'" />
	</td></tr>

    <tr><td valign="top" colspan="3" bgcolor="#ffffff">
<i>Graphic paths should be either relative to the installed cart (<b>./...</b>) 
or absolute with respect to the top of the Web site (<b>/fishcart/...</b>).</i>
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Web Page Graphic URI:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="pic<?php echo $i?>" size="40"
        onfocus="currfield='pic'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Thumbnail Graphic URI:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="tpic<?php echo $i?>" size="40"
        onfocus="currfield='tpic'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Banner Graphic URI:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="banr<?php echo $i?>" size="40"
        onfocus="currfield='banr'" /><br />
	</td></tr>

    <tr><td valign="top" colspan="1" bgcolor="#ffffff">
    Sale Graphic URI:<br />
	</td><td colspan="2" bgcolor="#ffffff">
    <input name="splash<?php echo $i?>" size="40"
        onfocus="currfield='splash'" /><br />
    </td></tr>

<tr><td align="left" colspan="3" bgcolor="#ffffff">

<?php

    $ccount = 0;
    $fcc->query("select catval,catdescr from cat ".
        "where catzid=$zoneid"); 
    while ( $fcc->next_record() ) {
        // pull all category info into arrays
        $car[]  = $fcc->f('catval');
        $card[] = $fcc->f('catdescr');
         
        $ccount++;

    } // while ( $fcc->next_record() ) 
    $fcc->free_result();

?>

    Select up to three categories for this product:<br />

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
                "where catzid=$zoneid and catlid=$lid order by catpath");
            while ( $get_cats->next_record() ) {
                $patharray = explode(':',$get_cats->f('catpath'));
                $catlst = $get_cats->f('catval');
                print '<option value="'.$catlst.'">';
                while (list($key, $val) = each($patharray)) {
                    if ($val != '') {
                        $get_scats->query("select catdescr from cat ".
                            "where catzid=$zoneid and catlid=$lid and catval=$val");
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

    <input type="hidden" name="prodlid<?php echo $i?>" value="<?php echo $lid?>" />
    
    <?php $i++;
} // while ( $i < $lt) 
?>

</td></tr>

<!-- nmb start -->
<tr><td width="33%" valign="top" bgcolor="#ffffff">

Use Inventory Quantity Field?<br />
<input type="radio" name="useinv" value="1" />YES
<input type="radio" name="useinv" value="0" checked="checked" />NO

<br /><br />

Periodic Service Product?<br />
<input type="radio" name="persvc" value="1" />YES
<input type="radio" name="persvc" value="0" checked="checked" />NO

<br /><br />

Downloadable Product?<br />
<input type="radio" name="useesd" value="1" 
 onClick="toggleESD('on')" />YES
<input type="radio" name="useesd" value="0" checked="checked" 
 onclick="toggleESD('off')" />NO

<input type="radio" name="genesd" value="1" /> Use FishCart Download System<br />
<input type="radio" name="genesd" value="0" /> Use External Download System<br />

<br /><br />

Charge shipping?<br />
<input type="radio" name="noship" value="0" checked="checked" />YES
<input type="radio" name="noship" value="1" />NO

<br /><br />

Charge tax?<br />
<input type="radio" name="notax" value="0" checked="checked" />YES
<input type="radio" name="notax" value="1" />NO

<br /><br />

Charge VAT?<br />
<input type="radio" name="novat" value="0" />YES
<input type="radio" name="novat" value="1" checked="checked" />NO

<br /><br />

VAT Percent:<br /><i>(format 0.nn)</i><br />
<input name="prodvat" size="10" 
    onfocus="currfield='prodvat'" /><br />

<?php /* currently not used
<br /><br />

Processing Method?<br />
<select name="prodcharge"> 
    <option value="0">Online Clearing</option>
    <option value="1">Authorize CC Only</option>
    <option value="2">Invoice Item</option>
</select> 
*/?>

</td>
<td width="33%" valign="top" bgcolor="#ffffff">

Max Order Qty:<br />
<input name="ordmax" size="10" onfocus="currfield='prodordmax'" /><br />

Product Sequence #: <i>optional for alternate product sort ordering</i><br />
<input name="prodseq" size="10" onfocus="currfield='prodseq'" /><br />

Inventory Quantity:<br />
<input name="invqty" size="10" onfocus="currfield='invqty'" />

<br /><br />

Product Weight (format: 0.0000)<br />
<input name="prodweight" size="10" onfocus="currfield='prodweight'" />

<br /><br />

Product Width (format: 0.0000)<br />
<input name="prodwidth" size="10" onfocus="currfield='prodwidth'" />

<br /><br />

Product Height (format: 0.0000)<br />
<input name="prodheight" size="10" onfocus="currfield='prodheight'" />

<br /><br />

Product Length (format: 0.0000)<br />
<input name="prodlength" size="10" onfocus="currfield='prodlength'" />

<br /><br />

<input type="checkbox" name="prodpackage" value="1" 
 onfocus="currfield='prodpackage'" />&nbsp;Separate shipping package?

</td><td width="33%" valign="top" bgcolor="#ffffff">

Setup Fee:<br />
<input name="setup" size="10" onfocus="currfield='setup'" /><br />

Suggested Retail Price:<br />
<input name="rtlprice" size="10" onfocus="currfield='price'" /><br />

Product Price:<br />
<input name="price" size="10" onfocus="currfield='price'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Product SKU: <i>required; must be unique</i><br />
<input name="sku" size="10" onfocus="currfield='sku'" /><br />

</td><td valign="top" bgcolor="#ffffff">
Product Activate Date:<br />
<i>YY MM DD; may be empty</i><br />
<input name="psy" size="5" onfocus="currfield='proddate'" />
<input name="psm" size="2" onfocus="currfield='proddate'" />
<input name="psd" size="2" onfocus="currfield='proddate'" />
<br />

</td><td valign="top" bgcolor="#ffffff">

Product Deactivate Date:<br />
<i>YY MM DD; may be empty</i><br />
<input name="pey" size="5" onfocus="currfield='proddate'" />
<input name="pem" size="2" onfocus="currfield='proddate'" />
<input name="ped" size="2" onfocus="currfield='proddate'" />
<br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Sale Price:<br />
<input name="saleprice" size="10" onfocus="currfield='saleprice'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Sale Start Date:<br /><i>YY MM DD</i><br />
<input name="ssy" size="5" onfocus="currfield='saledate'" />
<input name="ssm" size="2" onfocus="currfield='saledate'" />
<input name="ssd" size="2" onfocus="currfield='saledate'" />
<br />

</td><td valign="top" bgcolor="#ffffff">

Sale End Date:<br /><i>YY MM DD</i><br />
<input name="sey" size="5" onfocus="currfield='saledate'" />
<input name="sem" size="2" onfocus="currfield='saledate'" />
<input name="sed" size="2" onfocus="currfield='saledate'" />
<br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

Setup Fee Sale Price:<br />
<input name="stsaleprice" size="10" onfocus="currfield='stsaleprice'" /><br />

</td><td valign="top" bgcolor="#ffffff">

Setup Fee Sale Start Date:<br /><i>YY MM DD</i><br />
<input name="stssy" size="5" onfocus="currfield='stsaledate'" />
<input name="stssm" size="2" onfocus="currfield='stsaledate'" />
<input name="stssd" size="2" onfocus="currfield='stsaledate'" />
<br />

</td><td valign="top" bgcolor="#ffffff">

Setup Fee Sale End Date:<br /><i>YY MM DD</i><br />
<input name="stsey" size="5" onfocus="currfield='stsaledate'" />
<input name="stsem" size="2" onfocus="currfield='stsaledate'" />
<input name="stsed" size="2" onfocus="currfield='stsaledate'" />
<br />

</td></tr>

<tr><td colspan="3" align="center" bgcolor="#ffffff">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="hidden" name="cat_count" value="<?php echo $cat_count?>" />
<input type="submit" value="Add Product" onclick="closehelp()" />
<input type="reset" value="Clear Field" />

</form>
</td></tr>
<tr><td align="center" colspan="3" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
     onclick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php  require('./footer.php'); ?>
