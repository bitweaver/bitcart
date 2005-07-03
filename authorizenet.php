<?php

$my_authnet_userid = '';
$query_string  = '';

$query_string .= "&x_Test_Request=TRUE";
$query_string .= "&x_Tran_Key=YOULL_NEED_TO_SET_THIS";

$cc_exp = sprintf("%02d",$ccexp_month).$ccexp_year;

$query_string .= "x_Version=3.1";
$query_string .= "&x_Delim_Data=TRUE";
$query_string .= "&x_Login=$my_authnet_userid";
$query_string .= "&x_Amount=$ttotal";
$query_string .= "&x_Card_Num=$cc_number";
$query_string .= "&x_Exp_Date=$cc_exp";
$query_string .= "&x_Invoice_Num=$cartid";
// The below fields are required with Wells Fargo's SecureSource service:
$query_string .= "&x_First_Name=$billing_first";
$query_string .= "&x_Last_Name=$billing_last";
$query_string .= "&x_Company=$billing_first%20$billing_last";
$query_string .= "&x_Address=$billing_address1";
$query_string .= "&x_City=$billing_city";
$query_string .= "&x_State=$billing_state";
$query_string .= "&x_Zip=$billing_zip";
$query_string .= "&x_Country=$billing_country";
$query_string .= "&x_Phone=$billing_areacode-$billing_phone";
$query_string .= "&x_Email=$billing_email";
$query_string .= "&x_Customer_IP=".$_SERVER[REMOTE_ADDR];

exec("curl -d '$query_string' https://secure.authorize.net/gateway/transact.dll", $authorize, $ret);

$auth_return = split("\,", $authorize[0]);

// for debugging you can print the variables returned:
// for ($idx = 0; $idx < 39; ++$idx) {
//    $pos = $idx+1;
//  echo "Code".$pos.":  ".$auth_return[$idx]."<BR>";
// }
//echo "<b>Request URL:</b> https://secure.authorize.net/gateway/transact.dll?$query_string";

if($auth_return[0] == 1){

 $auth_code = $auth_return[4];
 $avs_code  = $auth_return[5];
 $trans_id  = $auth_return[6];

}elseif($auth_return[0] == 2){

 echo "<b>Your order cannot be processed at this time, as your credit card was not accepted.</b><br>";
 exit;

}elseif($auth_return[0] == 3){

 echo "<b>An error has occurred and your order cannot be processed at this time.</b><br>";
 exit;
}
?>
