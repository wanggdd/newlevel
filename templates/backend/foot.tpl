<script src="http://test.evyun.cn/test_EvyunUi/js/evPublicInit-min.js" charset="gbk"></script>
<script src="public/js/paidItems.min.js"></script>

<script>
    $(function () {
        var memberList = $('#memberList');
        memberList.on({
            click: function (ev) {
                var $this = $(this),
                    action = $this.data('action');
                switch (action) {
                    case 'lookVoucher':
                        publicFun.winIframe('memberList/lookVoucher.html', 590, '600', '²ú¿´Æ¾Ö¤');
                        break;
                }
            }
        }, '[data-action]');
    });
</script>
</body>

</html>