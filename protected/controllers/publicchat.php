<?php
//公告通知
class PublicchatController extends Controller
{
    #---------------------获取配置信息
    public function getList(){
        //查询自己的信息
        $model = new Model();
        $uid =Req::args('uid');
        $type =Req::args('type');

        if($type==1){ //企业登录
            $mine =$model->table("etp_enterprise")->where("id=".$uid)->find();
            $mine['sign'] = $mine['full_name'];
            $userGroup =$this->enterprise($type); //税务
            $govGroup =$this->government($type); //政务
        }else if($type==2){ //税务登录 
            $mine =$model->table("adm_manager")->where("id=".$uid)->find();

            if(!empty($mine['username'])) $mine['name'] =$mine['username'];

            $userGroup =$this->publicnews($uid,$type);
            $govGroup =$this->government($type); //政务
        }else if($type==3){ //政务登录
            $mine =$model->table("gov_user")->where("id=".$uid)->find();

            if(!empty($mine['username'])) $mine['name'] = $mine['username'];
           
            $userGroup =$this->publicnews($uid,$type);
            $gov =$this->enterprise($type); //税务

            $govGroup[0] =array('id'=>0,'groupname'=>'税务','online'=>0,'list'=>$gov);
           // print_r($govGroup);die;
        }
        $group =[];
        $return = [
       		'code' => 0,
       		'msg'=> '',
       		'data' => [
       			'mine' => [
                    'username' => $mine['name'],
                    'id' => $mine['id'],
                    'status' => 'online',
                    'sign' => $mine['sign'], 
                    'utype' => $type, 
                    'avatar' => Common::getAvatar($mine['head_pic'])
       			],
                'friend' => $userGroup,
                'govlist'=>$govGroup,
				'group' => $group
       		],
        ];
    
         //print_r( $return);die;
         echo json_encode($return);  
    }
    #------------------------企业信息
    public function publicnews($uid,$type)
    {
        $model = new Model();
       
            $publicnews =$model->table("publicnews")->fields("distinct to_id")->where("to_type=1 and send_id=$uid and send_type=$type")->limit(20)->findall();
            $userGroup[0] =array('id'=>0,'groupname'=>'企业最新动态','online'=>0);
            $parr =[];
            foreach($publicnews as $k=>$v){
                $public =$model->table("publicnews as p")
                ->join('left join etp_enterprise as e on e.id = p.to_id')
                ->fields("e.id,e.name as username,e.full_name as sign,e.head_pic,p.created_time")
                ->where("p.to_type=1 and p.send_id=$uid and p.send_type=$type and p.to_id=".$v['to_id'])
                ->order('p.created_time desc')->find();
                if(!empty($public['id'])){
                    $public['avatar'] = Common::getAvatar($public['head_pic']);
                    $public['utype']=1;
                    $parr[]= $public; 
                }   
            }
        $userGroup[0]['list']=$this->arraySort($parr, 'created_time', SORT_DESC);

        return $userGroup;
    }
    #-------------------------税务信息
    public function enterprise($type)
    {
        $model = new Model();

        if($type==1){
            $where ="is_msgofetp =1";
        }else{
            $where ="is_msgofgov =1";
        }
        $other =$model->table("adm_manager")->where($where)->findAll(); //税务用户
        $userGroup = $model->table("adm_dept")->fields("id,name as groupname")->findAll(); //税务组
        $list = [];  //群组成员信息
        $j = 0;
        foreach( $userGroup as $key=>&$vo ){
            $vo['online']=0;
            $vo['list'] =[];
            foreach($other as $k=>$v){
                if ($vo['id'] == $v['dept_id']) {
                    $list[$j]['username'] = empty($v['username']) ? $v['name'] : $v['username'];
                    $list[$j]['id'] = $v['id'];
                    $list[$j]['avatar'] = Common::getAvatar($v['head_pic']);
                    $list[$j]['sign'] = $v['sign'];
                    $list[$j]['utype'] = 2;
                    $vo['list']= $list; 
                    $j++;
                } 
            }
            $j = 0;
            unset($list);
        }
        return $userGroup;
    }
    #--------------------------政务信息
    public function government($type)
    {
        if($type==1){
            $where =" and is_msgofetp =1";
        }else{
            $where =" and is_msgofadm =1";
        }
        $model = new Model();
        $government =$model->table("gov_government")->fields("id,name as groupname")->findAll(); //证务 ->where('status=1')
        foreach ($government as $key => $value) {
            $govid[] = $value['id'];
        }
        $pid =implode(',',$govid);
        $govGroup = $model->table("gov_dept")->fields("id,gov_id,name as groupname")->where("gov_id in($pid)")->findAll(); //证务部门
        foreach ($govGroup as $k => $v) {
            $gid[] = $v['id'];
        }
        $gid =implode(',',$gid);
        $govuser =$model->table("gov_user")->where("dept_id in($gid)".$where)->findAll(); //证务用户
        unset($pid);unset($gid);
        $govlist = [];  //群组成员信息
        $i = 0;
        $j = 0;
        foreach($government as $key=>&$value){
            $value['online']=0;
            $value['list'] =[];
            foreach($govGroup as $key=>&$vol){
                if ($vol['gov_id'] == $value['id']) {
                    $vol['online']=0;
                    $vol['list'] =[];
                    foreach($govuser as $k=>$vl){
                        if ($vol['id'] == $vl['dept_id']) {
                            
                            $govlist[$i]['username'] = empty($vl['username']) ? $vl['name'] : $vl['username'];
                            $govlist[$i]['id'] = $vl['id'];
                            $govlist[$i]['avatar'] = Common::getAvatar($vl['head_pic']);
                            $govlist[$i]['sign'] = $vl['sign'];
                            $govlist[$i]['utype'] = 3;
                            $vol['list']= $govlist; 
                            $i++;
                        } 
                    }
                    $i = 0;
                    unset($govlist);
                    $value['list'][$j]= $vol; 
                    $j++;
                }
            }
            $j = 0;
            unset($vol);
        }
        return $government;
    }
    #------------------------信息展示内容排序
    function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
    #-----------------------发送信息
    public function sendmessage(){
        $data =Req::post();
        $data['created_time'] = date("Y-m-d H:i:s");
        $model = new Model();
        $in =$model->table("publicnews")->data($data)->insert();

       // $model->table("publicnews")->where("to_id={$data['send_id']} and to_type={$data['send_type']} and fromid=".$data['fromid'])->data(array('in_read'=>1))->update();

        if($in){
            $info = array('status'=>0,'msg'=>'发送成功');
        }else{
            $info = array('status'=>1,'msg'=>'发送失败');
        }
        echo JSON::encode($info);
    }

