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

header("Expires: ".GMDate("D, d M Y H:i:s")." GMT");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

$act = getparam('act');

$ssku   = getparam('ssku');
$sku    = getparam('sku');
$poptid = (int)getparam('poptid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');
require_once( BITCART_PKG_PATH.'flags.php');

if($zoneid==""||$langid==""){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;}

$fcp = new FC_SQL;
$fcg = new FC_SQL;

$fcp->query("select * from prodopt where poptid=$poptid and ".
            "poptlid=$langid and poptzid=$zoneid");
$fcp->next_record();

// create a javascript array of prodopt group names
echo "<script>\nvar jspoptgrp = new Array();\n";
$fcg->query(
	"select pgrpgrp,pgrpname from prodoptgrp ".
	"where pgrpzid=$zoneid and pgrplid=$langid order by pgrpgrp");
while( $fcg->next_record() ){
	$pgrpgrp = (int)$fcg->f('pgrpgrp');
	$pgrpname = $fcg->f('pgrpname');
	echo "jspoptgrp[\"$pgrpgrp\"] = \"$pgrpname\";\n";
}
$fcg->free_result();
echo "</script>\n";

?>

<h2 align=center>Modify A Product Option</h2>
<hr>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<b>
Modify A New Product Option<br />
</b>

</td></tr>
<tr><td valign=top align=left colspan=2 bgcolor="#FFFFFF">

<form method="post" name="poptupd" action="prodoptupd.php">

Base Product: <?php echo $ssku ?><br />

</td></tr>
<tr><td valign=top align=left colspan="1" bgcolor="#FFFFFF">

Name of this option:<br />
<input name=poptname size=30 value="<?php echo $fcp->f("poptname")?>"><br />

Short description of this option:<br />
<input name=poptsdescr size=30 value="<?php echo $fcp->f("poptsdescr")?>"><br />

Full description of this option:<br />
<textarea name=poptdescr rows=4 cols=40><?php echo $fcp->f("poptdescr")?></textarea><br />

</td><td colspan="1" valign="top" bgcolor="#FFFFFF">
<?php /*
Picture for this option:<br />
<input name=poptpic size=30 value="<?php echo $fcp->f("poptpic") ?>"><br />

Thumbnail picture for this option:<br />
<input name=popttpic size=30 value="<?php echo $fcp->f("popttpic")?>"><br />
*/ ?>
<table class="text" cellpadding="0" cellspacing="1" border="0" width="100%" bgcolor="#666666">
<tr><td class="subdivrow" colspan="3" align="center" bgcolor="#FFFFFF">
Option Price
</td></tr>
<tr><td colspan="3" valign="top" bgcolor="#FFFFFF">

Product Option Price:<br />
<input name="poptprice" size=10 value="<?php echo $fcp->f('poptprice')?>" onFocus="currfield='poptprice'"><br />

</td></tr>
<tr><td class="subdivrow" colspan="3" align="center" bgcolor="#FFFFFF">
Option Sale Price
</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

Sale Price:<br /><i>up to 11 places</i><br />
<input name="osaleprice" size="10" value="<?php echo $fcp->f('poptsaleprice')?>"
    onfocus="currfield='osaleprice'" /><br />

</td><td valign="top" bgcolor="#FFFFFF">

Sale Start Date:<br /><i>YYYY MM DD</i><br />
<?php $osd = $fcp->f('poptsalebeg');
if ($osd > 0) {?>
<input name="ossy" size="5" value="<?php echo date('Y',$osd);?>"
    onfocus="currfield='osaledate'" />
<input name="ossm" size="2" value="<?php echo date('m',$osd);?>"
    onfocus="currfield='osaledate'" />
<input name="ossd" size="2" value="<?php echo date('d',$osd);?>"
    onfocus="currfield='osaledate'" />
<?php } else {?>
<input name="ossy" size="5" value="" onfocus="currfield='osaledate'" />
<input name="ossm" size="2" value="" onfocus="currfield='osaledate'" />
<input name="ossd" size="2" value="" onfocus="currfield='osaledate'" />
<?php }?>
<br />

</td><td valign="top" bgcolor="#FFFFFF">

