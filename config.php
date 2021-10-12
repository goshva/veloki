<?php
	define('TIMEZONE', 'Europe/Moscow');
	date_default_timezone_set(TIMEZONE);
	class Config {

    const DBHOST = "185.12.95.147";
    const DBUSER = 'admin_admin';
    const DBPASS = 'X1eeJB93Vb';
    const DBNAME = 'admin_veloki';


	/*	const DBHOST = "localhost";
		const DBUSER = 'admin_admin';
		const DBPASS = 'X1eeJB93Vb';
		const DBNAME = 'gradmaster_velo';
	*/
    private $dsn = 'mysql:host=' . self::DBHOST . ';dbname=' . self::DBNAME . ';charset=utf8;';
	protected $conn = null;


	  // Constructor Function
	  public function __construct() {
	    try {
	      $this->conn = new PDO($this->dsn, self::DBUSER, self::DBPASS);
	      $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	    } catch (PDOException $e) {
	      die('Connectionn Failed : ' . $e->getMessage());
	    }
	    return $this->conn;
	  }

	  // Sanitize Inputs
	  public function test_input($data) {
	    $data = strip_tags($data);
	    $data = htmlspecialchars($data);
	    $data = stripslashes($data);
	    $data = trim($data);
	    return $data;
	  }

	  // JSON Format Converter Function
	  public function message($content, $status) {
	    return json_encode(['message' => $content, 'error' => $status]);
	  }
	}
?>