    #-------------------------接收信息
    public function getmessage(){
        $uid = Req::args('uid');
        $type = Req::args('type');
        $iscid = Filter::int(Req::args('iscid'));  //是否展示
        $lastid = Filter::int(Req::args('lastid'));
 
        $model = new Model();
       
        if($iscid>0){
            $obj =$model->table("publicnews")->where("to_id=$uid and to_type=$type and in_read=0 and id>$lastid")->order("id asc")->findALL();
        }else{
            $getID =$model->table("publicnews")->fields("DISTINCT to_id")->where("(send_id=$uid and send_type=$type) or (to_id=$uid and to_type=$type)")->order("id asc")->findALL();
           
            $query ="";
            foreach ($getID as $key => $value) {
                $query.="(SELECT * FROM `publicnews` where ((send_id=$uid and send_type=$type) or (to_id=$uid and to_type=$type)) and send_id={$value['to_id']}  LIMIT 20) UNION ";
            }
            $query= rtrim($query,"UNION ");
            $obj= $model->query($query." ORDER BY id asc");
        }
       
        if($obj){
            foreach($obj as $key=>&$val){
                $userlist= $this->getuserlist($val['send_id'],$val['send_type']);
               
                $val['username'] =$userlist['username'];
                $val['sign'] =$userlist['sign'];
                $val['utype'] =$val['send_type'];
                $val['avatar'] =Common::getAvatar($userlist['head_pic']);
                $val['time'] = strtotime($val['created_time']);
            }
        }
        $info = array('status'=>0,'msg'=>'','data'=>$obj);
      
        echo JSON::encode($info);
    }
    #-------------------------获取不同账号信息
    public function getuserlist($id,$type){
       $model = new Model();
        if($type==1){ 
            $other =$model->table("etp_enterprise")->fields('name as username,full_name as sign,head_pic')->where("id=".$id)->find();
        }else if($type==2){
            $other =$model->table("adm_manager")->fields('name,username,sign,head_pic')->where("id=".$id)->find();
            if(empty($other['username'])){
                $other['username'] =$other['name'];
            }
        }else{
            $other =$model->table("gov_user")->fields('name,username,sign,head_pic')->where("id=".$id)->find();
           
            if(empty($other['username'])){
                $other['username'] =$other['name'];
            }
           
        }
        return $other;
    }

