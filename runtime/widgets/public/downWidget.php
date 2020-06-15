
<div id="priavtekey-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;z-index: 99999999;">
  <form id="priavtekey_form" action="<?php echo urldecode(Url::urlFormat("/index/downfile"));?>">
    <input type="hidden" name="doctxid" id="doctxid">
    <input type="hidden" name="addressid" id="addressid">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <p>请输入您的私钥:</p>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <textarea  class="form-control" id="priavtekey" name="priavtekey" rows="7" pattern="required"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">提交</button>
            </div>
        </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  function down(addrid,txid) {
    $('#priavtekey-modal').find('[id="addressid"]').val(addrid);
    $('#priavtekey-modal').find('[id="priavtekey"]').val("");
    $('#priavtekey-modal').modal();
    $('#priavtekey-modal').find('[id="doctxid"]').val(txid);
    $('#priavtekey-modal').modal();
    return false;
  }

</script>


 <!-- Powered by baxgsun@163.com -->