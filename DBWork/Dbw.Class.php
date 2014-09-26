<?php

namespace DBWork;

use PDO;
use Exception;

class Dbw
{

    /**
     * [$_conR description]
     * @var Object
     */
    private static $_conR = null;

    /**
     * [$_cnnW description]
     * @var Object
     */
    private static $_cnnW = null;

    /**
     * [$_dbh description]
     * @var Object
     */
    private $_dbh;

    /**
     * [$_user description]
     * @var string
     */
    private $_user;

    /**
     * [$_dbname description]
     * @var string
     */
    private $_dbname;

    /**
     * [$_pass description]
     * @var string
     */
    private $_pass;

    /**
     * [$_options description]
     * @var array
     */
    private $_options;

    /**
     * [$_host description]
     * @var string
     */
    private $_host;

    /**
     * [$_sql description]
     * @var string
     */
    private $_sql;

    /**
     * [$_fields description]
     * @var array
     */
    private $_fields       = array();

    /**
     * [$_params description]
     * @var array
     */
    private $_params       = array();

    /**
     * [$_where description]
     * @var array
     */
    private $_where        = array();

    /**
     * [$_addLimit description]
     * @var boolean
     */
    private $_addLimit     = false;

    /**
     * [$_limit description]
     * @var integer
     */
    private $_limit;

    /**
     * [$_rowsCount description]
     * @var integer
     */
    private $_rowsCount;

    /**
     * [$_type description]
     * @var [type]
     */
    private $_type;

    /**
     * [$_query description]
     * @var resource
     */
    private $_query;

    /**
     * [$_debug description]
     * @var boolean
     */
    private $_debug;