    #---------------------设置已读消息
   public function inReadmessage()
   {
        $fromid = Req::args('fromid');
        $uid = Req::args('uid');
        $type = Req::args('type');
        $model = new Model();
        $up =$model->table("publicnews")->where("to_id=$uid and to_type=$type and fromid=".$fromid)->data(array('in_read'=>1))->update();
        if($up){
            $info = array('status'=>0,'msg'=>'修改成功'); 
        }else{
            $info = array('status'=>1,'msg'=>'修改失败'); 
        }
        echo JSON::encode($info);
   }
   #------------------------上传文件
   public function uploadimg(){
		$address_id=Req::args('address_id');
		$document_id=DocumentService::upload($address_id,"",$errmsg);
		$doc=(new DocumentEntity())->find($document_id);
	   // print_r($doc);die;
	    $info = array('status'=>1,'msg'=>'信息不完整！'); 
		if ($doc) {
			$info = array('code'=>0,'msg'=>'','data'=>array('src' =>$doc['txid'])); 
        }
		echo JSON::encode($info);die();
   }
   #-------------------------下载文件
   public function downfile(){

        $txid=Req::args('txid');

        $doc=(new DocumentEntity())->findWhere("txid='$txid'",'address_id'); 
        $addid =$doc['address_id'];
        $entity=new PrivatekeyEntity();
        $priKey=$entity->findWhere("address_id=$addid");
        if (!$priKey || !$priKey["privatekey"]) {
            print_r("文档密钥不完整，无法下载！");die();
        }
        
        $success=BlcHelper::downFile($addid,$priKey["privatekey"],$txid);
        if (!$success) {
            print_r("文档密钥错误，无法下载！");die();
        }
    }
    #----------------------聊天记录
    public function chatlog(){
        $this->redirect("chatlog",true,Req::args());
    }
    #----------------------聊天记录信息
    public function detail(){
      
        $uid =Req::args('uid');
        $toid =Req::args('id');
        $type =Req::args('type');
        $send_type =Req::args('send_type');
        $page =Req::args('page');
        $model = new Model();
        if( 'friend' == $type || 'govlist' == $type){
            $result =$model->table("publicnews")
            ->where("(send_id={$uid} and to_id={$toid}) or (send_id={$toid} and to_id={$uid})")->order('id asc')->findPage($page, 10, 1, true);//->findAll();
            foreach($result['data'] as $key=>&$val){
                $userlist= $this->getuserlist($val['send_id'],$val['send_type']);
                $val['username'] =$userlist['username'];
                $val['sign'] =$userlist['sign'];
                $val['avatar'] =Common::getAvatar($userlist['head_pic']);
                $val['time'] = strtotime($val['created_time']);
            }
            unset($result['html']);
            if( empty($result) ){
                $json =['code' => -1, 'data' => '', 'msg' => '没有记录'];
            }else{
                $json =['code' => 1, 'data' => $result['data'],'pages'=>$result['page']['totalPage'], 'msg' => 'success'];
            }

        }else if('group' == $type){

        }
        echo JSON::encode($json);
    }
    #-----------------------搜索企业信息
    public function search(){
        $model = new Model();
        $name =Req::args('name');
        $other =$model->table("etp_enterprise")->where("name like '%$name%'")->findALL();
        foreach($other as $k=>&$v){
            $v['username'] = $v['name'];
            $v['avatar'] = Common::getAvatar($v['head_pic']);
            $v['sign'] = $v['full_name'];  
        }
        echo JSON::encode($other);
    }

}
