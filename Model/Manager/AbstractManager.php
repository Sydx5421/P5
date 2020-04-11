<?php

namespace App\Model\Manager;

abstract class AbstractManager
{
    private $hasWhere = false;

    protected $query = "";
    protected $db;
    
    protected function dbConnect()
    {
//        $yaml = yaml_parse_file('./Config/parameters.yml');
//
//        $host = $yaml["database"]["host"];
//        $dbname = $yaml["database"]["dbname"];
//        $username = $yaml["database"]["username"];
//        $password = $yaml["database"]["password"];
        
//        $db = new \PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $username, $password);
        $this->db = new \PDO('mysql:host=localhost;dbname=p5_test;charset=utf8', 'root', '');
        return $this->db;
    }

    /**
     * @param string|array $table
     * @param array $fields
     * @return AbstractManager
     */
    protected function select($table, $fields)
    {
        $alias = "";

        if(is_array($table)){
            foreach ($table as $aliasName => $tableName){
                $table = $tableName;
                $alias = "`$aliasName`";
                break;
            }
        }

        $fields = implode("`, `", $fields);

        $this->query = "SELECT `$fields` FROM `$table` $alias ";

        return $this;
    }

    protected function where($condition)
    {
        $keyWord = $this->hasWhere? "AND" : "WHERE";
        $this->query .= " $keyWord ($condition) ";
        $this->hasWhere = true;

        return $this;
    }

    protected function sqlRows()
    {
        $req = $this->db->execute($this->query);

        $result = array();
        while ($row = $req->fetch(\PDO::FETCH_ASSOC)){
            $result[] = $row;
        }

        return $result;
    }

}