<{include file='nine_fenxiao/head.tpl'}>

<div class="look-sublevel-alert" id="memberSet">
    <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">
        <table class="table table-no-outer-border table-spacing-lg">
            <thead>
            <tr class="active text-center">
                <th>用户信息</th>
                <th>会员等级</th>
                <th>会员状态</th>
                <th>注册时间</th>
            </tr>
            </thead>
            <tbody>
            <{if $memberlist}>
            <{foreach key=key item=item from=$memberlist}>
            <tr class="text-center vertical-middle">
                <td>
                    <div class="member-info">
                        <p>用户名:<{$item.user_name}></p>
                        <p>昵称:<{$item.nick_name}></p>
                        <p>手机:<{$item.mobile}></p>
                    </div>
                </td>
                <td>
                    <{$item.grade_val}>
                </td>
                <td>
                    <{$item.status_title}>
                </td>
                <td>
                    <{$item.input_time}>
                </td>
            </tr>
            <{/foreach}>
            <{/if}>
            </tbody>
        </table>
    </div>
    <div class="submit-btn-area">
        <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>取消</span></button>
    </div>
</div>
<{include file='nine_fenxiao/foot.tpl'}>

<script>

    $(function () {
        var memberSet = $('#memberSet');
        memberSet.on({
            click: function (ev) {
                var $this = $(this),
                    action = $this.data('action');
                switch (action) {
                    case 'cancel':
                        // 取消按钮事件
                        popup.popupClose();
                        break;
                }
            }
        }, '[data-action]');
    });
</script>
