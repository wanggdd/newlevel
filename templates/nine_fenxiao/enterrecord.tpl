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
                                    <form method="get" action="">
                                    <div class="form-item">
                                        <label class="item-label">打款时间：</label>
                                        <div class="item-con">
                                            <div class="input-element-btn">
                                                <div class="input-element-joint" data-toggle="datePicker"
                                                     data-date-type="YYYY-MM-DD" data-date-max="2100-01-02"
                                                     data-date-min="2018-01-09" data-date-isrange="1">
                                                    <div class="input-element prefix">
                                                        <input size="10" type="text" readonly="readonly"
                                                               placeholder="开始时间" name="start_date" value="<{$start_date}>">
                                                        <i class="evicon evicon-date-1"></i>
                                                    </div>
                                                    <i class="joint-line">-</i>
                                                    <div class="input-element prefix">
                                                        <input size="10" type="text" readonly="readonly"
                                                               placeholder="结束时间" name="end_date" value="<{$end_date}>">
                                                        <i class="evicon evicon-date-1"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input-element">
                                                <input type="text" placeholder="用户名|昵称|手机号" size="30" name="search_mix" value="<{$search_mix}>">
                                            </div>
                                            <button type="submit" class="btn btn-primary"><span>搜索</span></button>
                                            <button type="submit" name="all" value="1" class="btn btn-outline-danger"><span>查看全部</span></button>
                                            <input type="hidden" name="type" value="search">

                                            <div class="right-btn-area pull-right">
                                                <a href="/ninefenxiao/memberlist.php" type="button" class="btn btn-outline-danger"><span>返回</span></a>
                                                <div type="button" class="btn btn-outline-danger"><i class="evicon evicon-leading-in-1"></i>
                                                    <a href="/NineFenXiao/exportenter.php?user_user_id=<{$user_user_id}>&start_date=<{$start_date}>&end_date=<{$end_date}>&search_mix=<{$search_mix}>"><span>导出数据</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <form method="get" action="" id="update_form">
                            <div class="data-table" data-toggle="allSelect" data-all-name="checkbox_all" data-target-name="checkbox_item">
                                <table class="table table-no-outer-border table-spacing-lg">
                                    <thead>
                                    <tr class="active text-center">
                                        <th width="80">选择</th>
                                        <th width="300">会员信息</th>
                                        <th>会员编号</th>
                                        <th>打款时间</th>
                                        <th>凭证信息</th>
                                        <th>收款金额（元）</th>
                                        <th>收款状态</th>
                                        <th>收款日期</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <{if $record_list}>
                                    <{foreach key=key item=item from=$record_list}>
                                    <tr class="text-center vertical-middle">
                                        <td>
                                            <label class="checkbox-element sm">
                                                <input type="checkbox" name="checkbox_item" value="<{$item.id}>">
                                                <i class="dot evicon evicon-right-2"></i>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-left">
                                                            <span><img class="media-object" src="<{$item.pic}>"></span>
                                                </div>
                                                <div class="media-body">
                                                    <p>用户名:<{$item.user_name}></p>
                                                    <p>昵称:<{$item.nick_name}></p>
                                                    <p>手机:<{$item.mobile}></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><{$item.out_member}></td>
                                        <td>
                                            <{$item.out_time}>

                                        </td>
                                        <td>
                                            <a href="###"  data-id="<{$item.id}>" data-action="lookvoucher">查看</a>
                                        </td>
                                        <td><{$item.payment_money}></td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <select name="status<{$item.id}>">
                                                    <option value="1" <{if $item.status == 1}>selected="selected"<{/if}>>待收款</option>
                                                    <option value="2" <{if $item.status == 2}>selected="selected"<{/if}>>已收款</option>
                                                    <option value="3" <{if $item.status == 3}>selected="selected"<{/if}>>已拒绝</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <{$item.enter_time}>
                                        </td>
                                    </tr>
                                    <{/foreach}>
                                    <{/if}>
                                    </tbody>
                                    <tfoot>
                                    <tr class="striped">
                                        <td colspan="8">
                                            <label class="checkbox-element sm">
                                                <input type="checkbox" name="checkbox_all">
                                                <i class="dot evicon evicon-right-2"></i>
                                                <span class="checkbox-label">全选</span>
                                            </label>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="set()"><span>修改</span></button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="del_set()"><span>删除</span></button>
                                            <input type="hidden" name="type" id="type">
                                            <input type="hidden" name="user_user_id" value="<{$user_user_id}>">
                                            <input type="hidden" name="ids" id="ids">
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            </form>
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

<script>
    function del_set(){
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
            alert('请先选择需要删除的数据');
            return false;
        }
        $('#ids').val(opt);
        $('#type').val('delgrade');
        publicFun.confirm('确定要删除吗',function(){
            $('#update_form').submit();
            //publicFun.point('修改成功', 1);
        });
    }


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
            alert('请先选择需要修改的数据');
            return false;
        }
        $('#ids').val(opt);
        $('#type').val('upgrade');
        publicFun.confirm('确定要修改吗',function(){
            $('#update_form').submit();
            //publicFun.point('修改成功', 1);
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
                    case 'lookvoucher':
                        publicFun.winIframe('/NineFenXiao/lookvoucher.php?id='+id, 600, 500, '查看凭证');
                        break;
                }
            }
        }, '[data-action]');
    });
</script>