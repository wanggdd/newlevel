<?php

// ����������ô����һ��sql���(ֻ�����ڲ��Ի���ʹ��)
function echoSql() {
    echo (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])
        ? $GLOBALS['MYSPACE']['SQL'] : '';
}

function echoSqlErrInfo() {
    if (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO']) {
        var_dump($GLOBALS['MYSPACE']['SQL_PARAM']);
    }

    return true;
}

function returnSql() {
    return  ($GLOBALS['MYSPACE']['SQL_ECHO'])
        ? $GLOBALS['MYSPACE']['SQL'] : '';
}
//д��־�ļ�
function slog($log_file = '', $logs = '')
{
  $toppath=$log_file;
  $logs="\r\n".$logs;
  $Ts=fopen($toppath,"a+");
  fputs($Ts,$logs."\r\n");
  fclose($Ts);
}
//д�ļ�
function write_file($file = '', $content = ''){

    $fp = fopen($file, "w");
    flock($fp, 2);
    fputs($fp, $content);
    flock($fp, 3);
    fclose($fp);
    chmod($file,0777);

}

/**
 * �ļ���С
 * @param  [type]  $file     [description]
 * @param  integer $max_size [description]
 * @return [type]            [description]
 */
function file_max_up($file = array(), $max_size = 1000000)
{
    if (empty($file)) {
        return false;
    }
    if ((int)$file['size'] < $max_size) {
        return true;
    } else {
        return false;
    }
}

/* ��ȡһ�����ȵ������������ַ� */
function cnsubstr($str = '',$strlen=10) {
    $res_str = '';
    for ($i=0;$i<$strlen;$i++) {
      if (ord(substr($str,$i,1))>0xa0) {
        $res_str.=substr($str,$i++,2);
      } else {
        $res_str.=substr($str,$i,1);
      }
    }
    return $res_str.' ';
}
function getNewTime($datetime = '',$num = 3)
{
    $len = strlen($datetime);
    return substr($datetime, 0,$len-$num);
}

//��ʽ���۸�
function format_price_new2015($price = '',$is_jan=0){
  $tmp_arr=explode('.',$price);
  $price = preg_replace("'0+$'","",$tmp_arr[1]);
  $price_end =substr($price,-1);
  if($price_end=='.')$price ='';
  if($price){
    $price=$tmp_arr[0].".".$price;
  }else{
    $price=$tmp_arr[0];
  }
  if((int)$price>10000 && $is_jan)$price=($price/10000)."��";
  return $price;
}
//���ͷ�ʽ
function peisong_type_info2015($id = 0){
  global $USERID,$DB_Product;
  $sql = 'select a.*,b.id as t_id,b.start_weight as t_start_weight,b.add_weight as t_add_weight,b.start_price as t_start_price,add_price as t_add_price
    from pro_delivery_method as a
    left join   pro_freight_templates as b  on a.templates_id=b.id
      where a.id='.$id.' and  a.is_del=0 and b.is_del=0  and  a.user_id='.$USERID;
  $peisong_type = $DB_Product->get_row($sql);

  if($peisong_type){
    $sql = 'select * from pro_logistics_companies
        where id in ('.$peisong_type['company_ids'].') and is_del=0 and user_id='.$USERID;
    $peisong_type['company'] = $DB_Product->get_results($sql,'O');

    $sql = 'select * from pro_special_freight_templates where  is_del=0 and f_id='.$peisong_type['t_id'];
    $peisong_type['tmp_teshu'] = $DB_Product->get_results($sql,'O');
  }
  return $peisong_type;
}
//���ͷ�
function peisong_price($provinceCode = '', $peisong_id = 0, $shopWeight = 0){
  global $USERID,$DB_Product;
    if($peisong_id == 925){
        $price = 0;
    }else{

        $sql = 'select a.*,b.id as t_id,b.start_weight as t_start_weight,b.add_weight as t_add_weight,b.start_price as t_start_price,add_price as t_add_price
                from pro_delivery_method as a
                left join   pro_freight_templates as b  on a.templates_id=b.id
                where  a.id='.$peisong_id.'  and  a.user_id='.$USERID;
        $peisong_info = $DB_Product->get_row($sql);

        if($peisong_info['t_add_weight']>0){
            if($shopWeight>$peisong_info['t_start_weight']){
                $flag = 1;
                $add_weight=ceil(($shopWeight-$peisong_info['t_start_weight'])/$peisong_info['t_add_weight']);
                $price = $peisong_info['t_start_price']+$add_weight*$peisong_info['t_add_price'];
            }else{
                $flag = 0;
                $price = $peisong_info['t_start_price'];
            }
        }else{
            $flag = 0;
            $price = $peisong_info['t_start_price'];
        }
        if($peisong_info['t_id']){
            $address_arr = explode('#',$provinceCode);
            $tmp_price = count_address_price($address_arr,$peisong_info['t_id'],$shopWeight,$add_weight);
            if($tmp_price){
                 $price = $tmp_price;
            }
        }
    }
    return $price;
}
function count_address_price($address_arr = array(), $id = 0, $shopWeight = 0, $add_weight = 0,$num=2){
    global $USERID,$DB_Product;
    if($num < 0 ){
        return false;
    }
    $sql = 'select * from  pro_special_freight_templates  where f_id='.$id.' and is_del=0 and area_type='.$num.' and user_id='.$USERID;
    $tmp_teshu_info = $DB_Product->get_results($sql,'O');
    if($tmp_teshu_info){
        $tmp_num = 0;
        //$tmp_arr = array();
        foreach($tmp_teshu_info as $k=>$v){
            $area_info = explode(",",$v->area);
            if(in_array($address_arr[$num],$area_info)){
                $tmp_num = 1;
                $tid = $v->id;
                break;
            }
        }

        if($tmp_num){
            $sql = 'select * from  pro_special_freight_templates  where f_id='.$id.' and is_del=0 and area_type='.$num.' and user_id='.$USERID.' and id='.$tid;
            $info = $DB_Product->get_row($sql,'O');
            if($info->add_weight > 0 && $shopWeight > $info->start_weight){
                $tmp_weight = ceil(($shopWeight-$info->start_weight)/$info->add_weight);
                $price = $info->start_price + $tmp_weight*$info->add_price;
            }else{
                $price = $info->start_price;
            }
            return $price;
        }else{
            return count_address_price($address_arr,$id,$shopWeight,$add_weight,$num-1);
        }
    }else{
        return count_address_price($address_arr,$id,$shopWeight,$add_weight,$num-1);
    }
}
/**
ת����λ�ĺ���
*/
function units_convert($source_str = '', $is_unit_convert=1){

    $unit_name_arr=array(   array("Hz","KHz","MHz","GHz"),
                          array("KB","MB","GB","TB"),
                          array("um","mm","cm","m","km"),
                          array("ns","ms","s","����","hr/Сʱ")
                       );

  //��λ֮���Ӧ�Ľ������� ||^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    $unit_times_arr=array(  array(1000,1000,1000      ),
                          array(1000,1000,1000      ),
                          array(1000,10,  100  ,1000),
                          array(1000,1000,60   ,60  )
                        );


  //����Ҫת���Ͳ�ת��
  if ($is_unit_convert==0){
        if(eregi("^([0-9]+[\.]{0,1}[0-9]+)(.*)$",$source_str,$tmp_arr)){
            $arr = explode('.',$tmp_arr[1]);
            if(strlen($arr[1])<2){
                return $source_str;
            }
            if(is_numeric($tmp_arr[1])){
                if((int)$tmp_arr[1] == $tmp_arr[1]){
                    $tmp_arr[1] = (int)$tmp_arr[1];
                    $source_str = (int)$tmp_arr[1].$tmp_arr[2];
                }else{
                    // $data_arr = split("\.",$tmp_arr[1]);
                    $data_arr = explode("\.",$tmp_arr[1]);
                    eregi("[0]{0,}$",$data_arr[1],$out);
                    if(strlen($out[0])){
                        $data_arr[1] = substr($data_arr[1],0,strlen($data_arr[1])-strlen($out[0]));
                    }
                    $tmp_arr[1] = $data_arr[0].($data_arr[1]?".".$data_arr[1]:"");
                    $source_str = $tmp_arr[1].$tmp_arr[2];
                }//end of if
            }
        }
        return  $source_str;
    }

    //��Ҫת����λ�ļ��϶���
    //��������ʽ�ֽ������λ��������������ĸ�ַ���
    if(eregi("^([0-9]+[\.]{0,1}[0-9]+)(.*)$",$source_str,$tmp_arr)){
    //var_dump($tmp_arr);
        if(is_numeric($tmp_arr[1])){
            if((int)$tmp_arr[1] == $tmp_arr[1]) {
                $tmp_arr[1] = (int)$tmp_arr[1];
                $source_str = (int)$tmp_arr[1].$tmp_arr[2];
            }else{//ȥ���������е�0
                // $data_arr = split("\.",$tmp_arr[1]);
                $data_arr = explode("\.",$tmp_arr[1]);
                eregi("[0]{0,}$",$data_arr[1],$out);
                if(strlen($out[0])){
                    $data_arr[1] = substr($data_arr[1],0,strlen($data_arr[1])-strlen($out[0]));
                }
                $tmp_arr[1] = $data_arr[0].($data_arr[1]?".".$data_arr[1]:"");
                $source_str = $tmp_arr[1].$tmp_arr[2];
            }//end of if

            //�ж��Ƿ��ڵ�λ������ {{{
            /*  $unit_in_arr =true:�д˵�λ   $unit_in_arr =flase:�޴˵�λ    */
            $unit_in_arr = false;
            for($point=0;$point<count($unit_name_arr);$point++){
                $tmp_point = array_search($tmp_arr[2],$unit_name_arr[$point]);
                /**
                �����õ�����ֵ{
                $unit_in_arr  : ��λ����
                $point        : ��λ���ڵĵ�һλ��
                $tmp_point    : ��λ���ڵĵڶ�λ��
                */
                if (is_numeric($tmp_point)){
                    $unit_in_arr = true;
                    break;
                }//end of
            }//end of for
            //�ж��Ƿ��ڵ�λ������ $unit_in_arr }}}

            if ($unit_in_arr==false){
                /**
                ������ѯ��λ�������ڱ�ת����λ�����йʶ�����ת��
                */
                return $source_str;
            }else{
                /**
                $unit_in_arr  : ��λ����
                $point        : ��λ���ڵĵ�һλ��
                $tmp_point    : ��λ���ڵĵڶ�λ��
                */
                //�õ�������Ƚ�
                if ($tmp_arr[1]<$unit_times_arr[$point][$tmp_point]){
                    return  $source_str;
                }else{
                    /**
                    ׼������[$point][$tmp_point]
                    $point��������λ$tmp_point��ѭ���ۼ�$tmp_point=>$tmp_final_point
                    �˳�ѭ��������������$tmp_final_num��λС�ڽ�����[$point][$tmp_final_point]
                        ��[$point][$tmp_final_point]�������^^^^^^^^^^^^^^^^^^^^^^
                    */
                    $circle = false;
                    for($tmp_final_num=$tmp_arr[1],$tmp_final_point=$tmp_point;$tmp_final_point<(count($unit_times_arr[$point]));
                        $tmp_final_point++){
                        $tmp_final_num = $tmp_final_num/$unit_times_arr[$point][$tmp_final_point];
                        $circle = true;//ֻ��ѭ��һ�βſ�������Խ�磬��λ������δ���������Ѿ��ͷ�����������
                        //�������1000g��Ҫת����1kg��ȥ��=��
                        if ($tmp_final_num<=$unit_times_arr[$point][$tmp_final_point]) break;
                    }//end of for
                    /**
                    $tmp_final_num      : ��������
                    $point              : ��λ���ƴ��ڵĵ�һλ��
                    $tmp_final_point    : ��λ���ڵĵڶ�λ��
                    */
                    return  $tmp_final_num.(($unit_name_arr[$point][$tmp_final_point+1] && $circle )?
                            $unit_name_arr[$point][$tmp_final_point+1] : $unit_name_arr[$point][$tmp_final_point]);
                }//end of
            }//end of if  ($unit_in_arr==false)

        }else{
            //�������־Ͳ�ת��
            return $source_str;
        }//end of if

    }else{
        //echo "û������ɹ�";
        return $source_str;
    }//end of if


}//end of functuon

