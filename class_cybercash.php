<?

    //=========================================================//
    //                                                         //
    //			  class.cybercash.inc                          //
    //                                                         //
    //	Author:	  Bob Bowker (bowker@iNetWebInc.com)           //
    //  Rights:   Free under GNU (if this header stays intact) //
    //	Update:	  July 14, 2000                                //
    //	Outline:  PHP3 Class to interface with CyberCash 3.x   //
    //                                                         //
    //  This is a consolidation of much work we have done in   //
	//  the past, as well as portions of the functions by	   //
	//  Flint Doungchak (flint@netsolutionsllc.com) and        //
	//  Patrick Shafer (pshafer@netsolutionsllc.com).   	   //
    //                                                         //
    //  Example Code to use this class is at the bottom.       //
    //                                                         //
    //=========================================================//


class cybercash	{
	var $classname = "cybercash";

/////////////////////////////////////////////////////////////////////////////////////////////////
//
// This class will work assuming only that:
//    a. you have CyberCash 3.2 installed on the server
//    b. you have a CyberCash account (even one in Test mode)
//
// Only 2 variables must be hard-coded before using this class ... all other
// needed information (such as your Merchant Key and CCID) is gathered automatically

// 1. enter the full path to the directory which contains the MCK executable files
// NOTE: the script must have permission to execute in this subdirectory,
// which contains the MCK executables "MCKencrypt" and "MCKdecrypt"
var $ccBaseDir = "/home/lucyann/secure_html/test-mck/mck-cgi/";			// conclude with "/"

// 2. enter a subdirectory to be used for temporary files
// NOTE: the script must have permission to touch, chmod, read, write 
// and unlink in this subdirectory
var $workBaseDir = "BITCART_PKG_PATHnukeme/";		// conclude with "/"

/////////////////////////////////////////////////////////////////////////////////////////////////

	// CyberCash-related variables
	var $MCKversion = "3.2.0.4";
	var $cybercashURL = "cr.cybercash.com";
	var $cybercashSCRIPT = "/cgi-bin/directcardpayment.cgi";
	var $merchant_key,$merchant_id,$response,$POP;
	var $MCKencrypt,$CRYPTcommand,$MCKdecrypt,$DECRYPTcommand;
	var	$encryptedFile0,$encryptedFile1,$decryptedFile0,$decryptedFile1;

	// individual order submission variables
	var $orderNum,$currency,$price,$amount,$address1,$address2;
	var $ccnum,$ccexp,$ccname,$ccaddress,$cccity,$ccstate,$cczip,$cccountry;

//////////////////////////////////////////////////
// Public functions
//////////////////////////////////////////////////

	function errorHandler($errroMsg) {
		global $gBitSystem;
		$errorEmail = $gBitSystem->getErrorEmail();
		$msg = "CyberCash Error ";
		$msg .= sprintf($this->ordernum?$this->ordernum:"(No Order Number)");
		$msg .= " " . date("H:i:s m/d/Y") . "\n\n";
		$msg .= "$errroMsg\n\n";
		if ($errorEmail)	{mail($errorEmail,"CyberCash Class Error",$msg,"X-Mailer: PHP/".phpversion());}
		else {echo $msg;}
		exit;
	}

