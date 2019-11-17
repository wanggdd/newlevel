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
                        <div class="tab-panel-item active member-grade-panel-item" id="memberGrade">
                            <form action="" class="form-element basic-function-form" method="post">
                                <div class="filter-form">
                                    <div class="form-element">
                                        <button type="button" data-action="addGrade" class="btn btn-primary">
                                            <i class="evicon evicon-plus-1"></i>
                                            <span>添加</span>
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
                                            <th>等级</th>
                                            <th>等级名称</th>
                                            <th>初始会员</th>
                                            <th>晋升金额</th>
                                            <th>操作</th>
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
                                                        <a class="text-primary" href="###" data-action="mechange" data-id="<{$item.id}>">更改</a>
                                                    <{else}>
                                                        <div class="member-set-area">
                                                            <a class="text-primary" href="###" data-action="meset" data-id="<{$item.id}>">设置</a>
                                                        </div>
                                                    <{/if}>

                                                </div>
                                            </td>
                                            <td>
                                                <{$item.promote_money}>
                                            </td>
                                            <td>
                                                <div class="href-area">
                                                    <a href="###" data-action="upset" data-id="<{$item.id}>">晋升设置</a>
                                                    <a href="###" data-action="del">删除</a>
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
                                                    <a class="text-primary" href="###" data-action="meset" data-id="<{$item.id}>">设置</a>
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
                                        <{/if}>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-item">
                                    <label class="item-label"></label>
                                    <div class="item-con">
                                        <input type="submit" class="btn btn-primary" value="提交">
                                    </div>
                                </div>
                            </form>
                            <!--
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
                        publicFun.winIframe('/NineFenXiao/promote.php?id='+id, 490, 320, '晋升设置');
                        break;
                    case 'meset':
                    case 'mechange':
                        publicFun.winIframe('/NineFenXiao/memberSet.php?id='+id, 800, 800, '初始用户等级');
                        break;
                    case 'del':
                        publicFun.confirm('确定要删除吗',function(){
                            publicFun.point('删除成功', 1);
                        });
                        break;
                    case 'addGrade':
                        publicFun.winIframe('/NineFenXiao/addgrade.php', 450, 350, '添加等级');
                        break
                }
            }

        }, '[data-action]');
    });
</script>