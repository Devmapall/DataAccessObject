<?php
require_once("IDAOClient.php");

class ExampleClass implements IDAOClient {
    
    private $ID;
    private $Name;
    private $Nr;
    private $Create_date;
    
    public function getID() {
        return $this->ID;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }
    
    public function getName() {
        return $this->Name;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }

    public function getNr() {
        return $this->Nr;
    }

    public function setNr($Nr) {
        $this->Nr = $Nr;
    }

    public function getCreate_date() {
        return $this->Create_date;
    }

    public function setCreate_date($Create_date) {
        $this->Create_date = $Create_date;
    }
    
    public function getDBVariables() {
        $values = array(
            "Name" => $this->Name,
            "Nr" => $this->Nr,
            "Create_date" => $this->Create_date
        );
        if($this->ID != null) {
            $values["ID"] = $this->ID;
        }
        return $values;
    }            
}

?>
