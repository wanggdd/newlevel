<{include file='./head.tpl'}>
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
                    <{include file='./tab.tpl'}>

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
                                                    <p>�û���:pfl001</p>
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
                                                        <dd title="����" data-value="1">
                                                            <a href="###"><span>����</span></a>
                                                        </dd>
                                                        <dd title="�ر�" data-value="2">
                                                            <a href="###"><span>�ر�</span></a>
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
                                        <td><span class="sub-text" data-action="lookSub">128</span></td>
                                        <td>2019-11-11</td>
                                        <td>
                                            <div class="href-area">
                                                <a data-action="lookQRCode" href="###">�鿴��ά��</a>
                                                <a href="getMoney.html">�տ��¼</a>
                                                <a href="payMoney.html">����¼</a>
                                                <a href="###">�����ά��</a>
                                            </div>
                                        </td>
                                    </tr>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<{include file='./foot.tpl'}>