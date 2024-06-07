<?php

class LCMDB
{
    private $connDB;
    private $stateDB;
    private $publicInfo;

    //Driver PDO para otras bases de datos
    // http://docs.php.net/manual/es/pdo.drivers.php

    public function __construct($config)
    {
        
        $host = "host=".$config->GetDataBaseParams('Host');
        $dbname = ";dbname=".$config->GetDataBaseParams('Name');
        $port = $config->GetDataBaseParams('Port')!="" ? ";port=".$config->GetDataBaseParams('Port') : "";
        $charset = ";charset=UTF8";
        $dsn = $config->GetDataBaseParams('Driver').":".$host.$dbname.$port.$charset;
        try
        {
            $this->connDB = new PDO($dsn,
                $config->GetDataBaseParams('User'),
                $config->GetDataBaseParams('Password'),
                array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
            $this->connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            
            error_log("Database Loaded!!->".$dsn);
            $this->stateDB = true;
            $this->SetPublicInfo($config->GetDataBaseParams('Driver'),$config->GetDataBaseParams('Name'));
        }
        catch(PDOException $ex){
            error_log("Database failed!!->".$dsn.":".$ex->getMessage());
            $this->stateDB = false;
            $this->SetPublicInfo($config->GetDataBaseParams('Driver'),$config->GetDataBaseParams('Name'),$ex->getMessage());
        }
    }

    public function CheckDbState()
    {
        return $this->stateDB;
    }

    private function SetPublicInfo($driver, $dbName, $error = ""){
        $this->publicInfo = array(
            "driver" => $driver,
            "dbName" => $dbName,
            "error" => $error,
            "state" => ($this->stateDB ? "connected" : "disconnected")
        );
    }

    public function GetPublicInfo(){
        return $this->publicInfo;
    }

    /**
     * Devuelve la primera fila de la consulta
     * @param $sql
     * @return array|null
     */
    public function FirstRow($sql){
        $result = $this->connDB->query($sql);
        if (!$result) {
            return null;
        }       
        $row = $result->fetch();
        //$result->free();
        return $row;
    }

    /**
     * Devuelve todas las filas que devuelva la query
     * @param $sql
     * @return array|null
     */
    public function Rows($sql){
        $result = $this->connDB->query($sql);
        if (!$result) {
            return null;
        }
        $rows = $result->fetchAll();
        //$result->free();
        
        return $rows;
    }

    /**
     * Devuelve el primer registro de la table por Id
     * @param $id
     * @param $fieldId
     * @param $table
     * @return array
     */
    public function FirstRowByField($id, $fieldId, $table){
        $sql = "SELECT * FROM `$table` WHERE $fieldId='$id' LIMIT 1";
        return $this->FirstRow($sql);
    }

    /**
     * Devuelve el primer registro de la table ordenado por fieldSequence
     * @param $id
     * @param $fieldId
     * @param $table
     * @return array
     */
    public function FirstRowByFieldOrderSequence($id, $fieldId, $table, $fieldSequence){
        $sql = "SELECT * FROM `$table` WHERE $fieldId='$id' ORDER BY $fieldSequence ASC LIMIT 1";
        return $this->FirstRow($sql);
    }

    /**
     * Devuelve todos los Id de la table
     * @param $id
     * @param $fieldId
     * @param $table
     * @return $row
     */
    public function RowsAllIds($table){
        $sql = "SELECT Id FROM `$table`";
        return $this->Rows($sql);
    }

    /**
     * Devuelve todos los registros de la table por Id
     * @param $id
     * @param $fieldId
     * @param $table
     * @return $row
     */
    public function RowsByField($id, $fieldId, $table){
        $sql = "SELECT * FROM `$table` WHERE $fieldId='$id'";
        return $this->Rows($sql);
    }

    /**
     * Devuelve todos los registros de la table por lista de campos y valor (Where)
     * @param $table
     * @param $arrFields
     * @return $row
     */
    public function RowsByFields($table, $arrFields){
        $sql = "SELECT * FROM `$table` ";

        $where = "";
        foreach ($arrFields as $key => $value){
            $where .= "$key='$value' AND ";
        }

        $where = substr($where, 0, -5); //quito el espacio+AND+espacio del final de la cadena

        $sql .= "WHERE $where";

        return $this->Rows($sql);
    }

    /**
     * Devuelve todos los registros de la table por Id
     * @param $table
     * @return $row
     */
    public function AllRows($table){
        $sql = "SELECT * FROM `$table`";
        return $this->Rows($sql);
    }

    /**
     * Inserta en la tabla adecuada el registro pasado por parámetro
     * @param $Object
     * @param $table
     * @return true/false
     */
    public function AddObject($object, $table){
        $fields = json_decode(json_encode($object), true);
        return $this->Add($fields,$table);
    }

    /**
     * Inserta en la tabla adecuada el registro pasado por parámetro
     * @param $arrFields
     * @param $table
     * @return $true/false
     */
    public function Add($arrFields, $table){
        try{
            $insert = "INSERT INTO $table ";
            $fields = "";
            $values = "";
            foreach($arrFields as $key => $value){
                $fields .= $key.",";
                $values .= "'$value',";
            }
            $fields = substr($fields,0, -1);
            $values = substr($values,0, -1);

            $insert .= "($fields) VALUES ($values)";

            $this->connDB->query($insert);

            $newId = $this->connDB->lastInsertId();
            return $newId;
        }
        catch(Exception $ex){
            return false;
        }        
    }

    /**
     * Actualiza el registro adecuado al where pasado por parametro
     * @param $object
     * @param $table
     * @param $arrWhere  {$field1, $field2, ....}
     * @return string
     */
    public function ModifyObject($object, $table, $arrWhere){
        $fields = json_decode(json_encode($object), true);
        return $this->Modify($fields, $table, $arrWhere);
    }

    /**
     * Actualiza el registro adecuado al where pasado por parametro
     * @param $arrFields
     * @param $table
     * @param $arrWhere  {$field1, $field2, ....}
     * @return string
     */
    public function Modify ($arrFields, $table, $arrWhere){
        $update = "UPDATE $table ";

        $set="";
        $where = "";
        $withWhere = false;
        foreach ($arrFields as $key => $value){
            //Si la clave esta en el where
            if(in_array($key, $arrWhere)){
                $where .= "$key='$value' AND ";
                $withWhere = true;
            }
            else{
                $set .= "$key='$value',";
            }
        }

        $set = substr($set,0, -1);
        $where = substr($where, 0, -5); //quito el espacio+AND+espacio del final de la cadena

        $update.= "SET $set WHERE $where";
        error_log($update);

        //Si tiene Where ejecuto el update, esto es una proteccion para no hacer updates masivos
        $result = 0;
        $rowsAffected = 0;
        //error_log($update);
        if($withWhere)
        {
            $upd = $this->connDB->prepare($update);
            $upd->execute();
            $rowsAffected = $upd->rowCount();

            //$result = $this->connDB->query($update);
            //$rowsAffected = $this->connDB->affected_rows;
        }
        return $rowsAffected;
    }

    /**
     * Elimina los registros que cumplan el Where
     * @param $table
     * @param $arrWhere   {field1 => "value1", field2 => "value2" ....}
     * @return mixed
     */
    public function Remove ($table, $arrWhere){
        $delete = "DELETE FROM $table ";

        $where = "";
        foreach ($arrWhere as $key => $value){
            $where .= "$key='$value' AND ";
        }

        if($where !== ""){
            $where = substr($where, 0, -5); //quito el espacio+AND+espacio del final de la cadena
            $delete .= "WHERE $where";
        }

        $del = $this->connDB->prepare($delete);
        $del->execute();
        $rowsAffected = $del->rowCount();

        return $rowsAffected;
    }
}