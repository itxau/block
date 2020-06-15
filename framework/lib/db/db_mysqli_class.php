<?php


/**
 * 用单列模式对mysql数据库的操作类
 *
 * @author:sun xian gen(baxgsun@163.com)
 * @class DBMysql
 */
class DBMysqli
{
    private static $dbo;
    private static $conn;
    private static $dbinfo;
    private function __construct(){}
    private function __clone(){}
    /**
     * 构造
     * @access public
     * @return mixed
     */
    public function __destruct()
    {
        mysqli_close(self::$conn);
    }
    /**
     * 取得实例
     *
     * @access public
     * @param mixed $dbinfo
     * @return mixed
     */
    public static function getInstance($dbinfo)
    {
        self::$dbinfo = $dbinfo;
        if(!self::$dbo instanceof self){
            // $host=Crypt::decode($dbinfo['host'],"host");
            // $user=Crypt::decode($dbinfo['user'],"user");
            // $password=Crypt::decode($dbinfo['password'],"password");
            // $name=Crypt::decode($dbinfo['name'],"name");
            $host=$dbinfo['host'];
            $user=$dbinfo['user'];
            $password=$dbinfo['password'];
            $name=$dbinfo['name'];
            self::$conn=mysqli_connect($host,$user,$password,$name,$dbinfo['port']);

            if(self::$conn===false){
                trigger_error('<span style="color:red;border:red 1px solid;padding:0.5em;">无法连接数据库，请检查数据库连接参数！</span>', E_USER_ERROR);
                exit;
            }
            $charset = isset($dbinfo['charset'])?$dbinfo['charset']:'utf8';
            mysqli_query(self::$conn,"SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            mysqli_query(self::$conn,"set names '$charset'");
            mysqli_select_db(self::$conn,$dbinfo['name']);
            self::$dbo=new self();
        }
        return self::$dbo;
    }
    /**
     * 检测是否存在
     *
     * @access public
     * @param mixed $name
     * @return mixed
     */
    public function exist($name)
    {
        $table = $this->doSql("SHOW TABLES LIKE '".$name."'");
        if($table) return mysqli_num_rows($table)==1?true:false;
        else return false;
    }
    public function escape_string($str){
        return self::$conn->real_escape_string($str);
    }
    /**
     * 执行SQL
     *
     * @access public
     * @param mixed $sql
     * @return mixed
     */
    public function doSql($sql)
    {
        //更好的处理表前缀
        //$mainStr = ltrim(self::$dbinfo['tablePre'],'Core_');
        $sql = preg_replace('/(\s+|,|`)(Core_)+/i','$1'.self::$dbinfo['tablePre'],$sql);
        $sql=trim($sql);
        if(DEBUG){
            $doSqlBeforTime = microtime(true);
            $result=mysqli_query(self::$conn,$sql);
            $useTime = microtime(true) - $doSqlBeforTime;
            Core::setSqlLog($sql,$useTime);
        }else {
            $result=mysqli_query(self::$conn,$sql);
        }
        //查询不成功时返回空数组
        $rows=array();
        //分析出读写操作
        if(preg_match("/^(select|show|\(select)(.*)/i",$sql)==0){

            if($result){
                if(stripos($sql,'insert')!==false){
                    return $this->lastId();
                }else if(stripos($sql,'update')!==false){
                    return  mysqli_affected_rows(self::$conn);
                }
                return $result;
            }
            return false;
        }else{
            if($result){
                while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) $rows[]=$row;
                mysqli_free_result($result);
                return $rows;
            }else if(DEBUG){

                throw new Exception("SQLError:{$sql} ,".mysqli_error(self::$conn)."", E_USER_ERROR);
            }
            return array();
        }
    }
    /**
     * 最后一条ID
     *
     * @access public
     * @return mixed
     */
    public function lastId()
    {
        return ($id = mysqli_insert_id(self::$conn)) >= 0 ? $id : @mysqli_result(mysqli_query("SELECT last_insert_id()"), 0);
    }
    public function beginTransaction(){
    }

    public function rollBack(){
    }

    public function commit(){
    }
    /**
     * 取得表信息
     *
     * @access public
     * @param mixed $table
     * @return mixed
     */
    public function getTableInfo($table)
    {
        $table = explode(' ', $table);
        $table = $table[0];
        $sql="show fields from `{$table}`";
        $result = $this->doSql($sql);
        if(!$result) return false;
        $pri = '';
        $fields = array();
        foreach ($result as $row) {
            if($row['Key']=='PRI')  $pri= $row['Field'];
            $fields[$row['Field']] = $row['Type'];
        }
        return array('primary_key'=>$pri,'fields'=>$fields);
    }
}
