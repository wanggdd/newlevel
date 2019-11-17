<{include file='./head.tpl'}>

<div class="member-set-alert" id="memberSet">
    <div class="filter-form">
        <div class="form-element">
            <div class="form-item-group">
                <div class="form-item">
                    <label class="item-label">�û��ǳƣ�</label>
                    <div class="item-con">
                        <div class="input-element-select">
                            <div class="select-element dropdown">
                                <!--
                                <div class="input-element suffix" data-type="select"
                                     data-multiple="0" data-toggle="dropdown">
                                    <input type="text" readonly="readonly" placeholder="�ȼ�" size="8">
                                    <input type="hidden" value="0">
                                    <i class="evicon evicon-arrow-up-2"></i>
                                </div>
                                -->
                                <select name="grade" class="input-element suffix"  data-type="select">
                                    <option value="0">ѡ��ȼ�</option>
                                    <{foreach key=key item=item from=$gradeList}>
                                        <option value="<{$grade}>"> <{$item.grade}>   </option>
                                    <{/foreach}>
                                </select>
                            </div>
                            <div class="input-element-joint" data-toggle="datePicker" data-date-type="YYYY-MM-DD"
                                 data-date-max="2019-01-02" data-date-min="2018-01-09" data-date-isrange="1">
                                <div class="input-element prefix">
                                    <input size="10" type="text" readonly="readonly" placeholder="��ʼʱ��"
                                           value="2018-01-09" name="start_time">
                                    <i class="evicon evicon-date-1"></i>
                                </div>
                                <i class="joint-line">-</i>
                                <div class="input-element prefix">
                                    <input size="10" type="text" readonly="readonly" placeholder="����ʱ��"
                                           value="2019-01-09" name="end_time">
                                    <i class="evicon evicon-date-1"></i>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary"><span>����</span></button>
                        <button type="button" class="btn btn-outline-danger"><span>�鿴ȫ��</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="" class="form-element" method="post">
    <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">

        <table class="table table-no-outer-border table-spacing-lg">
            <thead>
            <tr class="active text-center">
                <th>�û���</th>
                <th>�ȼ�</th>
                <th>�ǳ�</th>
                <th>�ֻ���</th>
                <th>ѡ��</th>
            </tr>
            </thead>
            <tbody>
            <{if !empty($user_info)}>
                <{foreach key=key item=item from=$user_info}>
                    <tr class="text-center vertical-middle">
                        <td>
                            <{$item.user_name}>
                        </td>
                        <td>
                            <{$item.user_grade}>
                        </td>
                        <td>
                            <{$item.nick_name}>
                        </td>
                        <td>
                            <{$item.mobile}>
                        </td>
                        <td>
                            <label class="radio-element">
                                <input type="radio" name="user_user_id" value="<{$item.id}>" <{if $info.user_user_id==$item.id}>checked<{/if}>>
                                <i class="dot"></i>
                            </label>
                        </td>
                    </tr>
                    <tr class="text-center vertical-middle">
                        <td>
                            <{$item.user_name}>������
                        </td>
                        <td>
                            <{$item.user_grade}>
                        </td>
                        <td>
                            <{$item.nick_name}>
                        </td>
                        <td>
                            <{$item.mobile}>
                        </td>
                        <td>
                            <label class="radio-element">
                                <input type="radio" name="user_user_id" value="8" <{if $info.user_user_id==$item.id}>checked<{/if}>>
                                <i class="dot"></i>
                            </label>
                        </td>
                    </tr>
                <{/foreach}>
            <{/if}>
            </tbody>
        </table>
    </div>
    <div class="pagination text-center">
            <span class="page-number">
                <a class="disabled" href="###" data-page="1">��һҳ</a>
                <a href="###" data-page="1">1</a>
                <b>2</b>
                <a href="###" data-page="3">3</a>
                <a href="###" data-page="4">4</a>
                <a href="###" data-page="5">5</a>
                <a href="###" data-page="3">��һҳ</a>
            </span>
    </div>
    <div class="submit-btn-area">
        <button type="button" data-action="cancel" class="btn btn-ouprimarytline-danger"><span>ȡ��</span></button>
        <button type="submit" data-action="enter" class="btn btn-"><span>ȷ��</span></button>
        <input type="hidden" name="id" value="<{$info.id}>">
        <input type="hidden" name="type" value="up">
    </div>
    </form>

</div>

<{include file='./foot.tpl'}>

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
                    /*case 'enter':
                        // ȷ����ť�¼�
                        popup.popupClose();
                        break;
                    */
                }
            }
        }, '[data-action]');
    });
</script>