<?php
if(!defined("PROTECT")) { exit("<h3 style=\"color:#FF0000;\">STOP!</h3>"); }

//-- MySQL-s interfeisi (ARAPEFI SHECVALOT!!!)
interface iMySQL {
	public function connect();
	public function query($query);
	public function close();
}

//-- MySQL klasi
class MySQL implements iMySQL {
	// Cvladebis gamocxadeba
	private $mysqli_host = MYSQL_HOST;
	private $mysqli_user = MYSQL_USER;
	private $mysqli_pass = MYSQL_PASS;
	private $mysqli_database = MYSQL_DATABASE;
	public static $db_link;
	private $query;
	private $insertId;
	/* METODEBI */
	// Metodi connect
	public function connect() {
		if(!self::$db_link) { // tu connect jer ar gamodzaxebula
			self::$db_link = $this;
			self::$db_link = mysqli_connect($this->mysqli_host, $this->mysqli_user, $this->mysqli_pass, $this->mysqli_database) or die("<p align=\"center\">".mysqli_error()."</p>");
			//mysqli_select_db($this->mysqli_database) or die("<p align=\"center\">".mysqli_error()."</p>");	mysqli_query("set names 'utf8'");
		} else {
			self::$db_link = null; // tu connect gamodzaxebulia
			echo "<script type=\"text/javascript\">\n<!--\nalert(\"MySQL-თან კავშირი შეწყვეტილია, რადგან თქვენ ცდილობთ დაუკავშირდეთ ორჯერ!\")\n-->\n</script>";
		}
	}
	// Metodi Query
	public function query($query) {
		if(self::$db_link) {
			$this->query = mysqli_query(self::$db_link,$query) or die("<p align=\"center\">".mysqli_error(self::$db_link)."</p>");
			return $this->query;
		}
	}

	public function insertId(){
		if(self::$db_link) {
			$this->insertId = mysqli_insert_id(self::$db_link) or die("<p align=\"center\">".mysqli_error(self::$db_link)."</p>");
			return $this->insertId;
		}
	}
	// Metodi close
	public function close() {
		if(self::$db_link) {
			mysqli_close(self::$db_link) or die("<p align=\"center\">".mysqli_error()."</p>");
		}
	}

} // MySQL klasis dasasruli


/*


CREATE TABLE users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(250) NOT NULL,
	email VARCHAR(250) NOT NULL UNIQUE,
	password VARCHAR(250) NOT NULL,
	secret VARCHAR(255),
	activation_code INT(5),
	reg_date DATETIME,
	reg_ip VARCHAR(250),
	last_login DATETIME,
	approved INT(1),
	logo VARCHAR(250),
	state INT(1),
);

CREATE TABLE history (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	module VARCHAR(250),
	array TEXT,
);


CREATE TABLE warehouse (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	wid INT(11),
	name VARCHAR(250),
	quantity FLOAT,
	dimension VARCHAR(250),
	unit_price FLOAT,
	refill_date DATE,
	date DATE,
	edit_date DATE,
	distributor_id INT(5),
	waybill VARCHAR(250),
	description TEXT,
);


CREATE TABLE tables (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	table_no INT(3),
	session_id VARCHAR(250),
	default_guest_count INT(3),
	reserver_code INT(5),
	reservec_time DATETIME,
	discount FLOAT,
	state INT(1),
	special_guest INT(1),
	payment_method VARCHAR(250),
	cash FLOAT,
	card FLOAT,

);


CREATE TABLE orders (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	menu_item_id INT(5),
	quantity INT(5),
	aditional_menu_item_id INT(5),
	order_time DATETIME,
	table_id INT(11),
	session_id VARCHAR(250),
);


CREATE TABLE menus (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(250),
	sell_price FLOAT,
	cost_price FLOAT,
	dimesnion VARCHAR(250),
	ingredients TEXT,
	average_time VARCHAR(250),
);


CREATE TABLE distributors (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(250),
	phone VARCHAR(250),
);





*/

?>