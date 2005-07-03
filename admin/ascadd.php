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

require_once( BITCART_PKG_PATH.'functions.php');
// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');   // includes flags.php

$fcv = new FC_SQL;
$fcz = new FC_SQL;

if($zoneid==""){?>
	Please click Back and select a zone.  Thank you.
<?php exit;}
?>

<h2 align="center">Add An Associate Profile</h2>
<hr />
<p></p>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Add An Associate Profile<br />
</b>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

Selected Zone: <?php 
$fcz->query("select zonedescr from zone ".
	"where zoneid=$zoneid order by zoneid"); 
$fcz->next_record();?>
<?php echo $fcz->f("zonedescr")?><br />
<?php $fcz->free_result();?>

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">

<form method="post" action="ascupd.php">

<input type="hidden" name="act" value="new" />

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />

Select Web Profile for this Associate
<?
  $fcw = new FC_SQL;
  $fcw->query("select count(*) as cnt from web ".
	  "where webzid=$zoneid and weblid=$langid"); 
  $wt=(int)$fcw->f("cnt");
  $fcw->free_result();
  $fcw->query("select * from web ".
        "where webzid=$zoneid and weblid=$langid order by webid"); 
?>

<select name="ascwebid" size="<?php echo $wt+1?>">
<option value="0" selected>[no web profile]</option>
<?
  while( $fcw->next_record() ){?>
   <option value="<?php echo $fcw->f("webid")?>"><?php echo $fcw->f("webdescr")?></option>
<?php  }
$fcw->free_result();
?>
</select>
<p></p>


Associate Name: <i>max 80 characters</i><br />
<input name="ascname" size="50" onFocus="currfield='asc'"><br />

Associate Address 1: <i>max 80 characters</i><br />
<input name="ascaddr1" size="50" onFocus="currfield='asc'"><br />

Associate Address 2: <i>max 80 characters</i><br />
<input name="ascaddr2" size="50" onFocus="currfield='asc'"><br />

Associate City, State, ZIP, Country: <i>max [40,3,12] characters</i><br />
<input name="asccity" size="30" onFocus="currfield='asc'" />
<input name="ascstate" size="3" onFocus="currfield='asc'" />
<input name="asczip" size="12" onFocus="currfield='asc'" />
<input name="ascnatl" size="3" onFocus="currfield='asc'" /><br />

<table border="0" cellpadding="3" bgcolor="#FFFFFF" class="text">
<tr><td bgcolor="#FFFFFF">

Associate E-Mail: <i>max 40 characters</i><br />
<input name="ascemail" size="40" onFocus="currfield='asc'" /><br />

Associate Phone: <i>max 20 characters</i><br />
<input name="ascphone" size="20" onFocus="currfield='asc'"><br />

Associate Fax: <i>max 20 characters</i><br />
<input name="ascfax" size="20" onFocus="currfield='asc'" /><br />

</td></tr>
</table>

Associate Service Name: <i>max 80 characters</i><br />
<input name="ascsvcname" size="50" onFocus="currfield='ascsvc'" /><br />

Associate Service Address 1: <i>max 80 characters</i><br />
<input name="ascsvcaddr1" size="50" onFocus="currfield='ascsvc'" /><br />

Associate Service Address 2: <i>max 80 characters</i><br />
<input name="ascsvcaddr2" size="50" onFocus="currfield='ascsvc'"><br />

Associate Service City, State, ZIP: <i>max [40,3,12] characters</i><br />
<input name="ascsvccity" size="30" onFocus="currfield='ascsvc'" />
<input name="ascsvcstate" size="3" onFocus="currfield='ascsvc'" />
<input name="ascsvczip" size="12" onFocus="currfield='ascsvc'" />
<input name="ascsvcnatl" size=3 onFocus="currfield='asc'"><br />

Associate Service E-Mail: <i>max 40 characters</i><br />
<input name="ascsvcemail" size="40" onFocus="currfield='ascsvc'"><br />

Associate Service Phone: <i>max 20 characters</i><br />
<input name="ascsvcphone" size="20" onFocus="currfield='ascsvc'"><br />

Associate Service Fax: <i>max 20 characters</i><br />
<input name="ascsvcfax" size="20" onFocus="currfield='ascsvc'" />

<p></p>

Online Order Script: <i>max 40 characters</i><br />
<input name="asconline" size="40" onFocus="currfield='asconline'" /><br />

Offline Order Script: <i>max 40 characters</i><br>
<input name="ascofline" size="40" onFocus="currfield='ascofline'" /><br />

Order Confirmation Script: <i>max 40 characters</i><br />
<input name="ascconfirm" size="40" onFocus="currfield='ascconfirm'" /><br />

Order Email Address: <i>max 40 characters</i><br />
<input name="ascoemail" size="40" onFocus="currfield='ascoemail'" /><br />

</td></tr>
<tr><td align="center" valign="center" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add" onClick="closehelp()" /><br />
<input type="reset" value="Clear Form">

</form><br />

</td><td align="center" valign="center" bgcolor="#FFFFFF">

<a href="asc.html"
 OnClick="openhelp('carthelp.html'); return false"
 OnMouseOver="img_act('xhelp'); return true"
 OnMouseOut="img_inact('xhelp'); return true">
<img src="sandhelpdim.gif" width="91" height="41" name="xhelp" border="0" /></a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
