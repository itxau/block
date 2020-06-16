<script type="text/javascript">

    function changeStatus(id,status) {
        doAjaxRequest("<?php echo urldecode(Url::urlFormat("/$con/status"));?>",{ids:id,status:status},true);
    }

    $('.op-handle').on('click',function(){
        var id=$(this).parent().parent().attr("data-refid");
        open_dialog("<?php echo urldecode(Url::urlFormat("/$con/handle/id/"));?>"+id,"<?php echo isset($title)?$title:"";?>-受理",<?php echo isset($width)?$width:"";?>,<?php echo isset($height)?$height:"";?>,false);
        return false;
    });

    $('.op-info').on('click',function(){
        var id=$(this).parent().parent().attr("data-refid");
        open_dialog("<?php echo urldecode(Url::urlFormat("/$con/info/id/"));?>"+id,"<?php echo isset($title)?$title:"";?>-查看",<?php echo isset($width)?$width:"";?>,<?php echo isset($height)?$height:"";?>,false);
        return false;
    });
   
    $('.op-new').on('click',function(){
        open_dialog("<?php echo urldecode(Url::urlFormat("/$con/edit"));?>","<?php echo isset($title)?$title:"";?>-新增",<?php echo isset($width)?$width:"";?>,<?php echo isset($height)?$height:"";?>,false);
        return false;
    });

    $('.op-new2').on('click',function(){
        open_dialog("<?php echo urldecode(Url::urlFormat("/$con/edit"));?>","<?php echo isset($title)?$title:"";?>-新增",<?php echo isset($width)?$width:"";?>,<?php echo isset($height)?$height:"";?>,true);
        return false;
    });

    $('.op-edit').on('click',function(){
        var id=$(this).parent().parent().attr("data-refid");
        open_dialog("<?php echo urldecode(Url::urlFormat("/$con/edit/id/"));?>"+id,"<?php echo isset($title)?$title:"";?>-修改",<?php echo isset($width)?$width:"";?>,<?php echo isset($height)?$height:"";?>,false);
        return false;
    });

    $('.op-delete').on('click',function(){
        var id=$(this).parent().parent().attr("data-refid");
        if (id>0) {
            deleteRecord("<?php echo urldecode(Url::urlFormat("/$con/delete"));?>",{ids:id});
        }
        return false;
    });

    $('.op-deletes').on('click',function(e){
        var ids=idsSelected();
        if (ids.length>0) {
            deleteRecord("<?php echo urldecode(Url::urlFormat("/$con/delete"));?>",{ids:ids});
        }
        return false;
    });

</script>
 <!-- Powered by baxgsun@163.com -->