function format_output($a = ''){
    //ת���س�
    //$a = htmlspecialchars($a);
    //����Ӳ�Ʒ���ݽ����˸�ʽ��
    $a = stripslashes($a);
    //$a = nl2br($a);
    $a = preg_replace("  ","&nbsp;",$a);
    $a = nl2br($a);
    return $a;
}
function get_manu_name($manu_name = ''){
    if(strstr($manu_name,"��")){
        $b_pos = strpos($manu_name,"��");
        if(ord(substr($manu_name,($b_pos-1),1))>0xa0){//is chinese
            $manu_name = substr($manu_name,0,$b_pos);
        }else{//is english
            $manu_name = substr($manu_name,($b_pos+2),-2);
        }
    }
    return $manu_name;
}

//ͼƬ����
function pic_re_size($pic = '', $width = 0, $height = 0){

    if(!$pic) return false;
    if(!$width)$width=80;
    if(!$height)$height=60;

    $img_arr=explode(';',$pic);
    $tmp_size='140_75';
    if($width>140)$tmp_size='200_200';
    $img=str_replace('/server/','/server/'.$tmp_size.'/',$img_arr[0]);
    //$img=$img_arr[0];
    if($img_arr[1]>$width){
        $t_width=$width;
        $t_height=(int)(($img_arr[2]/$img_arr[1])*$width);
    }else{
        $t_width=$img_arr[1];
        $t_height=$img_arr[2];
    }
    if($t_height>$height){
        $t_width=(int)(($t_width/$t_height)*$height);
        $t_height=$height;
    }
    return 'src="'.$img.'" width="'.$t_width.'" height="'.$t_height.'" ';
}

//ͼƬ����
function pic_re_size2($pic = '', $width = 0, $height = 0){

    if(!$pic) return false;
    if(!$width)$width=80;
    if(!$height)$height=60;

    $img_arr=explode(';',$pic);
    if(!$img_arr[1]){
        $tmp_arr = getimagesize($pic);
        $img_arr[1] = $tmp_arr[0];
        $img_arr[2] = $tmp_arr[1];
    }
    //$img=str_replace('www.ev123.com','211.144.133.71',$img_arr[0]);
    $img=$img_arr[0];
    if($img_arr[1]>$width){
        $t_width=$width;
        $t_height=(int)(($img_arr[2]/$img_arr[1])*$width);
    }else{
        $t_width=$img_arr[1];
        $t_height=$img_arr[2];
    }
    if($t_height>$height){
        $t_width=(int)(($t_width/$t_height)*$height);
        $t_height=$height;
    }
    return 'src="'.$img.'" width="'.$t_width.'" height="'.$t_height.'" ';
}

//��Ϣ
function get_info_con($info = ''){

    $info=str_replace("\u0020","&nbsp;",$info);
    $info=str_replace("\r\n","<br/>",$info);
    $info=str_replace("\r","<br/>",$info);
    $info=str_replace("\n","<br/>",$info);
    $info=str_replace('����   ','<br/>',$info);
    $info=str_replace('DIV','div ',$info);

    return $info;
}


//��Ϣ
function get_info_con3($info = ''){
    $info=str_replace("\u0020","&nbsp;",$info);
    $info=str_replace("\r\n","",$info);
    $info=str_replace("\r","",$info);
    $info=str_replace("\n","",$info);
    return $info;
}

function get_info_con2($info = ''){
    $info=str_replace("  ","��",$info);
    $info=str_replace("\u0020","&nbsp;",$info);
    $info=str_replace("\r\n","<br/>",$info);
    $info=str_replace("\r","<br/>",$info);
    $info=str_replace("\n","<br/>",$info);
    //$info=str_replace('����   ','<br/>',$info);
    $info=str_replace('DIV','div ',$info);

    return $info;
}