	function send()	{
	// see if we have CyberCash installed here
		if (!$this->getCcAccountDetails())	{$this->errorHandler("Cannot verify local CyberCash installation");}

	// clean up the data we're going to send
		$this->ccname = ereg_replace(" ","+",$this->ccname);
		$this->ccaddress = ereg_replace(" ","+",sprintf($this->address2?$this->address1 . " " . $this->address2:$this->address1));
		$this->cccity = ereg_replace(" ","+",$this->cccity);
		$this->ccstate = ereg_replace(" ","+",$this->ccstate);
		$this->cczip = ereg_replace(" ","+",$this->cczip);
		$this->cccountry = ereg_replace(" ","+",$this->cccountry);
		$this->amount = $this->currency . "+" . $this->price;

	// initialize needed text files for this order
		$this->createWorkFiles();
		
	// generate the messages
		$cpi = $this->createCpiMessage();
		if (!$cpi)	{$this->errorHandler("createCpiMessage() Error");}

		$mo = $this->createMoMessage();
		if (!$mo)	{$this->errorHandler("createMoMessage() Error");}

		$msg = "CPI=$cpi&MO=$mo";
		$encMsgCreated = $this->createEncryptedMessage($msg);
		if (!$encMsgCreated)	{$this->errorHandler("createEncryptedMessage() Error");}
		
		$mac = $sessionkey = $encryptedmessage = "";
		$fd = fopen($this->encryptedFile1, "r");
			while ($buffer = fgets($fd, 4096)) {	// read the 3 lines into appropriate variables
				if (!$mac)					{$mac = $buffer;}
				elseif (!$sessionkey)		{$sessionkey = $buffer;}
				elseif (!$encryptedmessage)	{$encryptedmessage = $buffer;}
			}
		fclose($fd);

		if (!($args = $this->createMacMessage($mac,$sessionkey,$encryptedmessage)))	{$this->errorHandler("createMacMessage() Error");}
		if (!($msg = $this->createArgsMessage($args)))								{$this->errorHandler("createArgsMessage() Error");}

	// open the connection and send the request for authorization
		if (!($ccConnect = fsockopen($this->cybercashURL,80,&$errno,&$errstr))) 	{$this->errorHandler("CyberCash connection failure: $errstr ($errno)");}
		else {
			fputs($ccConnect, $msg);
			while(!feof($ccConnect)) {
				for($i=1;$i<=9;$i++) {
					if ($i==8)	{$this->response = fgets($ccConnect,4096);}
					else 		{fgets($ccConnect,1024);}
				}
			}
		}
		fclose($ccConnect);
		if (!$this->response)	{return 0;}		// calling code handles this problem

	// clean up key name for PHP
		$this->response = ereg_replace("session-key","sessionkey",urldecode(chop($this->response)));
		parse_str($this->response);				// dig out $sessionkey, $mac and $message
			$sessionkey = chop($sessionkey);
			$mac = ereg_replace(" ","+",chop($mac));
			$message = ereg_replace(" ","+",chop($message));

	// decrypt results into the text file
		$DECRYPTinFD = fopen($this->decryptedFile0, "w");
			fwrite($DECRYPTinFD,$this->merchant_key,strlen($this->merchant_key));
			fwrite($DECRYPTinFD,"\r\n",strlen("\r\n"));
			fwrite($DECRYPTinFD,$sessionkey,strlen($sessionkey));
			fwrite($DECRYPTinFD,"\r\n",strlen("\r\n"));
			fwrite($DECRYPTinFD,$mac,strlen($mac));
			fwrite($DECRYPTinFD,"\r\n",strlen("\r\n"));
			fwrite($DECRYPTinFD,$message,strlen($message));
			fwrite($DECRYPTinFD,"\r\n",strlen("\r\n"));
		fclose($DECRYPTinFD);
		exec($this->DECRYPTcommand);

	// get the decrypted results
		$fd = fopen($this->decryptedFile1, "r");
			while ($buffer = fgets($fd, 4096)) {
				if (!$ccres)	{$ccres = $buffer;}
				else			{$ccres .= $buffer;}
			}
		fclose($fd);
		
	// parse and clean up the results
		parse_str($ccres);
		$this->POP = ereg_replace("-code","code",$POP);
		$this->POP = ereg_replace("-id","id",$this->POP);
		$this->POP = ereg_replace("pop.error-message","errormessage",$this->POP);
		$this->POP = ereg_replace("pop.order-valid-till","",$this->POP);
		$this->POP = ereg_replace("pop.sale-date","saledate",$this->POP);
		$this->POP = ereg_replace("pop.product-descr","productdescr",$this->POP);

		return 1;
	}

//////////////////////////////////////////////////
// Private functions
//////////////////////////////////////////////////

	function getCcAccountDetails()	{
		switch (substr($buffer,0,12))	{
			case (""):
				$this->merchant_id = substr($buffer,15,strlen($buffer)-15);
				$this->merchant_id = ereg_replace("[\n]","",$this->merchant_id);
				break;
			case (""):
				$this->merchant_key = substr($buffer,15,strlen($buffer)-15);
				$this->merchant_key = ereg_replace("[^0-9a-zA-Z]","",$this->merchant_key);
				break;
		}
		if ($this->merchant_id && $this->merchant_key)	{return 1;}
		else											{return 0;}
	}

	function createCpiMessage() {
	// check that we have all the data
		if (!$this->ccnum)		{$this->errorHandler("createCpiMessage Error - No ccnum");return 0;}
		if (!$this->ccexp)		{$this->errorHandler("createCpiMessage Error - No ccexp");return 0;}
		if (!$this->ccname)		{$this->errorHandler("createCpiMessage Error - No ccname");return 0;}
		if (!$this->ccaddress)	{$this->errorHandler("createCpiMessage Error - No ccaddress");return 0;}
		if (!$this->cccity)		{$this->errorHandler("createCpiMessage Error - No cccity");return 0;}
		if (!$this->ccstate)	{$this->errorHandler("createCpiMessage Error - No ccstate");return 0;}
		if (!$this->cczip)		{$this->errorHandler("createCpiMessage Error - No cczip");return 0;}
		if (!$this->cccountry)	{$this->errorHandler("createCpiMessage Error - No cccountry");return 0;}

	// prepare string
		$ret = "cpi.card-number=" . $this->ccnum;
		$ret .= "&cpi.card-exp=" . $this->ccexp;
		$ret .= "&cpi.card-name=" . $this->ccname;
		$ret .= "&cpi.card-address=" . $this->ccaddress;
		$ret .= "&cpi.card-city=" . $this->cccity;
		$ret .= "&cpi.card-state=" . $this->ccstate;
		$ret .= "&cpi.card-zip=" . $this->cczip;
		$ret .= "&cpi.card-country=" . $this->cccountry;
		$ret = rawurlencode($ret);
		return($ret);
	}

