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
                        <div class="tab-panel-item active member-list-panel-item" id="memberList">
                            <div class="filter-form">
                                <div class="form-element">
                                    <div class="form-item-group">
                                        <div class="form-item">
                                            <label class="item-label">ע��ʱ�䣺</label>
                                            <div class="item-con">
                                                <div class="input-element-btn">
                                                    <div class="input-element-joint" data-toggle="datePicker"
                                                         data-date-type="YYYY-MM-DD" data-date-max="2019-01-02"
                                                         data-date-min="2018-01-09" data-date-isrange="1">
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="��ʼʱ��" value="2018-01-09">
                                                            <i class="evicon evicon-date-1"></i>
                                                        </div>
                                                        <i class="joint-line">-</i>
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="����ʱ��" value="2019-01-09">
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
                                                    <input type="text" placeholder="��������" size="30">
                                                </div>
                                                <button type="button" class="btn btn-primary"><span>����</span></button>
                                                <button type="button" class="btn btn-outline-danger"><span>�鿴ȫ��</span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <{foreach from=$memberlist key=num item="member"}>
                                    <tr class="text-center vertical-middle">
                                        <td>
                                            <label class="checkbox-element sm">
                                                <input type="checkbox" name="checkbox_item">
                                                <i class="dot evicon evicon-right-2"></i>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-left">
                                                            <span><img class="media-object"
                                                                       src="images/member.jpg"></span>
                                                </div>
                                                <div class="media-body">
                                                    <p>�û���:<{$member.user_id}></p>
                                                    <p>�ǳ�:������</p>
                                                    <p>�ֻ�:13111111111</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>18999999</td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <div class="input-element suffix" data-type="select"
                                                     data-multiple="0" data-toggle="dropdown">
                                                    <input type="text" readonly="readonly" placeholder="��" size="5">
                                                    <input type="hidden" value="0">
                                                    <i class="evicon evicon-arrow-up-2"></i>
                                                </div>
                                                <div class="option-list dropdown-menu">
                                                    <dl>

                                                        <dd title="��" data-value="0">
                                                            <a href="###"><span>��</span></a>
                                                        </dd>

                                                    </dl>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <div class="input-element suffix" data-type="select"
                                                     data-multiple="0" data-toggle="dropdown">
                                                    <input type="text" readonly="readonly" placeholder="�ȼ�" size="8">
                                                    <input type="hidden" value="0">
                                                    <i class="evicon evicon-arrow-up-2"></i>
                                                </div>
                                                <div class="option-list dropdown-menu">
                                                    <dl>
                                                        <dd title="�ȼ�ѡ��" data-value="0">
                                                            <a href="###"><span>�ȼ�ѡ��</span></a>
                                                        </dd>
                                                        <dd title="һ��" data-value="1">
                                                            <a href="###"><span>һ��</span></a>
                                                        </dd>
                                                        <dd title="����" data-value="2">
                                                            <a href="###"><span>����</span></a>
                                                        </dd>
                                                        <dd title="����" data-value="3">
                                                            <a href="###"><span>����</span></a>
                                                        </dd>
                                                        <dd title="�ļ�" data-value="4">
                                                            <a href="###"><span>�ļ�</span></a>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="sub-text" data-action="lookSub"><{$member.lower_number}></span></td>
                                        <td><{$member.create_time|date_format:'%Y-%m-%d'}></td>
                                        <td>
                                            <div class="href-area">
                                                <a data-action="lookQRCode" href="###"></a>
                                                <a href="getMoney.html">�տ��¼</a>
                                                <a href="payMoney.html">����¼</a>
                                                <a href="###">�����ά��</a>
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
                                            <button type="button" class="btn btn-sm btn-primary" data-action="changeAllGrade"><span>�����޸ĵȼ�</span></button>
                                            <button type="button" class="btn btn-sm btn-primary"><span>��������</span></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="pagination text-center">
                               <{$page_str}>
                                <span class="page-sum">��<em><{$totalpage}></em>ҳ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<{include file='nine_fenxiao/foot.tpl'}>