<?php
require_once( '../bit_setup_inc.php' );
require('./functions.php');
require('./public.php');
require('./languages.php');
$esdid=(int)getparam('esdid');
if( $esdid == 0 ){
 echo fc_text('esdnotrans');
 echo fc_text('esdcustsvc');
 exit;
}
require('./pwesd.php');
$now=time();
// increment the download count
$fcesd = new FC_SQL;
$fcesd->query(
  "select esddlcnt,esddlmax,esddlexp,esddlfile from esd ".
  "where esdid=$esdid and esdoid='$pwuid'");
if( !$fcesd->next_record() ){
 echo fc_text('esdnodl');
 echo fc_text('esdcustsvc');
 exit;
}
$esddlcnt=(int)$fcesd->f('esddlcnt')+1;
$esddlmax=(int)$fcesd->f('esddlmax');
$esddlexp=(int)$fcesd->f('esddlexp');
$esddlfile=$fcesd->f('esddlfile');
$fcesd->free_result();
if( $esddlcnt > $esddlmax || $now > $esddlexp ){
 echo fc_text('esddlmax');
 echo fc_text('esdcustsvc');
 exit;
}
$fcesd->query("update esd set esddlcnt=$esddlcnt where esdid=$esdid");
$fcesd->commit();
$file=substr(strrchr($esddlfile,'/'),1);
set_magic_quotes_runtime(0);
$fd=fopen($esddlfile,'rb');
if( $fd ){
 $size=filesize($esddlfile);
 header("Content-Disposition: attachment; filename=$file");
 header("Content-Length: $size");
 header("Content-Type: application/download");
 while( $buf=fread($fd,16384) ){
  echo $buf;
 }
 fclose($fd);
}else{
 echo fc_text('esdnofile');
 echo fc_text('esdcustsvc');
}
?>
