<{include file='nine_fenxiao/head.tpl'}>

<div class="add-grade-alert" id="addGrade">
    <form action="" class="form-element">
        <div class="form-item">
            <label class="item-label">等级：</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="" size="22" name="grade" id="grade">
                </div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">等级名称：</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="" size="22" name="title" id="title">
                </div>
            </div>
        </div>
        <!--
        <div class="form-item">
            <label class="item-label">初级会员：</label>
            <div class="item-con">
                <a href="###" class="item-text text-primary">设置</a>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">晋升会员：</label>
            <div class="item-con">
                <a href="###" class="item-text text-primary">设置</a>
            </div>
        </div>
        -->
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>取消</span></button>
                <button type="button" data-action="enter" class="btn btn-primary" onclick="set();"><span>确定</span></button>
            </div>
        </div>
    </form>
</div>

<{include file='nine_fenxiao/foot.tpl'}>

<script>
    function set(){
        var grade = $('#grade').val();
        var title = $('#title').val();
        if(isNaN(grade)){
            publicFun.point('请输入合法的数字', 0);
            $('#grade').select();
        }
        $.post('/NineFenXiao/addgrade.php',{grade:grade,title:title},function(data,status){
            if(data.status=='success'){
                alert('设置成功!');
                winP.evPopup[winName].popupClose();
                parent.location.reload();

            }else{
                alert('设置失败，请检查添加项!');
            }

        });
    }

    $(function () {
        var addGrade = $('#addGrade');
        addGrade.on({
            click: function (ev) {
                var $this = $(this),
                    action = $this.data('action');
                switch (action) {
                    case 'cancel':
                        // 取消按钮事件
                        winP.evPopup[winName].popupClose();
                        break;
                    /*case 'enter':
                        // 确定按钮事件
                        winP.evPopup[winName].popupClose();
                        break;*/
                }
            }
        }, '[data-action]');
    });
</script>
</body>

</html>