//��ȡҳ���ʶ
function get_page_navi($userid = 0, $id = 0, $type = 0){

    $userid = (int)$userid;
    if(!$userid)return false;

    global $DB_Product,$DB_Peixun,$DB_Meet,$DB_Server,$DB_Cf,$DB_Resource,$DB_Procurement,$DB_Mt,$DB_Q,$DB_Ev123,$DB_Wl;

    //��������[��Ʒ]
    $current_navi='';
    $sql = "select pid from proinfo where userid='".$userid."' and  smallclassid!=0 limit 1 ";
    $tag = $DB_Product->get_var($sql);
    if($tag){
        if($type==1){
            $current_navi = '<div class="nor"><a href="/servers/pro_'.$id.'_0_1.html" class="h12">�� Ʒ</a></div>';
        }else{
            $current_navi = '<div class="p"></div>
                     <div class="act"><a href="/servers/pro_'.$id.'_0_1.html" class="bai12">�� Ʒ</a></div>';
        }
    }


    //��������[��ѵ]
    $sql = "select id from enpx where userid='".$userid."' and userid!=0 and sub_id!=0 limit 1 ";
    $tag = $DB_Peixun->get_var($sql);
    if($tag){
        if($type==2){
            $current_navi .= '<div class="nor"><a href="/servers/peixun_'.$id.'_0_1.html" class="h12">��ѵ�γ�</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                         <div class="act"><a href="/servers/peixun_'.$id.'_0_1.html" class="bai12">��ѵ�γ�</a></div>';
        }
    }

    //��������[չ��]
    $sql = "select id from meet2 where userid='".$userid."' and userid!=0 limit 1 ";
    $tag = $DB_Meet->get_var($sql);
    if($tag){
        if($type==3){
            $current_navi .= '<div class="nor"><a href="/servers/meet_'.$id.'_0_1.html" class="h12">չ ��</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                         <div class="act"><a href="/servers/meet_'.$id.'_0_1.html" class="bai12">չ ��</a></div>';
        }
    }

    //��������[����]
    $sql = "select id from cf_index where user_id='".$userid."' and userid!=0 limit 1 ";
    $tmp_sign = $DB_Cf->get_var($sql);
    if($tmp_sign){
        if($type==4){
            $current_navi .= '<div class="nor"><a href="/servers/plant_'.$id.'_0_1.html" class="h12">�� ��</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                          <div class="act"><a href="/servers/plant_'.$id.'_0_1.html" class="bai12">�� ��</a></div>';
        }
    }

    //����
    $sql = "select id  from companynew where userid='".$userid."' and userid!=0 and content!=''  limit 1";
    $news = $DB_Server->get_var($sql);
    if($news){
        if($type==5){
            $current_navi .= '<div class="nor"><a href="/servers/news_'.$id.'_0_1.html" class="h12">��˾����</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                              <div class="act"><a href="/servers/news_'.$id.'_0_1.html" class="bai12">��˾����</a></div>';
        }
    }
    //echo $sql;

    //��Դ����
    $sql = "select id  from resource where userid='".$userid."' limit 1";
    $source = $DB_Resource->get_var($sql);
    if($source){
        if($type==6){
            $current_navi .= '<div class="nor"><a href="/servers/hezuo_'.$id.'_0_1.html" class="h12">��Դ����</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                                  <div class="act"><a href="/servers/hezuo_'.$id.'_0_1.html" class="bai12">��Դ����</a></div>';
        }
    }


    //��ҵ�ɹ�
    $sql = "select id  from procurementinfo where userid='".$userid."' limit 1";
    $source = $DB_Procurement->get_var($sql);
    if($source){
        if($type==7){
            $current_navi .= '<div class="nor"><a href="/servers/cg_'.$id.'_0_1.html" class="h12">��ҵ�ɹ�</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                                  <div class="act"><a href="/servers/cg_'.$id.'_0_1.html" class="bai12">��ҵ�ɹ�</a></div>';
        }
    }

    //ý�忯��
    $sql = "select id  from seriescases where userid='".$userid."' limit 1";
    $source = $DB_Mt->get_var($sql);
    if($source){
        if($type==8){
            $current_navi .= '<div class="nor"><a href="/servers/mt_'.$id.'_0_1.html" class="h12">ý�忯��</a></div>';
        }else{
            $current_navi .= '<div class="p"></div>
                                  <div class="act"><a href="/servers/mt_'.$id.'_0_1.html" class="bai12">ý�忯��</a></div>';
        }
    }

    //��Ȧ
    if($userid){
        $sql = "select id  from circle where userid='".$userid."' and userid!=0  limit 1";
        $source = $DB_Q->get_var($sql);
        if($source){
            $sql = "select username from user where userid=".$userid;
            $tmp_username =  $DB_Ev123->get_var($sql);
            $current_navi .= '<div class="p"></div>
                                  <div class="act"><a href="http://q.ev123.com/'.$tmp_username.'" class="bai12">�ҵ���Ȧ</a></div>';
        }
    }

    //����
    if($userid){
        $sql = "select id from line_index where user_id='".$userid."' and user_id!=0  limit 1";
        $source = $DB_Wl->get_var($sql);
        if($source){
            if($type==9){
                $current_navi .= '<div class="nor"><a href="/servers/wl_'.$id.'_0_1.html" class="h12">������·</a></div>';
            }else{
                $current_navi .= '<div class="p"></div>
                                      <div class="act"><a href="/servers/wl_'.$id.'_0_1.html" class="bai12">������·</a></div>';
            }
        }
    }
    if($type!=10){
        $current_navi .= '<div class="p" id="ly"></div><div class="act"><a href="http://www.ev123.com/guest/'.$id.'_1.html" class="bai12">��������</a></div>';
    }
    if($type!=11){
        $current_navi .= '<div class="p" id="pinpai"></div><div class="act"><a href="http://www.ev123.com/servers/pinpai_'.$id.'_1_1.html" class="bai12">��ҵƷ��<div id="Layer1" style="position:absolute;"><img src="http://www.ev123.com/images/new_s.gif"></div></a></div>';
    }
    return $current_navi;
}

//������Ϣ
function set_mail($email = '', $mail_body = '', $title = ''){

    if($email && $mail_body){

        $smtpserver = "mail.ev123.com";//SMTP������

        $smtpserverport =25;//SMTP�������˿�

        $smtpusermail = "info@ev123.com";//SMTP���������û�����

        $smtpemailto = $email;//���͸�˭

        $smtpuser = "info@ev123.com";//SMTP���������û��ʺ�

        $smtppass = "5107166";//SMTP���������û�����

        $mailsubject = $title;//�ʼ�����

        $mailbody = $mail_body;//�ʼ�����

        $mailtype = "HTML";//�ʼ���ʽ��HTML/TXT��,TXTΪ�ı��ʼ�

        $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//�������һ��true�Ǳ�ʾʹ�������֤,����ʹ�������֤.

        $smtp->debug = false;//�Ƿ���ʾ���͵ĵ�����Ϣ
        //echo asdfasdf;
        if($smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype)){
            //$set_info ="<font color='red'>�ʼ����ͳɹ�!!</font><br>\n";
        }else{
            //$set_info ="<font color='red'>�ʼ�����ʧ��!!������һ��...��������ɹ�!���˹�����!!!</font><br>\n";
        }
        //echo $set_info;
    }
}

//�۸�
function treat_price($price = 0){
    $return_str='';
    if($price=='0.00' || $price==0){
        $return_str='�ޱ���';
    }else{
        $zheng=(int)$price;
        if(($price-$zheng)==0){
            $return_str='��'.$zheng;
        }else{
            $return_str='��'.$price;
        }
    }
    return $return_str;
}

//��ȡIP
function getIP2(){
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}

//���JS
function clear_js($content = ''){
    $search = array ("'<script[^>]*?>.*?</script>'si");
    $replace = array("");
    $content=preg_replace($search,$replace,$content);
    return $content;
}

//����$_POST��$_GET
function stripslashes_string($request_data = array()){
    $clean_data = array();
    if(!get_magic_quotes_gpc()){
        if(is_array($request_data)){
            foreach($request_data as $k => $v){
                $clean_data[$k] = is_array($v) ? stripslashes_string($v) : addslashes(trim($v));
            }
        }
    }
    return $clean_data;
}

