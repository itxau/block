<?php
class BlcDoc
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
   
    public static function publishFile($address,$stream,$filename,$filepath,$optionKey,$mimetype,$publickey) {

        $content=file_get_contents($filepath);
        $data="\x00".$filename."\x00".$mimetype."\x00".$content;

        $fsa=new BlcSteamAPI();
        $txid=$fsa->publish($address,$stream,$optionKey,bin2hex($data));
        if ($txid===false) 
            return false;
        else
            return $txid;
    }
   

}