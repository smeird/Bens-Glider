<?php

class SelectList
{
    protected $conn;
 
        public function __construct()
        {
            $this->DbConnect();
        }
 
        protected function DbConnect()
        {
            include("db/dbconn.php");
            $this->conn = mysql_connect($host,$user,$password) OR die("Unable to connect to the database");
            mysql_select_db($dbname,$this->conn) OR die("can not select the database $dbname");
            return TRUE;
        }
 
        public function ShowCategory()
        {
            $sql = "SELECT DISTINCT
					data_log.id_location, location.name
					FROM
						ben.data_log,
						ben.location,
						ben.data_type
					WHERE
						data_log.data_type = data_type.id_data_type
						AND data_log.id_location = location.id_location";
						
            $res = mysql_query($sql,$this->conn);
            //$category = '<option value="0">choose...</option>';
			$category = '';
            while($row = mysql_fetch_array($res))
            {
                $category .= '<option value="' . $row['id_location'] . '">' . $row['name'] . '</option>';
            }
            return $category;
        }
        
        public function ShowLocation()
        {
            $sql = "SELECT DISTINCT
					data_log.id_location, location.name
					FROM
						ben.data_log,
						ben.location,
						ben.data_type
					WHERE
						data_log.data_type = data_type.id_data_type
						AND data_log.id_location = location.id_location";
						
            $res = mysql_query($sql,$this->conn);
            //$category = '<option value="0">choose...</option>';
			$menu = '';
            while($row = mysql_fetch_array($res))
            {
                //$category .= '<option value="' . $row['id_location'] . '">' . $row['name'] . '</option>';
                $menu .= '<li> <a href="/maxmingraph.php?LOC=' . $row['id_location'] . '"><span>' . $row['name'] . '</span></a></li>';
            }
            return $menu;
        }
  
 
        public function ShowType()
        {
            $sql = "SELECT DISTINCT
						data_log.data_type as A, data_type.name
					FROM
						ben.data_log,
						ben.location,
						ben.data_type
					WHERE
						data_log.data_type = data_type.id_data_type
							AND data_log.id_location = location.id_location
							AND data_log.id_location = $_POST[id];";
            //echo $sql;
			$res = mysql_query($sql,$this->conn);
            //$type = '<option value="0">choose...</option>';
			$type = '';
            while($row = mysql_fetch_array($res))
            {
                $type .= '<option value="' . $row['A'] . '">' . $row['name'] . '</option>';
            }
            return $type;
        }
}
 
$opt = new SelectList();
?>
