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
require_once( BITCART_PKG_PATH.'functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$catval = (int)getparam('catval');
$newseq = (int)getparam('newseq');
$newsku = getparam('newsku');
$val    = (int)getparam('val');
$delsku = getparam('delsku');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcc = new FC_SQL;
$fcp = new FC_SQL;


if ($newsku){
  // verify the given SKU exists
  $fcp->query(
  "select prodsku from prod where prodsku='$newsku' and prodzid=$zoneid"); 
  if ( !$fcp->next_record() ){
  	// SKU does not exist?>
The SKU selected, <?php echo $newsku?>, does not exist in the database.
<p>
Please return to the Product / Category Maintenance page to continue.
<form method=post action="prodcatndx.php">
<input type=submit value="Return">
<input type=hidden name=val value="<?php echo $val?>">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
</form>
<?php 
	$fcp->rollback();
  	exit;
  }else{
    $fcp->free_result();
  }

  // verify the given association does not exist

  $fcc->query(
    "select * from prodcat ".
		"where pcatsku='$newsku' and pcatval=$val and pcatzid=$zoneid"); 
  if ( $fcc->next_record() ){
    $fcc->free_result();
  	// association does exist?>

The SKU selected, <?php echo $newsku?>,
is already associated with this category.
<p>
Please return to the Product / Category Maintenance page to continue.
<form method=post action="prodcatndx.php">
<input type=hidden name=val value="<?php echo $val?>">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=submit value="Return">
</form>

  	<?php 
	$fcc->rollback();
  	exit;
  }

  // add the association
  $pr=$fcc->query(
	"insert into prodcat (pcatval,pcatsku,pcatzid,pcatseq) ".
	"values ($val,'$newsku',$zoneid,$newseq)"); 
  if($pr){
	$fcc->commit();
  	echo "Work committed.\n";
  }else{
	$fcc->rollback();
  	echo "Error: work rolled back.\n";
  }
}

if ($delsku) {
	$pr=$fcc->query("delete from prodcat ".
			"where pcatval=$val and pcatsku='$delsku' and pcatzid=$zoneid"); 
	if($pr){
		$fcc->commit($sd);
  		echo "Work committed.\n";
	}else{
		$fcc->rollback($sd);
  		echo "Error: work rolled back.\n";
	}
}
?>

<p>

Click below to return to the Product / Category Maintenance page
</a>
<form method=post action="prodcatndx.php">
<input type=hidden name=zoneid value=<?php echo $zoneid?>>
<input type=hidden name=langid value=<?php echo $langid?>>
<input type=hidden name=val value="<?php echo $val?>">
<input type=submit value="Return">
</form>

<?php require('./footer.php');?>
