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
$ascid = (int)getparam('ascid');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');   // includes flags.php

$fca = new FC_SQL;
?>

<h2 align="center">Modify A Associate Profile</h2>
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

<b>
Modify A Associate Profile<br />
</b>

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">

<?php 
$fca->query("select * from associate where ascid=$ascid"); 
$fca->next_record();
?>

<form method="post" action="ascupd.php">

<input type="hidden" name="act" value="update" />

<input type="hidden" name="ascid" value="<?php echo $ascid?>" />
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />

Select Web Profile for this Associate<br />
<?
  $fcw = new FC_SQL;
  $fcw->query("select count(*) as cnt from web ".
          "where webzid=$zoneid and weblid=$langid");
  $fcw->next_record();
  $wt=(int)$fcw->f("cnt");
  $fcw->free_result();
  $fcw->query("select * from web ".
        "where webzid=$zoneid and weblid=$langid order by webid");
?>
<?php  echo "ascwebid is " . $fca->f("ascwebid"). "<br />
count is $wt zoneid is $zoneid langid is $langid<br />";
?>
<select name="ascwebid" size="<?php echo $wt+1?>">
<option value="0" selected>[no web profile]</option>
<?
  while( $fcw->next_record() ){?>
   <option value="<?php echo $fcw->f("webid")?>" <?php if ($fcw->f("webid") == $fca->f("ascwebid")) {echo "SELECTED";} ?> >
   <?php echo $fcw->f("webdescr")?>
<?php  }
$fcw->free_result();
?>
</select>
<p></p>

Associate Name: <i>max 80 characters</i><br />
<input name="ascname" size="50" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascname")?>" /><br />

Associate Address 1: <i>max 80 characters</i><br />
<input name="ascaddr1" size="50" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascaddr1")?>" /><br />

Associate Address 2: <i>max 80 characters</i><br />
<input name="ascaddr2" size="50" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascaddr2")?>" /><br />

Associate City, State, ZIP, Country: <i>max [40,3,12] characters</i><br />
<input name="asccity" size="30" onFocus="currfield='asc'"
 value="<?php echo $fca->f("asccity")?>" />
<input name="ascstate" size="3" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascstate")?>" />
<input name="asczip" size="12" onFocus="currfield='asc'"
 value="<?php echo $fca->f("asczip")?>" />
<input name="ascnatl" size="3" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascnatl")?>" /><br />

<table border="0" cellpadding="3" class="text">
<tr><td bgcolor="#FFFFFF">

Associate E-Mail: <i>max 40 characters</i><br />
<input name="ascemail" size="40" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascemail")?>" /><br />

Associate Phone: <i>max 20 characters</i><br />
<input name="ascphone" size="20" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascphone")?>" /><br />

Associate Fax: <i>max 20 characters</i><br />
<input name="ascfax" size="20" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascfax")?>" /><br />

</td></tr>
</table>

Associate Service Name: <i>max 80 characters</i><br />
<input name="ascsvcname" size="50" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcname")?>" /><br />

Associate Service Address 1: <i>max 80 characters</i><br />
<input name="ascsvcaddr1" size="50" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcaddr1")?>" /><br />

Associate Service Address 2: <i>max 80 characters</i><br />
<input name="ascsvcaddr2" size="50" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcaddr2")?>" /><br />

Associate Service City, State, ZIP: <i>max [40,3,12] characters</i><br />
<input name="ascsvccity" size="30" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvccity")?>" />
<input name="ascsvcstate" size="3" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcstate")?>" />
<input name="ascsvczip" size="12" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvczip")?>" />
<input name="ascsvcnatl" size="3" onFocus="currfield='asc'"
 value="<?php echo $fca->f("ascsvcnatl")?>" /><br />

Associate Service E-Mail: <i>max 40 characters</i><br />
<input name="ascsvcemail" size="40" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcemail")?>" /><br />

Associate Service Phone: <i>max 20 characters</i><br />
<input name="ascsvcphone" size="20" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcphone")?>" /><br />

Associate Service Fax: <i>max 20 characters</i><br />
<input name="ascsvcfax" size="20" onFocus="currfield='ascsvc'"
 value="<?php echo $fca->f("ascsvcfax")?>" />

<p>

Online Order Script: <i>max 40 characters</i><br />
<input name="asconline" size="40" onFocus="currfield='asconline'"
 value="<?php echo $fca->f("asconline")?>" /><br />

Offline Order Script: <i>max 40 characters</i><br />
<input name="ascofline" size="40" onFocus="currfield='ascofline'"
 value="<?php echo $fca->f("ascofline")?>"><br />

Order Confirmation Script: <i>max 40 characters</i><br />
<input name="ascconfirm" size="40" onFocus="currfield='ascconfirm'"
 value="<?php echo $fca->f("ascconfirm")?>" /><br />

Order Email Address: <i>max 40 characters</i><br />
<input name="ascoemail" size="40" onFocus="currfield='ascoemail'"
 value="<?php echo $fca->f("ascoemail")?>" /><br />

</td></tr>
<tr><td align="center" valign="center" bgcolor="#FFFFFF">

<input type="submit" value="Modify" onClick="closehelp()"><br />
<input type="reset" value="Clear Form">

</form><br />

</td><td align="center" valign="center" bgcolor="#FFFFFF">

<a href="ascdoc.html"
 OnClick="openhelp('carthelp.html'); return false"
 OnMouseOver="img_act('xhelp'); return true"
 OnMouseOut="img_inact('xhelp'); return true">
<img src="sandhelpdim.gif" width="91" height="41" name="xhelp"
border="0" ></a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php  require('./footer.php');?>
