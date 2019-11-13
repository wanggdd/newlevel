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
                        <div class="tab-panel-item active" id="basicFunction">
                            <form action="" class="form-element basic-function-form" method="post">
                                <div class="form-item">
                                    <label class="item-label">功能设置：</label>
                                    <div class="item-con">
                                        <div class="radio-element-group">
                                            <label class="radio-element">
                                                <input type="radio" name="on_off" value="1" <{if $info.on_off==1}>checked<{/if}>>
                                                <i class="dot"></i>
                                                <span class="radio-label">是</span>
                                            </label>
                                            <label class="radio-element">
                                                <input type="radio" name="on_off" value="0" <{if $info.on_off!=1}>checked<{/if}>>
                                                <i class="dot"></i>
                                                <span class="radio-label">否</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item">
                                    <label class="item-label">激活任务设置：</label>
                                    <div class="item-con">
                                        <div class="block">
                                            <label class="checkbox-element">
                                                <input type="checkbox" placeholder="0.00" name="nostate_active" value="1" <{if $info.nostate_active==1}>checked<{/if}>>
                                                <i class="dot evicon evicon-right-2"></i>
                                                <span class="checkbox-label">无状态升级至激活(打给直接上级)</span>
                                            </label>
                                        </div>
                                        <div class="block">
                                            <div class="input-element">
                                                <input type="text" placeholder="0.00" name="nostate_active_money" value="<{$info.nostate_active_money}>">
                                            </div>
                                            <span class="item-text">&nbsp;元</span>
                                        </div>
                                        <div class="block">
                                            <label class="checkbox-element">
                                                <input type="checkbox" placeholder="0.00" name="noactive_active" value="1" <{if $info.noactive_active==1}>checked<{/if}>>
                                                <i class="dot evicon evicon-right-2"></i>
                                                <span class="checkbox-label">无状态升级至激活(打给直接上级)</span>
                                            </label>
                                        </div>
                                        <div class="block">
                                            <div class="input-element">
                                                <input type="text" placeholder="0.00" name="noactive_active_money" value="<{$info['noactive_active_money']}>">
                                            </div>
                                            <span class="item-text">&nbsp;元</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-item">
                                    <label class="item-label"></label>
                                    <div class="item-con">
                                        <input type="submit" class="btn btn-primary" value="提交">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<{include file='./foot.tpl'}>