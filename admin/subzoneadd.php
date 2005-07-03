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

// ==========  end of variable loading  ==========

$fcs  = new FC_SQL;
$fcz  = new FC_SQL;
$fcsh = new FC_SQL;
?>

<h2 align="center">Add A SubZone Profile</h2>
<hr />
<p></p>

<center>
<form method="post" action="subzoneupd.php">
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#ffffff">


<input type="hidden" name="act" value="new" />

Selected Zone: <?php 
$zr=$fcz->query("select zonedescr from zone ".
	"where zoneid=$zoneid order by zoneid"); 
$fcz->next_record();
echo $fcz->f('zonedescr');
$fcz->free_result();
?><br />

</td></tr>
<tr><td colspan="2" bgcolor="#ffffff">

SubZone Description: <i>max 80 characters</i><br />
<input name="subzdescr" size="50" onfocus="currfield='descr'" /><br />

</td></tr>
<tr><td align="left" colspan="2" bgcolor="#FFFFFF">

Select parent subzone:
<select name="subzparent">
<option value="">[Top Level Subzone]</option>
<?php 
// get the list of subzones
$get_subz = new FC_SQL;
$get_subz->query("select subzsid,subzdescr from subzone ".
	"where subzid=$zoneid order by subzdescr");
while ( $get_subz->next_record() ){
	$pszid = (int)$get_subz->f('subzsid');
	$pszidd = $get_subz->f('subzdescr');
	print  "<option value=\"$pszid\">$pszidd</option>\n";
}
$get_subz->free_result();	
?>
</select><br />

</td></tr>
<tr><td valign="top" align="center" bgcolor="#ffffff">

SubZone Vendor ID:<br />
<select name="subzvendid" size="1">
<option value="" selected="selected">[select a vendor profile]</option>
<?php 
$fcs->query("select vendid,vendname from vend where vendzid=$zoneid"); 
while ( $fcs->next_record() ) {
    echo '<option value="'.$fcs->f('vendid').'">'.$fcs->f('vendname').'</option>';
}
$fcs->free_result();
?>
</select>

</td><td valign="top" align="left" bgcolor="#ffffff">

Default Shipping Method:<br />
<select name="subzshipdef" size="1">
<option value="" selected="selected">[select a shipping profile]</option>
<?php
$fcsh->query(
	"select shipid,shipdescr from ship ".
	"where shipzid=$zoneid and shiplid=$langid"); 
while ( $fcsh->next_record() ) {
    echo '<option value="'.$fcsh->f('shipid').'">'.$fcsh->f('shipdescr').'</option>';
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
while( $fcsh->next_record() ){
    echo '<input type="checkbox" name="subzshipid[]" value="'.$fcsh->f('shipid').'" />'.
        $fcsh->f('shipdescr')."<br />\n";
}
$fcsh->free_result();
?>

</td></tr>
<tr><td valign="top" align="center" bgcolor="#ffffff">

Sales Tax Name Not On Shipping:<br />
<input name="subztaxcmtn" size="10" onfocus="currfield='taxper'" /></p>
<p>
Sales Tax Name On Shipping:<br />
<input name="subztaxcmts" size="10" onfocus="currfield='taxper'" /></p>

</td><td valign="top" align="left" bgcolor="#ffffff">

Sales Tax Percentage Not On Shipping:<br />
<input name="subztaxpern" size="10" value="0.0"
    onfocus="currfield='taxper'" /></p>

Sales Tax Percentage On Shipping:<br />
<input name="subztaxpers" size="10" value="0.0"
    onfocus="currfield='taxper'" /><br />

<i>Split between the two fields to apply partial tax to shipping; 
only tax rates from the lowest level selected are applied, parent subzone
tax rates do not apply.  form: 0.nnnn</i>
<br />

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#ffffff">

<input type="hidden" name="subzwhsid" value="0" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add Profile" onclick="closehelp()" />
<input type="reset" value="Clear Form" />

</form>
</td></tr>
<tr><td align="center" colspan="2" bgcolor="#ffffff">

<a href="index.php?zoneid=<?php echo $zoneid?>&amp;langid=<?php echo $langid?>"
    onclick="closehelp();">
Return To Central Maintenance Page</a>

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
