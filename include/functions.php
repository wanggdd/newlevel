<?php

// ����������ô����һ��sql���(ֻ�����ڲ��Ի���ʹ��)
function echoSql() {
    echo (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])
        ? $GLOBALS['MYSPACE']['SQL'] : '';
}

function returnSql() {
    return (defined("TESTER") && $GLOBALS['MYSPACE']['SQL_ECHO'])
        ? $GLOBALS['MYSPACE']['SQL'] : '';
}

//���˷Ƿ���
function filter_feifa_word($word){
    global $FEIFA_ARR,$USERID;
    $FEIFA_ARR=mult_iconv('gbk','utf-8',$FEIFA_ARR);
    $word=iconv('gbk','utf-8',$word);
    $word = str_replace($FEIFA_ARR, '*', $word);
    return iconv('utf-8','gbk//IGNORE',$word);
}

//�������ת��
function mult_iconv($in_charset,$out_charset,$data)
{
    if(substr($out_charset,-8)=='//IGNORE'){
        $out_charset=substr($out_charset,0,-8);
    }
    if(is_array($data)){
        foreach($data as $key => $value){
            if(is_array($value)){
                $key=iconv($in_charset,$out_charset.'//IGNORE',$key);
                $rtn[$key]=mult_iconv($in_charset,$out_charset,$value);
            }elseif(is_string($key) || is_string($value)){
                if(is_string($key)){
                    $key=iconv($in_charset,$out_charset.'//IGNORE',$key);
                }
                if(is_string($value)){
                    $value=iconv($in_charset,$out_charset.'//IGNORE',$value);
                }
                $rtn[$key]=$value;
            }else{
                $rtn[$key]=$value;
            }
        }
    }elseif(is_string($data)){
        $rtn=iconv($in_charset,$out_charset.'//IGNORE',$data);
    }else{
        $rtn=$data;
    }
    return $rtn;
}

//��¼��־
function write_log(){
    global $USERID,$MANAGERID,$USERNAME,$MANAGERINFO;
    $path='/home/work/www/log/';
    if(!is_dir($path)){
        @mkdir($path,0775,true);
        @chmod($path,0775);
    }
    if(!is_dir($path.ceil($USERID/2000))){
        @mkdir($path.ceil($USERID/2000),0775,true);
        @chmod($path.ceil($USERID/2000),0775);
    }
    if(!is_dir($path.ceil($USERID/2000)."/".$USERID)){
        @mkdir($path.ceil($USERID/2000)."/".$USERID,0775,true);
        @chmod($path.ceil($USERID/2000)."/".$USERID,0775);
    }
    $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $param_str=GET_REQUEST_STR();

    $MANAGERNAME='';
    $toppath=$path.ceil($USERID/2000)."/".$USERID."/".date('Ymd').".log";
    if($MANAGERINFO)$MANAGERNAME=$MANAGERINFO['username'];
    $logs="���˺ţ�".$USERNAME." ���˺ţ�".$MANAGERNAME." IP:".getIP()." ʱ�䣺".date('Y-m-d H:i:s')." URL:".$url." ������".$param_str;
    $Ts=fopen($toppath,"a+");
    fputs($Ts,$logs."\r\n");
    fclose($Ts);

}

//��ȡREQUEST�ַ���
function GET_REQUEST_STR(){
    $REQUEST_STR='';
    if($_REQUEST){
        foreach ($_REQUEST as $key => $value) {
            //$value = preg_replace('\b(and|or)\b\s*?(<)/is', '$1$2', $value);
            if(is_array($value))$value=implode('#@#'.$value);
            $REQUEST_STR.=$key."=".$value."|||@|||";
        }
    }
    return $REQUEST_STR;
}


//д�ļ�
function write_file($file,$content) {

    $fp = fopen($file, "w");
    flock($fp, 2);
    fputs($fp, $content);
    flock($fp, 3);
    fclose($fp);

}

//�ļ�������ʾ
function file_max_up($file,$max_size=1000000){
    if((int)$file['size'] < $max_size){
        return true;
    }else{
        return false;
    }
}

/* ��ȡһ�����ȵ������������ַ� */
function cnsubstr($str,$strlen=10) {
    for($i=0;$i<$strlen;$i++){
      if(ord(substr($str,$i,1))>0xa0) $res_str.=substr($str,$i++,2);
      else  $res_str.=substr($str,$i,1);
    }
    return $res_str;
}

function format_output($a){
    //ת���س�
    //$a = htmlspecialchars($a);
    //����Ӳ�Ʒ���ݽ����˸�ʽ��
    $a = stripslashes($a);
    //$a = nl2br($a);
    $a = ereg_replace("  ","&nbsp;",$a);
    $a = nl2br($a);
    return $a;
}

function get_info_con($info){

    $info=str_replace("\u0020","&nbsp;",$info);
    $info=str_replace("\r\n","<br/>",$info);
    $info=str_replace("\r","<br/>",$info);
    $info=str_replace("\n","<br/>",$info);
    $info=str_replace('����   ','<br/>',$info);
    $info=str_replace('DIV','div ',$info);

    return $info;
}

function get_city_host2($city){
    global $city_host_arr;
    if(in_array($city,$city_host_arr)){
        $web_site=array_search($city,$city_host_arr);
    }else{
        $web_site='www.ev123.com';
    }
    return $web_site;
}


function baidu($key_word,$weburl,$baidu_url){
    $pattern='/\<span class=\"g\">(.*)<\/span>/Usi';
    $page_content = '';
    $page_content = file_get_contents($baidu_url);
    $test_arr=array();
    preg_match_all($pattern,$page_content,$test_arr);

    $p = explode('http://e.baidu.com/',$page_content);//ȥ���ƹ��URL
    $n = count($p)-1;

    $web_arr = preg_split("/(\s|,|��|;|��|��)/",$weburl,-1,PREG_SPLIT_NO_EMPTY);
    $ord_arr=array();
    if($web_arr){
        foreach($web_arr as $web){
            $ord=-1;
            foreach($test_arr[1] as $value=>$key){
                $key = str_replace('http://','',$key);
                if(count(explode($web,$key))>1){
                    $ord=$value+1;
                    break;
                }
            }
            $ord_arr[$web] = $ord;
        }
    }
    return $ord_arr;
}


function google($key_word,$weburl,$google_url){
    $pattern='/\<li class=g><h3 class=\"r\"><a href\=\"(.*)\"/Usi';
    $page_content = '';
    $page_content = file_get_contents($google_url);
    $test_arr=array();
    preg_match_all($pattern,$page_content,$test_arr);
    $web_arr = preg_split("/(\s|,|��|;|��|��)/",$weburl,-1,PREG_SPLIT_NO_EMPTY);
    $ord_arr=array();
    if($web_arr){
        foreach($web_arr as $web){
            $ord=-1;
            foreach($test_arr[1] as $value=>$key){
                $key = str_replace('http://','',$key);
                if(count(explode($web,$key))>1){
                    $ord=$value+1;
                    break;
                }
            }
            $ord_arr[$web] = $ord;
        }
    }

    return $ord_arr;
}

//������Ϣ
function set_mail($email,$mail_body,$title,$type='HTML',$fromname='��վϵͳ�ʼ�'){
    $email=trim($email);
    $title=trim($title);
    $message=trim($mail_body);
    $fromname=trim($fromname);

    if($email && $title && $message){
        $url= EMAIL_HOST.'/api/sohu_mail.php?subject='.urlencode($title)."&content=".urlencode($message)."&to_mail=".$email."&fromname=".urlencode($fromname);
        @file_get_contents($url);

        return 1;
    }else{
        return 0;
    }
}

// ԭͼ�ϴ�
function get_file_pic($update_name, $ser_id, $tag, $table){
    $file_url = '';
    //ȡ��չ��
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){
        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }

        $img_path = UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"], $img_path);
        OSSRun::uploadFile($img_path,$table."/{$folder_name}/{$file_name}");
        $file_url = IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }

    return $file_url;
}

