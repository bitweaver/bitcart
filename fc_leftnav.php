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

if(empty($pub_inc)){
 require('./public.php');
}
if(empty($cartid_inc)){
 require('./cartid.php');
}
if(empty($lang_inc)){
 require('./languages.php');
}
if(empty($flags_inc)){
 require('./flags.php');
}

// required new FC_SQLs

$fczone = new FC_SQL;
$fcnewprod = new FC_SQL;
$fccloseout = new FC_SQL;
$fclang = new FC_SQL;
$fcclnks=new FC_SQL;
$fcview=new FC_SQL;
$fcaux=new FC_SQL;
$fcwnav=new FC_SQL;

// if multiple catalogs to list

$fczone->query(
 "select count(*) as cnt from zone where zoneact=1");
$fczone->next_record();
$cnt=(int)$fczone->f("cnt");
$fczone->free_result();
if( $cnt > 1 ){
$fczone->query(
 "select zoneid,zonedescr from zone where zoneact=1 order by zoneid");
?>
<table class="navtext" border="0" cellpadding="0">
<tr><td align="left" valign="top">
<form name="zoneform" method="post" action="index.php">
<select name="zid" size="1" onChange="submit(); return false;">
<?php 
while( $fczone->next_record() ){
 $ztg=(int)$fczone->f("zoneid");
 if($ztg==$zid){?>
<option value="<?php echo $ztg?>" selected>
<?php }else{?>
<option value="<?php echo $ztg?>">
<?php }
 echo stripslashes($fczone->f("zonedescr"))."\n";?>
</option>
<?php
}
$fczone->free_result();

?>
</select><br />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<noscript>
<input type="submit" value="<?php echo fc_text("choosezone"); ?>" />
</noscript>
</form>
</td></tr></table>
<?php }

$fcwnav->query(
 "select webviewlogo,webviewlogoh,webviewlogow,webnewlogo,".
         "webnewlogoh,webnewlogow  from web where ".
         "webzid=$zid and weblid=$lid");
$fcwnav->next_record();
$webviewlogo  = $fcwnav->f('webviewlogo');
$webviewlogoh = $fcwnav->f('webviewlogoh');
$webviewlogow = $fcwnav->f('webviewlogow');
$webnewlogo   = $fcwnav->f('webnewlogo');
$webnewlogoh  = $fcwnav->f('webnewlogoh');
$webnewlogow  = $fcwnav->f('webnewlogow');

$fcwnav->free_result();

// if multiple language profiles to list
$fclang->query(
 "select count(*) as cnt from lang where langzid=$zid");
$fclang->next_record();
$cnt=(int)$fclang->f("cnt");
$fclang->free_result();
if( $cnt > 1 ){
$fclang->query(
 "select langid,langdescr from lang where langzid=$zid order by langid"); 
?>
<table class="navtext" border="0" cellpadding="0">
<tr><td align="left" valign="top">
<form name="langform" method="post" action="index.php">
<select name="lid" size="1" onChange="submit(); return false;">
<?php 
while( $fclang->next_record() ){
 $ltg=(int)$fclang->f("langid");
 if($ltg==$lid){?>
<option value="<?php echo $ltg?>" selected>
<?php }else{?>
<option value="<?php echo $ltg?>">
<?php }
 echo stripslashes($fclang->f("langdescr"))."\n";?>
</option>
<?php
}
$fclang->free_result();

?>
</select><br />
<input type="hidden" name="langchange" value="1" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<noscript>
<input type="submit" value="<?php echo fc_text("chooselang"); ?>" />
</noscript>
</form>
</td></tr></table>
<?php } ?>

<form name="homecat" method="get" action="display.php"
 onSubmit="
  if( document.homecat.cat.options.selectedIndex == 0 &&
      document.homecat.key1.value == '' ){
    alert('<?php echo fc_text('jspickone'); ?>');
    return false;
  }else{
    return true;
  }
 ">
<?php echo fc_text("choosekey"); ?><br />
<input name="key1" class="keywordsearch" size="14"><br />
<input type="hidden" name="olimit" value="0" />
<input type="hidden" name="zid" value="<?php echo $zid?>" />
<input type="hidden" name="lid" value="<?php echo $lid?>" />
<input type="hidden" name="cartid" value="<?php echo $cartid?>" />
</form>
<div id="button">
<ul>
<?php  // show the new items button
$fcnewprod->query("select count(*) as cnt from nprod where nzid=0 or nzid=$zid");
$fcnewprod->next_record();
if($fcnewprod->f('cnt')){?>
<li><a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&nlst=y&olimit=0&cat=&key1=&psku=">
<?php echo fc_text("newitems"); ?></a></li>
<?php  // show the closeout items button
$fccloseout->query("select count(*) as cnt from oprod where ozid=$zid");
$fccloseout->next_record();
if($fccloseout->f('cnt')){?>
<li><a href="display.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&olst=y&olimit=0">
<?php echo fc_text("closeout"); ?></a></li>
<?php } $fccloseout->free_result();
 } $fcnewprod->free_result();

// BEGIN CATEGORY LINKS

//auxilliary links. jheg

$fcaux->query("select title, url from auxlinks where loc=1 order by seq");
while ($fcaux->next_record()){
 $url = stripslashes($fcaux->f("url"));
 eval("\$url = \"$url\";");
 echo '<li><a href="'.$url.'">'.stripslashes($fcaux->f("title"))."</a></li>\n";
}
$fcaux->free_result();
// display a select list of product categories
// under='0' tells mysql to return the top level cats only
   $fcclnks->query("select catval,catdescr from ".
   "cat where catact=1 and catlid=$lid and ".
   "catzid=$zid and catunder=0 order by catseq asc");

	while($fcclnks->next_record()){ ?>
<li><a href="display.php?cat=<?php echo $fcclnks->f("catval");?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>&nlst=&olimit=0&key1=&psku="><?php echo stripslashes($fcclnks->f("catdescr"));?></a></li>
<?php
   }
// show the view cart button ?>
<li><a href="showcart.php?cartid=<?php echo $cartid?>&zid=<?php echo $zid?>&lid=<?php echo $lid?>"><?php echo fc_text("viewcart"); ?></a></li>
 <?php
   print "</ul>";
   
   $fcclnks->free_result();
// preview.php include bjh
if( !empty( $wflag1 ) && (empty( $nukepreview ) || $nukepreview !=1) ){
	if( $wflag1 & $flag_webshowpreview ){
		include ('preview.php');
	}
}//end of nukepreview
?>
</div>
<!--END CATEGORY LINKS-->
