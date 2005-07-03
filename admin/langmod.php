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
$show   = (int)getparam('show');
$srch   = (int)getparam('srch');
$lngid  = (int)getparam('lngid');
// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$no_lang_iso=1;
require_once( BITCART_PKG_PATH.'languages.php');?>

<h2 align=center>Modify A Language Profile</h2>
<hr>
<p>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<b>
Modify A Language Profile<br />
</b>

</td></tr>
<tr><td colspan=2 bgcolor="#FFFFFF">

<?php 
$fcl = new FC_SQL;
$fcz = new FC_SQL;

$fcl->query("select * from lang where langid=$lngid"); 
$fcl->next_record('langterms');
?>

<form method="post" action="langupd.php">

<input type=hidden name=act value=update>

<input type=hidden name=srch value="<?php echo $srch?>">
<input type=hidden name=show value="<?php echo $show?>">

Language Profile Description:<br />
<input name="langdescr" size=50 onFocus="currfield='descr'"
 value="<?php echo $fcl->f("langdescr");?>"><br />

<? php /* unused
Front Page Template:<br />
<input name="langtmpl" size=16 onFocus="currfield='tmpl'"
 value="<?php echo $fcl->f("langtmpl");?>"><br />

Subcategory Template:<br />
<input name="langstmpl" size=16 onFocus="currfield='stmpl'"
 value="<?php echo $fcl->f("langstmpl");?>"><br />

Product Display Template:<br />
<input name="langtdsp" size=16 onFocus="currfield='tdsp'"
 value="<?php echo $fcl->f("langtdsp");?>"><br />

Error Template:<br />
<input name="langterr" size=16 onFocus="currfield='terr'"
 value="<?php echo $fcl->f("langterr");?>"><br />
 */ ?>

Show Cart Script:<br />
<input name="langshow" size=50 onFocus="currfield='show'"
 value="<?php echo $fcl->f("langshow");?>"><br />

Show Geography Script:<br />
<input name="langgeo" size=50 onFocus="currfield='geo'"
 value="<?php echo $fcl->f("langgeo");?>"><br />

Order Entry Script:<br />
<input name="langordr" size=50 onFocus="currfield='ordr'"
 value="<?php echo $fcl->f("langordr");?>"><br />

Order Processing Script:<br />
<input name="langproc" size=50 onFocus="currfield='proc'"
 value="<?php echo $fcl->f("langproc");?>"><br />

Final Order Script:<br />
<input name="langfinl" size=50 onFocus="currfield='finl'"
 value="<?php echo $fcl->f("langfinl");?>">
<p>

Language Assigned to This Zone:<br />
<font color="$ff0000"><i>if modified, change product descriptions in this language also<i></font><br />
<?php $sz=count($language_names);
 $currl=$fcl->f("langiso");
?>
<select name=langiso size=<?php echo $sz ?>>
<?php
ksort ($language_names);
reset ($language_names);
while ( list($key,$val) = each($language_names)){
	if( $currl == $key ){
		echo '<option value="'.$key.'" selected>'.$val."\n";
	}else{
		echo '<option value="'.$key.'">'.$val."\n";
	}
}
?>
</select>

<p>
Front page promotions category for this language:<br />
<select name="langfppromo" size="1">
<option value="">[select a category]</option>
<?php 
$fp_cat=(int)$fcl->f("langfppromo");
$get_cats = new FC_SQL;
$get_scats = new FC_SQL;
$get_cats->query("select catval,catpath from cat ".
	"where catzid=$zoneid and catlid=$lngid order by catpath");
while ($get_cats->next_record()){
	$patharray = explode(":",$get_cats->f("catpath"));
	$catlst=$get_cats->f("catval");
	$selected = '';
	if( $catlst == $fp_cat ){
		$selected = ' selected';
	}
	print "<option value=\"$catlst\"${selected}>";
	while (list($key, $val)=each($patharray))
	{
		if ($val != ""){
			$get_scats->query("select catdescr from cat ".
				"where catzid=$zoneid and catlid=$lngid and catval=$val");
			if( $get_scats->next_record() ){
				print '/'.$get_scats->f("catdescr");
				$get_scats->free_result();
			}
		}
	}
	print  "</option>\n";
}
$get_cats->free_result();	
?>
</select>
</p>

<p>
Welcome text shown on the front page:<br />
<i>full HTML markup required for proper formatting</i><br />
<input type="checkbox" name="fmtwelcome" value="1" checked>&nbsp;Preserve line break formatting?<br>
<textarea name="langwelcome" rows=6 cols=60 
 wrap=virtual onFocus="currfield='langwelcome'"><?php
 echo ereg_replace("<br[ /]*>","",$fcl->f("langwelcome"));
?></textarea>
</p>

<p>
Copyright, or extra text shown in page footer:<br />
<i>full HTML markup required for proper formatting</i><br />
<input type="checkbox" name="fmtcopy" value="1" checked>&nbsp;Preserve line break formatting?<br>
<textarea name="langcopy" rows=6 cols=60 
 wrap=virtual onFocus="currfield='langcopy'"><?php 
 echo ereg_replace("<br[ /]*>","",$fcl->f("langcopy"));
?></textarea>
</p>

<p>
Comments, terms, conditions, etc. shown in page footer:<br />
<i>full HTML markup required for proper formatting</i><br />
<input type="checkbox" name="fmtterms" value="1" checked>&nbsp;Preserve line break formatting?<br>
<textarea name="langterms" rows=6 cols=60 
 wrap=virtual onFocus="currfield='langterms'"><?php 
 echo ereg_replace("<br[ /]*>","",$fcl->f("langterms"));
?></textarea>
</p>

</td></tr>
<tr><td align=center colspan="2" valign=center bgcolor="#FFFFFF">

<input type=hidden name=langzid 
 value="<?php echo $fcl->f("langzid");?>">
<input type=hidden name=lngid  value="<?php echo $lngid  ?>">
<input type=hidden name=lngiso value="<?php echo $currl  ?>">
<input type=hidden name=zoneid value="<?php echo $zoneid ?>">
<input type=hidden name=langid value="<?php echo $langid ?>">
<input type="submit" value="Modify" onClick="closehelp()">
<input type="reset" value="Clear Form">

</form>

</td></tr>
<tr><td align=center colspan=2 bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return To Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
