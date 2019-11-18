function uploadPhoto() {
    $("#photoFile").click();
}

/**
 * 上传图片
 */
function upload() {
    if ($("#photoFile").val() == '') {
        return;
    }
    var formData = new FormData();
    formData.append('pic', document.getElementById('photoFile').files[0]);
    $.ajax({
        url:"http://m.evyun.cn:12701/Frontend/Web/UUUploadPic?username=wolaiceshi&zz_userid=248478&zz_shellCode=%242y%2410%24o4IkxfHsJegI8aazuMrvOOme4m4xsGmSsBV9a32p1Trlk6aCXoUO6&zz_shellTime=5dcce893b0326&name=pic&type=1",
        type:"post",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.code == 200) {
                var qrcode_path = data.data.path;
                var user_id = $("#userid").val();
                $.post("/dom/ninefenxiao/qrsave.php",{qrcode:data.data.path,zz_userid:user_id},function(data,status){
                    if(data.status=='success'){
                        $(".c-rqcode__img").attr("src", qrcode_path);
                        $("#productImg").val(qrcode_path);
                    }else{
                        alert('上传错误,请重试');
                    }
                });


            } else {
                alert(data.msg);
            }
        },
        error:function(data) {
            alert("上传失败")
        }
    });
}

function upload_qrcode() {
    if ($("#upload_qrcode").val() == '') {
        return;
    }
    var formData = new FormData();
    formData.append('pic', document.getElementById('upload_qrcode').files[0]);
    $.ajax({
        url:"http://m.evyun.cn:12701/Frontend/Web/UUUploadPic?username=wolaiceshi&zz_userid=248478&zz_shellCode=%242y%2410%24o4IkxfHsJegI8aazuMrvOOme4m4xsGmSsBV9a32p1Trlk6aCXoUO6&zz_shellTime=5dcce893b0326&name=pic&type=1",
        type:"post",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.code == 200) {
                var qrcode_path = data.data.path;
                var user_id = $("#userid").val();
                $.post("/dom/ninefenxiao/qrsave.php",{qrcode:data.data.path,zz_userid:user_id},function(data,status){
                    if(data.status=='success'){
                        window.location.reload();
                    }else{
                        alert('上传错误,请重试');
                    }
                });


            } else {
                alert(data.msg);
            }
        },
        error:function(data) {
            alert("上传失败")
        }
    });
}

/**
 * 上传打款凭证
 */
function upload_voucher() {
    if ($("#photoFile").val() == '') {
        return;
    }
    var formData = new FormData();
    formData.append('pic', document.getElementById('photoFile').files[0]);
    $.ajax({
        url:"http://m.evyun.cn:12701/Frontend/Web/UUUploadPic?username=wolaiceshi&zz_userid=248478&zz_shellCode=%242y%2410%24o4IkxfHsJegI8aazuMrvOOme4m4xsGmSsBV9a32p1Trlk6aCXoUO6&zz_shellTime=5dcce893b0326&name=pic&type=1",
        type:"post",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            if (data.code == 200) {
                var qrcode_path = data.data.path;
                var user_user_id = $('#user_user_id').val();
                $.post("/ninefenxiao/paymentcode.php",{qrcode:data.data.path,user_user_id:user_user_id},function(data,status){
                    if(data.status=='success'){
                        $(".c-rqcode__img").attr("src", qrcode_path);
                        $("#productImg").val(qrcode_path);
                        alert('设置成功');
                    }else{
                        alert('上传错误,请重试');
                    }
                });


            } else {
                alert(data.msg);
            }
        },
        error:function(data) {
            alert("上传失败")
        }
    });
}