    public function __construct($dbParams)
    {
        $this->_host    = $dbParams['host'];
        $this->_dbname  = $dbParams['dbname'];
        $this->_user    = $dbParams['user'];
        $this->_pass    = $dbParams['pass'];
        $this->_options = $dbParams['options'];
        $this->_limit   = 1;

        try {

            $this->_dbh = new PDO("mysql:host=" . $this->_host . ";dbname=" . $this->_dbname, $this->_user, $this->_pass, $this->_options);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
       
    }

    public static function __callStatic($method, $params)
    {
 

        switch ($method) {

            case 'R':

                if (self::$_conR === null) {

                    if (empty($params)) {

                        $dbParams = array (
                                        'host'    => "localhost",
                                        'user'    => "root",
                                        'pass'    => "123",
                                        'dbname'  => "northwind",
                                        'options' => array(
                                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES hebrew",
                                            PDO::ATTR_TIMEOUT => 1000
                                            )
                                        );
                    } else {
                            
                        $dbParams = array (
                                        'host'    => $params[0]['host'],
                                        'user'    => $params[0]['user'],
                                        'pass'    => $params[0]['pass'],
                                        'dbname'  => $params[0]['dbname'],
                                        'options' => $params[0]['options']
                                        );
                    }
                   

                    self::$_conR =  new self($dbParams);
                }

                return self::$_conR;

                break;

            case 'W':

                if (self::$_conR === null) {

                    if (empty($params)) {

                        $dbParams = array (
                                        'host'    => "localhost",
                                        'user'    => "root",
                                        'pass'    => "123",
                                        'dbname'  => "northwind",
                                        'options' => array(
                                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES hebrew",
                                            PDO::ATTR_TIMEOUT => 1000
                                            )
                                        );
                    } else {
                            
                        $dbParams = array (
                                        'host'    => $params[0]['host'],
                                        'user'    => $params[0]['user'],
                                        'pass'    => $params[0]['pass'],
                                        'dbname'  => $params[0]['dbname'],
                                        'options' => $params[0]['options']
                                        );
                    }

                    self::$_cnnW =  new self($dbParams);
                }

                return self::$_cnnW;

                break;


        }

    }

    public function __call($method, $params)
    {

    
        if (preg_match('/^where(\d$)/', $method, $matches)) {

            $group = (int)$matches[1];
            
            if (count($params) === 3) {

                $this->where($group, $params[0], $params[1], $params[2]);
                
            } else {
                $this->where($group, $params[0], $params[1], "=");

            }
           
            return $this;
        }

        if (preg_match('/^whereBetween(\d$)/', $method, $matches)) {

            $group = (int)$matches[1];
            $this->whereBetween($group, $params[0], $params[1], $params[2]);
                      
            return $this;
        }


        if (preg_match('/^whereIn(\d$)/', $method, $matches)) {

            $group = (int)$matches[1];
            $this->whereIn($group, $params[0], $params[1]);
                      
            return $this;
        }


    }

    /*****************************************************/
    /* *************** SELECT METHOD ***************** */
    public function select($sql, $debug = false)
    {

        $this->_sql   = $sql;
        $this->_debug = $debug;

        preg_match_all('/[0-9]{1,}|\'.+?\'|true|false/i', $this->_sql, $matches);

        $this->_params = [];
        $this->_params = $matches[0];

        $this->_sql    = preg_replace_callback('/[0-9]{1,}|\'.+?\'|true|false/i', function () {
            
            return "?";

        }, $this->_sql);

        if ($this->_debug === true) {

            Debug::log($this->_sql, $this->_params);

        } else {

            $this->runSQL();

        }
       
    }


    /*****************************************************/
    /* *************** INSERT METHOD ***************** */
    public function insert($table, array $sql, $debug = false)
    {
        $this->_params = [];
        $this->_debug        = $debug;
        $fields              = array_keys($sql);
        $values              = array_values($sql);
        $this->_params = array_values($sql);

        $fields = array_map(function ($key) {
            return "`" . $key . "`";
        }, $fields);


        $values = array_map(function ($key) {
            return "?";
        }, $values);
       


        $this->_sql = " INSERT INTO " . $table . " (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";


        if ($this->_debug === true) {

            Debug::log($this->_sql, $this->_params);
          
        } else {

            $this->runSQL();
         
            return $this->_query->rowCount();
            
        }

        
    }


    /*****************************************************/
    /* *************** DELETE METHOD ***************** */
    public function delete($table)
    {
        $this->_params = [];
        $this->_addLimit = true;
        $this->_sql = "DELETE FROM " . $table;
        return $this;
    }


    /*****************************************************/
    /* *************** UPDATE METHOD ***************** */

    public function update($table, array $sql)
    {
        $this->_params = [];
        $this->_fields = array_keys($sql);
        $params        = array_values($sql);

        $this->_fields = array_map(function (&$key) {

            return '`' . $key . '`' . ' = ?';

        }, $this->_fields);

        
        $this->_params = array_map(function (&$param) {

                return $param;

        }, $params);

        $this->_sql = "UPDATE " . $table . " SET " . implode(", ", $this->_fields);
    
        return $this;
    }

    /*****************************************************/
     /* *************** WHERE METHODS ***************** */
    public function where($group, $field, $value, $separator)
    {
         
        array_push($this->_params, $value);
           
        $this->_where[$group][] = '`' . $field . '` ' . $separator . ' ?';
        return $this;
        
    }

    public function whereBetween($group, $field, $value, $value2)
    {
        
        array_push($this->_params, $value, $value2);
           
        $this->_where[$group][] = '(`' . $field . '` BETWEEN ? AND ?)';
             
        return $this;
        
    }


    public function whereIn($group, $field, array $values)
    {
        $inVals = array_map(function ($key) {

            return "?";

        }, $values);

        array_walk($values, function ($item) {

            array_push($this->_params, $item);
        });
          
        $this->_where[$group][] = '`' . $field . '` IN (' . implode(", ", $inVals) . ')';
             
        return $this;
        
    }

    /**************************************************************************/
    /* *************** EXECUTING UPDATE AND DELETE METHODS ***************** */
    public function exec($debug = false)
    {
        

        $where   = '';
        $counter = 0;
        $whereSQL = array();


        array_walk($this->_where, function ($item, $key) use (&$whereSQL) {
            
            $whereSQL = array_merge($whereSQL, $item);

        });


        if (is_array($whereSQL)) {

            foreach ($whereSQL as $key => $value) {

                $counter ++;

                if ($counter !== count($whereSQL)) {
                  
                    $where .= $value . " AND ";
                } else {

                     $where .= $value;
                }
            }
        }
        
        if ($this->_addLimit === true) {
            
            $this->_sql = $this->_sql . " WHERE ". $where . " LIMIT " . $this->_limit;
        
        } else {
             $this->_sql = $this->_sql . " WHERE ". $where;
        }

        if ($debug === true) {

            Debug::log($this->_sql, $this->_params);
            unset($this->_where);

        } else {

            if ($this->runSQL()) {

                unset($this->_where);

                return $this->_query->rowCount();

            } else {

                return false;
            }

            
        }
        
    }

    /* EXECUTE SQL */
    private function runSQL()
    {
        try {

            $this->_query = $this->_dbh->prepare($this->_sql);
                

            foreach ($this->_params as $key => &$param) {
                 
                $this->_type = is_null($param)    ? PDO::PARAM_NULL : PDO::PARAM_STR;
                $this->_type = is_bool($param)    ? PDO::PARAM_BOOL : PDO::PARAM_STR;
                $this->_type = is_integer($param) ? PDO::PARAM_INT  : PDO::PARAM_STR;

                $this->_query->bindParam($key+1, $param, $this->_type);

            }
                
            $this->_query->execute();

            return $this;
                
        } catch (Exception $e) {

            echo $e->getMessage();
                
        }
    }

    /***************************************************/
    /**************************************************/
    /**************** FETCH METHODS ******************/


    /* ********** FETCH FOR WHILE LOOP *********** */
    /*=============================================*/
    public function fetch()
    {
        if ($this->_debug === false) {
    
            $this->_query->setFetchMode(PDO::FETCH_ASSOC);
            return $this->_query->fetch();
            
              

        }
    }

    public function oFetch()
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_OBJ);
            return  $this->_query->fetch();

        }
         
    }

    public function fetchClass($className)
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_CLASS, $className);
            return  $this->_query->fetch();

        }
         
    }

    public function fetchClassAfterConstr($className)
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
            return  $this->_query->fetch();

        }
    }

    public function fetchIntoClass($newClassCall)
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_INTO, $newClassCall);
            return  $this->_query->fetch();

        }

    }


    /* ******* FETCH ALL WITHOUT WHILE LOOP ******* */
    /*==============================================*/
    public function fetchAll()
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_ASSOC);
            return  $this->_query->fetchAll();

        }
         
    }


    public function oFetchAll()
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_OBJ);
            return  $this->_query->fetchAll();

        }
      
    }


    public function fetchClassAll($className)
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_CLASS, $className);
            return  $this->_query->fetchAll();

        }
        
    }
                   
    public function fetchClassAllAfterConstr($className)
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROP_LATE, $className);
            return  $this->_query->fetchAll();

        }
        
    }

    public function fetchAllIntoClass($newClassCall)
    {
        if ($this->_debug === false) {

            $this->_query->setFetchMode(PDO::FETCH_INTO, $newClassCall);
            return  $this->_query->fetchAll();
        }
        
    }


    /***********************************************************/
    /* *************** LAST INSERT ID METHOD ***************** */
    public function getLastInsertId()
    {
        if ($this->_debug === false) {

            return (int)$this->_dbh->lastInsertId();

        } else {
            
            throw new Exception("Error Processing Request! Disable DEBUG first");
            
        }
    }

    /* Convert hebrew characters to ASCII */
    public static function heb2txt($str)
    {
        $match   = array(chr(171), chr(187), chr(182), chr(92), chr(47));
        $replace = array(chr(34), chr(34), chr(39), '', '');

           return str_replace($match, $replace, $str);
    }

    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }
}
