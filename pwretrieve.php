<?php
/*
pwretrieve v0.95

Written by B. van Ouwerkerk bvo@atz.nl

Published under the same license as FishCart.

Please send questions to the FishCart users list.

You can call this file from anywhere with a form method=post
the name of the inputbox is supposed to be pwlostmail.

This is the first release. Adding more features and languages
as soon as possible.
Latest release is available via CVS.

I hope this might be useful to someone, use it at your own risk..

This file is still under development and should currently not
be used in combination with ESD.
*/

header("Expires: 0");
header("Pragma: no-cache");
header("Cache-control: No-Cache");
require_once( '../bit_setup_inc.php' );

require('./functions.php');

// ========== start of variable loading ==========
// load passed variables and cookie variables
// int or double cast the numbers, no exceptions
// custid cookie is mime encoded, don't escape
$pwlostmail         = getparam('pwlostmail');
// ==========  end of variable loading  ==========

require('./public.php');

if (eregi("^[a-z0-9_\'\.-]+@[a-z0-9_\.-]+\.[a-z]{2,4}$",$pwlostmail)){
                    $pwlostmail=$pwlostmail;}else{
                                        $pwlostmail="";
                                }

if ($pwlostmail==""){
    echo 'no email address found or no valid email address in your request';
    exit;
}

// retrieve the record for the email address
    $wwkwijt = new FC_SQL;
    $wwkwijt->query("SELECT pwactive,pwemail,pwuid,pwpw from pw ".
                    "where pwemail='$pwlostmail' limit 1");
    $wwkwijt->next_record();
    if($wwkwijt->f("pwuid")==""){
            //no record for this address so we're supposed to build an errormessage here
            //let's keep it simple for now
            //this will be fixed in the next release
    echo "sorry, we don't know the address entered";
    exit;
    }

    if($wwkwijt->f("pwactive")=="0"){
            //login is not active so we're supposed to build an errormessage here
            //let's keep it simple for now
            //this will be fixed in the next release
    echo 'account is currently not active';
    exit;
    }

$login=$wwkwijt->f("pwuid");
$ww=$wwkwijt->f("pwpw");

//record found now sending email
$subject .="Information you requested from ";
$messages .="Your  catalog password:\n";
$messages .="username : $login\n";
$messages .="password : $ww\n";
$headers .="From: ".$gBitSystem->getSenderEmail()."\n";
$headers .="Return-Path: <".$gBitSystem->getSenderEmail().">\n";
mail($wwkwijt->f("pwemail"), $subject, $messages, $headers);

$wwkwijt->free_result();

?>
<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>
<BODY>
<a href="index.php">login with password</a>
</BODY>
</HTML>
