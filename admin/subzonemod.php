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

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

$act    = getparam('act');

//$subzvendid = (int)getparam('subzvendid');
//$subzsid    = (int)getparam('subzsid');

$subzone    = getparam('subzone');
// ==========  end of variable loading  ==========

list($subzvendid,$subzsid)=explode(":",$subzone);
$subzvendid=(int)$subzvendid;
$subzsid=(int)$subzsid;

$fcs  = new FC_SQL;
$fcv  = new FC_SQL;
$fcz  = new FC_SQL;
$fcj  = new FC_SQL;
$fcsh = new FC_SQL;
?>

<h2 align="center">Modify A SubZone Profile</h2>
<hr />
<p></p>

<center>
<form method="post" action="subzoneupd.php">
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" class="text" width="650">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">

<?php 
$fcs->query("select * from subzone ".
	"where subzid=$zoneid and subzsid=$subzsid"); 
$fcs->next_record();
?>


<input type="hidden" name="act" value="update" />

SubZone Description: <i>max 80 characters</i><br />
<input name="subzdescr" size="50" onfocus="currfield='descr'"
    value="<?php echo $fcs->f('subzdescr')?>" /><br />

</td></tr>
<tr><td align="left" colspan="2" bgcolor="#FFFFFF" bgcolor="#FFFFFF">

Select parent subzone:
<select name="subzparent">
<option value="">[Top Level Subzone]</option>
<?php 
$currsubzparent = (int)$fcs->f('subzparent');
// get the list of subzones
$get_subz = new FC_SQL;
$get_subz->query("select subzsid,subzdescr from subzone ".
	"where subzid=$zoneid order by subzdescr");
while ( $get_subz->next_record() ){
	$pszid = (int)$get_subz->f('subzsid');
	if( $pszid == $currsubzparent ){
		$chk = ' selected';
	}else{
		$chk = '';
	}
	if( $pszid != $subzsid ){
		// don't show our own to prevent self-reference
		$pszidd = $get_subz->f('subzdescr');
		print  "<option value=\"$pszid\"${chk}>$pszidd</option>\n";
	}
}
$get_subz->free_result();	
?>
</select><br />

</td></tr>
<tr><td valign="top" align="center" bgcolor="#FFFFFF" width="50%">

SubZone Vendor ID:<br />
<select name="subzvendid" size="1">
<?php
$fcz -> query ("select vendname from vend where vendid=$subzvendid");
$fcz ->next_record();
echo '<option value="'.$subzvendid.'" selected="selected">'.$fcz->f('vendname').'</option>';
$fcv->query("select vendid,vendname from vend where vendzid=$zoneid and vendid!=$subzvendid"); 
while ( $fcv->next_record() ) {?>
    <option value="<?php echo $fcv->f('vendid')?>"><?php echo $fcv->f('vendname')?></option>
<?php 
}
$fcz->free_result();
$fcv->free_result();?>
</select>

</td><td valign="top" align="left" bgcolor="#FFFFFF">

<?php $fcsh->query(
	"select shipid,shipdescr from ship ".
	"where shipzid=$zoneid and shiplid=$langid");?>
Default Shipping Method:<br />
<select name="subzshipdef" size="1">
<option value="" selected="selected">[select a shipping profile]</option>
<?php 
while ( $fcsh->next_record() ) {
    $fcj->query("select shipdef from subzship ".
		"where shipid=".$fcsh->f('shipid').
		" and shipszid=$subzsid and shiplid=$langid"); 
    $fcj->next_record();
    if ($fcj->f('shipdef') == 1) { 
        $subzshipdef_selected = ' selected="selected"';
    } else {
        $subzshipdef_selected = '';
    }
    echo '<option value="'.$fcsh->f('shipid').'"'.$subzshipdef_selected.'>'.
        $fcsh->f('shipdescr')."</option>\n";
    $fcj->free_result();
}
$fcsh->free_result();
?>
</select><br />
<br />

Other Associated Shipping Methods:<br />
<?php
$fcsh->query(
	"select shipid,shipdescr from ship ".
	"where shipzid=$zoneid and shiplid=$langid"); 
while ( $fcsh->next_record() ) {
    $fcj->query("select count(*) as scnt from subzship ".
		"where shipid=".$fcsh->f('shipid').
		" and shipszid=$subzsid and shiplid=$langid"); 
    $fcj->next_record();
    if ($fcj->f('scnt') > 0) {
        $subzshipid_checked = ' checked="checked"';
    } else {
        $subzshipid_checked = '';
    }
    echo '<input type="checkbox" name="subzshipid[]" value="'.$fcsh->f('shipid').'"'.$subzshipid_checked.' />'.
        $fcsh->f('shipdescr')."<br />\n";
    $fcj->free_result();
}
$fcsh->free_result();
?>

</td></tr>
<tr><td valign="top" align="center" bgcolor="#FFFFFF">

Sales Tax Name Not On Shipping:<br />
<input name="subztaxcmtn" size="10" value="<?php echo $fcs->f('subztaxcmtn')?>"
    onfocus="currfield='taxper'" /></p>

<p>
Sales Tax Name On Shipping:<br />
<input name="subztaxcmts" size="10" value="<?php echo $fcs->f('subztaxcmts')?>"
    onfocus="currfield='taxper'" /></p>
<br />

</td><td valign="top" align="left" bgcolor="#FFFFFF">

Sales Tax Percentage Not On Shipping:<br />
<input name="subztaxpern" size="10" onfocus="currfield='taxper'"
    value="<?php echo $fcs->f('subztaxpern')?>" /></p>
<p>
Sales Tax Percentage On Shipping:<br />
<input name="subztaxpers" size="10" onfocus="currfield='taxper'"
    value="<?php echo $fcs->f('subztaxpers')?>" /></p>

<i>Split between the two fields to apply partial tax to shipping; 
only tax rates from the lowest level selected are applied, parent subzone
tax rates do not apply.  form: 0.nnnn</i>
<br />

</td></tr>
<tr><td colspan="2" align="center" valign="middle" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="subzsid" value="<?php echo $subzsid?>" />
<input type="hidden" name="subzwhsid" value="0" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Profile" onclick="closehelp()" />
<input type="reset" value="Clear Form" />

</form>
</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
