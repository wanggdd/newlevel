<{include file='nine_fenxiao/head.tpl'}>

<div class="promote-set-alert" id="promoteSet">
    <form action="" class="form-element" method="post">
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <div class="select-element dropdown">
                    <!--
                    <div class="input-element suffix" data-type="select" data-multiple="0" data-toggle="dropdown">
                        <input type="text" readonly="readonly" placeholder="1" size="6">
                        <input type="hidden">
                        <i class="evicon evicon-arrow-up-2"></i>
                    </div>
                    -->
                    <select name="promote_lower_type" id="promote_lower_type" class="input-element suffix"  data-type="select">
                        <option value="1" <{if $info.promote_lower_type==1}>selected="selected"<{/if}>> 大于 </option>
                        <option value="2" <{if $info.promote_lower_type==2}>selected="selected"<{/if}>> 大于等于 </option>
                    </select>
                </div>
                <div class="input-element">
                    <input type="text" placeholder="" size="22" name="promote_lower_num" id="promote_lower_num" value="<{$info.promote_lower_num}>">
                </div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">晋升金额：</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="0.00" size="25" name="promote_money" id="promote_money" value="<{$info.promote_money}>">
                </div>
                <div class="item-text">&nbsp;元</div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>取消</span></button>
                <button type="button" data-action="enter" class="btn btn-primary" onclick="set();"><span>确定</span></button>
                <input name="id" type="hidden" value="<{$info.id}>">
                <input name="is_set" type="hidden" value="1">
            </div>
        </div>
    </form>
</div>

<{include file='nine_fenxiao/foot.tpl'}>

<script>
    function set(){
        var id = <{$info.id}>;
        var promote_money = $('#promote_money').val();
        var promote_lower_type = $('#promote_lower_type').val();
        var promote_lower_num = $('#promote_lower_num').val();
        $.post('/NineFenXiao/promote.php',{promote_money:promote_money,promote_lower_type:promote_lower_type,promote_lower_num:promote_lower_num,id:id,is_set:1},function(data,status){
            if(data.status=='success'){
                alert('设置成功!');
                winP.evPopup[winName].popupClose();
                parent.location.reload();
            }else{
                alert(data.msg);
            }
        });
    }
    $(function () {
        var promoteSet = $('#promoteSet');
        promoteSet.on({
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