<{include file='nine_fenxiao/head.tpl'}>

<div class="look-voucher-alert" id="lookVoucher" data-toggle="customScroll" data-axis="y" data-position="outside" data-theme="minimal-dark" data-scrollbtn="1">
    <dl>
        <dt>打款凭证：</dt>
        <dd>
            <div class="voucher-img"><img src="<{$record.payment_voucher}>" alt="支付凭证"></div>
        </dd>
        <dt>打款备注：</dt>
        <dd><p><{$record.payment_note}></p></dd>
        <{if $record.status == 3}>
        <dt>拒绝原因：</dt>
        <dd><p><{$record.refuse_reason}></p></dd>
        <{/if}>
        <dd class="enter-btn-area"><button data-action="enter" class="btn btn-primary">确定</button></dd>
    </dl>
</div>
<{include file='nine_fenxiao/foot.tpl'}>

<script>
    $(function () {
        var lookVoucher = $('#lookVoucher');
        lookVoucher.on({
            click: function (ev) {
                var $this = $(this),
                    action = $this.data('action');
                switch (action) {
                    case 'enter':
                        popup.popupClose();
                        break;
                }
            }
        }, '[data-action]');
    });
</script>
