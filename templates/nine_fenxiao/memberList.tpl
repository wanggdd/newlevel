<{include file='nine_fenxiao/head.tpl'}>
<div class="paid-item-wrap">
    <div class="panel paid-item-head">
        <div class="panel-body">
            <dl class="pull-left">
                <dt class="h4">
                <hr class="hr hr-y hr-5 primary no-spacing">
                <strong>付费项目</strong></dt>
                <dd class="text-gray">设置及管理您要优化的网站域名</dd>
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
                                            <label class="item-label">注册时间：</label>
                                            <div class="item-con">
                                                <div class="input-element-btn">
                                                    <div class="input-element-joint" data-toggle="datePicker"
                                                         data-date-type="YYYY-MM-DD" data-date-max="2019-01-02"
                                                         data-date-min="2018-01-09" data-date-isrange="1">
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="开始时间" value="2018-01-09">
                                                            <i class="evicon evicon-date-1"></i>
                                                        </div>
                                                        <i class="joint-line">-</i>
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="结束时间" value="2019-01-09">
                                                            <i class="evicon evicon-date-1"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-item">
                                            <label class="item-label">查找：</label>
                                            <div class="item-con">
                                                <div class="input-element">
                                                    <input type="text" placeholder="输入文字" size="30">
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
                                        <th width="80">选择</th>
                                        <th width="300">会员信息</th>
                                        <th>会员编号</th>
                                        <th>状态</th>
                                        <th>等级</th>
                                        <th>下级数量</th>
                                        <th>注册时间</th>
                                        <th>操作</th>
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
                                                    <p>用户名:<{$member.user_id}></p>
                                                    <p>昵称:批批批</p>
                                                    <p>手机:13111111111</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>18999999</td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <div class="input-element suffix" data-type="select"
                                                     data-multiple="0" data-toggle="dropdown">
                                                    <input type="text" readonly="readonly" placeholder="无" size="5">
                                                    <input type="hidden" value="0">
                                                    <i class="evicon evicon-arrow-up-2"></i>
                                                </div>
                                                <div class="option-list dropdown-menu">
                                                    <dl>

                                                        <dd title="无" data-value="0">
                                                            <a href="###"><span>无</span></a>
                                                        </dd>

                                                    </dl>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td><span class="sub-text" data-action="lookSub"><{$member.lower_number}></span></td>
                                        <td><{$member.create_time|date_format:'%Y-%m-%d'}></td>
                                        <td>
                                            <div class="href-area">
                                                <a data-action="lookQRCode" href="###"></a>
                                                <a href="getMoney.html">收款记录</a>
                                                <a href="payMoney.html">打款记录</a>
                                                <a href="###">分享二维码</a>
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
                                                <span class="checkbox-label">全选</span>
                                            </label>
                                            <button type="button" class="btn btn-sm btn-primary" data-action="changeAllGrade"><span>批量修改等级</span></button>
                                            <button type="button" class="btn btn-sm btn-primary"><span>批量导出</span></button>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="pagination text-center">
                               <{$page_str}>
                                <span class="page-sum">共<em><{$totalpage}></em>页</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<{include file='nine_fenxiao/foot.tpl'}>