<?php
class DatabaseShell
	{
		private $link;
		
		public function __construct($host, $user, $password, $database)
		{
			$this->link = mysqli_connect($host, $user, $password, $database);
			mysqli_query($this->link, "SET NAMES 'utf8'"); // устанавливаем кодировку
		}
		
		public function save($table, $data)
		{
			// сохраняет запись в базу
				$resalt = '';
	        foreach ($data as $key => $value) {
		        if (is_string($value)) {
			      $resalt .= " $key = '$value' ,";
		        }else{
			    $resalt .= " $key = $value ,";
		        }
		    }    
		    $str = trim($resalt,',');

		
	        $query = " INSERT INTO $table SET $str ";
	        $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));

		}
		
		public function del($table, $id)
		{
			// удаляет запись по ее id
			$query = "DELETE FROM $table WHERE id=$id";
			$result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
		}
		
		public function delAll($table, $ids)
		{
			// удаляет записи по их id
			$resalt = '';
	        foreach ($ids as $key) {
		        $resalt.= "$key ,";
	        }
            $str = rtrim($resalt,',');

			$query = "DELETE FROM $table WHERE id IN ($str)";
			$result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
		}
		
		public function get($table, $id)
		{
			// получает одну запись по ее id
			$query = "SELECT * FROM $table WHERE id = $id";
			$result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
			$row = mysqli_fetch_assoc($result);
			return $row;

		}
		
		public function getAll($table, $ids)
		{
			// получает массив записей по их id
			$resalt = '';
	        foreach ($ids as $key) {
		        $resalt.= "$key ,";
	        }
            $str = rtrim($resalt,',');

			$query = "SELECT * FROM $table WHERE id IN ($str)";
			$result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
			for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
			return $data;
	    }
		
		public function selectAll($table, $condition)
		{
			// получает массив записей по условию
			$query = "SELECT * FROM $table $condition";
			$result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
			for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
			return $data;
        }
	}

	$db = new DatabaseShell('localhost', 'mysql', 'mysql','test');
	//$db->save('users',  ['name' => 'vasa','famaly'=>'Petrov']);
	//$db->del('users', 16);
	//$db->delAll('users', [8, 9, 10]);
	//$user = $db->get('users', 11);
	//var_dump($user);
	//$users = $db->getAll('users', [11, 12, 14]);
	//var_dump($users);
	$users = $db->selectAll('users', 'where id >= 14');
	var_dump($users);

	