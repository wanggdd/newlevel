<{include file='./head.tpl'}>
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
                    <{include file='./tab.tpl'}>

                    <div class="tab-panel-body">
                        <div class="tab-panel-item active member-grade-panel-item" id="memberGrade">
                            <div class="filter-form">
                                <div class="form-element">
                                    <button type="button" class="btn btn-primary"><i class="evicon evicon-plus-1"></i><span>添加</span></button>
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
                                        <th>排序</th>
                                        <th>等级名称</th>
                                        <th>初始会员</th>
                                        <th>晋升金额</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="text-center vertical-middle">
                                        <td>
                                            <div class="input-element">
                                                <input type="text" size="5">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-element">
                                                <input type="text" size="15">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="member-set-area">
                                                <span>用户名/昵称</span>
                                                <a class="text-primary" href="###" data-action="mechange">更改</a>
                                            </div>
                                        </td>
                                        <td>
                                            1440
                                        </td>
                                        <td>
                                            <div class="href-area">
                                                <a href="###" data-action="upset">晋升设置</a>
                                                <a href="###" data-action="del">删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="text-center vertical-middle">
                                        <td>
                                            <div class="input-element">
                                                <input type="text" size="5">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-element">
                                                <input type="text" size="15">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="member-set-area">
                                                <a class="text-primary" href="###" data-action="meset">设置</a>
                                            </div>
                                        </td>
                                        <td>
                                            1440
                                        </td>
                                        <td>
                                            <div class="href-area">
                                                <a href="###" data-action="upset">晋升设置</a>
                                                <a href="###" data-action="del">删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination text-center">
                                    <span class="page-limits">
                                        <select>
                                            <option value="10条/页">10条/页</option>
                                            <option value="20条/页">20条/页</option>
                                            <option value="30条/页">30条/页</option>
                                            <option value="40条/页">40条/页</option>
                                        </select>
                                    </span>
                                <span class="page-number">
                                        <a class="disabled" href="###" data-page="1">上一页</a>
                                        <a href="###" data-page="1">1</a>
                                        <b>2</b>
                                        <a href="###" data-page="3">3</a>
                                        <a href="###" data-page="4">4</a>
                                        <a href="###" data-page="5">5</a>
                                        <a href="###" data-page="3">下一页</a>
                                    </span>
                                <span class="page-skip">到第<input type="text" size="2" maxlength="5">页<button
                                            class="btn btn-default">确定</button></span>
                                <span class="page-sum">共<em>5</em>页</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<{include file='./foot.tpl'}>