<?php 
// PHPlib <http://phplib.shonline.de/>
// PHPlib includes for database independence:
// require('db_odbc.inc');
// require('db_mysql.inc');
// require('db_pgsql.inc');
// require('db_oracle.inc');
// require('db_sybase.inc');
// 
// Ran into safe mode restrictions across various cart installs so
// decided to include the whole file inline.  We can either copy
// the file and include it, or we include it here.  The cart scales
// better to include it here.

// We also extend the classes to include free_result(), autocommit(),
// commit() and rollback() class functions.  For mysql these do nothing
// but are in place for compatibility.

// see admin.php also; it is almost identical.

$nsecurl = 'http://'.$_SERVER['HTTP_HOST'];
$cartdir = BITCART_PKG_URL;
$securl  = 'https://'.$_SERVER['HTTP_HOST'];
$secdir  = BITCART_PKG_URL;
$maintdir= BITCART_PKG_URL.'maint';

$pub_inc=1;
$databaseeng = 'pgsql';
$dialect  = '';

class DBbase_Sql {
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  var $Link_ID  = 0;
  var $Query_ID = 0;
  var $Record   = array();
  var $Row      = 0;

  var $Errno    = 0;
  var $Error    = "";

  function connect() {
	global $FC_Link_ID;
	if( !empty($FC_Link_ID) ){
	  $this->Link_ID=$FC_Link_ID;
	}
	if ( 0 == $this->Link_ID ) {
		$cstr = "dbname=".$this->Database;
		if( $this->Host ){
		 $cstr .= ' host='.$this->Host;
		}
		if( $this->User ){
		 $cstr .= ' user='.$this->User;
		}
		if( $this->Password ){
		 $cstr .= ' password='.$this->Password;
		}
		$this->Link_ID=pg_pconnect($cstr);
		if (!$this->Link_ID) {
			$this->halt("Link-ID == false, pconnect failed");
		}
	  }
  }

  function query($Query_String) {
    $this->connect();

#   printf("<br>Debug: query = %s<br>\n", $Query_String);

    $this->Query_ID = pg_Exec($this->Link_ID, $Query_String);
    $this->Row   = 0;

    $this->Error = pg_ErrorMessage($this->Link_ID);
    $this->Errno = ($this->Error == "")?0:1;
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }

    return $this->Query_ID;
  }
  
  function next_record() {
    $this->Record = @pg_fetch_array($this->Query_ID, $this->Row++);
    
    $this->Error = pg_ErrorMessage($this->Link_ID);
    $this->Errno = ($this->Error == "")?0:1;

    $stat = is_array($this->Record);
    if (!$stat && $this->Query_ID) {
      pg_freeresult($this->Query_ID);
      $this->Query_ID = 0;
    }
    return $stat;
  }

  function seek($pos) {
    $this->Row = $pos;
  }

  function metadata($table) {
    $count = 0;
    $id    = 0;
    $res   = array();

    $this->connect();
    $id = pg_exec($this->Link_ID, "select * from $table");
    if ($id < 0) {
      $this->Error = pg_ErrorMessage($id);
      $this->Errno = 1;
      $this->halt("Metadata query failed.");
    }
    $count = pg_NumFields($id);
    
    for ($i=0; $i<$count; $i++) {
      $res[$i]["table"] = $table;
      $name             = pg_FieldName  ($id, $i);
      $res[$i]["name"]  = $name;
      $res[$i]["type"]  = pg_FieldType  ($id, $i);
//zot:  phplib is wrong, $name in field size should be $i
//Mike got the line above corrected.
      $res[$i]["len"]   = pg_FieldSize  ($id, $i);
      $res[$i]["flags"] = "";
    }
    
    pg_FreeResult($id);
    return $res;
  }

  function affected_rows() {
    return pg_cmdtuples($this->Query_ID);
  }

  function num_rows() {
    return pg_numrows($this->Query_ID);
  }

  function num_fields() {
    return pg_numfields($this->Query_ID);
  }

  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
  	if( !empty( $this->Record[$Name] ) ) {
	    return $this->Record[$Name];
	}
  }

  function p($Name) {
    print $this->Record[$Name];
  }
  
  function halt($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br />\n", $msg);
    printf("<b>PostgreSQL Error</b>: %s (%s)<br />\n",
      $this->Errno,
      $this->Error);
    die("Session halted.");
  }
}

class FC_SQL extends DBbase_Sql {
  var $Host     = "localhost";
  var $Database = "fishcart";
  var $User     = "cfowler";
  var $Password = "";

  function free_result() {
   if($this->Query_ID) {
    return pg_FreeResult($this->Query_ID);
   }
  }

  function rollback() {
    //return pg_Exec($this->Link_ID, 'rollback work');
    return 1;
  }

  function commit() {
    //return pg_Exec($this->Link_ID, 'commit work');
    return 1;
  }

  function autocommit($onezero) {
    return 1;
  }

  function insert_id($col="",$tbl="",$qual="") {
   global $FC_Link_ID;
   if( !empty($FC_Link_ID) ){
    $this->Link_ID=$FC_Link_ID;
   }
   $ires=pg_Exec($this->Link_ID,"select $col from $tbl where $qual");
   if ( !pg_Fetch_Row($ires, 0) ) {
    return 0;
   }
   $iseq = pg_Result($ires, 0, "$col");
   pg_FreeResult($ires);
   return $iseq;
  }
}
?>
