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

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

$zid        =   (int)getparam('zid');
$lid        =   (int)getparam('lid');
$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
$ssku       =   getparam('ssku');
$srch       =   getparam('srch');
// ==========  end of variable loading  ==========

require('./admin.php');
require('header.php');

$fcl = new FC_SQL;
$fcpl = new FC_SQL;

if(!$zoneid || !$langid){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}?>

<h2 align=center>Product Maintenance</h2>
<hr>

<?php 
$prsub="where prodzid=$zoneid";
$plsub="where prodlzid=$zoneid and prodlid=$langid";
$subset=0;
if(strlen($ssku)>0){
 $prsub.=" and prodsku like ";
 $plsub.=" and prodlsku like ";
 if($srch=="b"){
  $prsub.="'$ssku%'";
  $plsub.="'$ssku%'";
 }elseif($srch=="c"){
  $prsub.="'%$ssku%'";
  $plsub.="'%$ssku%'";
 }elseif($srch=="e"){
  $prsub.="'%$ssku'";
  $plsub.="'%$ssku'";
 }else{
  $prsub.="'$ssku'";
  $plsub.="'$ssku'";
 }
 $subset=1;
}

$fcl->query("select count(*) as cnt from prod $prsub"); 
$fcl->next_record();
$pr=(int)$fcl->f('cnt');
$fcl->free_result();

$fcl->query("select count(*) as cnt from prodlang $plsub"); 
$fcl->next_record();
$pt=(int)$fcl->f('cnt');
$fcl->free_result();

if ( $pt > 200 ) {?>
Over 200 products were selected by your choice.  Please click the Back
button and qualify your search by entering a partial SKU and choosing
the appropriate Begins With, Contains or Ends With option.
<?php exit;
}
?>

<center>
<table border=0 cellpadding=4 cellspacing=1 bgcolor=#666666 width=650 class="text">
<tr><td align="center" colspan="2" valign="middle" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
<tr><td align=center valign=top bgcolor=#ffffff>

<b>Modify An Existing Product</b>

</td><td align="center" valign="top" bgcolor="#ffffff">

<b>Delete An Existing Product</b>

</td></tr>
<tr><td align=center valign=top bgcolor=#ffffff>

<form name=prodmod method="post" action="productmod.php">

<input type=hidden name=act value=update>
<input type=hidden name=show value=<?php if( isset( $show ) ) { echo $show; }?>>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>

<?php if($ssku==""||$subset>0){?>

To modify an existing product,<br>
select its name from the list and<br>
click <i>Modify Product.</i>
<br>

<?php 
// select products without taking language into account
// to cover when a language has been added and prodlang
// profiles don't yet exist for products
$fcl->query("select prodsku from prod $prsub order by prodsku asc"); 
?>

<select name=sku size="<?php echo min(20,$pr+1)?>"
 onChange="document.prodmod.action='productmod.php';submit();">
 onFocus="helpfocus('skumod');">
<option value="" selected>[no change]
<?php
while( $fcl->next_record() ){
 $tsku=$fcl->f('prodsku');
 $fcpl->query("select prodlsku,prodname from prodlang ".
	"where prodlsku='$tsku' and prodlid=$langid"); 
 if( $fcpl->next_record() ){
  $tmp=$fcpl->f('prodname');
  $tmp=substr(ereg_replace("<[A-Za-z0-9/\=\"\+\#\% ]*>"," ",$tmp),0,30);
  $fcpl->free_result();
 }else{
  $tmp="no language profile found";
 }
 echo '<option value="'.$tsku.'">'.$tsku.': '.$tmp;
}
?>
</select>

<?php }else{?>

Enter the SKU to modify:<br>
<input name=sku size="10"
 onFocus="currfield='sku';"><br>

<?php }?>

<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>

<input type="submit" value="Modify Product"
 onClick="closehelp()"><br>

</form>

</td><td align=center valign=top bgcolor=#ffffff>

<form METHOD="POST" action="productupd.php">

<input type=hidden name=act value=delete>
<input type=hidden name=show value=<?php if( isset( $show ) ) { echo $show; }?>>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>

<?php if($ssku==""||$subset>0){?>

To delete an existing product,<br>
select its name from the list and<br>
click <i>Delete Product.</i>
<br>

<?php 
$fcl->query("select prodlsku,prodname from prodlang ".
	"$plsub order by prodlsku"); 

?>

<select name=sku size="<?php echo min(20,$pt+1)?>"
 onFocus="currfield='skumod'">
<option value="" selected>[no change]
<?php while( $fcl->next_record() ){
 $tmp=stripslashes($fcl->f("prodname"));
 $tmp=ereg_replace("<[A-Za-z0-9/\=\"\+\#\% ]*>"," ",$tmp);?>
<option value="<?php echo $fcl->f("prodlsku")?>"><?php echo $fcl->f("prodlsku")?>: <?php echo substr($tmp,0,30)?>
<?php }?>
</select>
<p>

<?php }else{?>

Enter the SKU to delete:<br>
<input name=sku size="10"
 onFocus="currfield='sku'"><br>

<?php }?>

<p>

<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>

<input type="submit" value="Delete Product"
 onClick="closehelp()"><br>

</form>

</td></tr>
<tr><td align=center valign=top colspan=2 bgcolor=#ffffff>

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp()">
Return To Central Maintenance Page</a><br>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