//������
function debug(){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

//��֤���ַ�
function GetRandomCaptchaText($length = null) {
    if (empty($length)) {
        $length = rand(5, 8);
    }

    $words  = "abcdefghijlmnopqrstvwyz";
    $vocals = "aeiou";

    $text  = "";
    $vocal = rand(0, 1);
    for ($i=0; $i<$length; $i++) {
        if ($vocal) {
            $text .= substr($vocals, mt_rand(0, 4), 1);
        } else {
            $text .= substr($words, mt_rand(0, 22), 1);
        }
        $vocal = !$vocal;
    }
    return $text;
}

//ȡĬ��ע���ֶ�
function get_system_fields($USERID = 0){
    return array(
array('label'=>'�û���','form_type'=>'text','system_type'=>1,'allownull'=>0,'default_value'=>'','input_type'=>'username',
'order_sort'=>9,'allowshow'=>1,'options'=>'','class_id'=>0,'uid'=>$USERID,'jscheck'=>'','min_size'=>6,'max_size'=>18,'error_notice'=>'�û�����ʽ����'),

array('label'=>'����','form_type'=>'text','system_type'=>2,'allownull'=>0,'default_value'=>'','input_type'=>'password',
'order_sort'=>8,'allowshow'=>1,'options'=>'','class_id'=>0,'uid'=>$USERID,'jscheck'=>'','min_size'=>6,'max_size'=>16,'error_notice'=>'�����ʽ����'),

array('label'=>'����','form_type'=>'text','system_type'=>3, 'allownull'=>0,'default_value'=>'','input_type'=>'email',
'order_sort'=>7,'allowshow'=>1,'options'=>'','class_id'=>0,'uid'=>$USERID,'jscheck'=>'','max_size'=>64,'error_notice'=>'�����ʽ����'),

array('label'=>'�绰','form_type'=>'text','system_type'=>4, 'allownull'=>1,'default_value'=>'','input_type'=>'mobile',
'order_sort'=>6,'allowshow'=>0,'options'=>'','class_id'=>0,'uid'=>$USERID,'jscheck'=>'','max_size'=>15,'error_notice'=>'�绰��ʽ����'),

array('label'=>'�ǳ�','form_type'=>'text','system_type'=>5, 'allownull'=>1,'default_value'=>'','input_type'=>'',
'order_sort'=>5,'allowshow'=>0,'options'=>'','class_id'=>0,'uid'=>$USERID,'jscheck'=>'','max_size'=>30),

array('label'=>'�Ա�','form_type'=>'radio','system_type'=>6, 'allownull'=>1,'default_value'=>'','input_type'=>'',
'order_sort'=>4,'allowshow'=>0,'options'=>"��\r\nŮ",'class_id'=>0,'uid'=>$USERID,'jscheck'=>''),

array('label'=>'��������','form_type'=>'calendar','system_type'=>7, 'allownull'=>1,'default_value'=>'','input_type'=>'',
'order_sort'=>3,'allowshow'=>0,'options'=>"",'class_id'=>0,'uid'=>$USERID,'jscheck'=>'')
               );
}
/*array('label'=>'ͷ��','form_type'=>'file','system_type'=>8, 'allownull'=>1,'default_value'=>'','input_type'=>'',
'order_sort'=>2,'allowshow'=>1,'options'=>"",'class_id'=>0,'uid'=>$USERID,'jscheck'=>''),*/
function  get_regex_arr(){
    return array('email' => array('preg' => '/^\w+\.*\w*-*\w+@(\w+-?\w+\.)+[a-zA-Z]{2,8}$/i', 'name' => '����'),
                 'password' => array('preg' => '/^\w{6,16}$/', 'name' => '����'),
                 'tel' => array('preg' => '/^[0-9-]{2,30}$/', 'name' => '�绰'),
                 // 'mobile' => array('preg' => '/^0?1\d{10}$/', 'name' => '�ֻ�'),
                 // 'mobile' => array('preg' => '/^([1-9])(\d{7,10})$/', 'name' => '�ֻ�'),
                 'mobile' => array('preg' => '/^1(?:3\d|4[4-9]|5[0-35-9]|6[67]|7[013-8]|8\d|9\d)\d{8}$/', 'name' => '�ֻ�'),
                 'username' => array('preg' => '/^\w{6,18}$/i', 'name' => 'ע���û���'),
                 'qq' => array('preg' => '/^[0-9]{5,13}$/i', 'name' => 'QQ��'),
                 'wechat' => array('preg' => '/^[a-zA-Z1-9]{1}[-_a-zA-Z0-9]{5,19}$/i', 'name' => '΢��'),
                 'dateTime' => array('preg' => '/^\d{4}[-|\/]?((0?\d)|(1[0|1|2]))[-|\/]?[0|1|2|3]?\d\s{0,3}[0|1|2]\d:([0-5]\d:?){1,2}$/', 'name' => '����ʱ��'),
                 'date' => array('preg' => '/^\d{4}[-|\/]?((0?\d)|(1[0|1|2]))[-|\/]?[0|1|2|3]?\d$/', 'name' => '����'),
                 'ip' => array('preg' => '/^(\d{1,3}\.){3}\d{1,3}$/', 'name' => 'IP��ַ'),
                 'number' => array('preg' => '/^\d+$/', 'name' => 'ֻ��������'),
                 'onlyLetter' => array('preg' => '/^[a-zA-Z]+$/', 'name' => 'ֻ����Ӣ����ĸ��Сд'),
                 'anything' => array('preg' => '/.*/', 'name' => '����')
                 );
}

//�������HTML
function NoHTML($string = ''){

    $string = preg_replace("'<script[^>]*?>.*?</script>'si", "", $string);//ȥ��javascript
    $string = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $string);         //ȥ��HTML���
    $string = preg_replace("'([\r\n])[\s]+'", "", $string);               //ȥ���հ��ַ�
    $string = preg_replace("'&(quot|#34);'i", "", $string);               //�滻HTMLʵ��
    $string = preg_replace("'&(amp|#38);'i", "", $string);
    $string = preg_replace("'&(lt|#60);'i", "", $string);
    $string = preg_replace("'&(gt|#62);'i", "", $string);
    $string = preg_replace("'&(nbsp|#160);'i", "", $string);
    return $string;
}


/** * �Ա����ı�������json_encode
json_encode��֧�����ĵ�����
* @access public * @param $var
* @return string */
function var_json_encode($var = '') {
    $_var = var_urlencode($var);
    $_str = json_encode($_var);
    return $_str;
}

/**
* �Ա����������ݽ���urlencode����
ʹ���ڽ���json_encode��ʱ����б���Ĳ�������
��ֹjson_encodeʧ��
* @access private
* @param $var * @return array */
function var_urlencode($var = '') {
    if (empty($var)) { return false; }
    if (is_array($var)) {
        foreach ( $var as $k => $v ) {
            if (is_scalar ( $v )) {
                //if������������������
                $var [$k] = urlencode ( $v );
            } else {
                //else������������
                $var [$k] = var_urlencode ( $v );
            }
        }
    }else{//������������
        $var = urlencode ( $var );
    }
    return $var;
}

/**
json_decode��֧�����ĵ�����
**/
if (!function_exists('var_json_decode')) {
	function var_json_decode($var){
		$var=var_urldecode(json_decode($var,true));
		return $var;
	}
}
if (!function_exists('var_urldecode')) {
	function var_urldecode($var) {
		if (empty($var)) { return false; }
		if (is_array($var)) {
			foreach ( $var as $k => $v ) {
				if (is_scalar ( $v )) {
					//if������������������
					$var [$k] = urldecode ( $v );
				} else {
					//else������������
					$var [$k] = var_urldecode ( $v );
				}
			}
		}else{//������������
			$var = urldecode ( $var );
		}
		return $var;
	}
}

if (!function_exists('get_rand_code')) {
	function get_rand_code($length=20){
		// �����ַ������������������Ҫ���ַ�
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$password = '';
		for ( $i = 0; $i < $length; $i++ )
		{
			// �����ṩ�����ַ���ȡ��ʽ
			// ��һ����ʹ�� substr ��ȡ$chars�е�����һλ�ַ���
			// �ڶ�����ȡ�ַ����� $chars ������Ԫ��
			// $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
			$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
		}
		return $password;
	}
}

/**
 *
 * ��������ַ�����������32λ
 * @param int $length
 * @return ����������ַ���
 */
function getRandStr($length = 32)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str ="";
    for ( $i = 0; $i < $length; $i++ )  {
        $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}
//д�⴦��
function new_format_mysql_insert($val = '',$is_del_html=1,$is_del_js=1){
    $val=trim($val);

    if (empty($val)) {
        return $val;
    }
    if($is_del_html){
        $val=strip_tags($val);
        $val=str_replace('&nbsp;','',$val);
        $val=preg_replace("@<(.*?)>@is", "",$val);
        if($is_del_spec)$val=htmlspecialchars($val, ENT_COMPAT, "ISO-8859-1");
    }
    if($is_del_js){
        $search = array ("'<script[^>]*?>.*?</script>'si");
        $replace = array("");
        $val=preg_replace($search,$replace,$val);
    }
    $val = preg_replace('/(<|&lt;)a.*?href\s*?=\s*?[\"\']{0,1}(java)(.*?)[\"\']{0,1}.*?(>|&gt;)/is',"<a href='###'>",$val);
    $val = addslashes($val);
    return $val;
}


