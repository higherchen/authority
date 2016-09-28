<?php

class AuthItem extends Model
{

    const GET_ALL_SQL = 'SELECT * FROM auth_item ORDER BY mtime DESC';
    const GET_BY_TYPE_SQL = 'SELECT * FROM auth_item WHERE type=?';
    const GET_BY_ID_SQL = 'SELECT * FROM auth_item WHERE id=?';
    const GET_BY_NAME_SQL = 'SELECT * FROM auth_item WHERE name=?';
    const INSERT_SQL = 'INSERT INTO auth_item (name,type,rule_id,description,data,ctime,mtime) VALUES (?,?,?,?,?,?,?)';
    const UPDATE_SQL = 'UPDATE auth_item SET name=?,description=?,data=?,mtime=? WHERE type=? AND id=?';
    const DELETE_BY_ID_SQL = 'DELETE FROM auth_item WHERE type=? AND id=?';

    public function getAll()
    {
        $items = $this->_db->query(self::GET_ALL_SQL)->fetchAll(PDO::FETCH_ASSOC);

        return array_column($items, null, 'id');
    }

    public function getByType($type)
    {
        $stmt = $this->getStatement(self::GET_BY_TYPE_SQL);
        $stmt->execute([$type]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_column($items, null, 'id');
    }

    public function add($name, $type, $rule_id = null, $description = '', $data = '')
    {
        $stmt = $this->getStatement(self::INSERT_SQL);
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$name, $type, $rule_id, $description, $data, $now, $now]);
        $count = $stmt->rowCount();

        return $count ? $this->lastInsertId() : $count;
    }

    public function update($item_id, $type, $name, $description, $data = '')
    {
        $stmt = $this->getStatement(self::UPDATE_SQL);
        $stmt->execute([$name, $description, $data, date('Y-m-d H:i:s'), $type, $item_id]);

        return $stmt->rowCount();
    }

    public function getById($id)
    {
        $stmt = $this->getStatement(self::GET_BY_ID_SQL);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByName($name)
    {
        $stmt = $this->getStatement(self::GET_BY_NAME_SQL);
        $stmt->execute([$name]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function remove($type, $id)
    {
        $stmt = $this->getStatement(self::DELETE_BY_ID_SQL);
        $stmt->execute([$type, $id]);

        return $stmt->rowCount();
    }
}
