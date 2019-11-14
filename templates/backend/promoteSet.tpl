<{include file='./head.tpl'}>

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
                    <select name="promote_lower_type" class="input-element suffix"  data-type="select">
                        <option value="0" <{if $info.promote_lower_type==0}>checked<{/if}>> ���� </option>
                        <option value="1" <{if $info.promote_lower_type==1}>checked<{/if}>> ���ڵ��� </option>
                        <option value="2" <{if $info.promote_lower_type==2}>checked<{/if}>> ���� </option>
                    </select>
                </div>
                <div class="input-element">
                    <input type="text" placeholder="" size="22" name="promote_lower_num" value="<{$info.promote_lower_num}>">
                </div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">������</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="0.00" size="25" name="promote_money" value="<{$info.promote_money}>">
                </div>
                <div class="item-text">&nbsp;Ԫ</div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>ȡ��</span></button>
                <button type="submit" data-action="enter" class="btn btn-primary"><span>ȷ��</span></button>
                <input name="id" type="hidden" value="<{$info.id}>">
                <input name="is_set" type="hidden" value="1">
            </div>
        </div>
    </form>
</div>

<{include file='./foot.tpl'}>

<script>
    $(function () {
        var promoteSet = $('#promoteSet');
        promoteSet.on({
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