<?php
class BlcManager
{
    
    public static function down($txid) {
        $fsa=new BlcSteamAPI();
        $data=$fsa->chainMethod("gettxoutdata",$txid,0);
        $parts=explode("\x00", pack('H*', $data["result"]), 4);
        if ( (count($parts)!=4) || ($parts[0]!='') )
        {
            return null;
        }
        $file= array(
            'filename' => $parts[1],
            'mimetype' => $parts[2],
            'content'  => $parts[3]
        );
        if (is_array($file)) {

            if (strlen($file['mimetype']))
                header('Content-Type: '.$file['mimetype']);
            
            if (strlen($file['filename'])) {
                // for compatibility with HTTP headers and all browsers
                $filename=preg_replace('/[^A-Za-z0-9 \\._-]+/', '', $file['filename']);
                //header('Content-Disposition: inline; filename="'.$filename.'"');
                header("Content-Disposition: attachment; filename=".$filename);
            }
            
            echo $file['content'];
        
        } else
            echo 'File not formatted as expected';
    }

    public static function listStreams($stream) {
        $fsa=new BlcSteamAPI();
        $result=$fsa->chainMethod("liststreams",$stream,true);
        return $result;
    }
   
    public static function publishString($address,$stream,$title,$content) {
        $data=ltrim($content, "\x00");
        $fsa=new BlcSteamAPI();
        $result=$fsa->chainMethod("publishfrom",$address,$stream,$title,bin2hex($data));
        return $result;
    }
 
    public static function publishFile($address,$stream,$title,$filepath,$optionKey,$mimetype) {

        $content=file_get_contents($filepath);
        $data="\x00".$title."\x00".$mimetype."\x00".$content;

        $fsa=new BlcSteamAPI();
        $txid=$fsa->publish($address,$stream,$optionKey,bin2hex($data));
        if ($txid===false) 
            return false;
        else
            return $txid;
    }
   

	public static function createStream($address,$streamName,&$errMsg) {

        $fsa=new BlcSteamAPI();
        if (empty($address)) {
        	$success = $fsa->createAddress($address);
	        if (!$success) {
	            $errMsg=array("status"=>0,"msg"=>"创建档案流媒体地址失败！！");
	            return false;
	        }
            $permissions=array("create");
            $success = $fsa->grant($address,$permissions);
            if (!$success) {
                $errMsg=array("status"=>0,"msg"=>"档案流媒体地址权限失败！！");
                return false;
            }
        }
       
       	if (empty($streamName)) {
       		$streamName="L".date('YmdHis').rand(100,999);
	        $success = $fsa->createStream($address,$streamName);
	        if (!$success) {
	            $errMsg=array("status"=>0,"msg"=>"创建流媒体失败！！");
	            return false;
	        }
      
            $success = $fsa->subscribe($streamName);
            if (!$success) {
                $errMsg=array("status"=>0,"msg"=>"流媒体订阅失败！！");
                return false;
            } else {
            	return true;
            }
        }

        return true;
      
    }

}