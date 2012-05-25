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
header("Expires: 0");
require_once( '../../bit_setup_inc.php' );
$gBitSystem->verifyPermission( 'bit_p_bitcart_admin' );

require_once( BITCART_PKG_PATH.'functions.php');
require('./admin.php');
require('./header.php');

echo "<center><table border=\"0\" cellpadding=\"4\" width=\"650\" cellspacing=\"1\"><tr><td align=center bgcolor=#ffffff class=\"text\">";
if($rehash && !$instid){
 print "$_SERVER[SCRIPT_NAME] is not picking up the \"\$instid\" variable.  This needs to be set in the script ($_SERVER[SCRIPT_NAME]) before this program can continue.  This value is the name of your shopping cart.  If you don't know where to find this value, it is usually the directory in which the cart resides.  It is also littered throughout the maintenance scripts.";
}

// check the height and width for all pics in the cart
if($rehash && $instid){
   print "Now Processing Pictures In The Current Database.  This may take a few minutes, so don't close the window until the program has told you it is done.<br><br>";
   $tableweb = $instid."web";
   $tableprodopt = $instid."prodopt";
   $tablecat = $instid."cat";
   $tableprodlang = $instid."prodlang";
   $q = new FC_SQL;
   $r = new FC_SQL;
   exec("cd ..");
   $q->query("select webid,weblogo,webhdgraph,webftgraph,webnewlogo,webnewmast,webspeclogo,webspecmast,webviewlogo from $tableweb");
   while($q->next_record()){
    $weblogo = $q->f("weblogo");
    $webhdgraph = $q->f("webhdgraph");
    $webftgraph = $q->f("webftgraph");
    $webnewlogo = $q->f("webnewlogo");
    $webnewmast = $q->f("webnewmast");
    $webspeclogo = $q->f("webspeclogo");
    $webspecmast = $q->f("webspecmast");
    $webviewlogo = $q->f("webviewlogo");
     
	 
	$webid = $q->f("webid");
    $weblogow=0;
    $weblogoh=0;
    if($weblogo!=""){
     $imgs=getimagesize(imagepath($weblogo).$weblogo);
	 if($imgs[0]==0){
      echo "<b>Image file: $weblogo not found.</b><p>";
	 }else{
	  $weblogow=$imgs[0];
	  $weblogoh=$imgs[1];
	 }
   }
   $webhdgraphw=0;
   $webhdgraphh=0;
   if($webhdgraph!=""){
    $imgs=getimagesize(imagepath($webhdgraph).$webhdgraph);
	if($imgs[0]==0){
      echo "<b>Image file: $webhdgraph not found.</b><p>";
    }else{
      $webhdgraphw=$imgs[0];
	  $webhdgraphh=$imgs[1];
    }
   }
   $webftgraphw=0;
   $webftgraphh=0;
   if($webftgraph!=""){
    $imgs=getimagesize(imagepath($webftgraph).$webftgraph);
	if($imgs[0]==0){
      echo "<b>Image file: $webftgraph not found.</b><p>";
	}else{
	  $webftgraphw=$imgs[0];
	  $webftgraphh=$imgs[1];
	}
   }
   $webnewlogow=0;
   $webnewlogoh=0;
   if($webnewlogo!=""){
    $imgs=getimagesize(imagepath($webnewlogo).$webnewlogo);
	if($imgs[0]==0){
      echo "<b>Image file: $webnewlogo not found.</b><p>";
	}else{
	  $webnewlogow=$imgs[0];
	  $webnewlogoh=$imgs[1];
    }
   }
   $webnewmastw=0;
   $webnewmasth=0;
   if($webnewmast!=""){
    $imgs=getimagesize(imagepath($webnewmast).$webnewmast);
	if($imgs[0]==0){
      echo "<b>Image file: $webnewmast not found.</b><p>\n";
	}else{
	  $webnewmastw=$imgs[0];
	  $webnewmasth=$imgs[1];
	}
   }
   $webspeclogow=0;
   $webspeclogoh=0;
   if($webspeclogo!=""){
    $imgs=getimagesize(imagepath($webspeclogo).$webspeclogo);
	if($imgs[0]==0){
      echo "<b>Image file: $webspeclogo not found.</b><p>";
	}else{
	  $webspeclogow=$imgs[0];
	  $webspeclogoh=$imgs[1];
	}
   }
   $webspecmastw=0;
   $webspecmasth=0;
   if($webspecmast!=""){
    $imgs=getimagesize(imagepath($webspecmast).$webspecmast);
	if($imgs[0]==0){
      echo "<b>Image file: $webspecmast not found.</b><p>";
	}else{
	  $webspecmastw=$imgs[0];
	  $webspecmasth=$imgs[1];
	}
   }
   $webviewlogow=0;
   $webviewlogoh=0;
   if($webviewlogo!=""){
    $imgs=getimagesize(imagepath($webviewlogo).$webviewlogo);
	if($imgs[0]==0){
      echo "<b>Image file: $webviewlogo not found.</b><p>";
	}else{
	  $webviewlogow=$imgs[0];
	  $webviewlogoh=$imgs[1];
	}
   }
   $r->query("update $tableweb set weblogo='$weblogo',weblogow='$weblogow',weblogoh='$weblogoh',webhdgraph='$webhdgraph',".
  "webhdgraphw='$webhdgraphw',webhdgraphh='$webhdgraphh',webftgraphw='$webftgraphw',webftgraphh='$webftgraphh',webftgraph='$webftgraph',".
  "webnewlogo='$webnewlogo',webnewlogow='$webnewlogow',webnewlogoh='$webnewlogoh',webnewmast='$webnewmast',webnewmasth='$webnewmasth',webnewmastw='$webnewmastw',".
  "webspeclogo='$webspeclogo',webspeclogow='$webspeclogow',webspeclogoh='$webspeclogoh',webviewlogo='$webviewlogo',webviewlogow='$webviewlogow',webviewlogoh='$webviewlogoh',".
  "webspecmast='$webspecmast',webspecmasth='$webspecmasth',webspecmastw='$webspecmastw' where webid='$webid'");
} //end while
   $q->query("select poptpic,popttpic,poptid from $tableprodopt");
   while($q->next_record()){
    $poptpic = $q->f("poptpic");
    $popttpic = $q->f("popttpic");
    $poptid = $q->f("poptid");
    $poptpicw=0;
    $poptpich=0;
    if($poptpic!=""){
      $imgs=getimagesize(imagepath($poptpic).$poptpic);
	  if($imgs[0]==0){
        echo "<b>Image file: $poptpic not found.</b><p>\n";
      }else{
        $poptpicw=$imgs[0];
        $poptpich=$imgs[1];
      }
    }
    $popttpicw=0;
    $popttpich=0;
    if($popttpic!=""){
      $imgs=getimagesize(imagepath($popttpic).$popttpic);
	  if($imgs[0]==0){
        echo "<b>Image file: $popttpic not found.</b><p>\n";
      }else{
	    $popttpicw=$imgs[0];
	    $popttpich=$imgs[1];
	  }
    }
    $r->query("update $tableprodopt set poptpic='$poptpic',poptpicw='$poptpicw',poptpich='$poptpich',popttpic='$popttpic',popttpicw='$popttpicw',popttpich='$popttpich' where poptid='$poptid'");
} //end while
   $q->query("select prodlsku,prodpic,prodtpic,prodbanr,prodaudio,prodvideo from $tableprodlang");
   while($q->next_record()){
    $prodlsku=$q->f("prodlsku");
    $prodpic=$q->f("prodpic");
    $prodtpic=$q->f("prodtpic");
    $prodbanr=$q->f("prodbanr");
    $prodaudio=$q->f("prodaudio");
    $prodvideo=$q->f("prodvideo");
    if(!file_exists(imagepath($prodaudio).$prodaudio)){
	  $prodaudio="";
	}
	if(!file_exists(imagepath($prodvideo).$prodvideo)){
	  $prodvideo="";
	}
	$prodpicw=0;
    $prodpich=0;
    if($prodpic!=""){
	  $imgs=getimagesize(imagepath($prodpic).$prodpic);
	  if($imgs[0]==0){
        echo "<b>Image file: $prodpic not found.</b><p>\n";
      }else{
	    $prodpicw=$imgs[0];
		$prodpich=$imgs[1];
	  }
	}
    $prodtpicw=0;
    $prodtpich=0;
    if($prodtpic!=""){
	  $imgs=getimagesize(imagepath($prodtpic).$prodtpic);
	  if($imgs[0]==0){
        echo "<b>Image file: $prodtpic not found.</b><p>\n";
	  }else{
	    $prodtpicw=$imgs[0];
		$prodtpich=$imgs[1];
      }
	}
	$prodbanrw=0;
	$prodbanrh=0;
    if($prodbanr!=""){
	  $imgs=getimagesize(imagepath($prodbanr).$prodbanr);
	  if($imgs[0]==0){
        echo "<b>Image file: $prodbanr not found.</b><p>\n";
      }else{
	    $prodbanrw=$imgs[0];
		$prodbanrh=$imgs[1];
	  }
	}
    $r->query("update $tableprodlang set prodpic='$prodpic',prodpich='$prodpich',prodpicw='$prodpicw',prodtpic='$prodtpic',prodtpicw='$prodtpicw',prodtpich='$prodtpich',".
"prodbanr='$prodbanr',prodbanrh='$prodbanrh',prodbanrw='$prodbanrw',prodaudio='$prodaudio',prodvideo='$prodvideo' where prodlsku='$prodlsku'");
} //end while
$q->query("select catval,catlogo,catbanr from $tablecat");
while($q->next_record()){
  $catval =$q->f("catval");
  $catlogo=$q->f("catlogo");
  $catbanr=$q->f("catbanr");
  $catlogow=0;
  $catlogoh=0;
  if($catlogo!=""){
    $imgs=getimagesize(imagepath($catlogo).$catlogo);
	if($imgs[0]==0){
      echo "<b>Image file: $catlogo not found.</b><p>\n";
    }else{
	  $catlogow=$imgs[0];
	  $catlogoh=$imgs[1];
	}
  }
  $catbanrw=0;
  $catbanrh=0;
  if($catbanr!=""){
    $imgs=getimagesize(imagepath($catbanr).$catbanr);
	if($imgs[0]==0){
      echo "<b>Image file: $catbanr not found.</b><p>\n";
    }else{
	  $catbanrw=$imgs[0];
	  $catbanrh=$imgs[1];
	}
  }
  $r->query("update $tablecat set catlogo='$catlogo',catlogoh='$catlogoh',catlogow='$catlogow',catbanr='$catbanr',catbanrw='$catbanrw',catbanrh='$catbanrh'  where catval='$catval'");
} //end while
}// end if($rehash) -- only checks pics if rehash > 1 || must be set from command line.

print "</td></tr><tr><td align=center bgcolor=#ffffff><br><br>
<b>You are now done rebuilding the picture database.</b><br>

<form method='post' action='./rebuildpics.php'>
<input type='button' value='Continue' onClick='self.close();'>
</form></td></tr></table>";

include('./footer.php');
?>
