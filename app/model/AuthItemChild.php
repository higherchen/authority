<?php

class AuthItemChild extends Model
{

    const GET_ALL_SQL = 'SELECT * FROM auth_item_child';
    const GET_BY_PARENT_SQL = 'SELECT child FROM auth_item_child WHERE parent=?';
    const GET_BY_CHILD_SQL = 'SELECT parent FROM auth_item_child WHERE child=?';
    const INSERT_SQL = 'INSERT INTO auth_item_child (parent,child) VALUES (?,?)';

    public function getAll()
    {
        return $this->_db->query(self::GET_ALL_SQL)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelations() 
    {
        $all = $this->getAll();
        $parents = $children = [];
        foreach ($all as $item) {
            if (!isset($parents[$item['parent']])) {
                $parents[$item['parent']] = [];
            }
            $parents[$item['parent']][] = $item['child'];
            if (!isset($children[$item['child']])) {
                $children[$item['child']] = [];
            }
            $children[$item['child']][] = $item['parent'];
        }
        return ['parents' => $parents, 'children' => $children];
    }

    public function getChildren($id)
    {
        $stmt = $this->getStatement(self::GET_BY_PARENT_SQL);
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getParent($id)
    {
        $stmt = $this->getStatement(self::GET_BY_CHILD_SQL);
        $stmt->execute([$id]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function add($parent, $child)
    {
        $stmt = $this->getStatement(self::INSERT_SQL);
        if (is_array($child)) {
            foreach ($child as $item) {
                $stmt->execute([$parent, $item]);
            }
        } else {
            $stmt->execute([$parent, $child]);
        }

        return true;
    }

    public function remove($parent = '', $child = '')
    {
        $where = [];
        if ($parent) {
            $where[] = "parent={$parent}";
        }
        if ($child) {
            $where[] = "child={$child}";
        }
        if ($where) {
            $where = implode(' AND ', $where);
            return $this->_db->exec("DELETE FROM auth_item_child WHERE {$where}");
        }

        return false;
    }

    public function removeMulti($parents, $children) 
    {
        $parents = implode(',', $parents);
        $children = implode(',', $children);
        return $this->_db->exec("DELETE FROM auth_item_child WHERE parent IN ({$parents}) AND child IN ({$children})");
    }

    public function updateRelation($role_id, $item_ids)
    {
        $this->remove($role_id);
        $this->add($role_id, $item_ids);

        return true;
    }
}