	function createMoMessage() {
	// check that we have all the data
		if (!$this->orderNum)		{$this->errorHandler("createMoMessage Error - No orderNum");return 0;}
		if (!$this->amount)			{$this->errorHandler("createMoMessage Error - No amount");return 0;}
	// prepare string
		$ret = "mo.cybercash-id=" . $this->merchant_id;
		$ret .= "&mo.version=" . $this->MCKversion;
		$ret .= "&mo.signed-cpi=no";
		$ret .= "&mo.order-id=" . $this->orderNum;
		$ret .= "&mo.price=" . $this->amount;
		$ret .= "&mo.product-descr=%OA";
		$ret = rawurlencode($ret);
		return($ret);
	}

	function createEncryptedMessage($msg) {
	// check that we have all the data
		if (!$msg)	{$this->errorHandler("createEncryptedMessage Error - No msg");return 0;}
	// store the string in the text file
		$CRYPTinFD = fopen($this->encryptedFile0, "w");
			fwrite($CRYPTinFD,$this->merchant_key,strlen($this->merchant_key));
			fwrite($CRYPTinFD,"\r\n",strlen("\r\n"));
			fwrite($CRYPTinFD,$msg,strlen($msg));
			fwrite($CRYPTinFD,"\r\n",strlen("\r\n"));
		fclose($CRYPTinFD);
	// encrypt the stored string
		exec($this->CRYPTcommand);
		return 1;
	}

	function createMacMessage($mac,$sessionkey,$encryptedmsg) {
	// check that we have all the data
		if (!$mac)			{$this->errorHandler("createMacMessage Error - No mac");return 0;}
		if (!$sessionkey)	{$this->errorHandler("createMacMessage Error - No sessionkey");return 0;}
		if (!$encryptedmsg)	{$this->errorHandler("createMacMessage Error - No encryptedmsg");return 0;}
	// clean it up
		$mac=rawurlencode(chop($mac));
		$encryptedmessage=rawurlencode(chop($encryptedmsg));
		$sessionkey=ereg_replace("%20","+",rawurlencode(chop($sessionkey)));
		return "mac=$mac&session-key=$sessionkey&message=$encryptedmessage";
	}

	function createArgsMessage($args)	{
		$ret = "POST " . $this->cybercashSCRIPT . "/" . $this->merchant_id . " HTTP/1.0\n";
		$ret .= "User-Agent: CCMCK-" . $this->MCKversion . "\n";
		$ret .= "Content-type: application/x-www-form-urlencoded\n";
		$ret .= "Content-length: " . strlen($args) . "\n";
		$ret .= "\n";
		$ret .= "$args";
		return $ret;
	}

	function createWorkFiles()	{
	// initialize 4 text files needed for encrypt/decrypt on this order
		$this->encryptedFile0 = $this->workBaseDir . "msg0E-" . $this->orderNum . ".txt";
			touch($this->encryptedFile0);
			chmod($this->encryptedFile0,0777);
		$this->encryptedFile1 = $this->workBaseDir . "msg1E-" . $this->orderNum . ".txt";
			touch($this->encryptedFile1);
			chmod($this->encryptedFile1,0777);
		$this->decryptedFile0 = $this->workBaseDir . "msg0D-" . $this->orderNum . ".txt";
			touch($this->decryptedFile0);
			chmod($this->decryptedFile0,0777);
		$this->decryptedFile1 = $this->workBaseDir . "msg1D-" . $this->orderNum . ".txt";
			touch($this->decryptedFile1);
			chmod($this->decryptedFile1,0777);
			
		$this->MCKencrypt = $this->ccBaseDir . "MCKencrypt";
		$this->MCKdecrypt = $this->ccBaseDir . "MCKdecrypt";
		$this->CRYPTcommand = $this->MCKencrypt . " -f " . $this->encryptedFile0 . " > " . $this->encryptedFile1;
		$this->DECRYPTcommand = $this->MCKdecrypt . " -f " . $this->decryptedFile0 . " > " . $this->decryptedFile1;

	}

	function deleteWorkFiles()	{
	// delete 4 text files used for this order
		unlink ($this->encryptedFile0);
		unlink ($this->encryptedFile1);
		unlink ($this->decryptedFile0);
		unlink ($this->decryptedFile1);
	}

};	// end class

?>
