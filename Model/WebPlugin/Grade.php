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
     * ���ݻ�Աid�������Ƿ��ǳ�ʼ�û�
     * @param $user_id ��վ������ID
     * @param $user_user_id ��ԱID
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
     * �ж��Ƿ���Ҫ������������Ҫʱ����
     * @param $user_id ��վ������ID
     * @param $user_user_id ��ԱID
     * @return mixed
     */
    public static function promote($user_id,$user_user_id){
        //��ȡ��Ա��ǰ�ȼ�
        $member_info = Model_Member::getMemberById($user_user_id);
        /*δ�����û����������*/
        if($member_info[0]['status'] != 2)
            return false;

        $grade_info = self::getOneGrade($member_info[0]['grade']);
        /*˵���˻�Ա����Ӧ�ĵȼ��ѱ�ɾ��������ʱ��������*/
        //if($grade_info)
            //return false;
        //$current_grade = $grade_info['grade'];

        $current_grade = $member_info[0]['grade'];
        //�жϵ�ǰ��Ա�Ƿ���ϵͳ��ʼ�û����ǵĻ�����Ҫ������
        $init_info = self::getInfoByUser($user_id,$user_user_id);
        if($init_info){
            return true;
        }

        //�жϵ�ǰ��Ա�Ƿ��Ѿ�����ߵȼ���һ���ж��ټ���
        $max_grade = self::getMaxGrade($user_id);
        /*������û��ʱ��˵����ǰ��̨��δ���õȼ�*/
        if(!$max_grade)
            return false;
        /*���ʱ˵���Ѿ�����ߵȼ�������Ҫ����*/
        if($current_grade == $max_grade['id'])
            return true;

        //�ж��Ƿ�ﵽ�˽�������������
        $obj = \Factory::N('DBHelper', \Ebase::getDb('DB_Plugin_R'));
        $obj->from('grade s',[]);
        $obj->addAndWhere('user_id='.$user_id.' and grade>'.$grade_info['grade']);
        $obj->addOrderBy('grade','asc');
        $obj->setLimiter(0,1);
        $up_grade = $obj->query(false);
        /*��ȡ��ǰ��Ա���¼�����*/
        $lower_count = Model_Member::getLowerCount($user_user_id);
        /*������������ 1:���ڵ���  2:����*/
        if($up_grade['promote_lower_type'] == 1){
            if($lower_count >= $up_grade['promote_lower_num']){
                //����
                self::upUserGrade('grade',$up_grade['id'],$user_user_id);
                return true;
            }
        }
        if($up_grade['promote_lower_type'] == 2){
            if($lower_count = $up_grade['promote_lower_num']){
                //����
                self::upUserGrade('grade',$up_grade['id'],$user_user_id);
                return true;
            }
        }

    }

    //��ȡ��С�ȼ�
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