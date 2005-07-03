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

$act = getparam('act');

$subzdescr   = getparam('subzdescr');
$subzvendid  = (int)getparam('subzvendid');
$subzsid     = (int)getparam('subzsid');
$subzparent  = (int)getparam('subzparent');
$subzwhsid   = (int)getparam('subzwhsid');
$subzshipid  = getparam('subzshipid');  //array
$subzshipdef = (int)getparam('subzshipdef');
$subztaxpern = (double)getparam('subztaxpern');
$subztaxpers = (double)getparam('subztaxpers');
$subztaxcmtn = getparam('subztaxcmtn');
$subztaxcmts = getparam('subztaxcmts');

$subzone    = getparam('subzone');
// ==========  end of variable loading  ==========

if ($act=='delete'){
list($subzvendid,$subzsid)=explode(":",$subzone);
$subzvendid=(int)$subzvendid;
$subzsid=(int)$subzsid;
}

$fcs = new FC_SQL;

$droot="BITCART_PKG_PATH";

if ($zoneid == '') {
	echo '<p>No zone was selected.</p>';
	echo '<p>Please click the &quot;Back&quot; button on your browser '.
	    'and correct the errors.  Thank you.';
    exit;
}
if (strlen($subzdescr) > 80) {
	echo '<p>A field exceeds its maximum length.</p>';
	echo '<p>Please click the &quot;Back&quot; button on your browser '.
	    'and correct the errors.  Thank you.';
    exit;
}
if ($subzvendid == '') {
	echo '<p>A vendor profile must be selected.</p>';
	echo '<p>Please click the &quot;Back&quot; button on your browser '.
	    'and correct the errors.  Thank you.';
    exit;
}

$subzflag0 = 0;

if ($act == 'update') {

    $res = $fcs->query("update subzone set ".
	    "subzdescr='$subzdescr', subzparent=$subzparent, ".
	    "subztaxpern=$subztaxpern, subztaxpers=$subztaxpers, ".
	    "subztaxcmtn='$subztaxcmtn', subztaxcmts='$subztaxcmts', ".
	    "subzvendid=$subzvendid, subzwhsid=$subzwhsid,subzflag0=$subzflag0 ".
	    "where subzid=$zoneid and subzsid=$subzsid");
    $res = $fcs->query("delete from subzship ".
		"where shipszid=$subzsid and shiplid=$langid");
	if( $databaseeng=='odbc' && $dialect=='solid' ){
	    $res = $fcs->query("call subzship_ins (".
	        "$subzsid,$subzshipdef,1)");
    } else {
	    $res = $fcs->query("insert into subzship ".
	        "(shipszid,shipid,shipdef,shiplid)".
	        " values ".
	        "($subzsid,$subzshipdef,1,$langid)");
    }
    if ($subzshipid) {
        $i = 0;
        while ($i < count($subzshipid)) {
			$this_subzshipid = (int)$subzshipid[$i];
            if ($this_subzshipid != $subzshipdef) {
				if( $databaseeng=='odbc' && $dialect=='solid' ){
                    $res = $fcs->query("call subzship_ins (".
                        "$subzsid,$this_subzshipid,0)");
                } else {
                    $res = $fcs->query("insert into subzship ".
                        "(shipszid,shipid,shipdef,shiplid)".
                        " values ".
                        "($subzsid,$this_subzshipid,0,$langid)");
                }
            }
            $i++;
        }
    }
} elseif ($act == 'new') {

	if( $databaseeng=='odbc' && $dialect=='solid' ){
	    $res = $fcs->query("call subzone_ins (".
	        "$zoneid,'$subzdescr',".
	        "$subztaxpern,$subztaxpers,".
	        "'$subztaxcmtn','$subztaxcmts',".
	        "$subzvendid,$subzwhsid,$subzflag0,$subzparent)");
        $subzsid = $fcs->insert_id("subzid","subzone");
	    $res = $fcs->query("call subzship_ins (".
	        "$subzsid,$subzshipdef,1)");
    } else {
	    $res = $fcs->query("insert into subzone ".
            "(subzid,subzdescr,".
            "subztaxpern,subztaxpers,".
            "subztaxcmtn,subztaxcmts,".
            "subzvendid,subzwhsid,subzflag0,subzparent)".
            " values ".
            "($zoneid,'$subzdescr',".
            "$subztaxpern,$subztaxpers,".
            "'$subztaxcmtn','$subztaxcmts',".
            "$subzvendid,$subzwhsid,$subzflag0,$subzparent)");
            // get the ID of the shipping profile just added
        $subzsid = $fcs->insert_id("subzid","subzone");
        $res = $fcs->query("insert into subzship ".
            "(shipszid,shipid,shipdef,shiplid)".
            " values ".
            "($subzsid,$subzshipdef,1,$langid)");
    }
    if ($subzshipid) {    
        $i = 0; 
        while ($i < count($subzshipid)) {
			$this_subzshipid = (int)$subzshipid[$i];
            if ($this_subzshipid != $subzshipdef) {
				if( $databaseeng=='odbc' && $dialect=='solid' ){
                    $res = $fcs->query("call subzship_ins (".
                        "$subzsid,$this_subzshipid,0)");
                } else {
                    $res = $fcs->query("insert into subzship ".
                        "(shipszid,shipid,shipdef,shiplid)".
                        " values ".
                        "($subzsid,$this_subzshipid,0,$langid)");
                }
            }
            $i++;
        }
    }
} elseif ($act == 'delete') {

	$res = $fcs->query("delete from subzone ".
	    "where subzid=$zoneid and subzsid=$subzsid");
	$res = $fcs->query("delete from subzship ".
	    "where shipszid=$subzsid and shiplid=$langid");
} // if ($act == 'update') 

if (!$res) {
    $fcs->rollback();
	    echo "<b>Failure updating subzone: $res</b><br />\n";
} else {
	$fcs->commit();
	echo "Work Committed.<br />\n";
}
?>
<?php if ($act == 'insert') {?>
 <form method=post action="subzoneadd.php">
<?php } else {?>
 <form method=post action="subzonendx.php">
<?php }?>
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />
<input type="submit" value="Return to Subzone Maintenance" />
</form>

<?php require('./footer.php');?>
