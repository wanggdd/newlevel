<{include file='nine_fenxiao/head.tpl'}>
<div class="look-qrcode-alert">
    <div class="qrcode-box">
        <div class="qrcode">
            <input type="file" id="photoFile" style="display: none;" onchange="upload_voucher()">
            <img class="c-rqcode__img" src="<{$payment_code}>" onclick="uploadPhoto()">
            <input type="hidden" id="productImg" name="payment_code">
            <input type="hidden" id="user_user_id" value="<{$user_user_id}>" name="user_user_id">
        </div>
        <p onclick="uploadPhoto()">更改收钱二维码</p>
    </div>
</div>

<{include file='nine_fenxiao/foot.tpl'}>
<script src="/public/js/uploader.js"></script>
