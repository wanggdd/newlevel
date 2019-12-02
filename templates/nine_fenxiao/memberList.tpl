<{include file='nine_fenxiao/head.tpl'}>
<style>
    .current{background-color:#0090FF}
</style>
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
                        <div class="tab-panel-item active member-list-panel-item" id="memberList">
                            <div class="filter-form">
                                <div class="form-element">
                                    <div class="form-item-group">
                                        <form method="get" action="">
                                        <div class="form-item">
                                            <label class="item-label">ע��ʱ�䣺</label>
                                            <div class="item-con">
                                                <div class="input-element-btn">
                                                    <div class="input-element-joint" data-toggle="datePicker"
                                                         data-date-type="YYYY-MM-DD" data-date-max="2100-01-02"
                                                         data-date-min="2018-01-09" data-date-isrange="1">
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="��ʼʱ��" value="<{$start_date}>" name="start_date">
                                                            <i class="evicon evicon-date-1"></i>
                                                        </div>
                                                        <i class="joint-line">-</i>
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="����ʱ��" value="<{$end_date}>" name="end_date">
                                                            <i class="evicon evicon-date-1"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-item">
                                            <label class="item-label">���ң�</label>
                                            <div class="item-con">
                                                <div class="input-element">
                                                    <input type="text" placeholder="�û���|�ǳ�|�ֻ���" name="search_mix" size="30" value="<{$search_mix}>">
                                                </div>
                                                <button type="submit" class="btn btn-primary"><span>����</span></button>
                                                <button type="submit" name="all" value="1" class="btn btn-outline-danger"><span>�鿴ȫ��</span></button>
                                                <input type="hidden" name="type" value="search">
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <form method="get" action="" id="upform">
                            <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">
                                <table class="table table-no-outer-border table-spacing-lg">
                                    <!-- <colgroup>
                                        <col width="80">
                                        <col width="250">
                                        <col width="80">
                                        <col width="150">
                                        <col width="200">
                                        <col width="100">
                                        <col width="150">
                                        <col width="auto">
                                    </colgroup> -->
                                    <thead>
                                    <tr class="active text-center">
                                        <th width="80">ѡ��</th>
                                        <th width="300">��Ա��Ϣ</th>
                                        <th>��Ա���</th>
                                        <th>״̬</th>
                                        <th>�ȼ�</th>
                                        <th>�¼�����</th>
                                        <th>ע��ʱ��</th>
                                        <th>����</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <{if $memberlist}>
                                    <{foreach from=$memberlist key=num item="member"}>
                                    <tr class="text-center vertical-middle">
                                        <td>
                                            <label class="checkbox-element sm">
                                                <input type="checkbox" name="checkbox_item" value="<{$member.user_user_id}>">
                                                <i class="dot evicon evicon-right-2"></i>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-left">
                                                            <span><img class="media-object" src="<{$member.pic}>"></span>
                                                </div>
                                                <div class="media-body">
                                                    <p>�û���:<{$member.user_name}></p>
                                                    <p>�ǳ�:<{$member.nick_name}></p>
                                                    <p>�ֻ�:<{$member.mobile}></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><{$member.id}></td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <div class="input-element suffix" data-type="select"
                                                     data-multiple="0" data-toggle="dropdown">
                                                    <select name="status<{$member.user_user_id}>">
                                                        <option value="0" <{if $member.status==0}>selected="selected"<{/if}>>��״̬</option>
                                                        <option value="1" <{if $member.status==1}>selected="selected"<{/if}>>δ����</option>
                                                        <option value="2" <{if $member.status==2}>selected="selected"<{/if}>>�Ѽ���</option>
                                                        <option value="3" <{if $member.status==3}>selected="selected"<{/if}>>��</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <{if $grade_list}>
                                                <select name="grade<{$member.user_user_id}>">
                                                    <option value="0">�ȼ�ѡ��</option>
                                                    <{foreach key=k item=grade from=$grade_list}>
                                                    <option value="<{$grade.id}>" <{if $grade.id == $member.grade}>selected="selected"<{/if}>><{$grade.title}></option>
                                                    <{/foreach}>
                                                </select>
                                                <{/if}>
                                            </div>
                                        </td>
                                        <td><span class="sub-text" data-action="lookSub" data-id="<{$member.user_user_id}>"><{$member.lower_num}></span></td>
                                        <td><{$member.input_time}></td>
                                        <td>
                                            <div class="href-area">
                                                <a data-action="lookQRCode" data-id="<{$member.user_user_id}>" href="###">�տ���</a>
                                                <a href="/ninefenxiao/enterrecord.php?user_user_id=<{$member.user_user_id}>">�տ��¼</a>
                                                <a href="/ninefenxiao/outrecord.php?user_user_id=<{$member.user_user_id}>">����¼</a>
                                                <a data-action="share" data-id="<{$member.user_user_id}>" href="###">�����ά��</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <{/foreach}>

                                    </tbody>
                                    <tfoot>
                                    <tr class="striped">
                                        <td colspan="8">
                                            <label class="checkbox-element sm">
                                                <input type="checkbox" name="checkbox_all">
                                                <i class="dot evicon evicon-right-2"></i>
                                                <span class="checkbox-label">ȫѡ</span>
                                            </label>
                                            <button type="button" class="btn btn-sm btn-primary" data-action="changeAllGrade" onclick="set()"><span>�����޸ĵȼ�</span></button>
                                            <input type="hidden" name="start_date" value="<{$start_date}>">
                                            <input type="hidden" name="end_date" value="<{$end_date}>">
                                            <input type="hidden" name="search_mix" value="<{$search_mix}>">
                                            <input type="hidden" name="type" value="upgrade">
                                            <input type="hidden" name="ids" id="ids" value="">
                                            <button type="button" class="btn btn-sm btn-primary"><a href="/NineFenXiao/export.php?start_date=<{$start_date}>&end_date=<{$end_date}>&search_mix=<{$search_mix}>"><span style="color:#ffffff">��������</span></a> </button>

                                        </td>
                                    </tr>
                                    </tfoot>
                                    <{/if}>
                                </table>
                            </div>
                            </form>
                            <{if $memberlist}>
                            <div class="pagination text-center">
                               <{$page_str}>
                                <span class="page-sum">��<em><{$totalpage}></em>ҳ</span>
                            </div>
                            <{/if}>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<{include file='nine_fenxiao/foot.tpl'}>

<script>
    function set(){
        var opt = "";
        $("input[name='checkbox_item']").each(function () {
            if ($(this).is(":checked")) {
                var check_val = $(this).val();
                if(opt == '')
                    opt += check_val;
                else
                    opt += ',' + check_val;
            } else {
                opt += "";
            }
        });
        if(opt == ''){
            alert('����ѡ����Ҫ�޸ĵĻ�Ա');
            return false;
        }
        $('#ids').val(opt);
        publicFun.confirm('ȷ��Ҫ�޸���',function(){
            $('#upform').submit();
            //publicFun.point('�޸ĳɹ�', 1);
        });
    }

    $(function () {
        var memberList = $('#memberList');
        memberList.on({
            click: function (ev) {
                var $this = $(this),
                    action = $this.data('action'),
                    id = $this.data('id');
                switch (action) {
                    case 'lookQRCode':
                        publicFun.winIframe('/ninefenxiao/paymentcode.php?user_user_id='+id, 410, 320, '�����տ���');
                        break;
                    case 'lookSub':
                        publicFun.winIframe('/ninefenxiao/looksub.php?user_user_id='+id, 520, 630, '�¼���Ϣ');
                        break;
                    /*case 'changeAllGrade':
                        var opt = "";
                        $("input[name='checkbox_item']").each(function () {
                            if ($(this).is(":checked")) {
                                var check_val = $(this).val();
                                if(opt == '')
                                    opt += check_val;
                                else
                                    opt += ',' + check_val;
                            } else {
                                opt += "";
                            }
                        });
                        if(opt == ''){
                            alert('����ѡ����Ҫ�޸ĵȼ��Ļ�Ա');
                            return false;
                        }

                        publicFun.winIframe('/NineFenXiao/batchUpgrade.php?user_ids='+opt, 410, 290, '�����޸ĵȼ�');
                        break;*/
                    case 'share':
                        publicFun.winIframe('/NineFenXiao/share.php?user_user_id='+id, 410, 290, '�����ά��');
                        break;
                }
            }
        }, '[data-action]');
    });
</script>