<?php


class Database
{

    private $host=DB_HOST;
    private $user=DB_USER;
    private $pass=DB_PASS;
    private $dbname=DB_NAME;

    private $charset = 'utf8mb4';

    private $dbh;
    private $error;
    private $stmt;


    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname .';charset='. $this->charset;
        $options = array (
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        try {
            $this->dbh = new PDO ($dsn, $this->user, $this->pass, $options);
        }		// Catch any errors
        catch ( PDOException $e ) {
            $this->error = $e->getMessage();
        }

    }

    public function bind($param, $value, $type = null)
    {
        if (is_null ($type))
        {
            switch (true)
            {
                case is_int ($value) :
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool ($value) :
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null ($value) :
                    $type = PDO::PARAM_NULL;
                    break;
                default :
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function prepare($query)
    {
       return $this->stmt=$this->dbh->prepare($query);
    }
    public function execute()
    {
        return $this->stmt->execute();
    }

    public function set()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function count()
    {
        return $this->stmt->rowCount();
    }

    public function last_insert_id()
    {
        return $this->stmt->lastInsertId();
    }






}