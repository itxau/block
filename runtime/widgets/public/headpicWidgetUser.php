<link rel="stylesheet" type="text/css" href="<?php echo urldecode(Url::urlFormat("@static/vendors/webuploader/webuploader.css"));?>">
<script type="text/javascript" src="<?php echo urldecode(Url::urlFormat("@static/vendors/webuploader/webuploader.min.js"));?>"></script>
<style type="text/css">
    .headpic img {width: 200px;height:200px; border: 1px solid #ccc;}
    .webuploader-pick {width: 200px;}
</style>
<input type="hidden" name="head_pic" id="head_pic" value="<?php echo isset($head_pic)?$head_pic:"";?>">
<div class="headpic">
    <img src="<?php echo isset($head_pic)?$head_pic:"";?>" id="headpic_img">
</div>
<div>
    <span id="imagePicker">选择头像</span>
</div>

<script type="text/javascript">
    $(function(){
        var uploader = WebUploader.create({
            auto: true,
            fileVal:'imgFile',
            fileNumLimit:10,
            swf: '<?php echo urldecode(Url::urlFormat("@static/vendors/webuploader/Uploader.swf"));?>',
            server: '<?php echo urldecode(Url::urlFormat("/index/upload_headpic"));?>',
            pick: '#imagePicker',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/jpg,image/jpeg,image/png'
            },
            method: 'POST'
        });

        uploader.on( 'uploadSuccess', function( file , ret ) {
            
            $("#headpic_img").attr( 'src', ret.img );
            $('#head_pic').val(ret.img);
        });

        uploader.on("uploadAccept", function( file, data){
            if (data.error == undefined) {
                return true;
            }else{
                return false;
            }
        });

        uploader.on( 'uploadError', function( file ) {
            swal('对不起，图片上传失败！');
        });
    
    });
</script>
 <!-- Powered by baxgsun@163.com -->