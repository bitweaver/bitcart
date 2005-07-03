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
// addslashes() for non-numbers, no exceptions

//if $zid & $lid are found, they should be changed
//to $zoneid and $langid. Once all maint files
//are done, $zid and $lid can probably be eliminated.

//$zid        =   (int)getparam('zid');
//$lid        =   (int)getparam('lid');
$zoneid     =   (int)getparam('zoneid');
$langid     =   (int)getparam('langid');
$val     			=   (int)getparam('val');
$cat								=			(int)getparam('cat');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

if($zoneid==""||$langid==""){?>
	Please click Back and select a zone and/or language.  Thank you.
<?php exit;} //end zoneid if statement ?>

<h2 align="center">Category Maintenance</h2>
<hr />
<p></p>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" bgcolor="#FFFFFF">

<b>
Modify An Existing Category<br />
</b>

</td><td align="center" bgcolor="#FFFFFF">

<b>
Delete An Existing Category<br />
</b>

</td></tr>
<tr><td align="center" valign="top" bgcolor="#FFFFFF">

<form name="catform" method="post" action="categorymod.php">

To modify an existing category,<br />
select its name from the list and<br />
click <i>Modify Selected Category.</i>
<br />

<?php 
$fcc = new FC_SQL;

$fcc->query("select count(*) as cnt from cat ".
	"where catzid=$zoneid and catlid=$langid and catunder=0"); 
$fcc->next_record();
$ct=(int)$fcc->f("cnt");
$fcc->free_result();

?>
<select name="cat" size="<?php echo $ct+1; ?>"
 onChange="document.catform.action='categorymod.php';submit();">
<option value="" selected>[no change]</option>
<?php  // get the categories to modify
$get_cats = new FC_SQL;
$get_scats = new FC_SQL;
$get_cats->query("select * from cat where catzid=$zoneid and catlid=$langid order by catpath");
	while ($get_cats->next_record()){
		
	$patharray = explode(":",$get_cats->f("catpath"));
		$catlst=(int)$get_cats->f("catval");
		print "<option value=\"$catlst\">$catlst: ";
		while (list($key, $val)=each($patharray))
		{
			if ($val != ""){
				$get_scats->query("select catval,catdescr from cat where catzid=$zoneid and catlid=$langid and catval=$val order by catval asc");
				$get_scats->next_record();
				print"/";
				print stripslashes($get_scats->f("catdescr"));
				} // end $val if statement
					
		} // end inner catlst while loop
		print  "</option>\n";
		} // end outer get_cats while loop
$get_cats->free_result();	
$get_scats->free_result();
?>
</select>
<p>
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Selected Category" /><br />

</form>

</td><td align="center" valign="top" bgcolor="#FFFFFF">

<form name="catdel" method="post" action="categoryupd.php">

<input type="hidden" name="act" value="delete" />

To delete an existing category,<br />
select its name from the list and<br />
click <i>Delete Selected Category.</i>
<br />

<select name="cat" size="<?php echo $ct+1; ?>">
<option value="" selected>[no change]</option>
<?php  // get the categories to delete
$get_cats = new FC_SQL;
$get_scats = new FC_SQL;
$get_cats->query("select * from cat where catzid=$zoneid and catlid=$langid order by catpath");
	while ($get_cats->next_record()){
		
	$patharray = explode(":",$get_cats->f("catpath"));
		$catlst=(int)$get_cats->f("catval");
		print "<option value=\"$catlst\">";
		while (list($key, $val)=each($patharray))
		{
			if ($val != ""){
				$get_scats->query("SELECT catval,catdescr FROM cat WHERE catzid=$zoneid and catlid=$langid and catval=$val ORDER BY catval ASC");
				$get_scats->next_record();
				print"/";
				print stripslashes($get_scats->f("catdescr"));
							} // end val if statement
					
		} // end inner catlst while loop
		print  "</option>\n";
		} // end outer get_cats while loop
$get_cats->free_result();	
$get_scats->free_result();
?>
</select>

<p>

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Delete Selected Category" /><br />
</p>

</form>
</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>

</table>
</center>

<?php require('./footer.php');?>
