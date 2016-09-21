<?php

class Model
{
    protected $_database = 'default';
    protected $_table = '';

    // orm instance for model
    protected $_instance;

    public function __construct($table = '', $database = '')
    {
        if ($table) {
            $this->_table = $table;
        }
        if ($database) {
            $this->_database = $database;
        }
        if (!$this->_table) {
            $this->_table = strtolower(get_called_class());
        }
        $this->_instance = \ORM::for_table($this->_table, $this->_database);
    }

    public function __get($key)
    {
        return isset($this->_instance->$key) ? $this->_instance->$key : null;
    }

    public function __set($key, $value)
    {
        $this->_instance->$key = $value;
    }

    public function __call($method, $args)
    {
        if ($this->_instance && method_exists($this->_instance, $method)) {
            try {
                return call_user_func_array(array($this->_instance, $method), $args);
            } catch (Exception $e) {
                if ($e->getCode() == 'HY000') {
                    // 如果mysql gone away，自动重连
                    \ORM::set_db(null, $this->_database);
                }
                return call_user_func_array(array($this->_instance, $method), $args);
            }
        } else {
            return false;
        }
    }

    public function clean()
    {
        $this->_instance = \ORM::for_table($this->_table, $this->_database);

        return $this;
    }
}