Sale End Date:<br /><i>YYYY MM DD</i><br />
<?php $osd = $fcp->f('poptsaleend');
if ($osd > 0) {?>
<input name="osey" size="5" value="<?php echo date('Y',$osd);?>"
    onfocus="currfield='osaledate'" />
<input name="osem" size="2" value="<?php echo date('m',$osd);?>"
    onfocus="currfield='osaledate'" />
<input name="osed" size="2" value="<?php echo date('d',$osd);?>"
    onfocus="currfield='osaledate'" />
<?php } else {?>
<input name="osey" size="5" value="" onfocus="currfield='osaledate'" />
<input name="osem" size="2" value="" onfocus="currfield='osaledate'" />
<input name="osed" size="2" value="" onfocus="currfield='osaledate'" />
<?php }?>
<br />

</td></tr>
<tr><td class="subdivrow" colspan="3" align="center" bgcolor="#FFFFFF">
Option Setup Fee
</td></tr>
<tr><td colspan="3" valign="top" bgcolor="#FFFFFF">

Product Setup Fee:<br />
<input name="poptsetup" size=10 value="<?php echo $fcp->f('poptsetup')?>" onFocus="currfield='poptsetup'"><br />

</td></tr>
<tr><td class="subdivrow" colspan="3" align="center" bgcolor="#FFFFFF">
Option Setup Fee Sale Price
</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

Setup Sale Price:<br /><i>up to 11 places</i><br />
<input name="ossaleprice" size="10" value="<?php echo $fcp->f('poptssaleprice')?>"
    onfocus="currfield='ossaleprice'" /><br />

</td><td valign="top" bgcolor="#FFFFFF">

Setup Sale Start Date:<br /><i>YYYY MM DD</i><br />
<?php $ossd = $fcp->f('poptssalebeg');
if ($ossd > 0) {?>
<input name="osssy" size="5" value="<?php echo date('Y',$ossd);?>"
    onfocus="currfield='ossaledate'" />
<input name="osssm" size="2" value="<?php echo date('m',$ossd);?>"
    onfocus="currfield='ossaledate'" />
<input name="osssd" size="2" value="<?php echo date('d',$ossd);?>"
    onfocus="currfield='ossaledate'" />
<?php } else {?>
<input name="osssy" size="5" value="" onfocus="currfield='ossaledate'" />
<input name="osssm" size="2" value="" onfocus="currfield='ossaledate'" />
<input name="osssd" size="2" value="" onfocus="currfield='ossaledate'" />
<?php }?>
<br />

</td><td valign="top" bgcolor="#FFFFFF">

Setup Sale End Date:<br /><i>YYYY MM DD</i><br />
<?php $ossd = $fcp->f('poptssaleend');
if ($ossd > 0) {?>
<input name="ossey" size="5" value="<?php echo date('Y',$ossd);?>"
    onfocus="currfield='ossaledate'" />
<input name="ossem" size="2" value="<?php echo date('m',$ossd);?>"
    onfocus="currfield='ossaledate'" />
<input name="ossed" size="2" value="<?php echo date('d',$ossd);?>"
    onfocus="currfield='ossaledate'" />
<?php } else {?>
<input name="ossey" size="5" value="" onfocus="currfield='ossaledate'" />
<input name="ossem" size="2" value="" onfocus="currfield='ossaledate'" />
<input name="ossed" size="2" value="" onfocus="currfield='ossaledate'" />
<?php }?>
<br />
</td></tr>
</table>
</td></tr>
<tr><td valign=top colspan=1 bgcolor="#FFFFFF">

<p>
<input name="poptskumod" size=10 maxsize=20 value="<?php echo $fcp->f("poptskumod")?>" onFocus="currfield='poptskumod'">
Product Option SKU <i>size=20</i>
</p>

<input type=radio name=skuact value=1<?php if($fcp->f("poptflag1")&$flag_poptskupre){?> checked<?php }?>
> Is the option SKU prepended to the base SKU?<br />

<input type=radio name=skuact value=2<?php if($fcp->f("poptflag1")&$flag_poptskusuf){?> checked<?php }?>
> Is the option SKU appended to the base SKU?<br />

<input type=radio name=skuact value=4<?php if($fcp->f("poptflag1")&$flag_poptskusub){?> checked<?php }?>
> Does the option SKU replace the base SKU entirely?<br />

<input type=radio name=skuact value=8<?php if($fcp->f("poptflag1")&$flag_poptskumod){?> checked<?php }?>
> Is the option SKU subsituted into the base SKU?

