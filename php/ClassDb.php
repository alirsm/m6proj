<?php

$self_dir = dirname ( __FILE__ ) . "/";
//$self_filename = basename ( __FILE__ );
//require_once ("{$self_dir}ClassDb.php");
//require_once ("{$self_dir}Logger.php");

//if(!isset($log)) $log = new Logger(true, false, Logger::INFO, Logger::INFO, null, '/tmp/');

class Db {
	// The database connection
	protected static $connection;

	/**
	 * Connect to the database
	 *
	 * @return bool false on failure / mysqli MySQLi object instance on success
	 */
	public function connect() {
		global $self_dir;
		
		// Try and connect to the database
		if(!isset(self::$connection)) {
			// Load configuration as an array. Use the actual location of your configuration file
			$config = parse_ini_file("$self_dir/config.ini", true);
			$db = $config['mysql'];
			//self::$connection = new mysqli('127.0.0.1',$config['ali'],$config['abc123'],$config['cdr']);
			//self::$connection = new mysqli('localhost','alirsm_ali','mona2005','alirsm_cdr');
			self::$connection = new mysqli($db["host"],$db["user"],$db["password"],$db["dbname"]);
		}

		//if(self::$connection === false) {
		if ( self::$connection->connect_error ) {
			//$log->e( "ClassDb.php (". __LINE__ ."): unable to connect to mysql: err=" . mysqli_connect_errno() . " " . mysqli_connect_error() );
			
			return false;
		}
		return self::$connection;
	}

	/**
	 * Query the database
	 *
	 * @param $query The query string
	 * @return mixed The result of the mysqli::query() function
	 */
	public function query($query) {
		//global $log, $self_filename;
		
		// Connect to the database
		$connection = $this -> connect();

		// Query the database
		$result = $connection -> query($query);
		
		if( $result === false ) {
			//$log->e( "ClassDb.php (". __LINE__ ."): unable to run mysql query: {$query}" );
			return false;
		}
		
		return $result;
	}

	/**
	 * Fetch rows from the database (SELECT query)
	 *
	 * @param $query The query string
	 * @return bool False on failure / array Database rows on success
	 */
	public function select($query) {
		//global $log, $self_filename;
		
		$rows = array();
		$result = $this -> query($query);
		
		if( $result === false ) {
			//$log->e( "ClassDb.php (". __LINE__ ."): unable to run mysql query: {$query}" );
			return false;
		}
		
		while ($row = $result -> fetch_assoc()) {
			$rows[] = $row;
		}
		
		return $rows;
	}

	/**
	 * Fetch the last error from the database
	 *
	 * @return string Database error message
	 */
	public function error() {
		$connection = $this -> connect();
		return $connection -> error;
	}

	/**
	 * Quote and escape value for use in a database query
	 *
	 * @param string $value The value to be quoted and escaped
	 * @return string The quoted and escaped string
	 */
	public function quote($value) {
		$connection = $this -> connect();
		return "'" . $connection -> real_escape_string($value) . "'";
	}
	
}

//$db = new Db();
//$rows = $db -> select("SELECT `npa`,`state` FROM `LERG3` WHERE id=250");
//$rows = $db -> query("delete from employeee where id=111");
//$rows = $db -> select("select * from M6 where id=421");
//print_r($rows);
