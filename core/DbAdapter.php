<?php
namespace Core;

/**
 * Class DbAdapter
 *
 * @author Marek Karmelski
 */
class DbAdapter
{
    /**
     * @var object
     */
    private $_dbCon  = null;
    /**
     * @var object 
     */
    private $_result = null;
    /**
     *
     */
    public function __construct($host, $user, $pwd, $database, $port = 3306)
    {
        $this->_dbCon = new \mysqli($host, $user, $pwd, $database, $port);
        
        if ($this->_dbCon->connect_errno) {
            throw new \Exception('Databse connection failed! '. $this->_dbCon->connect_error);
        }
    }
    
    /**
     * @param string $query Query
     * @return mixed
     */
    public function queryDb($query) {
        if (is_object($this->_dbCon)) {
            if (($this->_result = $this->_dbCon->query($query)) === false) {
                throw new \Exception('Database query error!');
            } else {
                return true;
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * @return array Fetch a result as an associative array
     * @throws \Exception
     */
    public function getResultAssocArray()
    {
        if (is_object($this->_result)) {
            $assoc_tab = array();
            while ($row = $this->_result->fetch_assoc()) {
                array_push($assoc_tab, $row);
            }
            $this->_result->free();
            return $assoc_tab;
        } else {
            throw new \Exception('Query result not set!');
        }
    }
    
    /**
     * 
     * @return array Fetch a result row as an associative array
     * @throws \Exception
     */
    public function getResultAssocRow()
    {
        if (is_object($this->_result)) {
            $row =  $this->_result->fetch_assoc();
            $this->_result->free();
            return $row;
        } else {
            throw new \Exception('Query result not set!');
        }
    }
    
    /**
     * 
     * @return integer
     * @throws \Exception
     */
    public function getNumRows()
    {
        if (is_object($this->_result)) {
            return $this->_result->num_rows;
        } else {
            throw new \Exception('Query result not set!');
        }
    }
    
    /**
     * 
     * @return integer
     * @throws \Exception
     */
    public function getAffectedRows()
    {
        if (is_object($this->_dbCon)) {
            return $this->_dbCon->affected_rows;
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * 
     * @return integer
     * @throws \Exception
     */
    public function insertId()
    {
        if (is_object($this->_dbCon)) {
            return $this->_dbCon->insert_id;
        } else {
            throw new \Exception('Database connection not set!');
        }
    }

        /**
     * 
     * @param string $mode
     * @throws \Exception
     */
    public function setAutocommit($mode = true)
    {
        if (is_object($this->_dbCon)) {
            if ($this->_dbCon->autocommit($mode) == false) {
                throw new \Exception('Autocommit not set.');
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * @throws \Exception
     */
    public function commit()
    {
        if (is_object($this->_dbCon)) {
            if ($this->_dbCon->commit() == false) {
                throw new \Exception('Commit error.');
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * Close database connection
     * @throws \Exception
     */
    public function closeConnection()
    {
        if (is_object($this->_dbCon)) {
            $this->_dbCon->close();
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * @param string $string
     * @return string
     * @throws \Exception
     */
    public function escapeString($string)
    {
        if (is_object($this->_dbCon)) {
            return $this->_dbCon->real_escape_string($string);
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
}
