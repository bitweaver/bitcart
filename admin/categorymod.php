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
$cat								=			getparam('cat');
// ==========  end of variable loading  ==========
require('./admin.php');

require('./header.php');
?>

<h2 align="center">Modify A Category</h2>
<hr />
<p></p>

<?php 
$fcc = new FC_SQL;

$fcc->query("select * from cat where catval=$cat ".
	"and catzid=$zoneid and catlid=$langid order by catval"); 
if( !$fcc->next_record() ){?>
  The selected category could not be found.  This may reflect an
  inconsistent database; please check with your system
  administrator.
  <?php $fcc->free_result();
  exit;
} // end if statement?>

<center>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#666666" class="text" width="650">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="categoryndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Category Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Modify A Category<br />
</b>

</td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF">
<form method="post" action="categoryupd.php">

<input type="hidden" name="act" value="update" />
<input type="hidden" name="cat" value="<?php echo $cat?>" />

Select parent category:
<select name="catunder" size="1">
<?php
$catunder=(int)$fcc->f("catunder");
$fct = new FC_SQL;
$fct->query("select catval,catdescr from cat order by catdescr"); 
?>
<option value="0">[top level category]
<?php 
while( $fct->next_record() ){
	$catval=(int)$fct->f("catval");
	if( $catval == $cat ){
		continue;	// don't show this cat to avoid self reference
	}
	if( $catval == $catunder ){
		echo "<option value=\"$catval\" selected>";
	}else{
		echo "<option value=\"$catval\">";
	}
	echo stripslashes(substr($fct->f("catdescr"),0,30) . "\n");
}
$fct->free_result();
?>
</select><br />

Category Description:
<i>80 characters max</i><br />
<input name="descr" size="40" maxsize="80"
 value="<?php echo stripslashes($fcc->f("catdescr"))?>"
 onFocus="currfield='catdescr'" /><br />

Category Masthead Text:
<i>as long as desired</i><br />
<textarea name="catmast" wrap="virtual" rows="12" cols="60"
 onFocus="currfield='catmast'">
<?php echo stripslashes($fcc->f("catmast"))?>
</textarea>

<p><i>
Graphic paths should be either relative to the installed cart (<b>./...</b>)<br />
or absolute with respect to the top of the Web site (<b>//fishcart/...</b>).</i>
</p>
<p>

Category Masthead Logo URI:<br />
<input name="logo" size="40" maxsize="50"
 value="<?php echo $fcc->f("catlogo")?>"
 onFocus="currfield='logo'" /><br />

Background Graphic URI:
<i>50 characters max</i><br />
<input name="back" size="40" maxsize="50"
 value="<?php echo $fcc->f("catback")?>"
 onFocus="currfield='back'" /><br />


Category Button Graphic URI:
<i>50 characters max</i><br />
<input name="button" size="40" maxsize="50"
value="<?php echo $fcc->f("catbutton")?>"
 onFocus="currfield='button'" /><br /> 
 
Category Display Template: <i>16 characters max</i><br />
<i>overrides the language default template if given</i><br />
<input name="cattmpl" size="16" maxsize="16"
 value="<?php echo $fcc->f("cattmpl")?>"
 onFocus="currfield='cattmpl'" /><br />

<?php $tmp=$fcc->f("catsort");?>
Category Sort Field:<br />
<select name="catsort" size="2" onFocus="currfield='catsort'">
<option value="prodsku"<?php if($tmp=="prodsku"){echo " selected";}?>>Product SKU</option>
<option value="pcatseq"<?php if($tmp=="pcatseq"){echo " selected";}?>>Product Sequence</option>
</select><br />

Products to Display per Page:<br />
<input name="prodpage" size="5" onFocus="currfield='prodpage'"
 value="<?php echo (int)$fcc->f("catprodpage");?>" /><br />

Subcategory Columns to Display per Page:<br />
<input name="catcols" size="5" onFocus="currfield='catcols'"
 value="<?php echo (int)$fcc->f("catcols");?>" /><br />

<?php 
$tmp=ereg_replace("\"","&quot;",$fcc->f("catfree"));
?>
Zero Price Alternate Text Display: <i>example<br />
&lt;b&gt;&lt;i&gt;&lt;font color=&quot;#ff0000&quot;&gt;Free!&lt;/font&gt;&lt;/i&gt;&lt;/b&gt; is <b><font color="#ff0000">Free!</font></b><br /></i>
<input name="catfree" size="40" onFocus="currfield='catfree'"
 value="<?php echo $tmp?>" />
</p>
<p>

<?php (int)$tmp=$fcc->f("catact");
if($tmp==0){?>
<input type="checkbox" name="catact" value="1" onFocus="currfield='catact'" />
<?php }else{?>
<input type="checkbox" name="catact" value="1" checked onFocus="currfield='catact'" />
<?php } // end if statement ?>
Is this category active? <i>(check if so)</i><br />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#FFFFFF">
<a href="colors.html">Hexadecimal Color Chart</a>
</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

Background Color Hex Value:<br />
<input name="webbg" size="8" maxsize="6"
 value="<?php echo stripslashes($fcc->f("catbg"))?>"
 onFocus="currfield='webbg'" /><br />

</td><td valign="top" bgcolor="#FFFFFF">

TEXT Color Hex Value:<br />
<input name="webtext" size="8" maxsize="6"
 value="<?php echo stripslashes($fcc->f("cattext"))?>"
 onFocus="currfield='weblink'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

LINK Color Hex Value:<br />
<input name="weblink" size="8" maxsize="6"
 value="<?php echo stripslashes($fcc->f("catlink"))?>"
 onFocus="currfield='weblink'" /><br />

</td><td valign="top" bgcolor="#FFFFFF">

VLINK Color Hex Value:<br />
<input name="webvlink" size="8" maxsize="6"
 value="<?php echo stripslashes($fcc->f("catvlink"))?>"
 onFocus="currfield='weblink'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

ALINK Color Hex Value:<br />
<input name="webalink" size="8" maxsize="6"
 value="<?php echo stripslashes($fcc->f("catalink"))?>"
 onFocus="currfield='weblink'" /><br />

</td><td bgcolor="#FFFFFF">

Category Promotional SKU:<br />
<input name="sku" size="8" maxsize="50"
 value="<?php echo stripslashes($fcc->f("catsku"))?>"
 onFocus="currfield='sku'" /><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

Category Promo Banner URI:<br />
<input name="banr" size="40" maxsize="50"
 value="<?php echo $fcc->f("catbanr")?>"
 onFocus="currfield='banr'" /><br />

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Modify Category"
 onSubmit=""closehelp(); />
<input type="reset" value="Clear Field" />

</form>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="categoryndx.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Category Maintenance Page</a><br />

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
</table>
</center>

<?php require('./footer.php');?>
