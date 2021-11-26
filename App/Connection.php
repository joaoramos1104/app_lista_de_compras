<?php

namespace App;

class Connection {

	public static function getDb() {
		// try {

		// 	$conn = new \PDO(
		// 		"mysql:host=localhost;dbname=db_delivery;charset=utf8",
		// 		// "mysql:host=192.168.56.104:22;dbname=delivery;charset=utf8",
		// 		"root",
		// 		"joao1104" 
		// 	);

		// 	return $conn;

		// } catch (\PDOException $e) {
		// 	//.. tratar de alguma forma ..//
		// }




		// define('SERVER', '192.168.1.51:3306');
		// define('BANCO', 'concentrador');
		// define('SENHA', '123456');
		// define('USER', 'econect');


		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=db_delivery;charset=utf8",
				"root",
				""
			);
			return $conn;

//banco 2 externo
			// $conn2 = new \PDO('mysql:host=' . SERVER . ';dbname=' . BANCO, USER, SENHA);
			// return $conn2;
			
		} catch (\PDOException $e) {
			echo 'Error: ' . $e->getMessage();
		}
	

	}
}

?>