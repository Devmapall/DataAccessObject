<?php

/*
 * Conventions:
 * 1) IDAOClient->getDBVariables returns an assoziative array.
 * 2) In this array, there is a ID-key
 * 3) Date-members end with "_date"
 */
class DataAccessObject {

    private $connection;
    private $object;
    private $table;
    private $values;
    
    public function __construct($connection) {
        $this->connection = $connection;
        
    }
    
    public function setObject(IDAOClient $object=null) {
        $this->object = $object;
        $data = $object->getDBVariables();
        $this->table = get_class($object);
        $this->values = $data;
    }
    
    public function create() {
        $c = 1;
        
        $sql = "INSERT INTO ";
        $sql .= "`".$this->table."` (";
        foreach($this->values as $k => $v) {
           if($c == count($this->values)) {
               $sql .= $k.") ";
           }  else {
               $sql .= $k.", ";
           }
           $c++;
        }
        $sql .= "VALUES (";
        $c=1;
        foreach($this->values as $k => $v) {
            if($c == count($this->values)) {
                if(is_string($v)) {
                    $sql .= "'".$v."')";
                } else if(is_int($v)) {
                    $sql .= $v.")";
                } else if($this->endswith($k,"_date")) {
                    $sql .= "NOW())";
                }
            } else {
                if(is_string($v)) {
                    $sql .= "'".$v."', ";
                } else if(is_int($v)) {
                    $sql .= $v.", ";
                }else if($this->endswith($k,"_date")) {
                    $sql .= "NOW(), ";
                }
            }
            $c++;
        }
        $sql .= ";";
        echo $sql."<br>";
        mysql_query($sql);
    }
    
    public function readById($id) {
        $sql = "SELECT * FROM ".$this->table." WHERE ID = ".$id.";";
        $result = mysql_query($sql,$this->connection) OR die(mysql_error());
        $obj = $this->buildObj($result);
        echo $sql."<br>";
        return $obj;
    }
    
    public function readByAttributes($attributes) {
        $sql = "SELECT * FROM ".$this->table." WHERE ";
        foreach($attributes as $k=>$v) {
            if(is_string($v)) {
                if(preg_match("@\%(.*?)\%@",$v)) {
                    $sql .= $k." like '".$v."' ";
                } else {
                    $sql .= $k." = '".$v."' ";
                }
            } else {
                $sql .= $k." = ".$v." ";
            }
        }
        $sql .= ";";
        echo $sql."<br>";
        $result = mysql_query($sql);
        return $this->buildObj($result);
    }
    
    private function buildObj($result) {
        $row = mysql_fetch_assoc($result);
        $obj = new $this->table();
        if($obj instanceof IDAOClient) {
            foreach($obj->getDBVariables() as $k=>$v) {
                $method = "set".$k;
                $obj->$method($row[$k]);
            }
            $obj->setID($row["ID"]);
        }
        return $obj;
    }
    public function update() {
        $sql = "UPDATE ".$this->table." SET ";
        $c = 1;
        
        foreach($this->values as $k=>$v) {
            
            if(is_string($v) || $this->endswith($v,"_date")) {
                $sql .= $k." = '".$v."' ";
            }else if(is_int($v)) {
                $sql .= $k." = ".$v." ";
            }
            
            if($c < count($this->values)) {
                $sql .= ", ";
            }
            $c++;
        }
        
        $sql .= "WHERE ID = ".$this->values["ID"].";";
        
        echo $sql."<br>";
        mysql_query($sql) OR die(mysql_error());
    }
    
    public function delete() {
        $sql = "DELETE FROM ".$this->table." WHERE ID = ".$this->values["ID"].";";
        echo $sql."<br>";
        mysql_query($sql);
    }
    
    private function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }
}

?>
