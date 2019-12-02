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
                                        <form method="get" action="">
                                        <div class="form-item">
                                            <label class="item-label">注册时间：</label>
                                            <div class="item-con">
                                                <div class="input-element-btn">
                                                    <div class="input-element-joint" data-toggle="datePicker"
                                                         data-date-type="YYYY-MM-DD" data-date-max="2100-01-02"
                                                         data-date-min="2018-01-09" data-date-isrange="1">
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="开始时间" value="<{$start_date}>" name="start_date">
                                                            <i class="evicon evicon-date-1"></i>
                                                        </div>
                                                        <i class="joint-line">-</i>
                                                        <div class="input-element prefix">
                                                            <input size="10" type="text" readonly="readonly"
                                                                   placeholder="结束时间" value="<{$end_date}>" name="end_date">
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
                                                    <input type="text" placeholder="用户名|昵称|手机号" name="search_mix" size="30" value="<{$search_mix}>">
                                                </div>
                                                <button type="submit" class="btn btn-primary"><span>搜索</span></button>
                                                <button type="submit" name="all" value="1" class="btn btn-outline-danger"><span>查看全部</span></button>
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
                                                    <p>用户名:<{$member.user_name}></p>
                                                    <p>昵称:<{$member.nick_name}></p>
                                                    <p>手机:<{$member.mobile}></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><{$member.id}></td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <div class="input-element suffix" data-type="select"
                                                     data-multiple="0" data-toggle="dropdown">
                                                    <select name="status<{$member.user_user_id}>">
                                                        <option value="0" <{if $member.status==0}>selected="selected"<{/if}>>无状态</option>
                                                        <option value="1" <{if $member.status==1}>selected="selected"<{/if}>>未激活</option>
                                                        <option value="2" <{if $member.status==2}>selected="selected"<{/if}>>已激活</option>
                                                        <option value="3" <{if $member.status==3}>selected="selected"<{/if}>>空</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="select-element dropdown">
                                                <{if $grade_list}>
                                                <select name="grade<{$member.user_user_id}>">
                                                    <option value="0">等级选择</option>
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
                                                <a data-action="lookQRCode" data-id="<{$member.user_user_id}>" href="###">收款码</a>
                                                <a href="/ninefenxiao/enterrecord.php?user_user_id=<{$member.user_user_id}>">收款记录</a>
                                                <a href="/ninefenxiao/outrecord.php?user_user_id=<{$member.user_user_id}>">打款记录</a>
                                                <a data-action="share" data-id="<{$member.user_user_id}>" href="###">分享二维码</a>
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
                                            <button type="button" class="btn btn-sm btn-primary" data-action="changeAllGrade" onclick="set()"><span>批量修改等级</span></button>
                                            <input type="hidden" name="start_date" value="<{$start_date}>">
                                            <input type="hidden" name="end_date" value="<{$end_date}>">
                                            <input type="hidden" name="search_mix" value="<{$search_mix}>">
                                            <input type="hidden" name="type" value="upgrade">
                                            <input type="hidden" name="ids" id="ids" value="">
                                            <button type="button" class="btn btn-sm btn-primary"><a href="/NineFenXiao/export.php?start_date=<{$start_date}>&end_date=<{$end_date}>&search_mix=<{$search_mix}>"><span style="color:#ffffff">批量导出</span></a> </button>

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
                                <span class="page-sum">共<em><{$totalpage}></em>页</span>
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
            alert('请先选择需要修改的会员');
            return false;
        }
        $('#ids').val(opt);
        publicFun.confirm('确定要修改吗',function(){
            $('#upform').submit();
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
                    case 'lookQRCode':
                        publicFun.winIframe('/ninefenxiao/paymentcode.php?user_user_id='+id, 410, 320, '更改收款码');
                        break;
                    case 'lookSub':
                        publicFun.winIframe('/ninefenxiao/looksub.php?user_user_id='+id, 520, 630, '下级信息');
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
                            alert('请先选择需要修改等级的会员');
                            return false;
                        }

                        publicFun.winIframe('/NineFenXiao/batchUpgrade.php?user_ids='+opt, 410, 290, '批量修改等级');
                        break;*/
                    case 'share':
                        publicFun.winIframe('/NineFenXiao/share.php?user_user_id='+id, 410, 290, '分享二维码');
                        break;
                }
            }
        }, '[data-action]');
    });
</script>