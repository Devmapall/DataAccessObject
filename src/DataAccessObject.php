<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Marcel
 */
class DataAccessObject {
    //put your code here
    private $object;
    private $table;
    private $values;
    
    public function __construct(IDAOClient $object=null) {
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
        }
        $sql .= "VALUES (";
        $c=1;
        foreach($this->values as $k => $v) {
            if($c == count($this->values)) {
                if(is_string($v)) {
                    $sql .= "'".$v."')";
                } else if(is_int($v)) {
                    $sql .= $v.")";
                } else if($k == "dataset_created") {
                    $sql .= "NOW())";
                }
            } else {
                if(is_string($v)) {
                    $sql .= "'".$v."', ";
                } else if(is_int($v)) {
                    $sql .= $v.", ";
                }else if($k == "dataset_created") {
                    $sql .= "NOW(), ";
                }
            }
        }
        $sql .= ";";
        echo $sql."<br>";
    }
    
    public static function read($id) {
        
    }
    
    public function update() {
        
    }
}

?>
