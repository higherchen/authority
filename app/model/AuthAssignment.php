<?php

class AuthAssignment extends Model
{

    const GET_ALL_SQL = 'SELECT * FROM auth_assignment ORDER BY user_id DESC';
    const GET_BY_USER_ID_SQL = 'SELECT item_id FROM auth_assignment WHERE user_id=?';
    const GET_BY_ITEM_ID_SQL = 'SELECT user_id FROM auth_assignment WHERE item_id=?';
    const INSERT_SQL = 'INSERT INTO auth_assignment (user_id,item_id,ctime) VALUES (?,?,?)';
    const DELETE_BY_USER_ID = 'DELETE FROM auth_assignment WHERE user_id=?';

    // fetch data
    public function getAll()
    {
        return $this->_db->query(self::GET_ALL_SQL)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMulti($user_id, $item_ids)
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->getStatement(self::INSERT_SQL);
        foreach ($item_ids as $item_id) {
            $stmt->execute([$user_id, $item_id, $now]);
        }

        return true;
    }

    public function updateMulti($user_id, $item_ids, $remove_ids)
    {
        if ($remove_ids) {
            $remove_ids = implode(',', $remove_ids);
            $this->_db->exec("DELETE FROM auth_assignment WHERE user_id={$user_id} AND item_id IN ({$remove_ids})");
        }
        if ($item_ids) {
            $this->addMulti($user_id, $item_ids);
        }

        return true;
    }

    public function removeByUserId($user_id) 
    {
        $stmt = $this->getStatement(self::DELETE_BY_USER_ID);
        $stmt->execute([$user_id]);

        return $stmt->rowCount();
    }

    public function getItemIdsByUserId($user_id)
    {
        $stmt = $this->getStatement(self::GET_BY_USER_ID_SQL);
        $stmt->execute([$user_id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUserIdsByItemId($item_id)
    {
        $stmt = $this->getStatement(self::GET_BY_ITEM_ID_SQL);
        $stmt->execute([$item_id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUserIdsByItemIds($item_ids) 
    {
        $item_ids = implode(',', $item_ids);

        return $this->_db->query("SELECT user_id FROM auth_assignment WHERE item_id IN ({$item_ids})")->fetchAll(PDO::FETCH_COLUMN);
    }
}
