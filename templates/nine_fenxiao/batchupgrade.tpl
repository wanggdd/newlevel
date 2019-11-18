<{include file='nine_fenxiao/head.tpl'}>

<div class="change-grade-alert">
    <form action="" class="form-element">
        <div class="form-item">
            <label class="item-label">修改为：</label>
            <div class="item-con">
                <div class="select-element dropdown">
                    <select name="upgrade" class="input-element suffix" id="upgrade">
                        <option value="0">选择等级</option>
                        <{if $grade_list}>
                            <{foreach key=key item=item from=$grade_list}>
                                <option value="<{$item.id}>"><{$item.grade}></option>
                            <{/foreach}>
                        <{/if}>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <button type="button" class="btn btn-primary submit-btn" onclick="set()"><span>提交</span></button>
            </div>
        </div>
    </form>
</div>

<{include file='nine_fenxiao/foot.tpl'}>

<script>
    function set(){
        var user_ids = "<{$user_ids}>";
        var upgrade = $('#upgrade').val();
        if(upgrade == '0'){
            alert('请选择等级');
            return false;
        }
        $.post('/NineFenXiao/batchUpgrade.php',{user_ids:user_ids,type:1,upgrade:upgrade},function(data,status){
            if(data.status=='success'){
                alert('设置成功!');
                popup.popupClose();
                parent.location.reload();
            }else{
                alert('设置失败');
            }
        });
    }
</script>


