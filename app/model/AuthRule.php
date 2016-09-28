<?php

class AuthRule extends Model
{

    const INSERT_SQL = 'INSERT INTO auth_rule (name,data,ctime,mtime) VALUES (?,?,?,?)';
    const DELETE_BY_ID_SQL = 'DELETE FROM auth_rule WHERE id=?';

    public function add($name, $data)
    {
        $stmt = $this->getStatement(self::INSERT_SQL);
        $now = date('Y-m-d H:i:s');
        $stmt->execute([$name, $data, $now, $now]);

        return $this->lastInsertId();
    }

    public function remove($id)
    {
        $stmt = $this->getStatement(self::DELETE_BY_ID_SQL);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }
}