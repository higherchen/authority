<?php

class RoleMember extends Model
{

    const INSERT_SQL = 'INSERT INTO role_member (role_id,user_id,ctime) VALUES (?,?,?)';
    const DELETE_SQL = 'DELETE FROM role_member WHERE role_id=? AND user_id=?';

    public function add($role_id, $user_id)
    {
        $stmt = $this->getStatement(self::INSERT_SQL);
        $stmt->execute([$role_id, $user_id, date('Y-m-d H:i:s')]);

        return $this->lastInsertId();
    }

    public function remove($role_id, $user_id)
    {
        $stmt = $this->getStatement(self::DELETE_SQL);
        $stmt->execute([$role_id, $user_id]);

        return $stmt->rowCount();
    }
}