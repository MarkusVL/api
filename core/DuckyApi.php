<?php
namespace Core;

use \Core\DbAdapter;
/**
 * Class DuckyApi
 *
 * @author Marek Karmelski
 * @version 1.0
 */
class DuckyApi
{
    /**
     * @var object 
     */
    private $_db = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        try {
            $this->_db = new DbAdapter('127.0.0.1', 'root', 'root', 'api');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * @return string JSON response
     * @throws \Exception
     */
    public function getAllUsers() {
        if (is_object($this->_db)) {
            try {
                $query = "SELECT id, name, second_name, phone FROM users";
                $this->_db->queryDb($query);
                $data = $this->_db->getResultAssocArray();
                
                $response = array();
                $response['status'] = 'OK';
                $response['data'] = $data;
                
                return $this->_jsonResponse($response);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * @return string JSON response
     * @throws \Exception
     */
    public function getUser($userId)
    {
        if (is_object($this->_db)) {
            try {
                $userId = (int) $userId;
                
                $query = "SELECT id, name, second_name, phone 
                          FROM users 
                          WHERE id=".$userId;
                $this->_db->queryDb($query);
                $data = $this->_db->getResultAssocRow();
                
                $response = array();
                $response['status'] = 'OK';
                $response['data'] = $data;
                
                return $this->_jsonResponse($response);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * 
     * @param array $user
     * @return string JSON response
     * @throws \Exception
     */
    public function creatUser(array $user)
    {
        if (is_object($this->_db)) {
            try {
                $name        = $this->_db->escapeString($user['name']);
                $second_name = $this->_db->escapeString($user['second_name']);
                $phone       = $this->_db->escapeString($user['phone']);
                
                $query = "INSERT INTO users(name, second_name, phone) 
                          VALUES('".$name."', '".$second_name."', ".$phone.")";
                
                $this->_db->queryDb($query);
                $id = $this->_db->insertId();
                
                $data = array_merge(array('id' => $id), $user);
                
                $response = array();
                $response['status'] = 'OK';
                $response['data'] = $data;
                
                return $this->_jsonResponse($response);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }

    public function deleteUser($userId)
    {
        if (is_object($this->_db)) {
            try {
                $userId = (int)$userId;
                
                $query = "DELETE FROM users 
                          WHERE id=".$userId;
                $this->_db->queryDb($query);
                
                $response = array();
                $response['status'] = 'OK';
                $response['message'] = 'User deleted';
                
                return $this->_jsonResponse($response);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }
    
    /**
     * 
     * @param array $user
     * @return string JSON response
     * @throws \Exception
     */
    public function updateUser(array $user)
    {
        if (is_object($this->_db)) {
            try {
                $id          = (int)$this->_db->escapeString($user['id']);
                $name        = $this->_db->escapeString($user['name']);
                $second_name = $this->_db->escapeString($user['second_name']);
                $phone       = (int)$this->_db->escapeString($user['phone']);
                
                $query = "UPDATE users 
                          SET name = '".$name."',
                              second_name = '".$second_name."',
                              phone = ".$phone." 
                          WHERE id=".$id;
                
                $this->_db->queryDb($query);
                
                $response = array();
                $response['status'] = 'OK';
                $response['data'] = $user;
                
                return $this->_jsonResponse($response);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            throw new \Exception('Database connection not set!');
        }
    }

    /**
     * @param array $response
     * @return string JSON string
     */
    private function _jsonResponse(array $response) {
        return json_encode($response);
    }
}
