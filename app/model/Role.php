<?php

class Role extends Model
{

    const GET_ALL_SQL = 'SELECT * FROM role ORDER BY id DESC';
    const GET_BY_ID_SQL = 'SELECT * FROM role WHERE id=?';
    const INSERT_SQL = 'INSERT INTO role (type,name,rule,ctime,mtime) VALUES (?,?,?,?,?)';
    const UPDATE_SQL = 'UPDATE role SET name=?,rule=?,mtime=? WHERE id=?';
    const DELETE_BY_ID_SQL = 'DELETE FROM role WHERE id=?';

    public function getAll()
    {
        $roles = $this->_db->query(self::GET_ALL_SQL)->fetchAll(PDO::FETCH_ASSOC);

        return array_column($roles, null, 'id');
    }

    public function add($type, $name, $rule) 
    {
        $stmt = $this->getStatement(self::INSERT_SQL);
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$type, $name, $rule, $now, $now]);

        return $this->lastInsertId();
    }

    public function update($id, $name, $rule = '')
    {
        $stmt = $this->getStatement(self::UPDATE_SQL);
        $stmt->execute([$name, $rule, date('Y-m-d H:i:s'), $id]);

        return true;
    }

    public function getById($id)
    {
        $stmt = $this->getStatement(self::GET_BY_ID_SQL);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function remove($id)
    {
        $stmt = $this->getStatement(self::DELETE_BY_ID_SQL);
        $stmt->execute([$id]);

        return $stmt->rowCount();

    }
}
