<{include file='nine_fenxiao/head.tpl'}>
<div class="paid-item-wrap">
    <div class="panel paid-item-head">
        <div class="panel-body">
            <dl class="pull-left">
                <dt class="h4">
                <hr class="hr hr-y hr-5 primary no-spacing">
                <strong>������Ŀ</strong></dt>
                <dd class="text-gray">���ü�������Ҫ�Ż�����վ����</dd>
            </dl>
        </div>
    </div>
    <div class="tab-panel style-2 pay-order-body">
        <div class="tab-panel-body">
            <div class="tab-panel-item active no-spacing">
                <div class="tab-panel style-1">
                    <{include file='nine_fenxiao/tab.tpl'}>

                    <div class="tab-panel-body">
                        <div class="tab-panel-item active member-grade-panel-item" id="memberGrade">
                            <form action="" class="form-element basic-function-form" method="post">
                                <div class="filter-form">
                                    <div class="form-element">
                                        <button type="button" data-action="addGrade" class="btn btn-primary">
                                            <i class="evicon evicon-plus-1"></i>
                                            <span>���</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">
                                    <table class="table table-no-outer-border table-spacing-lg">
                                        <colgroup>
                                            <col width="auto">
                                            <col width="auto">
                                            <col width="200">
                                            <col width="150">
                                            <col width="200">
                                        </colgroup>
                                        <thead>
                                        <tr class="active text-center">
                                            <th>�ȼ�</th>
                                            <th>�ȼ�����</th>
                                            <th>��ʼ��Ա</th>
                                            <th>�������</th>
                                            <th>����</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <{if !empty($info)}>
                                        <{foreach key=key item=item from=$info}>
                                        <tr class="text-center vertical-middle">
                                            <td>
                                                <div class="input-element">
                                                    <input type="text" size="5" name="grade[]<{$key}>" value="<{$item.grade}>">
                                                    <input type="hidden" size="5" name="ids[]<{$key}>" value="<{$item.id}>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-element">
                                                    <input type="text" size="15" name="title[]<{$key}>" value="<{$item.title}>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="member-set-area">
                                                    <{if $item.name}>
                                                        <span><{$item.name}></span>
                                                        <a class="text-primary" href="###" data-action="mechange" data-id="<{$item.id}>">����</a>
                                                    <{else}>
                                                        <div class="member-set-area">
                                                            <a class="text-primary" href="###" data-action="meset" data-id="<{$item.id}>">����</a>
                                                        </div>
                                                    <{/if}>

                                                </div>
                                            </td>
                                            <td>
                                                <{$item.promote_money}>
                                            </td>
                                            <td>
                                                <div class="href-area">
                                                    <a href="###" data-action="upset" data-id="<{$item.id}>">��������</a>
                                                    <a href="###" data-action="del">ɾ��</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <{/foreach}>
                                        <{else}>
                                        <tr class="text-center vertical-middle">
                                            <td>
                                                <div class="input-element">
                                                    <input type="text" size="5" name="grade[]">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-element">
                                                    <input type="text" size="15" name="title[]">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="member-set-area">
                                                    <a class="text-primary" href="###" data-action="meset" data-id="<{$item.id}>">����</a>
                                                </div>
                                            </td>
                                            <td>
                                                1440
                                            </td>
                                            <td>
                                                <div class="href-area">
                                                    <a href="###" data-action="upset">��������</a>
                                                    <a href="###" data-action="del">ɾ��</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <{/if}>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-item">
                                    <label class="item-label"></label>
                                    <div class="item-con">
                                        <input type="submit" class="btn btn-primary" value="�ύ">
                                    </div>
                                </div>
                            </form>
                            <!--
                            <div class="pagination text-center">
                                    <span class="page-limits">
                                        <select>
                                            <option value="10��/ҳ">10��/ҳ</option>
                                            <option value="20��/ҳ">20��/ҳ</option>
                                            <option value="30��/ҳ">30��/ҳ</option>
                                            <option value="40��/ҳ">40��/ҳ</option>
                                        </select>
                                    </span>
                                <span class="page-number">
                                        <a class="disabled" href="###" data-page="1">��һҳ</a>
                                        <a href="###" data-page="1">1</a>
                                        <b>2</b>
                                        <a href="###" data-page="3">3</a>
                                        <a href="###" data-page="4">4</a>
                                        <a href="###" data-page="5">5</a>
                                        <a href="###" data-page="3">��һҳ</a>
                                    </span>
                                <span class="page-skip">����<input type="text" size="2" maxlength="5">ҳ<button
                                            class="btn btn-default">ȷ��</button></span>
                                <span class="page-sum">��<em>5</em>ҳ</span>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<{include file='nine_fenxiao/foot.tpl'}>
<script>
    $(function () {
        var memberGrade = $('#memberGrade');
        memberGrade.on({
            click: function (ev) {
                var $this = $(this),
                    action = $this.data('action'),
                    id = $this.data('id');

                switch (action) {
                    case 'upset':
                        publicFun.winIframe('/NineFenXiao/promote.php?id='+id, 490, 320, '��������');
                        break;
                    case 'meset':
                    case 'mechange':
                        publicFun.winIframe('/NineFenXiao/memberSet.php?id='+id, 800, 800, '��ʼ�û��ȼ�');
                        break;
                    case 'del':
                        publicFun.confirm('ȷ��Ҫɾ����',function(){
                            publicFun.point('ɾ���ɹ�', 1);
                        });
                        break;
                    case 'addGrade':
                        publicFun.winIframe('/NineFenXiao/addgrade.php', 450, 350, '��ӵȼ�');
                        break
                }
            }

        }, '[data-action]');
    });
</script>