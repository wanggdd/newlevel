<{include file='nine_fenxiao/head.tpl'}>

<div class="add-grade-alert" id="addGrade">
    <form action="" class="form-element">
        <div class="form-item">
            <label class="item-label">�ȼ���</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="" size="22" name="grade" id="grade">
                </div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">�ȼ����ƣ�</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="" size="22" name="title" id="title">
                </div>
            </div>
        </div>
        <!--
        <div class="form-item">
            <label class="item-label">������Ա��</label>
            <div class="item-con">
                <a href="###" class="item-text text-primary">����</a>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">������Ա��</label>
            <div class="item-con">
                <a href="###" class="item-text text-primary">����</a>
            </div>
        </div>
        -->
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>ȡ��</span></button>
                <button type="button" data-action="enter" class="btn btn-primary" onclick="set();"><span>ȷ��</span></button>
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
            publicFun.point('������Ϸ�������', 0);
            $('#grade').select();
        }
        $.post('/NineFenXiao/addgrade.php',{grade:grade,title:title},function(data,status){
            if(data.status=='success'){
                alert('���óɹ�!');
                winP.evPopup[winName].popupClose();
                parent.location.reload();

            }else{
                alert('����ʧ�ܣ����������!');
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
                        // ȡ����ť�¼�
                        winP.evPopup[winName].popupClose();
                        break;
                    /*case 'enter':
                        // ȷ����ť�¼�
                        winP.evPopup[winName].popupClose();
                        break;*/
                }
            }
        }, '[data-action]');
    });
</script>
</body>

</html>
