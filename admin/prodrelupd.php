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

// if $zid or $lid are found, they should be changed
// to $zoneid or $langid, respectively. Once all
// maint files are done, $zid and $lid can probably
// be eliminated.

$zoneid = (int)getparam('zoneid');
$langid = (int)getparam('langid');
$act    = getparam('act');

$relsku  = getparam('relsku');
$relprod = getparam('relprod');
$relseq  = (int)getparam('relseq');
$cnt     = (int)getparam('cnt');

// ==========  end of variable loading  ==========

require('./admin.php');
require('./header.php');

$fcp = new FC_SQL;

$droot="BITCART_PKG_PATH";

$relseq=(integer)$relseq;

if ($act == 'insert') {

    $fcpa = new FC_SQL;
    $fcpb = new FC_SQL;

        $fcpa->query("select prodzid from prod where prodsku='$relsku' ".
                     "and prodzid=$zoneid");
        if (!$fcpa->next_record()){
        echo "The ID of the base product you entered doesn't exist in the current zone. 
        Please click Back and enter another product ID";
        $fcpa->free_result();
        exit;
        }
        $fcpa->free_result();

        $fcpb->query("select prodzid from prod where prodsku='$relprod' ".
                     "and prodzid=$zoneid");
        if (!$fcpb->next_record()){
         echo "The ID of the related product you entered doesn't exist in the curren zone. 
              Please click Back and enter another product ID";
        $fcpb->free_result();
        exit;
        }
        $fcpb->free_result();


	$fcp->query("insert into prodrel ".
        "(relzone,relsku,relprod,relseq) ".
	    "values ".
	    "($zoneid,'$relsku','$relprod',$relseq)");
  	
} elseif ($act == 'update') {
	// reorder the sequence
	$i = 0;
	while ( $i < $cnt ) {
		$seq  = (int)getparam('seq'.$i); // get the sequence #
		$rsku = getparam('rsku'.$i);     // get the related product
		$fcp->query("update prodrel set relseq=$seq ".
		  "where relzone=$zoneid and relprod='$rsku' and relsku='$relsku'");
		$i++;
	}

} elseif ($act == 'delete' && $relsku ) {
	// delete one line from the relation table
	if ( $relprod ) {
	 $fcp->query("delete from prodrel where ".
	 	"relzone=$zoneid and relsku='$relsku' and relprod='$relprod'"); 
	} else {
	    $fcp->query(
	        "delete from prodrel where relzone=$zoneid and relsku='$relsku'"); 
	}
} // if ($act == 'insert')

//see if there is an entry in prodrel for the current product
$fcca = new FC_SQL;
$fcca -> query(
	"select * from prodrel where relzone=$zoneid and relsku='$relsku'");
             if($fcca->next_record()){
               $prelf=1;
               }else{$prelf=0;}
$fcca -> free_result();

/*fetch prodflag1 from prod table
Any other prodflag1 flag other then prodrel should be added here to keep
prodflag1 complete
*/
$fccb = new FC_SQL;
$fccb -> query("select prodflag1 from prod where prodsku='$relsku' ".
              "and prodzid='$zoneid'");
$fccb  -> next_record();
$prodflag1=(int)$fccb->f("prodflag1");
$fccb -> free_result();

$flag1 = 0;
if ($prodflag1 & $flag_noship)  {$flag1 |= (int)$flag_noship;}
if ($prodflag1 & $flag_notax)   {$flag1 |= (int)$flag_notax;}
if ($prodflag1 & $flag_novat)   {$flag1 |= (int)$flag_novat;}
if ($prodflag1 & $flag_useesd)  {$flag1 |= (int)$flag_useesd;}
if ($prodflag1 & $flag_genesd)  {$flag1 |= (int)$flag_genesd;}
if ($prodflag1 & $flag_persvc)  {$flag1 |= (int)$flag_persvc;}
if ($prodflag1 & $flag_package) {$flag1 |= (int)$flag_package;}
if ($prelf){  $flag1 |=(int)$flag_hasrel;}

$fccr = new FC_SQL;
$fccr -> query("update prod set prodflag1=$flag1 where prodsku='$relsku'".
              " and prodzid=$zoneid");
$fccr -> commit();

if ($act == 'insert') {?>
    <form method="post" action="prodreladd.php">
<?php 
} else { ?>
    <form method="post" action="prodrelndx.php">
<?php 
}?>
<input type="hidden" name="zoneid" value="<?php echo $zoneid?>" />
<input type="hidden" name="langid" value="<?php echo $langid?>" />

<?php 
if ($act == 'insert') {?>
    <input type="submit" value="Return to Add a Related Product"
        onclick="closehelp();" />
<?php 
} else {?>
    <input type="submit" value="Return to Related Product Maintenance"
    onclick="closehelp();" />
<?php 
}?>
</form>

<?php require('./footer.php');?>
