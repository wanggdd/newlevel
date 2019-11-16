<?php
/**
 * 测试model
 *
 * @author       ***
 * @version      01
 * @copyright    ***
 * @date         2019-11-08
 * @changelog
 *     暂无更改记录
 */

namespace Model\WebPlugin;

class Model_Wolaiceshi extends \Model
{
    /**
     * 获取详情
     * 2019/11/8 *** add
     *
     * @param array  $field
     * @param string $where
     *
     * @return mixed
     */
    public static function getInfo($field = [], $where = '')
    {
        if ($wehre) {
            return false;
        }

        $fields = is_array($field) ? $field : [$field => $field];

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('wolaiceshi s', $fields);
        $obj->addAndWhere($where);
        $obj->setLimiter(0, 1);
        return is_array($field) ? $obj->query() : $obj->query()->$field;
    }

    /**
     * 返回列表
     * 2019/11/2 *** add
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getList(array $data = [])
    {
        $fields     = isset($data['fields'])     ? $data['fields']     : [];
        $leftFields = isset($data['leftFields']) ? $data['leftFields'] : [];
        $where      = isset($data['where'])      ? $data['where']      : '';

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('wolaiceshi s', $fields);
        $obj->addAndWhere($where);
        return $obj->query();
    }
}