/**
    �������ַ�ת��ΪHTMLʵ��
*/
function get_html_entities($val = '') {
    if (empty($val)) return false;

    if (is_object($val)) $val = (array)$val;

    if (is_array($val)) {
        foreach ($val as $k=>$v) {
            if (is_array($v)) {
                $new_val[$k] = get_html_entities($v);
            } else {
                $new_val[$k] = htmlspecialchars($v, ENT_COMPAT, "ISO-8859-1", false);
            }

        }
        return $new_val;
    } else {
        return htmlspecialchars($val, ENT_COMPAT, "ISO-8859-1", false);
    }
}

//�Զ��ر�HTML
function close_html_tags($html = '') {
      #put all opened tags into an array
      preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
      $openedtags = $result[1];
      #put all closed tags into an array
      preg_match_all('#</([a-z]+)>#iU', $html, $result);
      $closedtags = $result[1];
      $len_opened = count($openedtags);
      # all tags are closed
      if (count($closedtags) == $len_opened) {
        return $html;
      }
      $openedtags = array_reverse($openedtags);
      # close tags
      for ($i=0; $i < $len_opened; $i++) {
          if (!in_array($openedtags[$i], $closedtags)){
            $html .= '</'.$openedtags[$i].'>';
          } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
          }
      }
      return $html;
 }

//��ȡ�����IP
function get_server_ip() {
    if (isset($_SERVER)) {
        if($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR'];
        } else {
            $server_ip = $_SERVER['LOCAL_ADDR'];
        }
    } else {
        $server_ip = getenv('SERVER_ADDR');
    }
    return $server_ip;
}

//�����û�����
function new_up_user_vip($USERID = 0, $user_user_id = 0, $is_shop = ''){
    //���� 2016.3.4����
    return Model_User::updateUserVipVar($USERID,$user_user_id,$is_shop);

    /*global $DB_Product,$DB_Ev123;
    $DB_Ev123_W = new DB_Ev123_W;
    //��ȡ�û�����վ���̳ǻ���
    $sql2 = "select vip,web_integral,integral from user_user where user_id={$USERID} and id={$user_user_id} limit 1";
    $user_info = $DB_Ev123->get_row($sql2);
    if($user_info){
        $sql="select web_integral,integral from user_user_grade where user_id={$USERID} and id={$user_info['vip']} limit 1";
        $level_info=$DB_Ev123->get_row($sql);
        if($user_info['web_integral']>=$level_info['web_integral'] && $user_info['integral']>=$level_info['integral'] &&($level_info['web_integral'] || $level_info['integral']) || !$level_info){
            //��ȡ��վ���̳ǵȼ����ֹ���
            $sql = "select id,web_integral,integral from user_user_grade where  user_id=".$USERID." and state=1 order by web_integral desc,integral desc";
            $level_arr = $DB_Ev123->get_results($sql);
            if($level_arr){
                foreach($level_arr as $k => $v){
                    if(($v['web_integral']<=$user_info['web_integral']) && $v['web_integral'] && $user_info['web_integral']){
                        $up_web_vip=$v['id'];
                        $up_web_k=$k;
                        break;
                    }
                }
                foreach($level_arr as $k => $v){
                    if(($v['integral']<=$user_info['integral']) && $v['integral'] && $user_info['integral']){
                        $up_sc_vip=$v['id'];
                        $up_sc_k=$k;
                        break;
                    }
                }
                if($up_web_vip && $up_sc_vip){
                    $level="";
                    $up_web_k >= $up_sc_k ? $level=$up_web_vip : $level=$up_sc_vip;
                    $sql = "update user_user set vip={$level} where user_id={$USERID} and id={$user_user_id}";
                    $DB_Ev123_W->query($sql);
                }
                if(!$up_web_vip && $up_sc_vip){
                    $sql = "update user_user set vip={$up_sc_vip} where user_id={$USERID} and id={$user_user_id}";
                    $DB_Ev123_W->query($sql);
                }
                if($up_web_vip && !$up_sc_vip){
                    $sql = "update user_user set vip={$up_web_vip} where user_id={$USERID} and id={$user_user_id}";
                    $DB_Ev123_W->query($sql);
                }
            }
        }
    }*/
}


//php���غ���Ҫ�ȼ�����Ҫ��һЩ������ʹ�ú����ж�
//�ж��Ƿ���ͨ���ֻ�����

function isMobile() {
    // �����HTTP_X_WAP_PROFILE��һ�����ƶ��豸
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }

    //�ж��ֻ����͵Ŀͻ��˱�־,�������д����
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array (
        'nokia',
        'sony',
        'ericsson',
        'mot',
        'samsung',
        'htc',
        'sgh',
        'lg',
        'sharp',
        'sie-',
        'philips',
        'panasonic',
        'alcatel',
        'lenovo',
        'iphone',
        'ipod',
        'blackberry',
        'meizu',
        'android',
        'netfront',
        'symbian',
        'ucweb',
        'windowsce',
        'palm',
        'operamini',
        'operamobi',
        'openwave',
        'nexusone',
        'cldc',
        'midp',
        'wap',
        'mobile');
        // ��HTTP_USER_AGENT�в����ֻ�������Ĺؼ���
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }

    //���via��Ϣ����wap��һ�����ƶ��豸,���ַ����̻����θ���Ϣ
    if (isset ($_SERVER['HTTP_VIA'])) {
        //�Ҳ���Ϊflase,����Ϊtrue
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }

    //Э�鷨����Ϊ�п��ܲ�׼ȷ���ŵ�����ж�
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
    // ���ֻ֧��wml���Ҳ�֧��html��һ�����ƶ��豸
    // ���֧��wml��html����wml��html֮ǰ�����ƶ��豸
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
        && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
        (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') <
        strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}



//��ʽ���۸�
function price_format($price = '',$is_jan=0){
    $tmp_arr=explode('.',$price);
    $price = preg_replace("'0+$'","",$tmp_arr[1]);
    $price_end =substr($price,-1);
    if($price_end=='.')$price ='';
    if($price){
        $price=$tmp_arr[0].".".$price;
    }else{
        $price=$tmp_arr[0];
    }
    if((int)$price>10000 && $is_jan)$price=($price/10000)."��";
    return $price;
}