//ͼƬƵ��ר��
//����ͼ
function update_own_pic_img($update_name,$ser_id,$tag,$table,$is_save_source=0){

    //ȡ��չ��
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/800_1500/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/800_1500/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/800_1500/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/640_480/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/640_480/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/640_480/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/560_420/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/560_420/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/560_420/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/300_1500/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/300_1500/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/300_1500/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/280_280/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/280_280/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/280_280/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_200/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/200_200/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/800_1500/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4.$table."/640_480/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4.$table."/560_420/{$folder_name}/{$file_name}";
        $img_path_s4=UPDATA_DIR_IMG4.$table."/200_200/{$folder_name}/{$file_name}";
        $img_path_s5=UPDATA_DIR_IMG4.$table."/150_150/{$folder_name}/{$file_name}";
        $img_path_s6=UPDATA_DIR_IMG4.$table."/120_90/{$folder_name}/{$file_name}";
        $img_path_s7=UPDATA_DIR_IMG4.$table."/100_80/{$folder_name}/{$file_name}";
        $img_path_s8=UPDATA_DIR_IMG4.$table."/300_1500/{$folder_name}/{$file_name}";
        $img_path_s9=UPDATA_DIR_IMG4.$table."/280_280/{$folder_name}/{$file_name}";

        @copy($update_name["tmp_name"],$img_path_s);
        if($is_save_source){
            @copy($img_path_s,$img_path);
            OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");
        }

        //����120ѹ��
        $size = @getimagesize($img_path_s);
        $width = $size[0];
        $height = $size[1];
        if($width>800 || $height>15000){
            $new_height2 = ($height*800)/$width;
            $new_width3  = 800;
            if(15000<$new_height2){
                $new_width3 = (800*15000)/$new_height2;
                $new_height2 = 15000;
            }else{
                $new_width3 = 800;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s");
        }else{
            @copy($img_path_s,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/800_1500/{$folder_name}/{$file_name}");

        if($width>640 || $height>480){
            $new_height2 = ($height*640)/$width;
            $new_width3  = 640;
            if(480<$new_height2){
                $new_width3 = (640*480)/$new_height2;
                $new_height2 = 480;
            }else{
                $new_width3 = 640;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s2");
        }else{
            @copy($img_path_s,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"{$table}/640_480/{$folder_name}/{$file_name}");

        if($width>560 || $height>420){
            $new_height2 = ($height*560)/$width;
            $new_width3  = 560;
            if(420<$new_height2){
                $new_width3 = (560*420)/$new_height2;
                $new_height2 = 420;
            }else{
                $new_width3 = 560;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s3");
        }else{
            @copy($img_path_s,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"{$table}/560_420/{$folder_name}/{$file_name}");

        if($width>200 || $height>200){
            $new_height2 = ($height*200)/$width;
            $new_width3  = 200;
            if(200<$new_height2){
                $new_width3 = (200*200)/$new_height2;
                $new_height2 = 200;
            }else{
                $new_width3 = 200;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s3 $img_path_s4");
        }else{
            @copy($img_path_s,$img_path_s4);
        }
        OSSRun::uploadFile($img_path_s4,"{$table}/200_200/{$folder_name}/{$file_name}");

        if($width>150 || $height>150){
            $new_height2 = ($height*150)/$width;
            $new_width3  = 150;
            if(150<$new_height2){
                $new_width3 = (150*150)/$new_height2;
                $new_height2 = 150;
            }else{
                $new_width3 = 150;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s4 $img_path_s5");
        }else{
            @copy($img_path_s,$img_path_s5);
        }
        OSSRun::uploadFile($img_path_s5,"{$table}/150_150/{$folder_name}/{$file_name}");

        if($width>120 || $height>90){
            $new_height2 = ($height*120)/$width;
            $new_width3  = 120;
            if(90<$new_height2){
                $new_width3 = (120*90)/$new_height2;
                $new_height2 = 90;
            }else{
                $new_width3 = 120;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s5 $img_path_s6");
        }else{
            @copy($img_path_s,$img_path_s6);
        }
        OSSRun::uploadFile($img_path_s6,"{$table}/120_90/{$folder_name}/{$file_name}");

        if($width>100 || $height>80){
            $new_height2 = ($height*100)/$width;
            $new_width3  = 100;
            if(80<$new_height2){
                $new_width3 = (100*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 100;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s6 $img_path_s7");
        }else{
            @copy($img_path_s,$img_path_s7);
        }
        OSSRun::uploadFile($img_path_s7,"{$table}/100_80/{$folder_name}/{$file_name}");

        if($width>300 || $height>1500){
            $new_height2 = ($height*300)/$width;
            $new_width3  = 300;
            if(1500<$new_height2){
                $new_width3 = (300*1500)/$new_height2;
                $new_height2 = 1500;
            }else{
                $new_width3 = 300;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s8");
        }else{
            @copy($img_path_s,$img_path_s8);
        }
        OSSRun::uploadFile($img_path_s8,"{$table}/300_1500/{$folder_name}/{$file_name}");

        if($width>280 || $height>280){
            $new_height2 = ($height*280)/$width;
            $new_width3  = 280;
            if(280<$new_height2){
                $new_width3 = (280*280)/$new_height2;
                $new_height2 = 280;
            }else{
                $new_width3 = 280;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s9");
        }else{
            @copy($img_path_s,$img_path_s9);
        }
        OSSRun::uploadFile($img_path_s9,"{$table}/280_280/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}


//����ͼ
function update_public_img($update_name,$ser_id,$tag,$table){

    //ȡ��չ��
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/150_150/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4.$table."/120_90/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4.$table."/100_80/{$folder_name}/{$file_name}";

        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];

        //200*200
        if($width>150 || $height>150){
            $new_height2 = ($height*150)/$width;
            $new_width3  = 150;
            if(150<$new_height2){
                $new_width3 = (150*150)/$new_height2;
                $new_height2 = 150;
            }else{
                $new_width3 = 150;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/150_150/{$folder_name}/{$file_name}");

        if($width>120 || $height>90){
            $new_height2 = ($height*120)/$width;
            $new_width3  = 120;
            if(90<$new_height2){
                $new_width3 = (120*90)/$new_height2;
                $new_height2 = 90;
            }else{
                $new_width3 = 120;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s2");
        }else{
            @copy($img_path,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"{$table}/120_90/{$folder_name}/{$file_name}");

        if($width>100 || $height>80){
            $new_height2 = ($height*100)/$width;
            $new_width3  = 100;
            if(80<$new_height2){
                $new_width3 = (100*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 100;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s3");
        }else{
            @copy($img_path,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"{$table}/100_80/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
    }else{
        $file_url='';
    }
    return $file_url;
}

//����ͼ
function update_focus_img($update_name,$ser_id,$tag,$table){

    //ȡ��չ��
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/715_165/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/715_165/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/715_165/".$folder_name,0777);
        }
        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/715_165/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];
        if($width>715 || $height>165){
            $new_height2 = ($height*715)/$width;
            $new_width3  = 715;
            if(165<$new_height2){
                $new_width3 = (715*165)/$new_height2;
                $new_height2 = 165;
            }else{
                $new_width3 = 715;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/715_165/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/715_165/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}

//վ������ͼ
function update_site_img($update_name,$ser_id,$tag,$table){

    //ȡ��չ��
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/180_40/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/180_40/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/180_40/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_60/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_60/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/200_60/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/220_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/220_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/220_80/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/270_130/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/270_130/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/270_130/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/150_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/150_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/150_80/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/180_40/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4.$table."/270_130/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4.$table."/200_60/{$folder_name}/{$file_name}";
        $img_path_s4=UPDATA_DIR_IMG4.$table."/220_80/{$folder_name}/{$file_name}";
        $img_path_s5=UPDATA_DIR_IMG4.$table."/150_80/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];

        //200*200
        if($width>180 || $height>40){
            $new_height2 = ($height*180)/$width;
            $new_width3  = 180;
            if(40<$new_height2){
                $new_width3 = (180*40)/$new_height2;
                $new_height2 = 40;
            }else{
                $new_width3 = 180;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/180_40/{$folder_name}/{$file_name}");

        //200*200
        if($width>270 || $height>130){
            $new_height2 = ($height*270)/$width;
            $new_width3  = 270;
            if(130<$new_height2){
                $new_width3 = (270*130)/$new_height2;
                $new_height2 = 130;
            }else{
                $new_width3 = 270;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");
        }else{
            @copy($img_path,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"{$table}/270_130/{$folder_name}/{$file_name}");

        //200*200
        if($width>200 || $height>60){
            $new_height2 = ($height*200)/$width;
            $new_width3  = 200;
            if(60<$new_height2){
                $new_width3 = (200*60)/$new_height2;
                $new_height2 = 60;
            }else{
                $new_width3 = 200;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s3");
        }else{
            @copy($img_path,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"{$table}/200_60/{$folder_name}/{$file_name}");

        //220*80
        if($width>220 || $height>80){
            $new_height2 = ($height*220)/$width;
            $new_width3  = 220;
            if(80<$new_height2){
                $new_width3 = (220*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 220;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s4");
        }else{
            @copy($img_path,$img_path_s4);
        }
        OSSRun::uploadFile($img_path_s4,"{$table}/220_80/{$folder_name}/{$file_name}");

        //220*80
        if($width>150 || $height>80){
            $new_height2 = ($height*150)/$width;
            $new_width3  = 150;
            if(80<$new_height2){
                $new_width3 = (150*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 150;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s5");
        }else{
            @copy($img_path,$img_path_s5);
        }
        OSSRun::uploadFile($img_path_s5,"{$table}/150_80/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}

//�̳�ͼ
function update_mall_img_editor($update_name,$ser_id,$tag,$table,$set_type=1,$urlPic){

    //ȡ��չ��
    global $USERID;
    $tmpRole = substr(UPDATA_DIR_IMG4, 0, strlen(UPDATA_DIR_IMG4)-1);
    $update_name['name']=str_replace(IMG_HOST,$tmpRole,$update_name['name']);
    $update_name['tmp_name']=str_replace(IMG_HOST,$tmpRole,$update_name['tmp_name']);
    $tmpDir =  substr($update_name['name'], 0, strrpos($update_name['name'], '/'));
    if(!is_dir($tmpDir)){
        @mkdir($tmpDir,0777,true);
        @chmod($tmpDir,0777);
    }

    if (!is_file($update_name['name']) && strpos($urlPic, 'http') !== false) {
        exec("wget -O {$update_name['name']} {$urlPic}");

        //������ͼƬ��ȡ������
        /*$hander = curl_init();
        $fp = fopen($update_name['name'], 'wb');
        curl_setopt($hander,CURLOPT_URL,$urlPic);
        curl_setopt($hander,CURLOPT_FILE,$fp);
        curl_setopt($hander,CURLOPT_HEADER,0);
        curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($hander,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($hander,CURLOPT_TIMEOUT,60);
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);*/
    }
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);
    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;
        if(!is_dir(UPDATA_DIR_IMG4.$table."/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/".$folder_name,0777);
        }

        /*if(!is_dir(UPDATA_DIR_IMG4.$table."/345_205/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/345_205/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/345_205/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/345_205/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/345_205/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/345_205/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/570_300/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/570_300/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/570_300/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/570_300/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/570_300/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/570_300/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_180/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_180/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/200_180/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_180/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_180/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/200_180/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/660_200/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/660_200/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/660_200/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/660_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/660_200/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/660_200/".$folder_name,0777);
        }*/

        $img_path=UPDATA_DIR_IMG4.$table."/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s4=UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s5=UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s6=UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s7=UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s8=UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s9=UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s10=UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/{$folder_name}/{$file_name}";
        //$img_path_s5=UPDATA_DIR_IMG4.$table."/345_205/{$folder_name}/{$file_name}";
        //$img_path_s6=UPDATA_DIR_IMG4.$table."/570_300/{$folder_name}/{$file_name}";
        //$img_path_s7=UPDATA_DIR_IMG4.$table."/200_180/{$folder_name}/{$file_name}";
        //$img_path_s8=UPDATA_DIR_IMG4.$table."/660_200/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path_s10);

        //����120ѹ��
        $size = @getimagesize($img_path_s10);
        $width = $size[0];
        $height = $size[1];
        if($width>800 || $height>1500){
            $new_height2 = ($height*800)/$width;
            $new_width3  = 800;
            if(1500<$new_height2){
                $new_width3 = (800*1500)/$new_height2;
                $new_height2 = 1500;
            }else{
                $new_width3 = 800;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s10");
        }
        @chmod($img_path_s10,0777);
        OSSRun::uploadFile($img_path_s10,"{$table}/800_1500/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s10,$img_path_s4,600,600);
        }else{
            //600*600
            if($width>600 || $height>600){
                $new_height2 = ($height*600)/$width;
                $new_width3  = 600;
                if(600<$new_height2){
                    $new_width3 = (600*600)/$new_height2;
                    $new_height2 = 600;
                }else{
                    $new_width3 = 600;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s4");
            }else{
                @copy($img_path_s10,$img_path_s4);
            }
        }
        @chmod($img_path_s4,0777);
        OSSRun::uploadFile($img_path_s4,"{$table}/600_600/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s4,$img_path_s7,380,380);
        }else{
            if($width>380 || $height>380){
                $new_height2 = ($height*380)/$width;
                $new_width3  = 380;
                if(380<$new_height2){
                    $new_width3 = (380*380)/$new_height2;
                    $new_height2 = 380;
                }else{
                    $new_width3 = 380;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s4 $img_path_s7");
            }else{
                @copy($img_path_s10,$img_path_s7);
            }
        }
        @chmod($img_path_s7,0777);
        OSSRun::uploadFile($img_path_s7,"{$table}/380_380/{$USERID}/{$folder_name}/{$file_name}");

        //280*210
        if($set_type==1){
            $is_success=my_image_resize($img_path_s7,$img_path_s,280,280);
        }else{
            if($width>280 || $height>280){
                $new_height2 = ($height*280)/$width;
                $new_width3  = 280;
                if(280<$new_height2){
                    $new_width3 = (280*280)/$new_height2;
                    $new_height2 = 280;
                }else{
                    $new_width3 = 280;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s7 $img_path_s");
            }else{
                @copy($img_path_s10,$img_path_s);
            }
        }
        @chmod($img_path_s,0777);
        OSSRun::uploadFile($img_path_s,"{$table}/280_210/{$USERID}/{$folder_name}/{$file_name}");

        //600*600
        if($set_type==1){
            $is_success=my_image_resize($img_path_s7,$img_path_s5,200,200);
        }else{
            if($width>200 || $height>200){
                $new_height2 = ($height*200)/$width;
                $new_width3  = 200;
                if(200<$new_height2){
                    $new_width3 = (200*200)/$new_height2;
                    $new_height2 = 200;
                }else{
                    $new_width3 = 200;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s5");
            }else{
                @copy($img_path_s10,$img_path_s5);
            }
        }
        @chmod($img_path_s5,0777);
        OSSRun::uploadFile($img_path_s5,"{$table}/200_200/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s5,$img_path_s9,150,150);
        }else{
            if($width>150 || $height>150){
                $new_height2 = ($height*150)/$width;
                $new_width3  = 150;
                if(150<$new_height2){
                    $new_width3 = (150*150)/$new_height2;
                    $new_height2 = 150;
                }else{
                    $new_width3 = 150;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s5 $img_path_s9");
            }else{
                @copy($img_path_s10,$img_path_s9);
            }
        }
        @chmod($img_path_s9,0777);
        OSSRun::uploadFile($img_path_s9,"{$table}/150_150/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s10,$img_path_s2,120,90);
        }else{
            if($width>120 || $height>90){
                $new_height2 = ($height*120)/$width;
                $new_width3  = 120;
                if(90<$new_height2){
                    $new_width3 = (120*90)/$new_height2;
                    $new_height2 = 90;
                }else{
                    $new_width3 = 120;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s2");
                //echo "convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s2";
            }else{
                @copy($img_path_s10,$img_path_s2);
            }
        }
        @chmod($img_path_s2,0777);
        OSSRun::uploadFile($img_path_s2,"{$table}/120_90/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s2,$img_path_s6,100,80);
        }else{
            if($width>100 || $height>80){
                $new_height2 = ($height*100)/$width;
                $new_width3  = 100;
                if(80<$new_height2){
                    $new_width3 = (100*80)/$new_height2;
                    $new_height2 = 80;
                }else{
                    $new_width3 = 100;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s6");
            }else{
                @copy($img_path_s10,$img_path_s6);
            }
        }
        @chmod($img_path_s6,0777);
        OSSRun::uploadFile($img_path_s6,"{$table}/100_80/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s9,$img_path_s8,60,60);
        }else{
            if($width>60 || $height>60){
                $new_height2 = ($height*60)/$width;
                $new_width3  = 60;
                if(60<$new_height2){
                    $new_width3 = (60*60)/$new_height2;
                    $new_height2 = 60;
                }else{
                    $new_width3 = 60;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s6 $img_path_s8");
            }else{
                @copy($img_path_s10,$img_path_s8);
            }
        }
        @chmod($img_path_s8,0777);
        OSSRun::uploadFile($img_path_s8,"{$table}/60_60/{$USERID}/{$folder_name}/{$file_name}");

        //40*40
        if($set_type==1){
            $is_success=my_image_resize($img_path_s8,$img_path_s3,40,40);
        }else{
            if($width>40 || $height>40){
                $new_height2 = ($height*40)/$width;
                $new_width3  = 40;
                if(40<$new_height2){
                    $new_width3 = (40*40)/$new_height2;
                    $new_height2 = 40;
                }else{
                    $new_width3 = 40;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s8 $img_path_s3");
            }else{
                @copy($img_path_s10,$img_path_s3);
            }
        }
        @chmod($img_path_s3,0777);
        OSSRun::uploadFile($img_path_s3,"{$table}/40_40/{$USERID}/{$folder_name}/{$file_name}");

        //345*205
        /*if($width>345 || $height>205){
            $new_height2 = ($height*345)/$width;
            $new_width3  = 345;
            if(205<$new_height2){
                $new_width3 = (345*205)/$new_height2;
                $new_height2 = 205;
            }else{
                $new_width3 = 345;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s5");
        }else{
            @copy($img_path,$img_path_s5);
        }*/

        //570*300
        /*if($width>570 || $height>300){
            $new_height2 = ($height*570)/$width;
            $new_width3  = 570;
            if(300<$new_height2){
                $new_width3 = (570*300)/$new_height2;
                $new_height2 = 300;
            }else{
                $new_width3 = 570;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s6");
        }else{
            @copy($img_path,$img_path_s6);
        }

        //200*180
        if($width>200 || $height>180){
            $new_height2 = ($height*200)/$width;
            $new_width3  = 200;
            if(180<$new_height2){
                $new_width3 = (200*180)/$new_height2;
                $new_height2 = 180;
            }else{
                $new_width3 = 200;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s7");
        }else{
            @copy($img_path,$img_path_s7);
        }

        //650*200
        if($width>660 || $height>200){
            $new_height2 = ($height*660)/$width;
            $new_width3  = 660;
            if(200<$new_height2){
                $new_width3 = (660*200)/$new_height2;
                $new_height2 = 200;
            }else{
                $new_width3 = 660;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s8");
        }else{
            @copy($img_path,$img_path_s8);
        }*/

        $file_url=IMG_HOST."/".$table."/{$USERID}/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}

//�̳�ͼ
function update_mall_img($update_name,$ser_id,$tag,$table,$set_type=1){

    //ȡ��չ��
    global $USERID;
    $update_name['name']=str_replace(IMG_HOST,UPDATA_DIR_IMG4,$update_name['name']);
    $update_name['tmp_name']=str_replace(IMG_HOST,UPDATA_DIR_IMG4,$update_name['tmp_name']);
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);
    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;
        if(!is_dir(UPDATA_DIR_IMG4.$table."/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/".$folder_name,0777);
        }

        /*if(!is_dir(UPDATA_DIR_IMG4.$table."/345_205/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/345_205/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/345_205/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/345_205/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/345_205/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/345_205/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/570_300/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/570_300/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/570_300/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/570_300/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/570_300/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/570_300/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_180/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_180/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/200_180/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_180/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_180/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/200_180/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/660_200/")){
            @mkdir(UPDATA_DIR_IMG4.$table."/660_200/",0777);
            @chmod(UPDATA_DIR_IMG4.$table."/660_200/",0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/660_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/660_200/".$folder_name,0777);
            @chmod(UPDATA_DIR_IMG4.$table."/660_200/".$folder_name,0777);
        }*/

        $img_path=UPDATA_DIR_IMG4.$table."/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/280_210/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4.$table."/120_90/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4.$table."/40_40/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s4=UPDATA_DIR_IMG4.$table."/600_600/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s5=UPDATA_DIR_IMG4.$table."/200_200/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s6=UPDATA_DIR_IMG4.$table."/100_80/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s7=UPDATA_DIR_IMG4.$table."/380_380/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s8=UPDATA_DIR_IMG4.$table."/60_60/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s9=UPDATA_DIR_IMG4.$table."/150_150/{$USERID}/{$folder_name}/{$file_name}";
        $img_path_s10=UPDATA_DIR_IMG4.$table."/800_1500/{$USERID}/{$folder_name}/{$file_name}";
        //$img_path_s5=UPDATA_DIR_IMG4.$table."/345_205/{$folder_name}/{$file_name}";
        //$img_path_s6=UPDATA_DIR_IMG4.$table."/570_300/{$folder_name}/{$file_name}";
        //$img_path_s7=UPDATA_DIR_IMG4.$table."/200_180/{$folder_name}/{$file_name}";
        //$img_path_s8=UPDATA_DIR_IMG4.$table."/660_200/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path_s10);

        //����120ѹ��
        $size = @getimagesize($img_path_s10);
        $width = $size[0];
        $height = $size[1];
        if($width>800 || $height>1500){
            $new_height2 = ($height*800)/$width;
            $new_width3  = 800;
            if(1500<$new_height2){
                $new_width3 = (800*1500)/$new_height2;
                $new_height2 = 1500;
            }else{
                $new_width3 = 800;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s10");
        }
        @chmod($img_path_s10,0777);
        OSSRun::uploadFile($img_path_s10,"{$table}/800_1500/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s10,$img_path_s4,600,600);
        }else{
            //600*600
            if($width>600 || $height>600){
                $new_height2 = ($height*600)/$width;
                $new_width3  = 600;
                if(600<$new_height2){
                    $new_width3 = (600*600)/$new_height2;
                    $new_height2 = 600;
                }else{
                    $new_width3 = 600;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s4");
            }else{
                @copy($img_path_s10,$img_path_s4);
            }
        }
        @chmod($img_path_s4,0777);
        OSSRun::uploadFile($img_path_s4,"{$table}/600_600/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s4,$img_path_s7,380,380);
        }else{
            if($width>380 || $height>380){
                $new_height2 = ($height*380)/$width;
                $new_width3  = 380;
                if(380<$new_height2){
                    $new_width3 = (380*380)/$new_height2;
                    $new_height2 = 380;
                }else{
                    $new_width3 = 380;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s4 $img_path_s7");
            }else{
                @copy($img_path_s10,$img_path_s7);
            }
        }
        @chmod($img_path_s7,0777);
        OSSRun::uploadFile($img_path_s7,"{$table}/380_380/{$USERID}/{$folder_name}/{$file_name}");

        //280*210
        if($set_type==1){
            $is_success=my_image_resize($img_path_s7,$img_path_s,280,280);
        }else{
            if($width>280 || $height>280){
                $new_height2 = ($height*280)/$width;
                $new_width3  = 280;
                if(280<$new_height2){
                    $new_width3 = (280*280)/$new_height2;
                    $new_height2 = 280;
                }else{
                    $new_width3 = 280;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s7 $img_path_s");
            }else{
                @copy($img_path_s10,$img_path_s);
            }
        }
        @chmod($img_path_s,0777);
        OSSRun::uploadFile($img_path_s,"{$table}/280_210/{$USERID}/{$folder_name}/{$file_name}");

        //600*600
        if($set_type==1){
            $is_success=my_image_resize($img_path_s7,$img_path_s5,200,200);
        }else{
            if($width>200 || $height>200){
                $new_height2 = ($height*200)/$width;
                $new_width3  = 200;
                if(200<$new_height2){
                    $new_width3 = (200*200)/$new_height2;
                    $new_height2 = 200;
                }else{
                    $new_width3 = 200;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s $img_path_s5");
            }else{
                @copy($img_path_s10,$img_path_s5);
            }
        }
        @chmod($img_path_s5,0777);
        OSSRun::uploadFile($img_path_s5,"{$table}/200_200/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s5,$img_path_s9,150,150);
        }else{
            if($width>150 || $height>150){
                $new_height2 = ($height*150)/$width;
                $new_width3  = 150;
                if(150<$new_height2){
                    $new_width3 = (150*150)/$new_height2;
                    $new_height2 = 150;
                }else{
                    $new_width3 = 150;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s5 $img_path_s9");
            }else{
                @copy($img_path_s10,$img_path_s9);
            }
        }
        @chmod($img_path_s9,0777);
        OSSRun::uploadFile($img_path_s9,"{$table}/150_150/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s10,$img_path_s2,120,90);
        }else{
            if($width>120 || $height>90){
                $new_height2 = ($height*120)/$width;
                $new_width3  = 120;
                if(90<$new_height2){
                    $new_width3 = (120*90)/$new_height2;
                    $new_height2 = 90;
                }else{
                    $new_width3 = 120;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s2");
                //echo "convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s10 $img_path_s2";
            }else{
                @copy($img_path_s10,$img_path_s2);
            }
        }
        @chmod($img_path_s2,0777);
        OSSRun::uploadFile($img_path_s2,"{$table}/120_90/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s2,$img_path_s6,100,80);
        }else{
            if($width>100 || $height>80){
                $new_height2 = ($height*100)/$width;
                $new_width3  = 100;
                if(80<$new_height2){
                    $new_width3 = (100*80)/$new_height2;
                    $new_height2 = 80;
                }else{
                    $new_width3 = 100;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s6");
            }else{
                @copy($img_path_s10,$img_path_s6);
            }
        }
        @chmod($img_path_s6,0777);
        OSSRun::uploadFile($img_path_s6,"{$table}/100_80/{$USERID}/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s9,$img_path_s8,60,60);
        }else{
            if($width>60 || $height>60){
                $new_height2 = ($height*60)/$width;
                $new_width3  = 60;
                if(60<$new_height2){
                    $new_width3 = (60*60)/$new_height2;
                    $new_height2 = 60;
                }else{
                    $new_width3 = 60;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s6 $img_path_s8");
            }else{
                @copy($img_path_s10,$img_path_s8);
            }
        }
        @chmod($img_path_s8,0777);
        OSSRun::uploadFile($img_path_s8,"{$table}/60_60/{$USERID}/{$folder_name}/{$file_name}");

        //40*40
        if($set_type==1){
            $is_success=my_image_resize($img_path_s8,$img_path_s3,40,40);
        }else{
            if($width>40 || $height>40){
                $new_height2 = ($height*40)/$width;
                $new_width3  = 40;
                if(40<$new_height2){
                    $new_width3 = (40*40)/$new_height2;
                    $new_height2 = 40;
                }else{
                    $new_width3 = 40;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s8 $img_path_s3");
            }else{
                @copy($img_path_s10,$img_path_s3);
            }
        }
        @chmod($img_path_s3,0777);
        OSSRun::uploadFile($img_path_s3,"{$table}/40_40/{$USERID}/{$folder_name}/{$file_name}");

        //345*205
        /*if($width>345 || $height>205){
            $new_height2 = ($height*345)/$width;
            $new_width3  = 345;
            if(205<$new_height2){
                $new_width3 = (345*205)/$new_height2;
                $new_height2 = 205;
            }else{
                $new_width3 = 345;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s5");
        }else{
            @copy($img_path,$img_path_s5);
        }*/

        //570*300
        /*if($width>570 || $height>300){
            $new_height2 = ($height*570)/$width;
            $new_width3  = 570;
            if(300<$new_height2){
                $new_width3 = (570*300)/$new_height2;
                $new_height2 = 300;
            }else{
                $new_width3 = 570;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s6");
        }else{
            @copy($img_path,$img_path_s6);
        }

        //200*180
        if($width>200 || $height>180){
            $new_height2 = ($height*200)/$width;
            $new_width3  = 200;
            if(180<$new_height2){
                $new_width3 = (200*180)/$new_height2;
                $new_height2 = 180;
            }else{
                $new_width3 = 200;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s7");
        }else{
            @copy($img_path,$img_path_s7);
        }

        //650*200
        if($width>660 || $height>200){
            $new_height2 = ($height*660)/$width;
            $new_width3  = 660;
            if(200<$new_height2){
                $new_width3 = (660*200)/$new_height2;
                $new_height2 = 200;
            }else{
                $new_width3 = 660;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s8");
        }else{
            @copy($img_path,$img_path_s8);
        }*/

        $file_url=IMG_HOST."/".$table."/{$USERID}/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}
//������Ϣͼ
function update_server_info_img($update_name,$ser_id,$tag){

    //ȡ��չ��
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4."serverinfo/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."serverinfo/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."serverinfo/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."serverinfo/80_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."serverinfo/80_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."serverinfo/80_80/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."serverinfo/120_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."serverinfo/120_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."serverinfo/120_90/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4."serverinfo/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4."serverinfo/80_80/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4."serverinfo/120_90/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"serverinfo/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];

        //120*90
        if($width>120 || $height>90){
            $new_height2 = ($height*120)/$width;
            $new_width3  = 120;
            if(90<$new_height2){
                $new_width3 = (120*90)/$new_height2;
                $new_height2 = 90;
            }else{
                $new_width3 = 120;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");
        }else{
            @copy($img_path,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"serverinfo/120_90/{$folder_name}/{$file_name}");

        //120*90
        if($width>80 || $height>80){
            $new_height2 = ($height*80)/$width;
            $new_width3  = 80;
            if(80<$new_height2){
                $new_width3 = (80*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 80;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s");
            //die("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"serverinfo/80_80/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/serverinfo/{$folder_name}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
    }else{
        $file_url='';
    }
    return $file_url;
}

//��˾ͼ
function update_server_img($update_name,$ser_id,$tag){

    //ȡ��չ��
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4."server/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."server/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."server/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."server/200_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."server/200_200/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."server/200_200/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."server/140_75/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."server/140_75/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."server/140_75/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."server/120_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."server/120_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."server/120_90/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4."server/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4."server/140_75/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4."server/200_200/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4."server/120_90/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"server/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];

        //200*200
        if($width>200 || $height>200){
            $new_height2 = ($height*200)/$width;
            $new_width3  = 200;
            if(200<$new_height2){
                $new_width3 = (200*200)/$new_height2;
                $new_height2 = 200;
            }else{
                $new_width3 = 200;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");

        }else{
            @copy($img_path,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"server/200_200/{$folder_name}/{$file_name}");

        if($width>140 || $height>75){
            $new_height2 = ($height*140)/$width;
            $new_width3  = 140;
            if(75<$new_height2){
                $new_width3 = (140*75)/$new_height2;
                $new_height2 = 75;
            }else{
                $new_width3 = 140;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"server/140_75/{$folder_name}/{$file_name}");

        //120*90
        if($width>120 || $height>90){
            $new_height2 = ($height*120)/$width;
            $new_width3  = 120;
            if(90<$new_height2){
                $new_width3 = (120*90)/$new_height2;
                $new_height2 = 90;
            }else{
                $new_width3 = 120;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s3");
        }else{
            @copy($img_path,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"server/120_90/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/server/{$folder_name}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
    }else{
        $file_url='';
    }
    return $file_url;
}

//��Ʒͼ
//param set_type ����ʽ 1 ���۶����� 2����������
function update_product_img($update_name,$ser_id,$tag,$server_path='',$set_type=1){

    //ȡ��չ��
    global $USERID;
    $update_name['name']=str_replace(IMG_HOST,UPDATA_DIR_IMG4,$update_name['name']);
    $update_name['tmp_name']=str_replace(IMG_HOST,UPDATA_DIR_IMG4,$update_name['tmp_name']);

    //�ж��Ƿ�Ϊ������Ŀ¼
    if($server_path){
        $tmp_name=end(explode('/',$server_path));
    }else{
        $tmp_name=$update_name["name"];
    }
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $ext=($ext=='tbi')?'jpg':$ext;
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.time().".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4."product/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/200_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/200_200/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/200_200/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/140_75/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/140_75/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/140_75/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/100_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/100_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/100_80/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/600_600/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/600_600/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/600_600/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/150_150/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/150_150/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/150_150/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/280_210/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/280_210/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/280_210/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/120_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/120_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/120_90/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."product/800_1500/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."product/800_1500/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."product/800_1500/".$folder_name,0777);
        }
        $img_path=UPDATA_DIR_IMG4."product/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4."product/140_75/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4."product/200_200/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4."product/100_80/{$folder_name}/{$file_name}";
        $img_path_s4=UPDATA_DIR_IMG4."product/600_600/{$folder_name}/{$file_name}";
        $img_path_s5=UPDATA_DIR_IMG4."product/150_150/{$folder_name}/{$file_name}";
        $img_path_s6=UPDATA_DIR_IMG4."product/280_210/{$folder_name}/{$file_name}";
        $img_path_s7=UPDATA_DIR_IMG4."product/120_90/{$folder_name}/{$file_name}";
        $img_path_s8=UPDATA_DIR_IMG4."product/800_1500/{$folder_name}/{$file_name}";

        if($server_path){
            @copy($server_path,$img_path_s8);
        }else{
            @copy($update_name["tmp_name"],$img_path_s8);
        }

        //����120ѹ��
        $size = @getimagesize($img_path_s8);
        $width = $size[0];
        $height = $size[1];

        if($width>800 || $height>1500){
            $new_height2 = ($height*800)/$width;
            $new_width3  = 800;
            if(1500<$new_height2){
                $new_width3 = (800*1500)/$new_height2;
                $new_height2 = 1500;
            }else{
                $new_width3 = 800;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s8 $img_path_s8");
        }
        @chmod($img_path_s8,0777);
        OSSRun::uploadFile($img_path_s8,"product/800_1500/{$folder_name}/{$file_name}");


        //600*600
        if($set_type==1){
            $is_success=my_image_resize($img_path_s8,$img_path_s4,600,600);
        }else{
            if($width>600 || $height>600){
                $new_height2 = ($height*600)/$width;
                $new_width3  = 600;
                if(600<$new_height2){
                    $new_width3 = (600*600)/$new_height2;
                    $new_height2 = 600;
                }else{
                    $new_width3 = 600;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s8 $img_path_s4");
            }else{
                @copy($img_path_s8,$img_path_s4);
            }
        }
        @chmod($img_path_s4,0777);
        OSSRun::uploadFile($img_path_s4,"product/600_600/{$folder_name}/{$file_name}");

        //280*210
        if($set_type==1){
            $is_success=my_image_resize($img_path_s8,$img_path_s6,280,280);
        }else{
            if($width>280 || $height>280){
                $new_height2 = ($height*280)/$width;
                $new_width3  = 280;
                if(280<$new_height2){
                    $new_width3 = (280*280)/$new_height2;
                    $new_height2 = 280;
                }else{
                    $new_width3 = 280;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s8 $img_path_s6");
            }else{
                @copy($img_path_s8,$img_path_s6);
            }
        }
        @chmod($img_path_s6,0777);
        OSSRun::uploadFile($img_path_s6,"product/280_210/{$folder_name}/{$file_name}");

        //200*200
        if($set_type==1){
            $is_success=my_image_resize($img_path_s4,$img_path_s2,200,200);
        }else{
            if($width>200 || $height>200){
                $new_height2 = ($height*200)/$width;
                $new_width3  = 200;
                if(200<$new_height2){
                    $new_width3 = (200*200)/$new_height2;
                    $new_height2 = 200;
                }else{
                    $new_width3 = 200;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s6 $img_path_s2");
            }else{
                @copy($img_path_s8,$img_path_s2);
            }
        }
        @chmod($img_path_s2,0777);
        OSSRun::uploadFile($img_path_s2,"product/200_200/{$folder_name}/{$file_name}");

        //150*150
        if($set_type==1){
            $is_success=my_image_resize($img_path_s2,$img_path_s5,150,150);
        }else{
            if($width>150 || $height>150){
                $new_height2 = ($height*150)/$width;
                $new_width3  = 150;
                if(150<$new_height2){
                    $new_width3 = (150*150)/$new_height2;
                    $new_height2 = 150;
                }else{
                    $new_width3 = 150;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s5");
            }else{
                @copy($img_path_s8,$img_path_s5);
            }
        }
        @chmod($img_path_s5,0777);
        OSSRun::uploadFile($img_path_s5,"product/150_150/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s8,$img_path_s,140,75);
        }else{
            if($width>140 || $height>75){
                $new_height2 = ($height*140)/$width;
                $new_width3  = 140;
                if(75<$new_height2){
                    $new_width3 = (140*75)/$new_height2;
                    $new_height2 = 75;
                }else{
                    $new_width3 = 140;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s5 $img_path_s");
            }else{
                @copy($img_path_s8,$img_path_s);
            }
        }
        @chmod($img_path_s,0777);
        OSSRun::uploadFile($img_path_s,"product/140_75/{$folder_name}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s7,120,90);
        }else{
            if($width>120 || $height>90){
                $new_height2 = ($height*120)/$width;
                $new_width3  = 120;
                if(90<$new_height2){
                    $new_width3 = (120*90)/$new_height2;
                    $new_height2 = 90;
                }else{
                    $new_width3 = 120;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s8 $img_path_s7");
            }else{
                @copy($img_path_s8,$img_path_s7);
            }
        }
        @chmod($img_path_s7,0777);
        OSSRun::uploadFile($img_path_s7,"product/120_90/{$folder_name}/{$file_name}");

        //100*80
        if($set_type==1){
            $is_success=my_image_resize($img_path_s7,$img_path_s3,100,80);
        }else{
            if($width>100 || $height>80){
                $new_height2 = ($height*100)/$width;
                $new_width3  = 100;
                if(80<$new_height2){
                    $new_width3 = (100*80)/$new_height2;
                    $new_height2 = 80;
                }else{
                    $new_width3 = 100;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s7 $img_path_s3");
            }else{
                @copy($img_path_s8,$img_path_s3);
            }
        }
        @chmod($img_path_s3,0777);
        OSSRun::uploadFile($img_path_s3,"product/100_80/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/product/{$folder_name}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
    }else{
        $file_url='';
    }
    return $file_url;
}

//�Զ��嵥��ͼ����
//�̳�ͼ
function update_own_img($update_name, $ser_id, $tag, $table, $own_width, $own_height, $type = 1, $saveOld = false, $isGreen = false)
{
    if ($own_width == 800 && $own_height == 1500) {
        $own_width  = 1920;
        $own_height = 200000;
    }
    //ȡ��չ��
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    if ($table == 'ev_user_app' && $own_width==144){
        $ext = 'png';
    }
    $tmp_ext = strtoupper($ext);
    if(!(int)$own_width || !(int)$own_height){
        return false;
    }

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){
        if ($table == 'wap_welcome') {
            $folder      = ((int)date('Yd'))*2000;
            $folder_name = ceil($folder/2000);
            $file_name = $ser_id."_".rand(0,10000).'_'.$tag.".".$ext;
        } else {
            $folder_name = ceil($ser_id/2000);
            $file_name = $ser_id."_".$tag.".".$ext;
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);

        //����ԭͼ
        if(!$saveOld){
            //ѹ��
            $size = @getimagesize($img_path);
            $width = $size[0];
            $height = $size[1];
            if (($table == 'wap_welcome' && $tag!=2)
                || $table == 'wap_scene') {
                if($width != $own_width || $height!= $own_height){
                    exit('ERROR:size');
                }
            }
            if($type==1)$is_success=my_image_resize($img_path,$img_path,$own_width,$own_height);
            if(!$is_success || $type!=1){
                if($width>$own_width || $height>$own_height){
                    $new_height2 = ($height*$own_width)/$width;
                    $new_width3  = $own_width;
                    if($own_height<$new_height2){
                        $new_width3 = ($own_width*$own_height)/$new_height2;
                        $new_height2 = $own_height;
                    }else{
                        $new_width3 = $own_width;
                    }
                    system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path");
                }
            }
        }
        @chmod($img_path,0777);
        $tmpPicInfo = OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        if ($isGreen) {
            if ($tmpPicInfo['illegality']) {
                return $tmpPicInfo;
            }
        }

        $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}


//�Զ��嵥��ͼ����
//�̳�ͼ
function updateOwnImgOne($update_name, $ser_id, $tag, $table, $own_width, $own_height, $type = 1, $saveOld = false, $isGreen = false)
{
    if ($own_width == 800 && $own_height == 1500) {
        $own_width  = 1920;
        $own_height = 200000;
    }
    //ȡ��չ��
    global $USERID;
    $tmp_name = $update_name["name"];
    $ext_arr  = explode('.', $tmp_name);
    $ext      = end($ext_arr);
    if ($table == 'ev_user_app' && $own_width == 144) {
        $ext = 'png';
    }
    $tmp_ext = strtoupper($ext);
    if (!(int)$own_width || !(int)$own_height) {
        return false;
    }

    if ($tmp_ext == 'PNG' || $tmp_ext == 'GIF' || $tmp_ext == 'JPG' || $tmp_ext == 'JPEG' || $tmp_ext == 'ICO') {
        if ($table == 'wap_welcome') {
            $folder      = ((int)date('Yd')) * 2000;
            $folder_name = ceil($folder / 2000);
            $file_name   = $ser_id."_".rand(0, 10000).'_'.$tag.".".$ext;
        } else {
            $folder_name = ceil($ser_id / 2000);
            $file_name   = $ser_id."_".$tag.".".$ext;
        }

        if (!is_dir(UPDATA_DIR_IMG4."{$table}/{$folder_name}")) {
            @mkdir(UPDATA_DIR_IMG4."{$table}/{$folder_name}", 0777, true);
            @chmod(UPDATA_DIR_IMG4."{$table}/{$folder_name}", 0777);
        }

        $img_path = UPDATA_DIR_IMG4."{$table}/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"], $img_path);

        //����ԭͼ
        if (!$saveOld) {
            //ѹ��
            $size   = @getimagesize($img_path);
            $width  = $size[0];
            $height = $size[1];
            if (($table == 'wap_welcome' && $tag != 2)
                || $table == 'wap_scene') {
                if ($width != $own_width || $height != $own_height) {
                    exit('ERROR:size');
                }
            }
            if ($type == 1) {
                $is_success = my_image_resize($img_path, $img_path, $own_width, $own_height);
            }
            if (!$is_success || $type != 1) {
                if ($width > $own_width || $height > $own_height) {
                    $new_height2 = ($height * $own_width) / $width;
                    $new_width3  = $own_width;
                    if ($own_height < $new_height2) {
                        $new_width3  = ($own_width * $own_height) / $new_height2;
                        $new_height2 = $own_height;
                    } else {
                        $new_width3 = $own_width;
                    }
                    system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path");
                }
            }
        }
        @chmod($img_path, 0777);

        $tmpPicInfo = OSSRun::uploadFile($img_path, UPDATA_DIR_OSS."{$table}/{$folder_name}/{$file_name}");

        if ($isGreen) {
            if ($tmpPicInfo['illegality']) {
                return $tmpPicInfo;
            }
        }

        $file_url = IMG_HOST."/".UPDATA_DIR_OSS."{$table}/{$folder_name}/{$file_name}?t=".rand(0, 10000);
    } else {
        $file_url = '';
    }

    return $file_url;
}

/**
 * �Զ��嵥��ͼ����
 * 2019/4/1 aliang update
 *
 * @param      $update_name     // ͼƬ����
 * @param      $ser_id          // ���id
 * @param      $tag             // �����
 * @param      $table           // ·��
 * @param      $own_width       // ���
 * @param      $own_height      // �߶�
 * @param int  $type            // 1�ߴ����ȣ�0���۶�����
 * @param bool $saveOld         // �Ƿ񱣴�ԭͼ 0 �� 1 ��
 * @param bool $isGreen         // �Ƿ��߰�����ƽӿ� 0 �� 1 ��
 *
 * @return array|bool|string
 */
function updateOwnImg($update_name, $ser_id, $tag, $table, $own_width, $own_height, $type = 1, $saveOld = false, $isGreen = false)
{
    if ($own_width == 800 && $own_height == 1500) {
        $own_width  = 1920;
        $own_height = 200000;
    }

    $USER_ID = USER_ID;

    //ȡ��չ��
    $tmp_name = $update_name["name"];
    $ext_arr  = explode('.', $tmp_name);
    $ext      = end($ext_arr);
    if ($table == 'ev_user_app' && $own_width == 144) {
        $ext = 'png';
    }

    $tmp_ext = strtoupper($ext);
    if (!(int)$own_width || !(int)$own_height) {
        return false;
    }

    if ($tmp_ext == 'PNG' || $tmp_ext == 'GIF' || $tmp_ext == 'JPG' || $tmp_ext == 'JPEG') {
        if ($table == 'wap_welcome') {
            $folder      = ((int)date('Yd')) * 2000;
            $folder_name = ceil($folder / 2000);
            $file_name   = $ser_id."_".rand(0, 10000).'_'.$tag.".".$ext;
        } else {
            $folder_name = ceil($ser_id / 2000);
            $file_name   = $ser_id."_".$tag.".".$ext;
        }

        if (!is_dir(UPDATA_DIR_IMG4."{$table}/".$folder_name)) {
            @mkdir(UPDATA_DIR_IMG4."{$table}/".$folder_name, 0777, true);
            @chmod(UPDATA_DIR_IMG4."{$table}/".$folder_name, 0777);
        }

        $img_path = UPDATA_DIR_IMG4."{$table}/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"], $img_path);

        //����ԭͼ
        if (!$saveOld) {
            //ѹ��
            $size   = @getimagesize($img_path);
            $width  = $size[0];
            $height = $size[1];
            if (($table == 'wap_welcome' && $tag != 2) || $table == 'wap_scene') {
                if ($width != $own_width || $height != $own_height) {
                    exit('ERROR:size');
                }
            }
            if ($type == 1) {
                $is_success = my_image_resize($img_path, $img_path, $own_width, $own_height);
            }
            if (!$is_success || $type != 1) {
                if ($width > $own_width || $height > $own_height) {
                    $new_height2 = ($height * $own_width) / $width;
                    $new_width3  = $own_width;
                    if ($own_height < $new_height2) {
                        $new_width3  = ($own_width * $own_height) / $new_height2;
                        $new_height2 = $own_height;
                    } else {
                        $new_width3 = $own_width;
                    }
                    system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path");
                }
            }
        }

        @chmod($img_path, 0777);
        $tmpPicInfo = OSSRun::uploadFile($img_path, "u/{$USER_ID}/{$table}/{$folder_name}/{$file_name}");

        if ($isGreen) {
            if ($tmpPicInfo['illegality']) {
                return $tmpPicInfo;
            }
        }

        $file_url = IMG_HOST."/u/{$USER_ID}/{$table}/{$folder_name}/{$file_name}?t=".rand(0, 10000);
    } else {
        $file_url = '';
    }

    return $file_url;
}

//��չͼ
function update_meet_img($update_name,$ser_id,$tag,$table){

    //ȡ��չ��
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/230_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/230_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/230_90/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/230_90/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //230*90
        if($width>230 || $height>90){
            $new_height2 = ($height*230)/$width;
            $new_width3  = 230;
            if(90<$new_height2){
                $new_width3 = (230*90)/$new_height2;
                $new_height2 = 90;
            }else{
                $new_width3 = 230;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/230_90/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}";
    }else{
        $file_url='';
    }
    return $file_url;
}
//�Ź���
function update_group_img($update_name,$ser_id,$tag,$table,$set_type=1,$server_url = ''){

    //ȡ��չ��
    global $USERID;
    if(!empty($server_url)){
        $tmp_name=end(explode('/',$server_url));
    }else{
        $tmp_name=$update_name["name"];
    }
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/100_80/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/120_90/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/190_115/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/190_115/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/190_115/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/200_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/200_200/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/200_200/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/230_140/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/230_140/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/230_140/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/300_182/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/300_182/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/300_182/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/320_195/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/320_195/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/320_195/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/470_285/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/470_285/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/470_285/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/100_80/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4.$table."/120_90/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4.$table."/150_150/{$folder_name}/{$file_name}";
        $img_path_s4=UPDATA_DIR_IMG4.$table."/190_115/{$folder_name}/{$file_name}";
        $img_path_s5=UPDATA_DIR_IMG4.$table."/200_200/{$folder_name}/{$file_name}";
        $img_path_s6=UPDATA_DIR_IMG4.$table."/230_140/{$folder_name}/{$file_name}";
        $img_path_s7=UPDATA_DIR_IMG4.$table."/300_182/{$folder_name}/{$file_name}";
        $img_path_s8=UPDATA_DIR_IMG4.$table."/320_195/{$folder_name}/{$file_name}";
        $img_path_s9=UPDATA_DIR_IMG4.$table."/470_285/{$folder_name}/{$file_name}";
        if($server_url){
            @copy($server_url,$img_path);
        }else{
            @copy($update_name["tmp_name"],$img_path);
        }
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];
        //100*80
        if($width>100 || $height>80){
            $new_height2 = ($height*100)/$width;
            $new_width3  = 100;
            if(80<$new_height2){
                $new_width3 = (100*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 100;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }
        OSSRun::uploadFile($img_path_s,"{$table}/100_80/{$folder_name}/{$file_name}");

        //120*90
        if($width>120 || $height>90){
                $new_height2 = ($height*120)/$width;
                $new_width3  = 120;
                if(90<$new_height2){
                    $new_width3 = (120*90)/$new_height2;
                    $new_height2 = 90;
                }else{
                    $new_width3 = 120;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");
        }else{
            @copy($img_path,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"{$table}/120_90/{$folder_name}/{$file_name}");

        //150*150
        if($width>150 || $height>150){
            $new_height2 = ($height*150)/$width;
            $new_width3  = 150;
            if(150<$new_height2){
                $new_width3 = (150*150)/$new_height2;
                $new_height2 = 150;
            }else{
                $new_width3 = 150;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s3");
        }else{
            @copy($img_path,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"{$table}/150_150/{$folder_name}/{$file_name}");

        //190_115
        if($width>190 || $height>115){
            $new_height2 = ($height*190)/$width;
            $new_width3  = 190;
            if(115<$new_height2){
                $new_width3 = (190*115)/$new_height2;
                $new_height2 = 115;
            }else{
                $new_width3 = 190;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s4");
        }else{
            @copy($img_path,$img_path_s4);
        }
        OSSRun::uploadFile($img_path_s4,"{$table}/190_115/{$folder_name}/{$file_name}");

        //200*200
        if($width>200 || $height>200){
            $new_height2 = ($height*200)/$width;
            $new_width3  = 200;
            if(200<$new_height2){
                $new_width3 = (200*200)/$new_height2;
                $new_height2 = 200;
            }else{
                $new_width3 = 200;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s5");
        }else{
            @copy($img_path,$img_path_s5);
        }
        OSSRun::uploadFile($img_path_s5,"{$table}/200_200/{$folder_name}/{$file_name}");

        //230_140
        if($width>230 || $height>140){
            $new_height2 = ($height*230)/$width;
            $new_width3  = 230;
            if(140<$new_height2){
                $new_width3 = (230*140)/$new_height2;
                $new_height2 = 140;
            }else{
                $new_width3 = 230;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s6");
        }else{
            @copy($img_path,$img_path_s6);
        }
        OSSRun::uploadFile($img_path_s6,"{$table}/230_140/{$folder_name}/{$file_name}");

        //300_182
        if($width>300 || $height>182){
          $new_height2 = ($height*300)/$width;
          $new_width3  = 300;
          if(182<$new_height2){
              $new_width3 = (300*182)/$new_height2;
              $new_height2 = 182;
          }else{
              $new_width3 = 300;
          }
          system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s7");
        }else{
          @copy($img_path,$img_path_s7);
        }
        OSSRun::uploadFile($img_path_s7,"{$table}/300_182/{$folder_name}/{$file_name}");

      //320_195
      if($width>320 || $height>195){
          $new_height2 = ($height*320)/$width;
          $new_width3  = 320;
          if(195<$new_height2){
              $new_width3 = (320*195)/$new_height2;
              $new_height2 = 195;
          }else{
              $new_width3 = 320;
          }
          system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s8");
      }else{
          @copy($img_path,$img_path_s8);
      }
      OSSRun::uploadFile($img_path_s8,"{$table}/320_195/{$folder_name}/{$file_name}");

      //470_285
      if($width>470 || $height>285){
          $new_height2 = ($height*470)/$width;
          $new_width3  = 470;
          if(285<$new_height2){
              $new_width3 = (470*285)/$new_height2;
              $new_height2 = 285;
          }else{
              $new_width3 = 470;
          }
          system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s9");
      }else{
          @copy($img_path,$img_path_s9);
      }
      OSSRun::uploadFile($img_path_s9,"{$table}/470_285/{$folder_name}/{$file_name}");

      $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}
//�Ź����ͼ
function update_group_img_ad($update_name,$ser_id,$tag,$table,$set_type=1){
    //ȡ��չ��
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4.$table."/708_100/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/708_100/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/708_100/".$folder_name,0777);
        }
        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/708_100/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];
        if($width>708 || $height>100){
            $new_height2 = ($height*708)/$width;
            $new_width3  = 708;
            $new_height2;
            if(100<$new_height2){
                $new_width3 = (708*100)/$new_height2;
                $new_height2 = 100;
            }else{
                $new_width3 = 708;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/708_100/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}
/*
* �ϴ�΢������ͼƬ
*/
function upload_wx_img($upload_name,$ser_id,$tag){
    //ȡ��չ��
    global $USERID;
    $tmp_name=$upload_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);
    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = time();
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4."wechat/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."wechat/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."wechat/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."wechat/180_100/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."wechat/180_100/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."wechat/180_100/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."wechat/80_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."wechat/80_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."wechat/80_80/".$folder_name,0777);
        }
        if(!is_dir(UPDATA_DIR_IMG4."wechat/360_200/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4."wechat/360_200/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4."wechat/360_200/".$folder_name,0777);
        }

        $img_path=UPDATA_DIR_IMG4."wechat/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4."wechat/180_100/{$folder_name}/{$file_name}";
        $img_path_s2=UPDATA_DIR_IMG4."wechat/360_200/{$folder_name}/{$file_name}";
        $img_path_s3=UPDATA_DIR_IMG4."wechat/80_80/{$folder_name}/{$file_name}";
        @copy($upload_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"wechat/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];

        //180*100
        if($width>180 || $height>100){
            $new_height2 = ($height*180)/$width;
            $new_width3  = 180;
            if(100<$new_height2){
                $new_width3 = (180*100)/$new_height2;
                $new_height2 = 100;
            }else{
                $new_width3 = 180;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");
        }else{
            @copy($img_path,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"wechat/360_200/{$folder_name}/{$file_name}");

        // 360*200
        if($width>360 || $height>200){
            $new_height2 = ($height*360)/$width;
            $new_width3  = 360;
            if(200<$new_height2){
                $new_width3 = (360*200)/$new_height2;
                $new_height2 = 200;
            }else{
                $new_width3 = 360;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s2 $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"wechat/180_100/{$folder_name}/{$file_name}");
        // 80*80
        if($width>80 || $height>80){
            $new_height2 = ($height*80)/$width;
            $new_width3  = 80;
            if(80<$new_height2){
                $new_width3 = (80*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 80;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path_s3 $img_path_s2");
        }else{
            @copy($img_path,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"wechat/80_80/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/wechat/{$folder_name}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
    }else{
        $file_url='';
    }
    return $file_url;
}
//�ͷ���ά��
function upload_kf_code($update_name,$ser_id,$tag,$table){
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);
    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;
        $table .= '/code';

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/136_136/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/136_136/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/136_136/".$folder_name,0777);
        }
        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
        $img_path_s=UPDATA_DIR_IMG4.$table."/136_136/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];
        if($width>136 || $height>136){
            $new_height2 = ($height*136)/$width;
            $new_width3  = 136;
            if(136<$new_height2){
                $new_width3 = (136*136)/$new_height2;
                $new_height2 = 136;
            }else{
                $new_width3 = 136;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/136_136/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/136_136/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}
/**
 * ��ȡ�û���ά��
 */
function download_user_code($server_url,$table){
    global $USERID;
    //ȡ��չ��
    if(!empty($server_url)){
        $tmp_name = end(explode('/',$server_url));
    }
    $folder_name = ceil($USERID/2000);
    $file_name = $USERID.".jpg";

    if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
        @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
        @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
    }

    if(!is_dir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name)){
        @mkdir(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777,true);
        @chmod(UPDATA_DIR_IMG4.$table."/150_150/".$folder_name,0777);
    }
    $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
    $img_path_s=UPDATA_DIR_IMG4.$table."/150_150/{$folder_name}/{$file_name}";
    if($server_url){
        @copy($server_url,$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");
    }
        //����120ѹ��
    $size = @getimagesize($img_path);
    $width = $size[0];
    $height = $size[1];
    //150*150
    if($width>150 || $height>150){
        $new_height2 = ($height*150)/$width;
        $new_width3  = 150;
        if(150<$new_height2){
            $new_width3 = (150*150)/$new_height2;
            $new_height2 = 150;
        }else{
            $new_width3 = 150;
        }
        system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
    }
    OSSRun::uploadFile($img_path_s,"{$table}/150_150/{$folder_name}/{$file_name}");

    $file_url=IMG_HOST."/".$table."/{$folder_name}/{$file_name}?t=".rand(0,10000);
    return $file_url;
}

//��ȡ�ֻ���ĿURL
function get_wap_channel_url($channel_id){
    global $DB_Ev123;

    $sql="select type,url from ev_user_channel where id=".$channel_id;
    $chan_arr=$DB_Ev123->get_row($sql);
    $chan_type=$chan_arr['type'];

    $return_url='';
    if($chan_type==7){//��̳Ƶ��
        $return_url = '/wap/bbs_index.php?username='. USER_NAME .'&channel_id='.$channel_id.'&flag=1';
    }
    if($chan_type==10){//��ƷƵ��
        $return_url = '/'. USER_NAME .'/wap_pro/'.$channel_id.'_0_0_1.html';
    }
    if($chan_type==11){//����Ƶ��
        $return_url = '/'. USER_NAME .'/wap_doc/'.$channel_id.'_0_0_1.html';
    }
    if($chan_type==12){//��ҳƵ��
        $return_url = '/'. USER_NAME .'/wap_single/'.$channel_id.'.html';
    }
     if($chan_type==16){//���ع���
        $return_url = '/wap/download.php?channel_id='.$channel_id.'&username='.USER_NAME;
    }
    if($chan_type==37){//�Զ���Ƶ��
        $return_url = $chan_arr['url'];
    }
    if($chan_type==14){//����Ƶ��
        $return_url = '/'. USER_NAME .'/wap_ser/'.$channel_id.'_0_1.html';
    }
    if($chan_type==18){//ͼƬƵ��
        $return_url = '/'. USER_NAME .'/wap_pic/'.$channel_id.".html";
    }
    if($chan_type==20){//��Ƶ��
        $return_url = '/wap/form.php?username='. USER_NAME ."&channel_id=".$channel_id;
    }
    if($chan_type==3){//��������
        $return_url = '/wap/guest_book.php?username='. USER_NAME ;
    }
    if($chan_type==15){
        $return_url = '/'. USER_NAME .'/wap_item/list_'.$channel_id.'_0.html';
    }
    if($chan_type==36){
        $return_url = '/wap/tuan_list.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    if($chan_type==38){
        $return_url = '/wap/integral_list.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    if($chan_type==39){
        $return_url = '/wap/blank.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    if($chan_type==41){
        $return_url = '/wap/coupon.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    if($chan_type==42){
        $return_url = '/wap/activity.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    //�ŵ�
    if($chan_type==43){
        $return_url = '/wap/store_list.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    //΢����
    if($chan_type==44){
        $return_url = '/wap/survey_list.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    //360��ȫ��
    if($chan_type==45){
        $return_url = '/wap/overall_list.php?username='.  USER_NAME  .'&channel_id='. $channel_id;
    }
    //����
    if($chan_type==46){
        $return_url = '/dom/meet_activity/meet_list.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    //��˾�Ŷ�
    if($chan_type==47){
        $return_url = '/dom/person_info/person_list.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    //����Ԥ��
    if($chan_type==49){
        $return_url = '/dom/area/area_list.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    //��������
    if($chan_type==50){
        $return_url = '/dom/food_menu/food_menu_list.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    //��Ƶ�γ�
    if($chan_type==51){
        $return_url = '/dom/video/video_list.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    //΢��Ƭ
    if($chan_type==52){
        $return_url = '/wap/wap_card/wap_card_list.php?username='. USER_NAME .'&channel_id='.$channel_id;
    }
    //΢����
    if($chan_type==53){
        $return_url = '/wap/wap_scene/scene_list.php?username='. USER_NAME .'&channel_id='.$channel_id;
    }
    //΢����
    if($chan_type==54){
        $return_url = '/wap/shops/index.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    if ($chan_type == 58) {
        //һԪ��
        $return_url = '/dom/yiyuangou/yiyuangou_list.php?username='.USER_NAME.'&channel_id='.$channel_id.'&wap=1';
    }
    //��ҳ
    if ($chan_type == 59) {
        $return_url = "/wap/HuangYeList/HuangYeList.php?username=".USER_NAME."&channel_id={$channel_id}";
    }
    //΢ͶƱ
    if($chan_type==60){
        $return_url = '/dom/vote/vote_list.php?username='. USER_NAME .'&channel_id='.$channel_id.'&wap=1';
    }
    //΢����
    if($chan_type==61){
        $return_url = '/dom/enrollment/enrollment_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    //΢����
    if($chan_type==62){
        $return_url = '/wap/micro_power/Wap_Activity_List.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    //΢����
    if($chan_type==63){
        $return_url = '/dom/haibao/haibao_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    //΢���
    if($chan_type==64){
        $return_url = '/wap/weihongbao/index.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    //΢ҳ
    if($chan_type==67){
        $return_url = '/wap/MiniPage/minipage_list.php?username='. USER_NAME .'&channel_id='. $channel_id;
    }
    //΢ǩ��
    if($chan_type==68){
        $return_url = '/wap/wap_signin/Wap_SignIn_Proceed.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    //���뺯
    if($chan_type==69){
        $return_url = '/wap/wap_invitation/Wap_Invitation_Activity.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    //��ʱ��
    if($chan_type==71){
        $return_url = '/wap/time_buy/Time_Buy_Product.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ΢ר��
    if($chan_type==73){
        $return_url = '/wap/wap_topics/Wap_Topics_List.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ɨ��֧��
    if($chan_type==74){
        $return_url = '/wap/wap_sweepPay/Wap_SweepPay_Store.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ƴ�Ź�
    if($chan_type==75){
        $return_url = '/wap/fight_group/fight_group_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ֧����
    if($chan_type==77){
        $return_url = '/dom/paycard_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ��Ա��Ȩ
    if($chan_type==78){
        $return_url = '/dom/user_privileges.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ��֤��ѯ
    if($chan_type==79){
        $return_url = '/wap/cert/index.php?username=' . USER_NAME . '&channel_id=' . $channel_id . '&wap=1';
    }
    // ΢����
    if($chan_type==80){
        $return_url = '/wap/wap_bargain/Wap_Bargain_List.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ��Ա�̳�
    if($chan_type==81){
        $return_url = '/dom/user_product_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ��ֹ�
    if($chan_type==82){
        $return_url = '/dom/clearance/clearance_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // 720ȫ��
    if($chan_type==83){
        $return_url = '/dom/qj720.php?username='. USER_NAME .'&wap=1&channel_id='. $channel_id;
    }
    // �����ר��
    if($chan_type==84){
        $return_url = '/dom/award_topic/award_topic_list.php?username='. USER_NAME .'&wap=1&channel_id='. $channel_id;
    }
    // ���ܽɷ�
    if($chan_type==85){
        $return_url = '/wap/universal_payment/index.php?username='. USER_NAME .'&wap=1&channel_id='. $channel_id;
    }
    // ΢�ʴ�
    if($chan_type==86){
        $return_url = '/dom/zhidao/new_questions.php?username='. USER_NAME .'&wap=1&channel_id='. $channel_id;
    }
    if($chan_type==87){
        $return_url = "/wap/xcxIndex.php?username=". USER_NAME ."&channel_id={$channel_id}";
    }
    // ΢Ԥ��
    if($chan_type==88){
        $return_url = '/dom/presales_product_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // �����ƹ�
    if($chan_type==89){
        $return_url = '/wap/scan_payment/index.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ��ʱ��ɱ
    if($chan_type==90){
        $return_url = '/wap/time_seckill/time_seckill_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ����ȯ
    if($chan_type==91){
        $return_url = '/wap/cash_coupon/cash_coupon_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ���˺��
    if($chan_type==92){
        $return_url = '/dom/redpacket_united/redpacket_united_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // �ƹ��̼�
    if($chan_type==93){
        $return_url = '/wap/extension_business/extension_business_list.php?username='. USER_NAME .'&channel_id='. $channel_id .'&wap=1';
    }
    // ϴ����
    if($chan_type==94){
        $return_url = '/wap/carWash_card/carWash_card.php?username='. USER_NAME .'&wap=1&channel_id='. $channel_id;
    }
    //�Ƶ궩��
    if($chan_type==97){
        $return_url = '/wap/hotel/list.php?username='. USER_NAME .'&wap=1&channel_id='. $channel_id;
    }
    return $return_url;
}


//��ȡ��ĿURL
function get_channel_url($channel_id)
{
    $chInfo = Model_Channel::getChannelInfo(
        array(
            'type' => 'type',
            'url' => 'url',
        ),
        "id={$channel_id}"
    );
    $type = $chInfo->type;

    $username = USER_NAME;

    switch ($type) {
        case 10:    //��ƷƵ��
            $url = "/{$username}/products/{$channel_id}_0_0_1.html";
            break;
        case 11:    //����Ƶ��
            $url = "/{$username}/vip_doc/{$channel_id}_0_0_1.html";
            break;
        case 12:    //��ҳƵ��
            $url = "/{$username}/single_{$channel_id}.html";
            break;
        case 13:    //�Զ���Ƶ��
            $url = $chInfo->url;
            break;
        case 14:    //����Ƶ��
            $url = "/{$username}/ser_{$channel_id}_0_1.html";
            break;
        case 16:    //����Ƶ��
            $url = "/dom/down_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 17:    //չ��Ƶ��
            $url = "/self_define/meet_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 18:    //ͼƬƵ��
            $url = "/{$username}/pic/pic_{$channel_id}_0.html";
            break;
        case 19:    //��ƸƵ��
            $url = "/{$username}/job/list_{$channel_id}_0_1.html";
            break;
        case 20:    //��Ƶ��
            $url = "/dom/form.php?username={$username}&channel_id={$channel_id}";
            break;
        case 3:     //��������
            $url = "/self_define/guest_book.php?username={$username}";
            break;
        case 4:
            $url = "/dom/blog.php?channel_id={$channel_id}&username={$username}";
            break;
        case 5:
            $url = "/self_define/qy_sq.php?channel_id={$channel_id}&username={$username}";
            break;
        case 6:
            $url = "/{$username}/company_0.html";
            break;
        case 7:
            $url = "/dom/bbs_index.php?channel_id={$channel_id}&username={$username}";
            break;
        case 8:
            $url = "/dom/album_index.php?channel_id={$channel_id}&username={$username}";
            break;
        case 9:
            $url = "/{$username}/wl2010_0_1.html";
            break;
        case 15:
           $url = "/{$username}/item_{$channel_id}_0.html";
            break;
        case 30:    //����
            $url = "/self_define/price.php?channel_id={$channel_id}&username={$username}";
            break;
        case 31:    //�ٿ�
            $url = "/dom/baike_index.php?channel_id={$channel_id}&username={$username}";
            break;
        case 32:    //֪��
           $url = "/{$username}/zd_{$channel_id}.html";
            break;
        case 33:    //��ѵ�γ�
            $url = "/dom/peixun_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 34:    //�ر�
            $url = "/self_define/map.php?channel_id={$channel_id}&username={$username}";
            break;
        case 35:    //��ѽ�վ
            $url = "/{$username}/jz_{$channel_id}/1_1.html";
            break;
        case 36:    //�Ź�
            $url = "/{$username}/tuan_{$channel_id}/list_1.html";
            break;
        case 38:    //�����̳�
            $url = "/{$username}/vip_jifen/{$channel_id}_0_0_1.html";
            break;
        case 41:    //����
            $url = "/dom/Coupon/Coupon.php?username={$username}&channel_id={$channel_id}";
            break;
        case 43:    //�ŵ�
            $url = "/dom/user_store/user_store_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 46:    //����
            $url = "/dom/meet_activity/meet_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 47:    //��˾�Ŷ�
            $url = "/dom/person_info/person_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 48:    //���ʱش�
            $url = "/dom/answer_questions/answer_questions_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 49:    //����Ԥ��
            $url = "/dom/area/area_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 50:    //��������
            $url = "/dom/food_menu/food_menu_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 51:    //��Ƶ�γ�
            $url = "/dom/video/video_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 54:    //����
            $url = "/wap/shops/index.php?username={$username}&channel_id={$channel_id}&pc=1";
            break;
        case 58:    //һԪ��
            $url = "/dom/yiyuangou_pc/yiyuangou_list.php?username={$username}&channel_id={$channel_id}";
        break;
        case 59:    //��ҳ
           $url = "/self_define/HuangYeList.php?username={$username}&channel_id={$channel_id}";
            break;
        case 70:    //400�绰
           $url = "/dom/400/400_index.php?username={$username}&channel_id={$channel_id}";
            break;
        case 71:    //��ʱ��
           $url = "/wap/time_buy/Time_Buy_Product.php?username={$username}&channel_id={$channel_id}";
            break;
        case 72:    //��ѽ�վ
            $url = "/dom/my_jz/myJzIndex.php?username={$username}&channel_id={$channel_id}";
            break;
        case 76:    //�հ�ҳ
           $url = "/{$username}/bk_{$channel_id}.html";
            break;
        case 77:    //֧����
           $url = "/dom/paycard_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 78:    //��Ա��Ȩ
           $url = "/dom/user_privileges.php?username={$username}&channel_id={$channel_id}";
            break;
        case 79:    //��֤��ѯ
           $url = "/dom/cert/index.php?username={$username}&channel_id={$channel_id}&data-saveurl=1&is_action=is_action";
            break;
        case 82:    //��ֹ�
           $url = "/dom/clearance/clearance_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 81:    //��Ա�̳�
           $url = "/dom/user_product_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 85:    //��Ա��Ȩ
           $url = "/wap/universal_payment/index.php?username={$username}&channel_id={$channel_id}";
            break;
        case 88:    //΢Ԥ��
           $url = "/dom/presales_product_list.php?username={$username}&channel_id={$channel_id}";
            break;
        case 83:
            $url = "/dom/qj720.php?username={$username}&channel_id={$channel_id}";
            break;
        default:
            $url = '';
            break;
    }
    return $url;
}

function get_channel_content($channel_id){
    global $DB_Ev123,$DB_Product,$DB_Server,$USERNAME,$USERID;

    $return_url='';

    $sql="select type from ev_user_channel where id=".$channel_id;
    $chan_type=$DB_Ev123->get_var($sql);
    //��Ʒ
    if($chan_type==10){
        $sql = "select bigclassid from proinfo where userid=".$USERID." and channel_id={$channel_id} and is_del=0 and infostate='publish' group by bigclassid ";
        $rec = $DB_Product->get_results($sql);
        $id_str = "";
        if($rec){
            foreach($rec as $r){
                if($id_str<>""){
                    $id_str.=",";
                }
                $id_str.=$r['bigclassid'];
            }
        }
        if($id_str){
            $tmp_sql = " id in(".$id_str.") or ";
        }

        $sql="select id,classname,url,url_yemian from proclass where ".$tmp_sql." (type=1 and parentid=0 and channel_id=".$channel_id." and is_del=0 and classname!='') order by order_sort desc,id desc";
        $big_arr=$DB_Product->get_results($sql,'O');

        if($big_arr){
            foreach($big_arr as $big){
                //$tmp_arr=array();
                $return_url='<div class="Link_c_1">'.$big->classname;



                /*$big_class_arr[$big->id]['classname']=$big->classname;
                $big_class_arr[$big->id]['link']='/'.$USERNAME.'/products/'.$channel_id.'_'.$big->id.'_0_1.html';

                if($big->url && $big->url_yemian){
                    $big_class_arr[$big->id]['link']=$big->url;
                }else{
                    $big_class_arr[$big->id]['link']='/'.$USERNAME.'/products/'.$channel_id.'_'.$big->id.'_0_1.html';
                }*/


                $sql="select id,classname,url,url_yemian from proclass where userid=".$USERID." and channel_id=".$channel_id." and type=2 and parentid=".$big->id." and classname!='' and is_del=0 order by order_sort desc,id desc ";

                $tmp_arr=$DB_Product->get_results($sql,'O');

                if($tmp_arr){
                    $return_url.='<ul class="Link_c_2">';

                    foreach($tmp_arr as $tmp){
                        $return_url.='<li>'.$tmp->classname.'</li>';
                        /*$sub_class_arr[$big->id][$tmp->id]['classname']=$tmp->classname;
                        $sub_class_arr[$big->id][$tmp->id]['link']='/'.$USERNAME.'/products/'.$channel_id.'_'.$big->id.'_'.$tmp->id.'_1.html';*/

                        if($tmp->url && $tmp->url_yemian){
                            $sub_class_arr[$big->id][$tmp->id]['link']=$tmp->url;
                        }else{
                            $sub_class_arr[$big->id][$tmp->id]['link']='/'.$USERNAME.'/products/'.$channel_id.'_'.$big->id.'_'.$tmp->id.'_1.html';
                        }

                    }
                    $return_url.='</ul>';

                }

                $return_url.='</div>';

            }
        }
    }
    //����
    if($chan_type==11){
        $sql="select id,name,url,url_yemian from ev_user_class where userid=".$userid." and channel_id=".$channel_id." and type=1 and state=0 and parentid=0 and name!='' order by order_sort desc,id desc ";
        $big_arr=$DB_Ev123->get_results($sql,'O');

        if($big_arr){
            $i=0;
            foreach($big_arr as $big){
                $tmp_arr=array();

                $big_class_arr[$big->id]['classname']=$big->name;
                if($big->url && $big->url_yemian){
                    $big_class_arr[$big->id]['link']=$big->url;
                }else{
                    $big_class_arr[$big->id]['link']='/'.$USERNAME.'/vip_doc/'.$channel_id.'_'.$big->id.'_0_1.html';
                }
                $big_class_arr[$big->id]['num']=$i;
                $i++;

                $sql="select id,name,url,url_yemian from ev_user_class where userid=".$userid." and channel_id=".$channel_id." and type=2 and parentid=".$big->id." and name!='' and state=0 order by order_sort desc,id desc ";
                $tmp_arr=$DB_Ev123->get_results($sql,'O');

                if($tmp_arr){
                    $j=0;
                    foreach($tmp_arr as $tmp){
                        $sub_class_arr[$big->id][$tmp->id]['classname']=$tmp->name;
                        $sub_class_arr[$big->id][$tmp->id]['num']=$j;
                        $j++;

                        if($tmp->url && $tmp->url_yemian){
                            $sub_class_arr[$big->id][$tmp->id]['link']=$tmp->url;
                        }else{
                            $sub_class_arr[$big->id][$tmp->id]['link']='/'.$USERNAME.'/vip_doc/'.$channel_id.'_'.$big->id.'_'.$tmp->id.'_1.html';
                        }
                    }
                }
            }
        }

    }
    //��ҳ
    if($chan_type==12){
        $sql="select name from ev_user_channel where id=".$channel_id;
        $chan_name=$DB_Ev123->get_var($sql);
    }
    //����
    if($chan_type==14){
        $sql = "select sub_id from serverinfo where userid=".$USERID." and channel_id=".$channel_id." group by sub_id" ;
        $sub_arr = $DB_Server->get_results($sql);
        $sub_class_arr = array();
        if($sub_arr){
            $j = 0;
            foreach($sub_arr as $sub){
                $sql = "select classname from serverclass where id=".$sub['sub_id']." and classname!='' ";
                $class_name = $DB_Server->get_var($sql);
                if($class_name){
                    $sub_class_arr[$j]['id'] = $sub['sub_id'];
                    $sub_class_arr[$j]['classname'] = $class_name;
                    $j++;
                }

            }
        }
    }


    return $return_url;

}


function rand_keys($length)
{
    $pattern = '1234asdfoewenasnoweruyoOWER9234*()^$!_+=-hijklmnopqrstuvwxyzABCDEFGHIJKLOMZ';    //�ַ���
    for($i=0;$i<$length;$i++)
    {
        $key.= $pattern{mt_rand(0,35)};//����php�����
    }
    return $key;
}

function tmp_pic_set_size($img_path,$img_path_s2,$t_width,$t_height,$new_fn,$source=array()){
    $img_path = str_replace("https://", "http://", $img_path);

    if($new_fn){
        $is_success=my_image_resize($img_path,$img_path_s2,$t_width,$t_height);
    }else{
        $is_success=0;
    }

    if(!$is_success){
        $size = $source ? $source : @getimagesize($img_path);

        $width = $size[0];
        $height = $size[1];
        if($width>$t_width || $height>$t_height){
            $new_height2 = ($height*$t_width)/$width;
            $new_width3  = $t_width;
            if($t_height<$new_height2){
                $new_width3 = ($t_width*$t_height)/$new_height2;
                $new_height2 = $t_height;
            }else{
                $new_width3 = $t_width;
            }

            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");
        }else{
            @copy($img_path,$img_path_s2);
        }
    }
    @chown($img_path_s2, 0777);
    $remotePath = str_replace(UPDATA_DIR_IMG4, '', $img_path_s2);
    OSSRun::uploadFile($img_path_s2,$remotePath);
    return $img_path_s2;
}

function tmpPicSetSize($img_path,$img_path_s2,$t_width,$t_height,$new_fn,$source=array())
{
    $img_path = str_replace("https://", "http://", $img_path);

    if ($new_fn) {
        $is_success = my_image_resize($img_path, $img_path_s2, $t_width, $t_height);
    } else {
        $is_success = 0;
    }


    if (!$is_success) {
        $size = $source ? $source : @getimagesize($img_path);

        $width  = $size[0];
        $height = $size[1];
        if ($width > $t_width || $height > $t_height) {
            $new_height2 = ($height * $t_width) / $width;
            $new_width3  = $t_width;
            if ($t_height < $new_height2) {
                $new_width3  = ($t_width * $t_height) / $new_height2;
                $new_height2 = $t_height;
            } else {
                $new_width3 = $t_width;
            }

            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s2");
        } else {
            @copy($img_path, $img_path_s2);
        }
    }
    @chown($img_path_s2, 0777);
    $remotePath = str_replace(REPLACE_DIR_IMG4, '', $img_path_s2);

    OSSRun::uploadFile($img_path_s2, $remotePath);

    return $img_path_s2;
}

//��ȡIP
function getIP(){
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

//��ʽ���۸�
function format_price_new($price){
    $tmp_arr=explode('.',$price);
    $price = preg_replace("'0+$'","",$tmp_arr[1]);
    $price_end =substr($price,-1);
    if($price_end=='.')$price ='';
    if($price){
        $price=$tmp_arr[0].".".$price;
    }else{
        $price=$tmp_arr[0];
    }
    return $price;
}

//д�⴦��
function format_mysql_insert($val, $is_del_html = 1, $is_del_js = 1, $is_del_spec = 0, $isSpace = 1)
{
    if ($isSpace) {
        $val = trim($val);
    }

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
        $search = array ("'<script[^>]*?>
.*?</script>'si");
        $replace = array("");
      $val=preg_replace($search,$replace,$val);
    }
    $val=mysql_real_escape_string($val);
    if ($isSpace) {
        $val=trim($val);
    }
    //$val=addslashes($val);
    return $val;
}

//�ӽ��ܺ���
function Encrypt($string,$operation,$key=''){
    $key=md5($key);
    $key_length=strlen($key);
      $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
    $string_length=strlen($string);
    $rndkey=$box=array();
    $result='';
    for($i=0;$i<=255;$i++){
           $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0;$i<256;$i++){
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++){
        $a=($a+1)%256;
        $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation=='D'){
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
            return substr($result,8);
        }else{
            return'';
        }
    }else{
        return str_replace('=','',base64_encode($result));
    }
}


function shellCode($username,$userId)
{
    return  md5(md5($username.$userId).'Bzt@#*&^)#@321Bzt');
}

/** ����$_POST��$_GET��
*   JiangBiao@20121011 add
*/
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
//����
function debug($level = 0) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

function NoHTML($string){

    $string = preg_replace("'<script[^>]*?>.*?</script>'si", "", $string);//ȥ��javascript
    $string = preg_replace("'<[\/\!]*?[^<>]*?>'si", "", $string);         //ȥ��HTML���
    //$string = preg_replace("'([\r\n])[\s]+'", "", $string);               //ȥ���հ��ַ�
    $string = preg_replace("'&(quot|#34);'i", "", $string);               //�滻HTMLʵ��
    $string = preg_replace("'&(amp|#38);'i", "", $string);
    $string = preg_replace("'&(lt|#60);'i", "", $string);
    $string = preg_replace("'&(gt|#62);'i", "", $string);
    $string = preg_replace("'&(nbsp|#160);'i", "", $string);
    return $string;
}

function html2text($document)
{
    $search = array(
        "'<script[^>]*?>.*?</script>'si", // ȥ�� javascript
        "'<style[^>]*?>.*?</style>'si", // ȥ�� css
        "'<[/!]*?[^<>]*?>'si", // ȥ�� HTML ���
        "'<!--[/!]*?[^<>]*?>'si", // ȥ�� ע�ͱ��
        "'([rn])[s]+'", // ȥ���հ��ַ�
        "'&(quot|#34);'i", // �滻 HTML ʵ��
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&#(d+);'e"
    );
    $replace = array ("", "", "", "", "\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\1)");
    //$documentΪ��Ҫ�����ַ����������ԴΪ�ļ�����$document = file_get_contents('http://www.sina.com.cn');
    $document = preg_replace($search, $replace, $document);
    $out      = str_replace(array("\n", "\r", " ","&nbsp;","\t"), '', $document);
    return $out;
}

/*
*˵�������������ǰ�һ��ͼ��ü�Ϊ�����С��ͼ��ͼ�񲻱���
* ����˵�������� ��Ҫ����ͼƬ�� �ļ�����������ͼƬ�ı����ļ�����������ͼƬ�Ŀ�������ͼƬ�ĸ�
* written by smallchicken
* time 2008-12-18
*/
// ��������Сͼ�񣬲���ط����죬���������Σ������¿հ�
function my_image_resize($src_file, $dst_file , $new_width , $new_height)
{
    if ($new_width == 1920 && $new_height == 200000) {
        $new_width  = 800;
        $new_height = 1500;
    }
    if ($new_width < 1 || $new_height < 1) {
        echo "params width or height error !";

        return false;
    }
    if (!file_get_contents($src_file)) {
        echo $src_file." is not exists !";

        return false;
    }

    // ͼ������
    $type = exif_imagetype($src_file);

    $support_type = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);

    if (!in_array($type, $support_type, true)) {
        echo 'asdfasd';
        echo "this type of image does not support! only support jpg , gif or png";

        return false;
    }
    //Load image
    switch ($type) {
        case IMAGETYPE_JPEG :
            $src_img = imagecreatefromjpeg($src_file);
            break;
        case IMAGETYPE_PNG :
            $src_img = imagecreatefrompng($src_file);
            //return false;
            break;
        case IMAGETYPE_GIF :
            $src_img = imagecreatefromgif($src_file);
            break;
        default:
            echo "Load image error!";

            return false;
    }

    $w       = imagesx($src_img);
    $h       = imagesy($src_img);
    $ratio_w = 1.0 * $new_width / $w;
    $ratio_h = 1.0 * $new_height / $h;
    $ratio   = 1.0;

    if ($w == $new_width && $h == $new_height) {
        @copy($src_file, $dst_file);
    } elseif (($ratio_w < 1 && $ratio_h < 1) || ($ratio_w > 1 && $ratio_h > 1)) { // ���ɵ�ͼ��ĸ߿��ԭ���Ķ�С���򶼴� ��ԭ���� ȡ������Ŵ�ȡ�������С����С�ı����ͱȽ�С�ˣ�
        if ($ratio_w < $ratio_h) {
            $ratio = $ratio_h; // ���һ����ȵı����ȸ߶ȷ����С�����ո߶ȵı�����׼���ü���Ŵ�
        } else {
            $ratio = $ratio_w;
        }
        // ����һ���м����ʱͼ�񣬸�ͼ��Ŀ�߱� ��������Ŀ��Ҫ��
        $inter_w   = (int)($new_width / $ratio);
        $inter_h   = (int)($new_height / $ratio);
        $inter_img = imagecreatetruecolor($inter_w, $inter_h);

        $color = imagecolorallocate($inter_img, 255, 255, 255);
        imagecolortransparent($inter_img, $color);
        imagefill($inter_img, 0, 0, $color);

        imagecopy($inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h);
        // ����һ�������߳���Ϊ��С����Ŀ��ͼ��$ratio��������ʱͼ��
        // ����һ���µ�ͼ��
        $new_img = imagecreatetruecolor($new_width, $new_height);
        $color2  = imagecolorallocate($new_img, 255, 255, 255);
        imagecolortransparent($new_img, $color2);
        imagefill($new_img, 0, 0, $color2);

        imagecopyresampled($new_img, $inter_img, 0, 0, 0, 0, $new_width, $new_height, $inter_w, $inter_h);

        switch ($type) {
            case IMAGETYPE_JPEG :
                $tmp = imagejpeg($new_img, $dst_file, 100); // �洢ͼ��
                break;
            case IMAGETYPE_PNG :
                imagepng($new_img, $dst_file);
                break;
            case IMAGETYPE_GIF :
                imagegif($new_img, $dst_file, 100);
                break;
            default:
                break;
        }
    } else { // Ŀ��ͼ�� ��һ���ߴ���ԭͼ��һ����С��ԭͼ ���ȷŴ�ƽ��ͼ��Ȼ��ü�
        $ratio = $ratio_h > $ratio_w ? $ratio_h : $ratio_w; //ȡ��������Ǹ�ֵ
        // ����һ���м�Ĵ�ͼ�񣬸�ͼ��ĸ߻���Ŀ��ͼ����ȣ�Ȼ���ԭͼ�Ŵ�
        $inter_w = (int)($w * $ratio);
        $inter_h = (int)($h * $ratio);
        if ($new_width == 800 && $new_height == 1500) {
            $inter_img = imagecreatetruecolor($inter_w, $inter_h);
            //��ԭͼ���ű�����ü�
            imagecopyresampled($inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h, 1500, 1500);
            // ����һ���µ�ͼ��
            $new_img = imagecreatetruecolor(800, 800);
            imagecopy($new_img, $inter_img, 0, 0, 0, 0, 1500, 1500);
        } else {
            $inter_img = imagecreatetruecolor($inter_w, $inter_h);
            //��ԭͼ���ű�����ü�
            imagecopyresampled($inter_img, $src_img, 0, 0, 0, 0, $inter_w, $inter_h, $w, $h);
            // ����һ���µ�ͼ��
            $new_img = imagecreatetruecolor($new_width, $new_height);
            imagecopy($new_img, $inter_img, 0, 0, 0, 0, $new_width, $new_height);
        }

        switch ($type) {
            case IMAGETYPE_JPEG :
                imagejpeg($new_img, $dst_file, 100); // �洢ͼ��
                break;
            case IMAGETYPE_PNG :
                imagepng($new_img, $dst_file);
                break;
            case IMAGETYPE_GIF :
                imagegif($new_img, $dst_file, 100);
                break;
            default:
                break;
        }
    }// if3
    //die();

    return true;
}// end function



/**
* �Ա����������ݽ���urlencode����
ʹ���ڽ���json_encode��ʱ����б���Ĳ�������
��ֹjson_encodeʧ��
* @access private
* @param $var * @return array */
function var_urlencode($var) {
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

/** * �Ա����ı�������json_encode
json_encode��֧�����ĵ�����
* @access public * @param $var
* @return string */
function var_json_encode($var) {
    $_var = var_urlencode($var);
    $_str = json_encode($_var);
    return $_str;
}

/**
json_decode��֧�����ĵ�����
**/
function var_json_decode($var){
    $var=var_urldecode(json_decode($var,true));
    return $var;
}
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

//�õ�ip
function get_host_ip($host) {
    $ip =  gethostbyname($host);
    if($ip == $host)
        return false;
    else
        return $ip;
}

//��֤Email
function validateEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

//�Զ��ر�HTML
function close_html_tags($html) {
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
            if(!strstr($openedtags[$i],'img')){
                $html .= '</'.$openedtags[$i].'>';
            }
          } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
          }
      }
      return $html;
 }


 // ��Ҫ��ѯ���ֶι�������ת��Ϊ�ַ���
function get_field_string($fields) {
    if(!is_array($fields) || empty($fields)) {
            return false;
        }

    $final_fields = array();
    foreach($fields as $key=>$field){
       array_push($final_fields, $field.' as  '.$key);
    }
    return $tmp_sql = !empty($final_fields) ? join(',',$final_fields) : '';
}

function insert($table, $field_array=array(), $db=false, $debug=false){
    if( empty($field_array) || !is_array($field_array) ) {
        return false;
    }

    if (empty($db)) {
        global $DB_Ev123;
        $db = $DB_Ev123;
    }

    $fields    = implode(',',array_keys($field_array));
    $valuesArr = array_values($field_array);
    foreach($valuesArr as &$_v){
        $_v = "'$_v'";
    }
    $values = implode(',', $valuesArr);

    $sql = 'insert into '. $table .'('. $fields .') values('. $values .')';
    if(!empty($debug)){
        echo 'SQL:'.$sql.'<br>';
    }
    $db->query($sql);
    return $db->last_insert_id();
}

function update($table, $field_array=array(), $where='', $db=false, $debug=false){
    if( empty($field_array) || !is_array($field_array) ) {
        return false;
    }

    if (empty($db)) {
        global $DB_Ev123;
        $db = $DB_Ev123;
    }

    $tmp_sql = $concat = '';
    foreach($field_array as $key=>$value){
        $concat .= $key."='$value',";
    }

    $new_fields = substr($concat,0,-1);
    $tmp_sql    = (is_string($where) && $where ) ? ' where '.$where : '';
    $sql        = 'update '.$table.' set '.$new_fields.$tmp_sql;

    if(!empty($debug)){
        echo 'SQL:'.$sql.'<br>';
    }

    $db->query($sql);
    return $db->affected_rows();
}
/**
 * [upload_wap_bbs_code ΢����ͷ��]
 * @param  [type] $update_name [description]
 * @param  [type] $ser_id      [description]
 * @param  [type] $tag         [description]
 * @param  [type] $table       [description]
 * @return [type]              [description]
 */
function upload_wap_bbs_code($update_name,$ser_id,$tag,$table){
    global $USERID;
    $tmp_name=$update_name["name"];
    $ext_arr=explode('.',$tmp_name);
    $ext=end($ext_arr);
    $tmp_ext = strtoupper($ext);
    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){

        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$USERID."_".$tag.".".$ext;

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name,0777);
        }

        if(!is_dir(UPDATA_DIR_IMG4.$table."/80_80/".$folder_name)){
            @mkdir(UPDATA_DIR_IMG4.$table."/80_80/".$folder_name,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/80_80/".$folder_name,0777);
        }
         $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/{$file_name}";
         $img_path_s=UPDATA_DIR_IMG4.$table."/80_80/{$folder_name}/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);
        OSSRun::uploadFile($img_path,"{$table}/{$folder_name}/{$file_name}");

        //����120ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];
        if($width>80 || $height>80){
            $new_height2 = ($height*80)/$width;
            $new_width3  = 80;
            if(80<$new_height2){
                $new_width3 = (80*80)/$new_height2;
                $new_height2 = 80;
            }else{
                $new_width3 = 80;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path_s");
        }else{
            @copy($img_path,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"{$table}/80_80/{$folder_name}/{$file_name}");

        $file_url=IMG_HOST."/".$table."/80_80/{$folder_name}/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}

/**
 * url ����
 * @param  integer $redirectType ��ת��ʽ
 * @param  string  $URL          url��ַ
 * @param  string  $alert        js������ʾ��Ϣ
 */
function redirect($redirectType=3, $URL='', $alert='') {
    switch($redirectType)
    {
        case 1:
            header("location: $URL");
            break;
        case 2:
            echo("<script language=\"JavaScript\" type=\"text/javascript\"> window.location.href = \"$URL\"; </script>");
            break;
        case 3:
            echo ' <SCRIPT> setTimeout("window.location.replace(\"'.$URL.'\")",500);  </SCRIPT>';
            exit();
            break;
        case 4:
            echo "<script type='text/javascript'>alert('{$alert}');window.location.href='{$URL}'</script>";
            break;
        case 5:
            echo "<script type='text/javascript'>alert('{$alert}');history.go(-1);</script>";
            break;
        default:
            header("location: $URL");
            break;
    }
    exit();
}
/**
 * [get_member_integral ��վ��Ա����]
 * @param  array   $field     [��ʾ�ֶ�]
 * @param  integer $type      [type=1 �̳ǻ��� type>1 ��վ����]
 * @param  integer $member_id [��վ��ԱID]
 * @param  integer $pagerows  [��ʾ����]
 * @param  integer $page      [��ǰҳ��]
 * @param  integer $is_debug  [�Ƿ����SQL]
 * @return [array] $rs        [��������]
 */
function get_member_integral($field = array() , $type = 2 , $member_id = 0 ,$pagerows = 0, $page = 1 , $is_debug = 0){
    global $DB_Ev123,$DB_Product,$USERNAME,$USERID;
    $tmp_where          = $type == 1 ? ' AND type = 1 AND state = 1' : ' AND type >1';
    $tmp_where          .= !empty($member_id) ? ' AND user_user_id = '.$member_id : '';

    if(!empty($pagerows)){
        //��ҳ
        if(!$page || 0 ==(int)$page){
            $page           = 1;
            $offset         = 0;
        }else if($page<=0){
            $page           = 1;
            $offset         = 0;
        }else{
            $page           = $page;
            $offset         = ($page-1) *   $pagerows;
        }
        $integral_cnt       = 'SELECT COUNT(*) FROM '.USER_INTEGRAL_TABLE.' WHERE is_del = 0 AND user_id = '.$USERID.$tmp_where;
        if(!empty($is_debug)){
            echo '<br/>'.$integral_cnt;
        }
        $integral_cnt       =  $DB_Product->get_var($integral_cnt);
        $tmp_limit          =  'LIMIT '.$offset.' , '.$pagerows;
    }
    $field_str          = !empty($field) ? implode(',', $field) : '*';
    $integral_info      = 'SELECT '.$field_str.' FROM '.USER_INTEGRAL_TABLE.'
                           WHERE is_del = 0 AND user_id ='.$USERID.$tmp_where.
                           ' ORDER BY id DESC '.
                           $tmp_limit
                           ;
    if(!empty($is_debug)){
        echo '<br/>'.$integral_info;
    }
    $integral_info      = $DB_Product->get_results($integral_info);

    if(!empty($integral_info)){

        return array('integral_info'=>$integral_info , 'integral_cnt'=>$integral_cnt);
    }else{
        return false;
    }
}
/**
 * [get_member_used_integral ��վ��Ա�������Ѽ�¼ join ��ѯ integral order o integral_order_detail d]
 * @param  array   $field     [��ʾ�ֶ�]
 * @param  integer $member_id [��վ��ԱID]
 * @param  integer $pagerows  [��ʾ����]
 * @param  integer $state     [1δ���� 2������ 3�����]
 * @param  integer $page      [��ǰҳ��]
 * @param  integer $is_debug  [�Ƿ�����SQL]
 * @return [array] $rs        [��������]
 */
function get_member_used_integral($field = array() , $state = 1 , $member_id = 0 ,$pagerows = 0, $page = 1 , $is_debug = 0){
    global $DB_Ev123,$DB_Product,$USERNAME,$USERID;

    $tmp_where = ' o.is_del = 0 AND o.user_id = '.$USERID;
    $tmp_where .= !empty($state)      ? ' AND state = '.$state : '';
    $tmp_where .= !empty($member_id)  ? ' AND user_user_id = '.$member_id : '';
    if(!empty($pagerows)){
        //��ҳ
        if(!$page || 0 ==(int)$page){
            $page   = 1;
            $offset = 0;
        }else if($page<=0){
            $page   = 1;
            $offset = 0;
        }else{
            $page   = $page;
            $offset = ($page-1) *   $pagerows;
        }
        $used_integral_cnt  = 'SELECT count(*) FROM integral_order o LEFT JOIN integral_order_detail d ON o.id = d.order_id
                               WHERE '. $tmp_where;
        if(!empty($is_debug)){
            echo '<br />'.$used_integral_cnt;
        }
        $used_integral_cnt  = $DB_Product->get_var($used_integral_cnt);
        $tmp_limit          = 'LIMIT '.$offset.' , '.$pagerows;
    }

    $field_str          = !empty($field) ? implode(',', $field) : '*';
    $used_integral_info = 'SELECT '. $field_str .' FROM integral_order o LEFT JOIN integral_order_detail d ON o.id = d.order_id
                           WHERE  '. $tmp_where.' ORDER BY id DESC '.$tmp_limit;
    if(!empty($is_debug)){
        echo '<br />'.$used_integral_info;
    }
    $used_integral_info = $DB_Product->get_results($used_integral_info);
    if(empty($used_integral_info)){
        return false;
    }
    if(is_array($field) && in_array('d.product_id', $field)){
        $id_str = '';
        $id_arr = array();

        foreach ($used_integral_info as $k => $v) {
            empty($id_str) ? $id_str  = $v['product_id'] : $id_str .= ','. $v['product_id'];
            $id_arr[$v['product_id']] = $v['product_id'];
        }
         $id_str = implode(',', $id_arr);
         if (!empty($id_str)) {
            $integral_product = 'SELECT id,name,web_integral,mall_integral FROM integral_product
                                 WHERE is_del = 0 AND user_id = '.$USERID.' AND id IN ('. $id_str .') ';
            $product_info     = $DB_Product->get_results($integral_product);
        }

        if ($product_info) {
            foreach($product_info as $k => $v){
                $product_arr[$v['id']] = $v;
            }
            foreach ($used_integral_info as $k=>$v) {

                if(!empty($product_arr[$v['product_id']])){
                    $used_integral_info[$k]['product_info'] = $product_arr[$v['product_id']];
                }
            }
        }
    }
    return array('used_integral_info'=>$used_integral_info , 'used_integral_cnt'=>$used_integral_cnt);
}
//ͼƬ���
function upload_common_user_pic($update_name,$ser_id,$tag,$server_path='',$set_type=1){
    global $USERID;
    //�ж��Ƿ�Ϊ������Ŀ¼
    if($server_path){
        $tmp_name = end(explode('/',$server_path));
    }else{
        $tmp_name = $update_name["name"];
    }
    $ext_arr = explode('.',$tmp_name);
    $ext     = end($ext_arr);
    $ext     = ($ext=='tbi') ? 'jpg' : $ext;
    $tmp_ext = strtoupper($ext);
    $sizeArr[] = array('width'=>100,'height'=>80);
    $sizeArr[] = array('width'=>120,'height'=>90);
    $sizeArr[] = array('width'=>150,'height'=>150);
    $sizeArr[] = array('width'=>200,'height'=>200);
    $sizeArr[] = array('width'=>280,'height'=>280);
    $sizeArr[] = array('width'=>380,'height'=>380);
    $sizeArr[] = array('width'=>600,'height'=>600);
    $sizeArr[] = array('width'=>800,'height'=>10000);
    $sizeArr[] = array('width'=>1440,'height'=>300);

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){
        $folder_name = ceil($ser_id/2000);
        $file_name   = $ser_id."_".$tag.".".$ext;

        foreach ($sizeArr as $k => $v) {
            $tmpSize = $v['width'].'_'.$v['height'];
            if(!is_dir(UPDATA_DIR_IMG4."common_pic/".$tmpSize."/".$USERID)){
                @mkdir(UPDATA_DIR_IMG4."common_pic/".$tmpSize."/".$USERID,0777,true);
                @chmod(UPDATA_DIR_IMG4."common_pic/".$tmpSize."/".$USERID,0777);
            }
        }
        $img_path    = UPDATA_DIR_IMG4."common_pic/{$USERID}/{$file_name}";
        $img_path_s  = UPDATA_DIR_IMG4."common_pic/150_150/{$USERID}/{$file_name}";
        $img_path_s2 = UPDATA_DIR_IMG4."common_pic/200_200/{$USERID}/{$file_name}";
        $img_path_s3 = UPDATA_DIR_IMG4."common_pic/280_280/{$USERID}/{$file_name}";
        $img_path_s4 = UPDATA_DIR_IMG4."common_pic/380_380/{$USERID}/{$file_name}";
        $img_path_s5 = UPDATA_DIR_IMG4."common_pic/600_600/{$USERID}/{$file_name}";
        $img_path_s6 = UPDATA_DIR_IMG4."common_pic/800_10000/{$USERID}/{$file_name}";
        $img_path_s7  = UPDATA_DIR_IMG4."common_pic/100_80/{$USERID}/{$file_name}";
        $img_path_s8  = UPDATA_DIR_IMG4."common_pic/120_90/{$USERID}/{$file_name}";
        $img_path_s9  = UPDATA_DIR_IMG4."common_pic/1440_300/{$USERID}/{$file_name}";

        if($server_path){
            @copy($server_path,$img_path_s6);
        }else{
            @copy($update_name["tmp_name"],$img_path_s6);
        }

        //����120ѹ��
        $size   = @getimagesize($img_path_s6);
        $width  = $size[0];
        $height = $size[1];
        $imgSize = array('width'=>$width,'height'=>$height);
        cut_pic_size($imgSize,800,10000,$img_path_s6,$img_path_s6);
        OSSRun::uploadFile($img_path_s6,"common_pic/800_10000/{$USERID}/{$file_name}");

        //100*80
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s7,100,80);
        }else{
            cut_pic_size($imgSize,100,80,$img_path_s6,$img_path_s7);
        }
        OSSRun::uploadFile($img_path_s7,"common_pic/100_80/{$USERID}/{$file_name}");

        //120*90
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s8,120,90);
        }else{
            cut_pic_size($imgSize,120,90,$img_path_s6,$img_path_s8);
        }
        OSSRun::uploadFile($img_path_s8,"common_pic/120_90/{$USERID}/{$file_name}");

        //150*150
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s,150,150);
        }else{
            cut_pic_size($imgSize,150,150,$img_path_s6,$img_path_s);
        }
        OSSRun::uploadFile($img_path_s,"common_pic/150_150/{$USERID}/{$file_name}");

        //200_200
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s2,200,200);
        }else{
            cut_pic_size($imgSize,200,200,$img_path_s6,$img_path_s2);
        }
        OSSRun::uploadFile($img_path_s2,"common_pic/200_200/{$USERID}/{$file_name}");

        //280_280
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s3,280,280);
        }else{
            cut_pic_size($imgSize,280,280,$img_path_s6,$img_path_s3);
        }
        OSSRun::uploadFile($img_path_s3,"common_pic/280_280/{$USERID}/{$file_name}");

        //380_380
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s4,380,380);
        }else{
            cut_pic_size($imgSize,380,380,$img_path_s6,$img_path_s4);
        }
        OSSRun::uploadFile($img_path_s4,"common_pic/380_380/{$USERID}/{$file_name}");

        //600_600
        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s5,600,600);
        }else{
            cut_pic_size($imgSize,600,600,$img_path_s6,$img_path_s5);
        }
        OSSRun::uploadFile($img_path_s5,"common_pic/600_600/{$USERID}/{$file_name}");

        if($set_type==1){
            $is_success=my_image_resize($img_path_s6,$img_path_s9,1440,300);
        }else{
            cut_pic_size($imgSize,1440,300,$img_path_s6,$img_path_s9);
        }
        OSSRun::uploadFile($img_path_s9,"common_pic/1440_300/{$USERID}/{$file_name}");

        $file_url=IMG_HOST."/common_pic/{$USERID}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
    }else{
        $file_url='';
    }
    return $file_url;
}

function upload_wap_card($update_name,$ser_id,$tag,$set_type=1){
    global $USERID;
  $tmp_name = $update_name["name"];
  $ext_arr = explode('.',$tmp_name);
  $ext     = end($ext_arr);
  $ext     = ($ext=='tbi') ? 'jpg' : $ext;
  $tmp_ext = strtoupper($ext);
  $sizeArr[] = array('width'=>640,'height'=>410);
  $sizeArr[] = array('width'=>640,'height'=>430);
  $sizeArr[] = array('width'=>640,'height'=>320);
  if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){
    $folder_name = ceil($ser_id/2000);
    $file_name   = $ser_id."_".$USERID."_".$tag.".".$ext;

    foreach ($sizeArr as $k => $v) {
      $tmpSize = $v['width'].'_'.$v['height'];

      if(!is_dir(UPDATA_DIR_IMG4.'wap_card/'.$folder_name.'/'.$tmpSize)){
        @mkdir(UPDATA_DIR_IMG4.'wap_card/'.$folder_name.'/'.$tmpSize,0777,true);
        @chmod(UPDATA_DIR_IMG4.'wap_card/'.$folder_name.'/'.$tmpSize,0777);
      }
    }
    $img_path    = UPDATA_DIR_IMG4."wap_card/{$folder_name}/{$file_name}";
    $img_path_s  = UPDATA_DIR_IMG4."wap_card/{$folder_name}/640_410/{$file_name}";
    $img_path_s3 = UPDATA_DIR_IMG4."wap_card/{$folder_name}/640_430/{$file_name}";
    $img_path_s4 = UPDATA_DIR_IMG4."wap_card/{$folder_name}/640_320/{$file_name}";

    @copy($update_name["tmp_name"],$img_path);

    $size   = @getimagesize($img_path);
    $width  = $size[0];
    $height = $size[1];
    $imgSize = array('width'=>$width,'height'=>$height);
    cut_pic_size($imgSize,640,$height,$img_path,$img_path);
    OSSRun::uploadFile($img_path,"wap_card/{$folder_name}/{$file_name}");

    //640_410
    if($set_type==1){
      $is_success=my_image_resize($img_path,$img_path_s,640,410);
    }else{
      cut_pic_size($imgSize,640,410,$img_path,$img_path_s);
    }
    OSSRun::uploadFile($img_path_s,"wap_card/{$folder_name}/640_410/{$file_name}");

    //640_430
    if($set_type==1){
      $is_success=my_image_resize($img_path,$img_path_s3,640,430);
    }else{
      cut_pic_size($imgSize,640,430,$img_path,$img_path_s3);
    }
    OSSRun::uploadFile($img_path_s3,"wap_card/{$folder_name}/640_430/{$file_name}");

    //640_320
    if($set_type==1){
      $is_success=my_image_resize($img_path,$img_path_s4,640,320);
    }else{
      cut_pic_size($imgSize,640,320,$img_path,$img_path_s4);
    }
    OSSRun::uploadFile($img_path_s4,"wap_card/{$folder_name}/640_320/{$file_name}");

    $file_url=IMG_HOST."/wap_card/{$folder_name}/{$file_name}?t=".rand(0,10000).";".$width.';'.$height;
  }else{
    $file_url='';
  }
  return $file_url;
}

function cut_pic_size($pic_size,$width,$height,$path1,$path2){
    if($pic_size['width']>$width || $pic_size['height']>$height){
            $new_height2 = ($pic_size['height']*$width)/$pic_size['width'];
            $new_width3  = $width;
            if($height<$new_height2){
                $new_width3 = ($width*$height)/$new_height2;
                $new_height2 = $height;
            }else{
                $new_width3 = $width;
            }
            system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $path1 $path2");
        }else{
            @copy($path1,$path2);
      }
}
/**
 * [channelOperate ���Ƶ�����÷���]
 * @param  integer $type       [Ƶ������TYPEֵ]
 * @param  [type]  $is_zujian  [�Ƿ����������]
 * @param  integer $channel_id [Ƶ��ID��]
 * @return [type]              [description]
 */
function channelOperate($type=0, $is_zujian=0, $channel_id=0){

    if ( empty($channel_id) && empty($type)) return false;
    $table = 'ev_user_channel';
    $db      = 'DB_Ev123';
    /*$channelField = array('id'=>'id','type'=>'type','is_del'=>'is_del');
    $channelInfo = Model_Channel::getChannelInfo($channelField,'type='.$type);*/

    $channelInfo = Model_Public::querySql(
        "select id,type,is_del from ev_user_channel where type={$type} && userid=". USER_ID ." order by id desc",
        'get_row'
    );
    if ( $type == '10001' ) {
        $addField   = array('name'=>'֧����',
                            'userid'=>USER_ID,
                            'type'=>77,
                            'is_show'=>0,
                            'wap_is_show'=>0,
                            'is_pc'=>0,
                            'is_show'=>0,
                            'wap_exclsive'=>0,
                            'input_time'=>TIME_STR,
                            );
        $channelInfo  = Model_Public::add($addField,'ev_user_channel');
        return $channelInfo;
    }
    if (!empty($channelInfo)) {
        if (!empty($channelInfo->is_del)) {
            $updateField = array('is_del'=>0,'update_time'=>TIME_STR);
            $updateWhere = 'userid='.USER_ID.' AND id='.$channelInfo->id;
            $update      = Model_Public::update($updateField, $updateWhere, $table);
        }
        return $channelInfo;
    } else {
         $channelName = array(7=>'��̳',36=>'�Ź�',38=>'�����̳�',43=>'�ŵ��ѯ',46=>'����',47=>'��˾�Ŷ�',48=>'���ʱش�',49=>'����Ԥ��',50=>'��������',51=>'��Ƶ�γ�',52=>'΢��Ƭ',53=>'΢����',54=>'΢����',58=>'һԪ��',60=>'΢ͶƱ',61=>'΢����',62=>'΢����',64=>'΢���',67=>'΢ҳ',68=>'΢ǩ��',69=>'���뺯',71=>'��ʱ��',73=>'����ר��',74=>'ɨ��֧��',75=>'ƴ�Ź�',77=>'֧����',79=>'���ܲ�ѯ',80=>'΢����',81=>'��Ա�̳�',82=>'��ֹ�',83=>'ȫ��',84=>'�����ר��',86=>'΢�ʴ�',89=>'�����ƹ�',90=>'��ʱ��ɱ',91=>'���˴���ȯ',92=>'���˺��',93=>'�ƹ��̼�',94=>'ϴ����');
        // �ֻ�ר����Ŀ
        $wapChannel  = Support_Channel::config('onlyWapChType');
        // û�����������Ŀ
        $notSetChannel = array(36,43,47,49,50,60,);
        if (array_key_exists($type, $channelName)){
            $addField    = array(
                'name'        => $channelName[$type],
                'userid'      => USER_ID,
                'type'        => $type,
                'is_show'     => 1,
                'input_time'  => TIME_STR,
                'wap_is_show' => 1,
                'update_time' => TIME_STR
            );
            if (in_array($type, $wapChannel)) {
                $addField['wap_exclsive'] = 1;
                $addField['is_pc']        = 0;
                $addField['is_mobile']    = 1;
            } elseif (in_array($type, array(48,))) {
                $addField['wap_exclsive'] = 0;
                $addField['is_pc']        = 1;
                $addField['is_mobile']    = 0;
            }
            // �����Ӳ���ʾ��Ŀ
            if ( ($is_zujian == 1) && !in_array($type, $notSetChannel) ) {
                $addField['is_show']     = 0;
                $addField['wap_is_show'] = 0;
            }

            // ��֤�Ƿ�Ϊ��ҵӦ��
            $sql = "select id from ev_model_class_power
                   where is_del=0 && new_hangye_tag=1 && channel_type={$type}
                   && state=1 && ev_class_id!=0";
            $hyId = Model_Public::querySql($sql, 'get_var');

            if ($hyId) {
                $addField['hy_id'] = $hyId;
            }
            $addChannel  = Model_Public::add($addField, $table);
            return $addField;
        }
    }
    return false;
}

/**
 * ����city county address ���ȫ��ַ
 * @param  int $city ����
 * @param  int $county ʡ��
 * @param  string $address   �����ַ
 */
function getAllAddress($city,$address){
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
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
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
    $recursive_counter--;
}

/**
 * ������ת��ΪJSON�ַ������������ģ�
 * @param [type] $array Ҫת��������
 */
function JSON($array)
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
function addslashesArrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (is_object($array)) {
                addslashesArrayRecursive($array->$key, $function, $apply_to_keys_also);
            } else {
                addslashesArrayRecursive($array[$key], $function, $apply_to_keys_also);
            }
        } elseif (is_object($value)) {
            if (is_object($array)) {
                addslashesArrayRecursive($array->$key, $function, $apply_to_keys_also);
            } else {
                addslashesArrayRecursive($array[$key], $function, $apply_to_keys_also);
            }
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
                    $array->$new_key = $array->$key;
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
function addslashesJSON($array, $delSpecial = 1)
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

//ƻ��APP����ͼ����
function uploadAppleAppImg($update_name,$ser_id,$tag,$table,$own_width,$own_height,$type=1){
    //ȡ��չ��
    $tmp_name = $update_name["name"];
    $ext_arr  = explode('.',$tmp_name);
    $ext      = end($ext_arr);

    $tmp_ext = strtoupper($ext);
    if(!(int)$own_width || !(int)$own_height){
        return false;
    }

    if($tmp_ext=='PNG' || $tmp_ext=='GIF' ||  $tmp_ext=='JPG'|| $tmp_ext=='JPEG'){
        $folder_name = ceil($ser_id/2000);
        $file_name = $ser_id."_".$tag.".".strtolower($ext);

        if(!is_dir(UPDATA_DIR_IMG4.$table."/".$folder_name.'/'.USER_NAME)){
            @mkdir(UPDATA_DIR_IMG4.$table."/".$folder_name.'/'.USER_NAME,0777,true);
            @chmod(UPDATA_DIR_IMG4.$table."/".$folder_name.'/'.USER_NAME,0777);
        }

        $img_path=UPDATA_DIR_IMG4.$table."/{$folder_name}/".USER_NAME."/{$file_name}";
        @copy($update_name["tmp_name"],$img_path);

        //ѹ��
        $size = @getimagesize($img_path);
        $width = $size[0];
        $height = $size[1];
        if ( $own_width == 120) {
            if($width != $own_width || $height!= $own_height){
                exit('ERROR:size');
            }
        }
        if($type==1)$is_success=my_image_resize($img_path,$img_path,$own_width,$own_height);
        if(!$is_success || $type!=1){
            if($width>$own_width || $height>$own_height){
                $new_height2 = ($height*$own_width)/$width;
                $new_width3  = $own_width;
                if($own_height<$new_height2){
                    $new_width3 = ($own_width*$own_height)/$new_height2;
                    $new_height2 = $own_height;
                }else{
                    $new_width3 = $own_width;
                }
                system("convert -geometry ".$new_width3."x".$new_height2." -quality 100 +profile '*' $img_path $img_path");
            }
        }
        OSSRun::uploadFile($img_path,$table."/{$folder_name}/".USER_NAME."/{$file_name}");

        $file_url=IMG_HOST."/".$table."/{$folder_name}/". USER_NAME ."/{$file_name}?t=".rand(0,10000);
    }else{
        $file_url='';
    }
    return $file_url;
}

/*
*   ������http��URLת������http��URL
*   @param      string   $webUrl (����û��http)
*   @return     string   $webUrl (��HTTP)
*/
function getHttpUrl($webUrl){
    if(strpos($webUrl, 'http://')===false && strpos($webUrl, 'https://')===false && strpos($webUrl, '//')===false){
        if(strpos($webUrl, '/')===0){
            $webUrl = substr($webUrl, 1);
        }
        if(file_get_contents('http://'.$webUrl)){
            $webUrl = 'http://'.$webUrl;
        }elseif(file_get_contents('https://'.$webUrl)){
            $webUrl = 'https://'.$webUrl;
        }
    }
    return $webUrl;
}

/**
 * { function_description }
 * @param      string   $url     (����)
 * @param      integer  $second  (���ʳ�ʱ)
 * @return     string   $content (�ɼ�������)
 */
function postXmlCurl($url, $second = 30)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT,$second);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}


/**
 * { function_description }
 * @param      string   $srcurl     (�������)
 * @param      integer  $second     (��ǰ��������)
 * @return     string    $url       (��������)
 */
function getAbsolutelyUrl($srcurl, $baseurl) {
  $srcinfo = parse_url($srcurl);
  if(isset($srcinfo['scheme']) || strpos($srcurl,'//')===0) {
    if(strpos($srcurl,'//')===0)$srcurl=str_replace('//','http://',$srcurl);
    return $srcurl;
  }
  $baseinfo = parse_url($baseurl);
  if(strpos($baseurl,'//')===0){
    $url = 'http://'.$baseinfo['host'];
  }else{
    $url = $baseinfo['scheme'].'://'.$baseinfo['host'];
  }
  if(substr($srcinfo['path'], 0, 1) == '/') {
    $path = $srcinfo['path'];
  }else{
    $path = dirname($baseinfo['path']).'/'.$srcinfo['path'];
  }
  $rst = array();
  $path_array = explode('/', $path);
  if(!$path_array[0]) {
    $rst[] = '';
  }
  foreach ($path_array AS $key => $dir) {
    if ($dir == '..') {
      if (end($rst) == '..') {
        $rst[] = '..';
      }elseif(!array_pop($rst)) {
        $rst[] = '..';
      }
    }elseif($dir && $dir != '.') {
      $rst[] = $dir;
    }
   }
  if(!end($path_array)) {
    $rst[] = '';
  }
  $url .= implode('/', $rst);
  return str_replace('\\', '/', $url);
}

//����Ա����ݰ�
function fopen_utf8($filename)
{
    $encoding = '';

    $handle = fopen($filename, 'r');

    $bom = fread($handle, 2);

    rewind($handle);

    if($bom === chr(0xff).chr(0xfe)  || $bom === chr(0xfe).chr(0xff))
    {
        // UTF16 Byte Order Mark present
        $encoding = 'UTF-16';
    }
    else
    {
        //read first 1000 bytes
        $file_sample = fread($handle, 1000) + 'e';

        // + e is a workaround for mb_string bug
        rewind($handle);

        $encoding = mb_detect_encoding($file_sample,'UTF-8, UTF-7, ASCII, EUC-JP,SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP');
    }

    if ($encoding)
    {
        stream_filter_append($handle, 'convert.iconv.'.$encoding.'/UTF-8');
    }

    return  ($handle);
}

function deldir($dir)
{
  //��ɾ��Ŀ¼�µ��ļ���
  $dh = opendir($dir);

  while ($file = readdir($dh))
  {
    if($file!="." && $file!="..")
    {
      $fullpath = $dir."/".$file;

      if(!is_dir($fullpath))
      {
          unlink($fullpath);
      }
      else
      {
          deldir($fullpath);
      }
    }
  }

  closedir($dh);

  //ɾ����ǰ�ļ��У�
  return rmdir($dir);
}

//php���غ���Ҫ�ȼ�����Ҫ��һЩ������ʹ�ú����ж�
//�ж��Ƿ���ͨ���ֻ�����
if (!function_exists('isMobile')) {
    function isMobile() {
        // �����HTTP_X_WAP_PROFILE��һ�����ƶ��豸
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //���via��Ϣ����wap��һ�����ƶ��豸,���ַ����̻����θ���Ϣ
        if (isset ($_SERVER['HTTP_VIA'])) {
            //�Ҳ���Ϊflase,����Ϊtrue
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
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
}

/**

 * ����post����

 * @param string $url �����ַ

 * @param array $post_data post��ֵ������

 * @return string

 */

function phpAnalogPost($url, $post_data)
{

    $postdata = http_build_query($post_data);

    $options = array(

        'http' => array(

            'method' => 'POST',

            'header' => 'Content-type:application/x-www-form-urlencoded',

            'content' => $postdata,

            'timeout' => 30 * 60 // ��ʱʱ�䣨��λ:s��

        )

    );

    $context = stream_context_create($options);

    $result = file_get_contents($url, false, $context);

    return $result;
}

/**
 * �ļ�����Ψһ��ʶ
 * 2019/8/3 aliang add
 *
 * @param int    $userid
 * @param int    $tokenId
 *
 * @return string
 */
function getFileRequestFlag($userid = 0, $tokenId = 0)
{
    $DATE_STR = DATE_STR;

    return md5("{$userid}_{$tokenId}_{$DATE_STR}Bzt@#*&^)#@321Bzt");
}
/**
 * ������ҳʹ��
 * 2019/10/31 he add
 *
 * @param int    $page
 * @param int    $cnt
 * @param int    $pagerows
 * @return array
 */
function getLimit($page = 0, $cnt = 0,$pagerows = 20){
    if(!$page || 0 ==(int)$page){
        $page   = 1;
        $offset = 0;
    }else if($page<=0){
        $page   = 1;
        $offset = 0;
    }else{
        $page=$page;
        $offset=($page-1)*$pagerows;
    }

    $page_cnt = ceil($cnt/$pagerows);
    if ($page == $page_cnt) {
        $end = $cnt - $offset;
    } else {
        $end = $pagerows;
    }
    return ['start' => $offset, 'end' => $end];
}