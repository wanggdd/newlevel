<{include file='./head.tpl'}>

<div class="promote-set-alert" id="promoteSet">
    <form action="" class="form-element">
        <div class="form-item">
            <label class="item-label">下级数量：</label>
            <div class="item-con">
                <div class="select-element dropdown">
                    <div class="input-element suffix" data-type="select" data-multiple="0" data-toggle="dropdown">
                        <input type="text" readonly="readonly" placeholder="1" size="6">
                        <input type="hidden">
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
                <div class="input-element">
                    <input type="text" placeholder="" size="22">
                </div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">晋升金额：</label>
            <div class="item-con">
                <div class="input-element">
                    <input type="text" placeholder="500" size="25">
                </div>
                <div class="item-text">&nbsp;元</div>
            </div>
        </div>
        <div class="form-item">
            <label class="item-label"></label>
            <div class="item-con">
                <button type="button" data-action="cancel" class="btn btn-outline-danger"><span>取消</span></button>
                <button type="button" data-action="enter" class="btn btn-primary"><span>确定</span></button>
            </div>
        </div>
    </form>
</div>

<{include file='./foot.tpl'}>