//��̳С���Ƿ�ɷ���
function is_user_fatie_check($USERID = 0, $user_id = 0, $sub_id = 0,$tag = ''){
    global $DB_Ev123;
    if(empty($user_id)) return false;
    if(empty($sub_id)) return false;
    //$tag 1������  2�����
    $sql="select vip from user_user where id={$user_id} and is_del=0";
    $vip=$DB_Ev123->get_var($sql);
    $sql="select is_fatie,is_check,is_reply from user_bbs_fatie_set where user_id={$USERID} and sub_forums_id={$sub_id} order by id desc limit 1";
    $tmp_info=$DB_Ev123->get_row($sql);
    if($tag==1){
        if($tmp_info['is_fatie']){
            if(strpos($tmp_info['is_fatie'],',')){
                $is_fatie=explode(',',$tmp_info['is_fatie']);
                foreach($is_fatie as $k =>$v){
                    if(($v==$vip) || ($v==1 && $vip==0)){
                        return true;//�ɷ���
                    }
                }
            }elseif(($vip==$tmp_info['is_fatie']) || ($vip==0 && $tmp_info['is_fatie']==1)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }elseif($tag==2){
        if($tmp_info['is_check']){
            if(strpos($tmp_info['is_check'],',')){
                $is_check=explode(',',$tmp_info['is_check']);
                foreach($is_check as $k =>$v){
                    if(($v==$vip) || ($v==1 && $vip==0)){
                        return true;
                    }
                }
            }elseif(($vip==$tmp_info['is_check']) || ($vip==0 && $tmp_info['is_check']==1)){
                return true;
            }else{
                return false;//�����
            }
        }else{
            return false;//�����
        }
    }elseif($tag==3){
        if($tmp_info['is_reply']){
            if(strpos($tmp_info['is_reply'],',')){
                $is_reply=explode(',',$tmp_info['is_reply']);
                foreach($is_reply as $k =>$v){
                    if(($v==$vip) || ($v==1 && $vip==0)){
                        return true;
                    }
                }
            }elseif(($vip==$tmp_info['is_reply']) || ($vip==0 && $tmp_info['is_reply']==1)){
                return true;
            }else{
                return false;//������
            }
        }else{
            return true;//����
        }
    }
    return  false;

}

function get_default_pic() {
    return $arr = array(
        'yuan_pic'      => 'http://img.ev123.com/pic/nopic/200_200.jpg',
        'pic_80_80'     => 'http://img.ev123.com/pic/detail2009/no_pic2.gif',
        'pic_100_80'    => 'http://img.ev123.com/pic/nopic/100_80.jpg',
        'pic_120_90'    => 'http://img.ev123.com/pic/nopic/120_90.jpg',
        'pic_200_200' => 'http://img.ev123.com/pic/nopic/200_200.jpg',
        'pic_150_150' => 'http://img.ev123.com/pic/nopic/150_150.jpg',
    );
}

//����Ȩ��
function is_gn_power_2015($USERID = 0, $pro_type = ''){
    global $DB_Ev123;
    if(!$pro_type)return false;
    $sql = "select id from record_gongneng
            where userid='".$USERID."' and is_del=0 and end_date>='".date("Y-m-d")."' and pro_type=".$pro_type;
    $is_power=$DB_Ev123->get_var($sql);
    if(!$is_power && $pro_type==3){
        $sql = "select siteid from user where userid=".$USERID;
        $user_siteid=$DB_Ev123->get_var($sql);
        $is_power = in_array($user_siteid,array(33,30,31,32)) ? 1 : $is_power;
    }
    return $is_power?1:0;
}

/**
 * �ֻ��˴�����ʾҳ��
 */
function wap_web_msg_page($tmp_msg=array(), $special='') {
global $home_link,$LA;

if (empty($home_link)) {
    $home_link = '###';
}
/**
 * ������Ϣ����Ĭ��ֵ
 */
$wap_error_msg = array(
    'title'        => $LA['E_canshuyouwu'].'��',
    'time'         => '1',
    'address_name' => $LA['E_KeJinXingYiXiaCaoZuo'],
    'link'         => $home_link,
    'status'       => 'fail',
    'only_title'   => 0,
);

if (is_array($tmp_msg) && !empty($tmp_msg)) {
    $wap_error_msg = array_merge($wap_error_msg, $tmp_msg);
}

// js��ת
if ($special == 'js_history') {
    $wap_error_msg['link'] = 'javascript:history.go(-1);';
    $js_location           = 'history.go(-1);';
} else {
    $js_location           = 'window.location.href="'. $wap_error_msg['link'] .'";';
}

/**
 * ״̬class��ҳ��title����ȷ/����
 */
if ($wap_error_msg['status'] == 'fail') {
    $web_class = 'errorPrompt';
    $webIcon = '/images/wap/warn.png';
} else if ($wap_error_msg['status'] == 'success') {
    $web_class = 'succeedPrompt';
    $webIcon = '/images/wap/warn.png';
}
if ($wap_error_msg['time']) {
    $tmpTimeHtml = $tmpTimeJs = '';
    $tmpTimeHtml = '<span id="second">'. $wap_error_msg['time'] .'</span>'.$LA['E_Miao_Zhong_Hou'];
    $tmpTimeJs = <<<EOF
           window.onload = function(){
            var second = document.getElementById("second");
            var num = {$wap_error_msg['time']};
            var times = setInterval(function(){
                if(num>=0){
                    second.textContent = num;
                    num--;
                }else{
                    clearInterval(times);
                    {$js_location}
                }
            },1000);
        }
EOF;
}

if ($wap_error_msg['buttonArr']) {
    // $content = $tmpTimeHtml . $LA['E_Tiao_Zhuan_Dao']. $wap_error_msg['address_name'] .'��';
    $content = $tmpTimeHtml . $wap_error_msg['address_name'] .'��';
    $tmpButtonHtml = '';
    foreach ($wap_error_msg['buttonArr'] as $k=>$v){
        $tmpButtonHtml .='<a href="'. $v['url'].'">'. $v['name'] .'</a>';
    }
} else {
    if (empty($wap_error_msg['only_title'])) {
        // $content = $tmpTimeHtml. $LA['E_Tiao_Zhuan_Dao']. $wap_error_msg['address_name'] .'��<a href="'. $wap_error_msg['link'] .'">'.$LA['E_Li_Ji_Tiao_Zhuan'].'</a>';
        $content = $tmpTimeHtml. $wap_error_msg['address_name'] .'��<a href="'. $wap_error_msg['link'] .'">'.$LA['E_Li_Ji_Tiao_Zhuan'].'</a>';
    } else {
        $content = $tmpTimeHtml. $LA['E_Tiao_Zhuan'].'��<a href="'. $wap_error_msg['link'] .'">'.$LA['E_Li_Ji_Tiao_Zhuan'].'</a>';
    }
}
echo <<<EOT
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0,target-densitydpi=medium"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta content="telephone=no" name="format-detection" />
    <meta content="email=no" name="format-detection" />
      <script language="javascript" src="/include/jquery-1.7.1.min.js"></script>
    <title>��Ϣ</title>
    <style type="text/css">
    .alt_bk{position:fixed; z-index: 10; top:0; left:0; right:0; bottom:0; max-width: 640px; margin:0 auto;}
    .alt_frame_bg{position:absolute; width:100%; height:100%; background:#000; opacity: 0.8; filter:alpha(opacity=80); -webkit-opacity: 0.8; -moz-opacity: 0.8;}
    .alt_frame_c{position:absolute; width:100%;}

    /*��ʾ����ת*/
        .Prompt_info{background-color: #fff; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;font-family:"΢���ź�","Arial","����";}
        .Prompt_info_text{margin-bottom: 10px; display: inline-block; line-height: 25px; padding:10px 10px 0 52px; position: relative;}
        .Prompt_info_text span{display: inline-block; float:left; font-size: 14px;}
        .Prompt_info_text span.Prompt_img{width:32px; height:32px; margin-right: 10px; position: absolute; left:10px; top:10px;}
        .Prompt_info_text span.Prompt_img img{vertical-align: middle;}
        .Prompt_info_url{text-align: center; padding-bottom: 10px;}
        .Prompt_info_url a{color:#c00; font-size: 14px;}
        .Prompt_btn{padding:5px 0 10px; text-align:center;}
        .Prompt_btn a{display:inline-block; padding:5px 10px; border:1px solid #CCC; font-size:14px; margin:0 5px; border-radius:5px; text-decoration:none; background-color:#EEE; color:#555;}

    </style>
    <script type="text/javascript">
        {$tmpTimeJs}
        $(function(){
        $(".alt_bk").show();
        var text_w = $(".Prompt_info_text").outerWidth(),
            text_h = $(".Prompt_info_text").height(),
            bk_c = $(".Prompt_info"),
            bk_c_h = bk_c.height(),
            img_h = $(".Prompt_img"),
            alert_w = $(".alt_bk").width();
        if(text_w >= alert_w){
            text_w = text_w-20;
        } else if(text_w < 320){
            text_w = 320;
        }
        img_h.css({
            "height":text_h,
            "top":text_h/2-5+'px'
        });
        bk_c.css({
            "width":text_w,
            "margin-left":-text_w/2,
            "margin-top":-bk_c_h/2,
            "top":"50%",
            "left":"50%"
        });
    })
    </script>
    </head>
    <body>
    <div class="alt_bk" style="">
        <div class="alt_frame_bg"></div>
        <div class="alt_frame_c Prompt_info">
            <span class="Prompt_info_text">
                <span class="Prompt_img" ><img src="{$webIcon}"></span>
                <span class="Prompt_text">{$wap_error_msg['title']}</span>
            </span>
            <div class="Prompt_info_url">
                {$content}
            </div>
            <div class="Prompt_btn">{$tmpButtonHtml}</div>
        </div>
    </div>
    </body>
    </html>
EOT;

exit();
}


function shellCode($username = '', $userId = 0)
{
    return  md5(md5($username.$userId).'Bzt@#*&^)#@321Bzt');
}

/**
 * api��½���ܴ�
 * 2019/10/17 aliang add
 *
 * @param string $username
 * @param int    $UUserId
 *
 * @return bool|string
 */
function shellCodeApi($username = '', $UUserId = 0)
{
    return password_hash("{$username}{$UUserId}Bzt@#*&^)#@321Bzt", PASSWORD_DEFAULT);
}

/**
 * user_user���û���½
 * 2019/10/18 aliang add
 *
 * @param string $username ��վ�û���
 * @param int    $zzUserid website.user_user.id
 * @param int    $type
 */
function userUserSetCookie($username = '', $zzUserid = 0, $type = 0)
{
    if ($type == 1) {
        $time = time() + 3600 * 24 * 3600;
    } elseif ($type == 2) {
        $time = time() + 3600 * 24 * 30;
    } else {
        $time = time() + 3600 * 24;
    }

    $shellCode    = shellCode($username, $zzUserid);
    $shellCodeApi = shellCodeApi($username, $zzUserid);

    setcookie("zz_userid", $zzUserid, $time, "/");
    setcookie("zz_shellCode", $shellCode, $time, "/");
    setcookie("zz_shellCodeApi", $shellCodeApi, $time, "/");
}

/**
 * �Զ��������Ȩ��
 * @param  string $ids ����ȼ���id�ַ���
 * @return array             ���ء�is_member����ֵΪ1��Ϊ������ӣ�
 *                               ֧�ֵġ�member_name���ȼ������ַ���
 */
function userToDefineFormAddRight($ids='') {
    global $DB_Ev123, $login_info, $LA;

    $sql_limit_arr = $limit_ids = explode(',', $ids);

    $memberIdsArr = array();
    $member_name  = ''; // ������ӱ���Ա���ַ���
    $is_member    = 0; // ���=1Ϊ���ڲ����ƻ�Ա

    // ������ͨ��Ա��=0��
    if ((int)$sql_limit_arr[0] === 0) {
        $member_name = $LA['E_PuTongHuiYuan'];
        $memberIdsArr[] = 0;
        unset($sql_limit_arr[0]);
    }

    if (!is_null($sql_limit_arr)) {
        // ��ǰ��վ�����û��ȼ�
        $sql_limit_ids = implode(',', $sql_limit_arr);
        $sql = 'select * from user_user_grade
                where  user_id='. USER_ID .' && state=1
                && id in ('. $sql_limit_ids .')';
        $level_arr = $DB_Ev123->get_results($sql);

        if (!empty($level_arr)) {
            foreach ($level_arr as $k => $v) {
                    $memberIdsArr[] = $v['id'];
                    $member_name    .= ' '. $v['grade_name'] .' ';
            }
        }
    }

    if($login_info['id'] && !empty($memberIdsArr)
            && in_array($login_info['vip'], $memberIdsArr)) {
        $is_member = 1;
    }

    return array('is_member'=>$is_member, 'member_name'=>$member_name);
}

/**
 * �Զ��������ʱ���ж�
 * @param  string $startTime ��ʼʱ��
 * @param  string $endTime   ����ʱ��
 */
function userToDefineFormAddTime($startTime='', $endTime='') {
    if (empty($startTime) || empty($endTime)
        || $startTime == '0000-00-00 00:00:00'
        || $endTime == '0000-00-00 00:00:00'
    ) { return false; }

    $start_arr = explode(':', $startTime);
    $end_arr   = explode(':', $endTime);
    $start_str = $start_arr[0];
    $end_str   = $end_arr[0];

    $start_timestamp = strtotime($startTime);
    $end_timestamp   = strtotime($endTime);
    /**
     * state
     * @state 1 ���ڽ���
     * @state 2 δ��ʼ
     * @state 3 �ѽ���
     */
  $state = 0;
    if (time() - $start_timestamp > 0 && $end_timestamp - time() > 0) {
        $state = 1;
    } else if (time() - $start_timestamp < 0) {
        $state = 2;
    } else if ((time()-$end_timestamp) > 0) {
        $state = 3;
    }

    return array('state'=>$state, 'start_str'=>$start_str, 'end_str'=>$end_str);
}

/**
 * ����city county address ���ȫ��ַ
 * @param  int $city ����
 * @param  int $county ʡ��
 * @param  string $address   �����ַ
 */
function getAllAddress($city = '', $address = ''){
    global $DB_Product,$DB_Peixun,$DB_Meet,$DB_Server,$DB_Cf,$DB_Resource,$DB_Procurement,$DB_Mt,$DB_Q,$DB_Ev123,$DB_Wl;
    $sql = "select name,province from city where code='".$city."'";
    $cityRow = $DB_Ev123->get_row($sql);
  $address = $cityRow['province'].$cityRow['name'].$address;
  return  $address;
}

/**
 * ʹ���ض�function������������Ԫ��������
 * @param  [type]  &$array             Ҫ������ַ���
 * @param  [type]  $function           Ҫִ�еĺ���
 * @param  boolean $apply_to_keys_also �Ƿ�ҲӦ�õ�key��
 * @return [type]                      [description]
 */
function arrayRecursive(&$array, $function = '', $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    if($array){
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } elseif (is_object($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            if (is_object($array)) {
                $array->$key = $function($value);
            } else {
                $array[$key] = $function($value);
            }
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                if (is_object($array)) {
                    $array->$new_key = $array[$key];
                    unset($array->$key);
                } else {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }


            }
        }
    }
    }
    $recursive_counter--;
}

/**
 * ������ת��ΪJSON�ַ������������ģ�
 * @param [type] $array Ҫת��������
 */
function JSON($array = array())
{
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    return urldecode($json);
}


/**
 * ʹ���ض�function������������Ԫ��������
 * @param  [type]  &$array             Ҫ������ַ���
 * @param  [type]  $function           Ҫִ�еĺ���
 * @param  boolean $apply_to_keys_also �Ƿ�ҲӦ�õ�key��
 * @return [type]                      [description]
 */
function addslashesArrayRecursive(&$array, $function = '', $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            addslashesArrayRecursive($array[$key], $function, $apply_to_keys_also);
        } elseif (is_object($value)) {
            addslashesArrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            if (is_object($array)) {
                $array->$key = $function(addslashes($value));
            } else {
                $array[$key] = $function(addslashes($value));
            }
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                if (is_object($array)) {
                    $array->$new_key = $array[$key];
                    unset($array->$key);
                } else {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }

            }
        }
    }
    $recursive_counter--;
}

/**
 * ������ת��ΪJSON�ַ������������ģ�
 * @param [type] $array Ҫת��������
 */
function addslashesJSON($array = array(), $delSpecial = 1)
{
    addslashesArrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    $json = urldecode($json);

    /**
     * ��json��Ҫ����ǰ̨ʱ,��Ҫȥ��һЩ�����ַ�
     */
    if ($delSpecial) {
        $json = str_replace("\'", "\\\'", $json);
        $json = str_replace(array("\r\n", "\r", "\n", "\t"), '', $json);
    }
    return $json;
}


/**
 * ������������ϸ
 * @param  [type]  $channel_id [description]
 * @param  integer $class_id   [description]
 * @param  [type]  $type       [description]
 * @param  integer $num        [description]
 * @param  integer $detail_id  [description]
 * @return [type]              [description]
 */
function newGetDetailArr($channel_id = 0,$class_id=0,$type,$num=100,$detail_id=0){
    global $USERID,$DB_Ev123,$DB_Server,$DB_User_Doc;
    $detail_arr=array();
    if($channel_id && $type){
        //����
        if($type==11){
            if($class_id)$tmp_sql=" and (big_id=".$class_id." or sub_id=".$class_id.")  ";
            if($detail_id)$tmp_sql=" and id=".$detail_id;
            $sql = "select id,title,brief,pic,price,is_pay from ev_user_doc_content
            where userid=".$USERID." and is_del=0 and infostate='publish' and channel_id=".$channel_id." ".$tmp_sql."
            order by order_sort desc,publish_date desc,id desc limit ".$num;
            $pro_arr = $DB_User_Doc->get_results($sql);
            if($pro_arr){
                foreach($pro_arr as $pro){
                    $detail_arr[$pro['id']]['name'] = $pro['title'];
                    $detail_arr[$pro['id']]['id'] = $pro['id'];
                    $detail_arr[$pro['id']]['content'] = $pro['brief'];
                    $detail_arr[$pro['id']]['pic'] = $pro['pic'];
                    $detail_arr[$pro['id']]['price'] = $pro['price'];
                    $detail_arr[$pro['id']]['is_pay'] = $pro['is_pay'];
                }
            }
        }
        //����
        if($type==14){
            if($class_id)$tmp_sql=" and (bigclassid=".$class_id." or sub_id=".$class_id.")  ";
            if($detail_id)$tmp_sql=" and id=".$detail_id;
            $sql = "select id,title,content,pic,price,s_price
            from serverinfo where userid=".$USERID." and is_del=0 and channel_id=".$channel_id." ".$tmp_sql."
            order by order_sort desc, id desc limit ".$num;
            $pro_arr = $DB_Server->get_results($sql);
            if($pro_arr){
                foreach($pro_arr as $pro){
                    $detail_arr[$pro['id']]['name'] = $pro['title'];
                    $detail_arr[$pro['id']]['id'] = $pro['id'];
                    $detail_arr[$pro['id']]['content'] = $pro['content'];
                    $detail_arr[$pro['id']]['pic'] = $pro['pic'];
                    $detail_arr[$pro['id']]['price'] = $pro['price'];
                    $detail_arr[$pro['id']]['s_price'] = $pro['s_price'];
                }
            }
        }
        return $detail_arr;
    }else{
        return false;
    }
}

//�ָ��ַ������������ֻ򵥴�
if (!function_exists('split_zh_en')) {
    function split_zh_en($str = '')
    {
        if (!trim($str)) {
            return;
        }
        // $key_arr = split(" |��", $str);
        $key_arr = explode(" |��", $str);
        $new_arr = [];
        if ($key_arr) {
            foreach ($key_arr as $key) {
                if (trim($key)) {
                    $tmp_arr = arr_split_zh(trim($key));
                    if ($tmp_arr) {
                        foreach ($tmp_arr as $tmp_val) {
                            if (trim($tmp_val))
                                $new_arr[] = trim($tmp_val);
                        }
                    }
                }
            }
        }

        return $new_arr;
    }
}

if (!function_exists('arr_split_zh')) {
    /**
     * Convert a string to an array
     * @param string $str
     * @param number $split_length
     * @return multitype:string
     */
    function arr_split_zh($tempaddtext = ''){
       //$tempaddtext = iconv("UTF-8", "gb2312", $tempaddtext);
        $cind = 0;
        $arr_cont=array();
        $tmp_en='';
        for($i=0;$i<strlen($tempaddtext);$i++)
        {
            if(strlen(substr($tempaddtext,$cind,1)) > 0){
                if(ord(substr($tempaddtext,$cind,1)) < 0xA1 ){ //���ΪӢ����ȡ1���ֽ�
                    $tmp_en.=substr($tempaddtext,$cind,1);
                    if((strlen(substr($tempaddtext,$cind+1,1)) <= 0) || (ord(substr($tempaddtext,$cind+1,1)) > 0xA1)){
                        array_push($arr_cont,$tmp_en);
                        $tmp_en='';
                    }
                    $cind++;
                }else{
                    array_push($arr_cont,substr($tempaddtext,$cind,2));
                    $cind+=2;
                }
            }
        }
        return $arr_cont;
    }
}

/**
 * host��ѯ����
 * @param  integer $type  ��ѯ����
 *                        1  ��֤��ǰ�����Ƿ����(δɾ��),������user_id
 *                        host
 *                        2 ��֤��ǰ�����Ƿ����(δɾ��/��ɾ��),������user_id
 *                        host
 *                        3 ��ȡ��վ������,����������
 *                        userid
 *                        4 ��ȡ��վ�ֻ�������,����������
 *                        userid
 *                        5 ��֤��ǰ�����ĺϷ���,������userid
 *                        userid,host
 *                        6 ��֤��ǰ���������ĺϷ���,������ָ������
 *                        userid,host
 *                        7 ��֤��ǰ�����Ƿ�Ϊpc�ĺϷ�����,������userid
 *                        userid,host
 *                        8 ��֤��ǰ�����Ƿ�Ϊwap�ĺϷ�����,������userid
 *                        userid,host
 * @param  array   $param [description]
 * @return array|bool|mixed|object|stdClass
 */
function hostQuery($type = 0, $param = array())
{
    global $DB_Ev123;

    if ($type == 1) {
        $sql = "select user_id from host_manage where is_del=0 && host='{$param['host']}'";
        return $DB_Ev123->get_var($sql);
    } elseif ($type == 2) {
        $sql = "select user_id from host_manage where host='{$param['host']}'";
        return $DB_Ev123->get_var($sql);
    } elseif ($type == 3) {
        $sql = "select host from host_manage where is_del=0 && user_id='{$param['userid']}' && type=0 && is_main=1";
        $host = $DB_Ev123->get_var($sql);
        if (!$host) {
            $sql = "select host from host_manage where is_del=0 && user_id='{$param['userid']}' && type=0 order by id asc limit 1";
            $host = $DB_Ev123->get_var($sql);
        }
        return $host;
    } elseif ($type == 4) {
        $sql = "select host from host_manage where is_del=0 && user_id='{$param['userid']}' && type=1 order by id asc limit 1";
        return $DB_Ev123->get_var($sql);
    } elseif ($type == 5) {
        $sql = "select user_id from host_manage where is_del=0 && user_id={$param['userid']} && host='{$param['host']}'";
        return $DB_Ev123->get_var($sql);
    } elseif ($type == 6) {
        $sql = "select {$param['fields']} from host_manage where is_del=0 && type=3 && user_id={$param['userid']} && host='{$param['host']}'";
        return $DB_Ev123->get_row($sql, 'O');
    } elseif ($type == 7) {
        $sql = "select user_id from host_manage where is_del=0 && type=0 && user_id={$param['userid']} && host='{$param['host']}'";
        return $DB_Ev123->get_var($sql);
    } elseif ($type == 10) {
        $sql = "select user_id from host_manage where is_del=0 && host='{$param['host']}'";
        return $DB_Ev123->get_results($sql, 'O');
    } elseif ($type == 9) {
        $sql = "select user_id from host_manage where is_del=0 && type=3 && user_id={$param['userid']} && host='{$param['host']}'";
        return $DB_Ev123->get_var($sql);
    } elseif ($type == 8) {
        $sql = "select user_id from host_manage where is_del=0 && type=1 && user_id={$param['userid']} && host='{$param['host']}'";
        return $DB_Ev123->get_var($sql);
    }

    return false;
}

//дredis����
if (!function_exists('set_redis_cache')) {
	function set_redis_cache($tmp_redis = '', $redis_key = '', $res = '', $time=86400){
    	if(!$tmp_redis || !$redis_key || !$res) return false;
    	$is_obj=is_object($res);
        if($is_obj)$res=(array)($res);
        $tmp_redis->set($redis_key, $res,$time);
        $tmp_redis->set($redis_key."_time",time(),$time);
        $tmp_redis->set($redis_key."_obj", $is_obj,$time);
        return ;
    }
}

//��redis����
if (!function_exists('get_redis_cache')) {
	function get_redis_cache($return_data = '', $redis_obj = ''){
    	if(is_array($return_data)){
            if($redis_obj){
                $res =(object)$return_data;
            }else{
                $res=array();
                foreach($return_data as $t_k=>$t_v){
                    $res[$t_k]=(object)$t_v;
                }
            }
        }else{
            $res =	$return_data;
        }
        return  $res;
    }
}

//���û�ID
if (!function_exists('get_uid')) {
	function get_uid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                    .substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12)
                    .chr(125);// "}"
            return md5($uuid);
        }
    }
}

