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
        $this->table = $data["table"];
        $this->values = $data["values"];
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
    }
    
    public function read($table,$id) {
        $sql = "SELECT * FROM ".$table." WHERE ID = ".$id.";";
        $result = mysql_query($sql,$this->connection);
        $row = mysql_fetch_assoc($result);
        $obj = new $table();
        if($obj instanceof IDAOClient) {
            foreach($obj->getDBVariables() as $k=>$v) {
                $method = "set".$k;
                $obj->$method($row[0][$k]);
            }
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
        }
        
        $sql .= "WHERE ID = ".$this->values["ID"].";";
        
        echo $sql;
    }
    
    public function delete() {
        $sql = "DELETE FROM ".$this->table." WHERE ID = ".$this->values["ID"].";";
        echo $sql;
    }
    
    private function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }
}

?>
