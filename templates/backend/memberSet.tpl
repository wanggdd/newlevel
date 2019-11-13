<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="GBK">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>��������</title>
    <script src="http://test.evyun.cn/js/jquery-1.9.1.min.js"></script>
    <link rel="stylesheet" href="http://test.evyun.cn/test_EvyunUi/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/paidItems.min.css">

</head>

<body>
<div class="member-set-alert" id="memberSet">
    <div class="filter-form">
        <div class="form-element">
            <div class="form-item-group">
                <div class="form-item">
                    <label class="item-label">�û��ǳƣ�</label>
                    <div class="item-con">
                        <div class="input-element-select">
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
                            <div class="input-element-joint" data-toggle="datePicker" data-date-type="YYYY-MM-DD"
                                 data-date-max="2019-01-02" data-date-min="2018-01-09" data-date-isrange="1">
                                <div class="input-element prefix">
                                    <input size="10" type="text" readonly="readonly" placeholder="��ʼʱ��"
                                           value="2018-01-09">
                                    <i class="evicon evicon-date-1"></i>
                                </div>
                                <i class="joint-line">-</i>
                                <div class="input-element prefix">
                                    <input size="10" type="text" readonly="readonly" placeholder="����ʱ��"
                                           value="2019-01-09">
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
            <tr class="text-center vertical-middle">
                <td>
                    qwguo
                </td>
                <td>
                    12
                </td>
                <td>
                    ����
                </td>
                <td>
                    18201109020
                </td>
                <td>
                    <label class="radio-element">
                        <input type="radio" name="sex_0_0">
                        <i class="dot"></i>
                    </label>
                </td>
            </tr>
            <tr class="text-center vertical-middle">
                <td>
                    qwguo
                </td>
                <td>
                    12
                </td>
                <td>
                    ����
                </td>
                <td>
                    18201109020
                </td>
                <td>
                    <label class="radio-element">
                        <input type="radio" name="sex_0_0">
                        <i class="dot"></i>
                    </label>
                </td>
            </tr>
            <tr class="text-center vertical-middle">
                <td>
                    qwguo
                </td>
                <td>
                    12
                </td>
                <td>
                    ����
                </td>
                <td>
                    18201109020
                </td>
                <td>
                    <label class="radio-element">
                        <input type="radio" name="sex_0_0">
                        <i class="dot"></i>
                    </label>
                </td>
            </tr>
            <tr class="text-center vertical-middle">
                <td>
                    qwguo
                </td>
                <td>
                    12
                </td>
                <td>
                    ����
                </td>
                <td>
                    18201109020
                </td>
                <td>
                    <label class="radio-element">
                        <input type="radio" name="sex_0_0">
                        <i class="dot"></i>
                    </label>
                </td>
            </tr>
            <tr class="text-center vertical-middle">
                <td>
                    qwguo
                </td>
                <td>
                    12
                </td>
                <td>
                    ����
                </td>
                <td>
                    18201109020
                </td>
                <td>
                    <label class="radio-element">
                        <input type="radio" name="sex_0_0">
                        <i class="dot"></i>
                    </label>
                </td>
            </tr>
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
        <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>ȡ��</span></button>
        <button type="button" data-action="enter" class="btn btn-primary"><span>ȷ��</span></button>
    </div>
</div>
<script src="http://test.evyun.cn/test_EvyunUi/js/evPublicInit-min.js" charset="gbk"></script>
<script src="js/paidItems.min.js"></script>

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
                    case 'enter':
                        // ȷ����ť�¼�
                        popup.popupClose();
                        break;
                }
            }
        }, '[data-action]');
    });
</script>
</body>

</html>
