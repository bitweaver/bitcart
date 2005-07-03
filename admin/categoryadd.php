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
// ==========  end of variable loading  ==========

require('./admin.php');


require('./header.php');
?>

<h2 align="center">Add A Category</h2>
<hr />
<p></p>

<center>
<table border="0" cellpadding="4" cellspacing="1" bgcolor="#666666" width="650" class="text">
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<a href="index.php?zoneid=<?php echo $zoneid?>&langid=<?php echo $langid?>"
 onClick="closehelp();">
Return to Central Maintenance Page</a><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<b>
Add A New Category<br />
</b>

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

<form method="post" action="categoryupd.php">

<input type="hidden" name="act" value="insert" />

Selected Zone: <?php 

$fctmp = new FC_SQL;

$fctmp->query("select zonedescr from zone ".
	"where zoneid=$zoneid order by zoneid"); 
$fctmp->next_record();
echo stripslashes($fctmp->f("zonedescr"));
$fctmp->free_result();
?><br />

Selected Language: <?php 
$fctmp->query("select langdescr from lang ".
	"where langzid=$zoneid and langid=$langid order by langid"); 
$fctmp->next_record();
echo stripslashes($fctmp->f("langdescr"));
$fctmp->free_result();
?><br />

</td></tr>
<tr><td align="left" colspan="2" bgcolor="#FFFFFF">

Select parent category:
<select name="catunder">
<option value="">[Top Level Category]</option>
<?php 
// get the list of categories
$get_cats = new FC_SQL;
$get_scats = new FC_SQL;
$get_cats->query("select catval,catpath from cat ".
	"where catzid=$zoneid and catlid=$langid order by catpath");
while ( $get_cats->next_record() ){
	$patharray = explode(":",$get_cats->f("catpath"));
	$catlst=$get_cats->f("catval");
	print "<option value=\"$catlst\">";
	while (list($key, $val)=each($patharray))
	{
		if ($val != ''){
			$get_scats->query("select catdescr from cat ".
				"where catzid=$zoneid and catlid=$langid and catval=$val");
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
</select><br />

Category Description:
<i>80 characters max</i><br />
<input name="descr" size="40" maxsize="80"
 onFocus="currfield='catdescr'" /><br />

Category Masthead Text:
<i>as long as desired</i><br />
<textarea name="catmast" wrap="virtual" rows="12" cols="60"
 onFocus="currfield='catmast'">
</textarea>

<p><i>
Graphic paths should be either relative to the installed cart (<b>./...</b>)<br />
or absolute with respect to the top of the Web site (<b>//fishcart/...</b>).</i>
</p>
<p>

Category Masthead Logo URI:<br />
<input name="logo" size="40" maxsize="50"
 onFocus="currfield='logo'" /><br />

Background Graphic URI:
<i>50 characters max</i><br />
<input name="back" size="40" maxsize="50"
 onFocus="currfield='back'" /><br />
 
 
Category Button Graphic URI:
<i>50 characters max</i><br />
<input name="button" size="40" maxsize="50" 
onFocus="currfield='button'" /><br />

Category Display Template: <i>16 characters max</i><br />
<i>overrides the language default template if given</i><br />
<input name="cattmpl" size="16" maxsize="16"
 onFocus="currfield='cattmpl'" /><br />

Category Sort Field:<br />
<select name="catsort" size="2" onFocus="currfield='catsort'">
<option value="prodsku" selected>Product SKU</option>
<option value="pcatseq">Product Sequence</option>
</select><br />

Products to Display per Page:<br />
<input name="prodpage" size="5" onFocus="currfield='prodpage'"
 value="5" /><br />

Subcategory Columns to Display per Page:<br />
<input name="catcols" size="5" onFocus="currfield='catcols'"
 value="5" /><br />

Zero Price Alternate Text Display:<br />
<font size="-1">
<i>example:<br />&lt;b&gt;&lt;i&gt;&lt;font color=&quot;#ff0000&quot;&gt;Free!&lt;/font&gt;&lt;/i&gt;&lt;/b&gt; is <b><font color="#ff0000">Free!</font></b></font><br />
<input name="catfree" size="40" onFocus="currfield='catfree'" />
<p></p>

<input type="checkbox" name="catact" value="1" onFocus="currfield='catact'" checked />
Is this category active? <i>(check if so)</i><br />

</td></tr>
<tr><td valign="top" colspan="2" bgcolor="#FFFFFF">
<a href="colors.html">Hexadecimal Color Chart</a>
</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

Background Color Hex Value:<br />
<input name="webbg" size="8" maxsize="6"
 onFocus="currfield='webbg'" /><br />

</td><td valign="top" bgcolor="#FFFFFF">

TEXT Color Hex Value:<br />
<input name="webtext" size="8" maxsize="6"
 onFocus="currfield='weblink'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#ffffff">

LINK Color Hex Value:<br />
<input name="weblink" size="8" maxsize="6"
 onFocus="currfield='weblink'" /><br />

</td><td valign="top" bgcolor="#FFFFFF">

VLINK Color Hex Value:<br />
<input name="webvlink" size="8" maxsize="6"
 onFocus="currfield='weblink'" /><br />

</td></tr>
<tr><td valign="top" bgcolor="#FFFFFF">

ALINK Color Hex Value:<br />
<input name="webalink" size="8" maxsize="6"
 onFocus="currfield='weblink'" /><br />

</td><td bgcolor="#FFFFFF">

Category Promotional SKU:<br />
<input name="sku" size="8" maxsize="50"
 onFocus="currfield='sku'" /><br />

</td></tr>
<tr><td align="center" colspan="2" bgcolor="#FFFFFF">

Category Promo Banner URI:<br />
<input name="banr" size="40" maxsize="50"
 onFocus="currfield='banr'" /><br />

</td></tr>
<tr><td colspan="2" align="center" bgcolor="#FFFFFF">

<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Add Category"
 onSubmit="closehelp();" />
<input type="reset" value="Clear Field" />

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
