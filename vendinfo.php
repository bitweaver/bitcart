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

//Required 
$fcv=new FC_SQL

?>
<table class="text" align="left" width="780" cellpadding="0" border="0">
<tr><td width="135">
<img src="clearpixel.gif" width="134" height="1">
</td><td align="center">

<?php  // display the vendor contact information
$fcv->query("select * from vend where vendzid=$zid"); 
$fcv->next_record();
?>

<table class="text" align="center" width="50%" border="0" cellpadding="6" cellspacing="0">

<?php
if( $copy ){
 echo '<tr><td align="left" colspan="2"><p>'.$copy.'</p></td></tr>';
}
if( $lterms ){
 echo '<tr><td align="center" colspan="2"><p><a href="terms.php?lid='.$lid.'" target="_top" border="0">'.fc_text('termscon').'</a></p></td></tr>';
}
?>

<tr><td align="left" valign="top">

<b><i><?php echo fc_text('contactinfo'); ?></i></b><br />
<?php
if($fcv->f('vendname')){ echo stripslashes($fcv->f('vendname'))?><br /><?php }
if($fcv->f('vendaddr1')){ echo stripslashes($fcv->f('vendaddr1'))?><br /><?php }
if($fcv->f('vendaddr2')){ echo stripslashes($fcv->f('vendaddr2'))?><br /><?php }
if($fcv->f('vendcity')){ echo stripslashes($fcv->f('vendcity'))?>, <?php echo stripslashes($fcv->f('vendstate'))?> <?php echo stripslashes($fcv->f('vendzip'))?>  <?php echo stripslashes($fcv->f('vendnatl'))?><br /><?php }
if($fcv->f('vendphone')){ echo stripslashes($fcv->f('vendphone'))?><br /><?php }
if($fcv->f('vendfax')){ echo stripslashes($fcv->f('vendfax'))?><br /><?php }
if($fcv->f('vendemail')){?><a href="mailto:<?php echo stripslashes($fcv->f('vendemail'))?>"><?php echo stripslashes($fcv->f('vendemail'))?></a><br /><?php }?>

</td><td align="left" valign="top">

<b><i><?php echo fc_text('supportinfo'); ?></i></b><br />
<?php  // display the vendor service information
if($fcv->f('vsvcname')){ echo stripslashes($fcv->f('vsvcname'))?><br /><?php }
if($fcv->f('vsvcaddr1')){ echo stripslashes($fcv->f('vsvcaddr1'))?><br /><?php }
if($fcv->f('vsvcaddr2')){ echo stripslashes($fcv->f('vsvcaddr2'))?><br /><?php }
if($fcv->f('vsvccity')){ echo stripslashes($fcv->f('vsvccity')).', '.stripslashes($fcv->f('vsvcstate')).' '.stripslashes($fcv->f('vsvczip')).'  '.stripslashes($fcv->f('vsvcnatl'))?><br /><?php }
if($fcv->f('vsvcphone')){ echo stripslashes($fcv->f('vsvcphone'))?><br /><?php }
if($fcv->f('vsvcfax')){ echo stripslashes($fcv->f('vsvcfax'))?><br /><?php }
if($fcv->f('vsvcemail')){?><a href="mailto:<?php echo stripslashes($fcv->f('vsvcemail'))?>"><?php echo stripslashes($fcv->f('vsvcemail'))?></a><br /><?php }?>

</td></tr></table>
</td></tr></table>
