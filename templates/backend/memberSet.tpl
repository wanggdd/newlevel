<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="GBK">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>晋升设置</title>
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
                    <label class="item-label">用户昵称：</label>
                    <div class="item-con">
                        <div class="input-element-select">
                            <div class="select-element dropdown">
                                <div class="input-element suffix" data-type="select"
                                     data-multiple="0" data-toggle="dropdown">
                                    <input type="text" readonly="readonly" placeholder="等级" size="8">
                                    <input type="hidden" value="0">
                                    <i class="evicon evicon-arrow-up-2"></i>
                                </div>
                                <div class="option-list dropdown-menu">
                                    <dl>
                                        <dd title="等级选择" data-value="0">
                                            <a href="###"><span>等级选择</span></a>
                                        </dd>
                                        <dd title="一级" data-value="1">
                                            <a href="###"><span>一级</span></a>
                                        </dd>
                                        <dd title="二级" data-value="2">
                                            <a href="###"><span>二级</span></a>
                                        </dd>
                                        <dd title="三级" data-value="3">
                                            <a href="###"><span>三级</span></a>
                                        </dd>
                                        <dd title="四级" data-value="4">
                                            <a href="###"><span>四级</span></a>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="input-element-joint" data-toggle="datePicker" data-date-type="YYYY-MM-DD"
                                 data-date-max="2019-01-02" data-date-min="2018-01-09" data-date-isrange="1">
                                <div class="input-element prefix">
                                    <input size="10" type="text" readonly="readonly" placeholder="开始时间"
                                           value="2018-01-09">
                                    <i class="evicon evicon-date-1"></i>
                                </div>
                                <i class="joint-line">-</i>
                                <div class="input-element prefix">
                                    <input size="10" type="text" readonly="readonly" placeholder="结束时间"
                                           value="2019-01-09">
                                    <i class="evicon evicon-date-1"></i>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary"><span>搜索</span></button>
                        <button type="button" class="btn btn-outline-danger"><span>查看全部</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">
        <table class="table table-no-outer-border table-spacing-lg">
            <thead>
            <tr class="active text-center">
                <th>用户名</th>
                <th>等级</th>
                <th>昵称</th>
                <th>手机号</th>
                <th>选择</th>
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
                    果果
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
                    果果
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
                    果果
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
                    果果
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
                    果果
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
                <a class="disabled" href="###" data-page="1">上一页</a>
                <a href="###" data-page="1">1</a>
                <b>2</b>
                <a href="###" data-page="3">3</a>
                <a href="###" data-page="4">4</a>
                <a href="###" data-page="5">5</a>
                <a href="###" data-page="3">下一页</a>
            </span>
    </div>
    <div class="submit-btn-area">
        <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>取消</span></button>
        <button type="button" data-action="enter" class="btn btn-primary"><span>确定</span></button>
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
                        // 取消按钮事件
                        popup.popupClose();
                        break;
                    case 'enter':
                        // 确定按钮事件
                        popup.popupClose();
                        break;
                }
            }
        }, '[data-action]');
    });
</script>
</body>

</html>
