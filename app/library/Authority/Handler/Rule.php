<?php

namespace Authority;

class RuleHandler
{
    /**
     * 新增规则.
     *
     * @param \Authority\Rule $rule
     *
     * @return \Authority\CommonRet $ret
     */
    public static function add(Rule $rule)
    {
        $ret = new CommonRet();

        $id = (new \AuthRule())->add($rule->name, $rule->data ? : null);
        $ret->ret = \Constant::RET_OK;
        $ret->data = json_encode(['id' => $id]);

        return $ret;
    }

    /**
     * 删除规则.
     *
     * @param int $rule_id
     *
     * @return \Authority\CommonRet $ret
     */
    public static function remove($rule_id)
    {
        $ret = new CommonRet();

        $count = (new \AuthRule())->remove($rule_id);
        $ret->ret = $count ? \Constant::RET_OK : \Constant::RET_DATA_NO_FOUND;

        return $ret;
    }
}