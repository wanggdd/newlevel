<?php

namespace Model\WebPlugin;
use Model\WebPlugin\Model_User;

class Model_Grade extends \Model
{
    public static function getGrade($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('user_id='.$user_id);

        $info = $obj->query(false);
        if($info){
            foreach($info as $key=>$item){
                $user_info = Model_User::getUser('id='.$item['user_user_id']);
                $info[$key]['name'] = $user_info['user_name'].'/'.$user_info['nick_name'];
            }
        }
        return $info;
    }

    public static function getOneGrade($id = 0){
        if(!$id){
            return false;
        }
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('id='.$id);

        $info = $obj->query(false);

        return $info ? $info[0] : false;
    }

    public static function getGradeByUserGrade($grade = 0,$user_id = 0){
        if(!$grade || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('grade='.$grade.' and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function getGradeById($id = 0,$user_id = 0){
        if(!$id || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('id='.$id.' and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function getGradeByTitle($title = '',$user_id = 0){
        if(!$title || !$user_id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('title="'.$title.'" and user_id='.$user_id);

        return $obj->query(false);
    }

    public static function upOneGrade($data = array(),$id = 0){
        if(!$data || !$id){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        return $obj->update('grade s',$data,'id='.$id);
    }

    public static function addOneGrade($data = array()){
        if(!$data){
            return false;
        }

        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        return $obj->insert('grade',$data);
    }

    public static function getGradeListByUser($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('user_id='.$user_id);
        $obj->addOrderBy('grade','desc');

        return $obj->query(false);
    }

    public static function getMaxGrade($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('is_del=0');
        $obj->addAndWhere('user_id='.$user_id);
        $obj->addOrderBy('grade','desc');
        $obj->setLimiter(0,1);

        return $obj->query(false);
    }

    /**
     * 根据会员id，查找是否是初始用户
     * @param $user_id 网站所属人ID
     * @param $user_user_id 会员ID
     * @return mixed
     */
    public static function getInfoByUser($user_id,$user_user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('user_id='.$user_id.' and user_user_id='.$user_user_id);
        $result = $obj->query(false);
        if($result)
            return $result[0];
        return false;
    }



    /**
     * 判断是否需要晋升，并在需要时晋升
     * @param $user_id 网站所属人ID
     * @param $user_user_id 会员ID
     * @return mixed
     */
    public static function promote($user_id,$user_user_id){
        //获取会员当前等级
        $member_info = Model_Member::getMemberById($user_user_id);
        /*未激活用户不参与晋升*/
        if($member_info[0]['status'] != 2)
            return false;

        $grade_info = self::getOneGrade($member_info[0]['grade']);
        /*说明此会员所对应的等级已被删除或处理，这时不做处理*/
        //if($grade_info)
            //return false;
        //$current_grade = $grade_info['grade'];

        $current_grade = $member_info[0]['grade'];
        //判断当前会员是否是系统初始用户（是的话不需要晋升）
        $init_info = self::getInfoByUser($user_id,$user_user_id);
        if($init_info){
            return true;
        }

        //判断当前会员是否已经是最高等级（一共有多少级）
        $max_grade = self::getMaxGrade($user_id);
        /*此数据没有时，说明当前后台还未设置等级*/
        if(!$max_grade)
            return false;
        /*相等时说明已经是最高等级，不需要晋升*/
        if($current_grade == $max_grade['id'])
            return true;

        //判断是否达到了晋升条件并晋升
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('user_id='.$user_id.' and grade>'.$grade_info['grade']);
        $obj->addOrderBy('grade','asc');
        $obj->setLimiter(0,1);
        $up_grade = $obj->query(false);
        /*获取当前会员的下级总数*/
        $lower_count = Model_Member::getLowerCount($user_user_id);
        /*晋升数量规则 1:大于等于  2:等于*/
        if($up_grade['promote_lower_type'] == 1){
            if($lower_count >= $up_grade['promote_lower_num']){
                //晋升
                self::upUserGrade('grade',$up_grade['id'],$user_user_id);
                return true;
            }
        }
        if($up_grade['promote_lower_type'] == 2){
            if($lower_count = $up_grade['promote_lower_num']){
                //晋升
                self::upUserGrade('grade',$up_grade['id'],$user_user_id);
                return true;
            }
        }

    }

    //获取最小等级
    public static function getMinGrade($user_id){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('user_id='.$user_id);
        $obj->addOrderBy('grade','asc');
        $obj->setLimiter(0,1);
        $result = $obj->query(false);
        return $result;
    }

    public static function upUserGrade($field,$value,$uid){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));

        return $obj->update('member s',[$field=>$value],'user_user_id='.$uid);
    }

    public static function getNextGrade($user_id,$grade_id,$type = 'bigger'){
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('user_id='.$user_id);
        if($type == 'bigger'){
            $obj->addAndWhere('and grade>'.$grade_id);
        }else{
            $obj->addAndWhere('and grade<'.$grade_id);
        }
        $obj->addOrderBy('grade','desc');
        $obj->setLimiter(0,1);
        $up_grade = $obj->query(false);
        if($up_grade){
            return $up_grade['id'];
        }else{
            $minGrade = self::getMinGrade($user_id);
            return $minGrade['id'];
        }
    }

}