//���û�ID
if (!function_exists('is_md5')) {
	function is_md5($password = '',$num=32) {
    	return preg_match("/^[a-f0-9]{".$num."}$/", $password);
    }
}

//�����û�����ʱ��
if (!function_exists('update_user_action')) {
    function update_user_action()
    {
        Model_Public::update(
            array('update_time' => TIME_STR),
            "userid=".USER_ID,
            'user',
            'DB_Ev123_W'
        );
    }
}

/**
 * �ѷ���β�Ŀո��滻��ָ���ַ�
 * 2019/1/25 aliang add
 *
 * @param string $str
 * @param string $replaceStr
 *
 * @return null|string|string[]
 */
function whitespaceReplaceStr($str = '', $replaceStr = '')
{
    $str = trim($str);
    if (!$str) {
        return $str;
    }

    $res = preg_replace('/\s+/', $replaceStr, $str);

    return $res;
}

/**
 * ֻ�滻һ���ַ���
 * 2019/10/31 aliang add
 *
 * @param $needle
 * @param $replace
 * @param $haystack
 *
 * @return mixed
 */
function str_replace_once($needle, $replace, $haystack)
{
    $pos = strpos($haystack, $needle);
    if ($pos === false) {
        return $haystack;
    }

    return substr_replace($haystack, $replace, $pos, strlen($needle));
}