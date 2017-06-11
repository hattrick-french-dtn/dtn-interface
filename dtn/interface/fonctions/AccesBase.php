<?php
//----------------------------------------------
// Classe d'acces aux bases de données MYSQL
//----------------------------------------------

class AccesBase{

	var $CONNECTION;
	var $DATABASE = "";
	var $USERNAME = "";
	var $PASSWORD = "";
	var $SERVER = "";
	var $isSqlTrace=false;
	var $fileSqlTrace="";
		
	function signaleErr($numErr) {
		if($numErr==2) {
			print("Erreur de connexion<br/>".$this->CONNECTION->errorCode().":");
			print_r($this->CONNECTION->errorInfo());
			return false;
		}
	
		if($numErr==1) {
			print("Erreur d'execution Mysql<br/>Requete non appropriée<br/>".$this->CONNECTION->errorCode().":");
			print_r($this->CONNECTION->errorInfo());
			return false;
		}
	
		print("Erreur d'execution Mysql<br/>".$this->CONNECTION->errorCode().":");
		print_r($this->CONNECTION->errorInfo());
		return false;
	}

	function init() {
		global $conn;
		$user = $this->USERNAME;
		$pass = $this->PASSWORD;
		$server = $this->SERVER;
		$dbase = $this->DATABASE;

		$this->CONNECTION = $conn;
		return true;
	}

	function setSqlTrace($file) {
		if ($file) {
			$this->isSqlTrace=true;
			$this->fileSqlTrace=$file;
		} else {
			$this->isSqlTrace=false;
		}
	}
	
	function trace($sql) {
		if ($this->isSqlTrace) {
			//trace activée
			$handle=fopen($this->fileSqlTrace,"a");

			// Assurons nous que le fichier est accessible en écriture
			if (is_writable($this->fileSqlTrace)) {
				//ouverture de fichier
				if (!$handle = fopen($this->fileSqlTrace, 'a')) {
					print "Impossible d'ouvrir le fichier ".$this->fileSqlTrace;
					exit;
				}
				// Write $somecontent to our opened file.
				if (!fwrite($handle, $sql.";\r\n")) {
					print "Impossible d'écrire dans le fichier ($filename)";
					exit;
				}
				fclose($handle);
			} else {
				print "Le fichier $filename n'est pas accessible en écriture.";
			}
		}
	}
	

	function select ($sql) {
		if(empty($sql)) return false;
		if(!preg_match("/^select/i",$sql)) { $this->signaleErr(1); return false; }

		if(empty($this->CONNECTION)) {$this->signaleErr(2); return false; }
		$results = $this->CONNECTION->query($sql);
		if( (!$results) or (empty($results)) ) {$this->signaleErr(0); return false; }
		$c = 0;
		$data = array();
		while ( $row = $results->fetch(PDO::FETCH_BOTH))
		{
			$data[$c] = $row;
			$c++;
		}
		$results=NULL;
		return $data;
	}

	function insert ($sql) {
		if(empty($sql)) { return false; }
		if(!preg_match("/^insert/i",$sql))
		{
			$this->signaleErr(1);
			return false;
		}
		if(empty($this->CONNECTION)) {$this->signaleErr(2); return false; }
		$results = $this->CONNECTION->exec($sql);
		if(!$results) {$this->signaleErr(0); return false; }
		$this->trace($sql);
		//$result = mysql_insert_id();
		//return $result;
		return true;
	}

	function update($sql)
	{
		if(empty($sql)) return false;
		if(!preg_match("/^update/i",$sql)) {$this->signaleErr(1); return false; }
		if(empty($this->CONNECTION)) {$this->signaleErr(2); return false; }
		$results = $this->CONNECTION->exec($sql);
		if(!$results) {$this->signaleErr(0); return false; }
		$this->trace($sql);
		return true;
	}

	function delete($sql)
	{
		if(empty($sql)) return false;
		if(!preg_match("/^delete/i",$sql)) {$this->signaleErr(1); return false; }
		if(empty($this->CONNECTION)) {$this->signaleErr(2); return false; }
		$results = $this->CONNECTION->exec($sql);
		if(!$results) {$this->signaleErr(0); return false; }
		$this->trace($sql);
		return true;
	}

	function query($sql)
	{
		if(empty($sql)) {$this->signaleErr(1); return false; }
		if(empty($this->CONNECTION)) {$this->signaleErr(2); return false; }
		$results = $this->CONNECTION->query($sql);
		if(!$results) {$this->signaleErr(0); return false; }
		return true;
	}
	
	function close()
	{
		$this->CONNECTION = NULL;
	}

	function last_index_id() {
		return($this->CONNECTION->lastInsertId());
	}

	// function list_tables($db) {
		// return(mysql_list_tables($db));
	// }

	// function count_rows($table) {
		// return(mysql_numrows($table));
	// }

	// function nom_tables($table,$i) {
		// return(mysql_tablename($table,$i));
	// }


	// function count_records($db,$table) {
		// $result = mysql_db_query($db, "select count(*) as num from ".$table);
		// $num = mysql_result($result,0,"num");
		// return($num);
	// }
}

//--------------------------------------------------------------------------
//Initialisation de la BDD
//--------------------------------------------------------------------------
function initBD() {
        //création de l'objet base
		$maBase                   = new AccesBase;
        // $maBase->DATABASE         = DATABASE;
        // $maBase->USERNAME         = USERNAME;
        // $maBase->PASSWORD         = PASSWORD;
        // $maBase->SERVER           = SERVER;
        $maBase->init();

		//activation trace SQL
		$maBase->setSqlTrace(SQL_FILE);
			
        return ($maBase);
}



?>