<p>
Base SKU pattern to be substituted with the option SKU<br />
<input type=text name=poptskusub size=10 value="<?php echo $fcp->f("poptskusub")?>">
</p>

</td><td valign=top bgcolor="#FFFFFF">

<p>
<input name="poptseq" size=10 value="<?php echo $fcp->f("poptseq")?>" onFocus="currfield='poptseq'">
Product Sequence <i>(arbitrary numeric value for ordering)</i>
</p>

<p>
<?php
$poptgrp = (int)$fcp->f('poptgrp');
$fcg->query(
	"select pgrpgrp,pgrpname from prodoptgrp ".
	"where pgrpzid=$zoneid and pgrplid=$langid order by pgrpgrp");
?>
<select name=poptgrp size=1 
 onChange="
  pgrpndx = document.poptupd.poptgrp.options.selectedIndex;
  pgrpgrp = document.poptupd.poptgrp.options[pgrpndx].value;
  pgrpnam = jspoptgrp[pgrpgrp];
  document.poptupd.pgrpname.value = pgrpnam;
  //document.poptupd.pgrpname.value = '';
  //alert('The currently stored product option group name will remain unchanged unless you enter a new group name below.');
 ">
<?php
while( $fcg->next_record() ){
	$pgrpgrp = (int)$fcg->f('pgrpgrp');
	$pgrpname = $fcg->f('pgrpname');
	//if( empty($pgrpname) ){
	//	$pgrpname = "Option Group $pgrpgrp";
	//}
	$sel='';
	if( $poptgrp == $pgrpgrp ){
		$sel = ' selected';
		// keep this for below
		$pgrpnamesel = $pgrpname;
	}
	echo "<option value=\"$pgrpgrp\"${sel}>$pgrpgrp: $pgrpname</option>\n";
}
$pgrpgrp++;		// next unused option group number
echo "<option value=\"$pgrpgrp\">$pgrpgrp: Create A New Option Group</option>\n";
?>
</select>
Product Option Group Value<br />
<i>(options with identical values are treated as being in the same group)</i>
</p>

<p>
<input type=text name=pgrpname size=20 value="<?php echo $pgrpnamesel; ?>">
Product Option Group Name<br />
<i>(displayed at the top of the option select list; to name or rename the 
group enter text in this field)</i>
</p>

Is this product option group required?<br />
<input type=checkbox name=grpreq value="<?php echo $flag_poptgrpreq?>"<?php if($fcp->f("poptflag1")&$flag_poptgrpreq){?> checked<?php }?>><br />

Can only one option in this group be chosen?<br />
<input type=checkbox name=grpexc value="<?php echo $flag_poptgrpexc?>"<?php if($fcp->f("poptflag1")&$flag_poptgrpexc){?> checked<?php }?>><br />

Is a quantity field needed for this group?<br />
<input type=checkbox name=grpqty value="<?php echo $flag_poptgrpqty?>"<?php if($fcp->f("poptflag1")&$flag_poptgrpqty){?> checked<?php }?>><br />

<p>
Is the price relative to the product base price, or is it absolute,
replacing the base product price?<br />
<i>(applies to entire group)</i><br />

<input type=radio name=prcrel value="<?php echo $flag_poptprcrel?>"<?php if($fcp->f("poptflag1")&$flag_poptprcrel){?> checked<?php }?>>Relative<br />
<input type=radio name=prcrel value=0<?php if(!($fcp->f("poptflag1")&$flag_poptprcrel)){?> checked<?php }?>>Absolute
</p>

<p>
<i>(if other product options exist with the above group number,
the above values will be set for the entire group)</i>
</p>

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type=hidden name=act value=update>
<input type=hidden name=poptid value=<?php echo $poptid?>>
<input type=hidden name=poptzid value=<?php echo $fcp->f("poptzid")?>>
<input type=hidden name=poptlid value=<?php echo $fcp->f("poptlid")?>>
<input type=hidden name=show value=<?php echo $show?>>
<input type=hidden name=ssku value=<?php echo $ssku?>>
<input type=hidden name=srch value=<?php echo $srch?>>
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type="submit" value="Modify Product Option" onClick="closehelp()">

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php  require('./footer.php'); ?>
