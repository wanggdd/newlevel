<{include file='nine_fenxiao/head.tpl'}>

<div class="look-sublevel-alert" id="memberSet">
    <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">
        <table class="table table-no-outer-border table-spacing-lg">
            <thead>
            <tr class="active text-center">
                <th>�û���Ϣ</th>
                <th>��Ա�ȼ�</th>
                <th>��Ա״̬</th>
                <th>ע��ʱ��</th>
            </tr>
            </thead>
            <tbody>
            <{if $memberlist}>
            <{foreach key=key item=item from=$memberlist}>
            <tr class="text-center vertical-middle">
                <td>
                    <div class="member-info">
                        <p>�û���:<{$item.user_name}></p>
                        <p>�ǳ�:<{$item.nick_name}></p>
                        <p>�ֻ�:<{$item.mobile}></p>
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
        <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>ȡ��</span></button>
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
                        // ȡ����ť�¼�
                        popup.popupClose();
                        break;
                }
            }
        }, '[data-action]');
    });
</script>
