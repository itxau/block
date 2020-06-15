<?php


/**
 * 用单列模式对mysql数据库的操作类
 *
 * @author:sun xian gen(baxgsun@163.com)
 * @class DBMysql
 */
class DBMysql
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
		mysql_close(self::$conn);
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
		if(!self::$dbo instanceof self)
		{
			// $host=Crypt::decode($dbinfo['host'],"host");
            // $user=Crypt::decode($dbinfo['user'],"user");
            // $password=Crypt::decode($dbinfo['password'],"password");
			// $name=Crypt::decode($dbinfo['name'],"name");
			$host=$dbinfo['host'];
            $user=$dbinfo['user'];
            $password=$dbinfo['password'];
            $name=$dbinfo['name'];
            self::$conn=mysqli_connect($host,$user,$password,$name,$dbinfo['port']);

			//self::$conn=mysql_connect($dbinfo['host'].':'.$dbinfo['port'],$dbinfo['user'],$dbinfo['password']);

			if(self::$conn===false)
			{
				trigger_error('<span style="color:red;border:red 1px solid;padding:0.5em;">无法连接数据库，请检查数据库连接参数！</span>', E_USER_ERROR);
				exit;
			}
			$charset = isset($dbinfo['charset'])?$dbinfo['charset']:'utf8';
			mysql_query("set names '$charset'");
            mysql_query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
			mysql_select_db($dbinfo['name'],self::$conn);
			self::$dbo=new self();
		}
		return self::$dbo;
	}
    public function escape_string($str){
        return mysql_real_escape_string($str,self::$conn);
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
		if(is_resource($table)) return mysql_num_rows($table)==1?true:false;
		else return false;
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
			$result=mysql_query($sql,self::$conn);
			$useTime = microtime(true) - $doSqlBeforTime;
			Core::setSqlLog($sql,$useTime);
		}else {
			$result=mysql_query($sql,self::$conn);
		}
		//查询不成功时返回空数组
		$rows=array();
		//分析出读写操作 
		if(preg_match("/^(select|show|\(select)(.*)/i",$sql)==0)
		{

			if($result)
			{
				if(stripos($sql,'insert')!==false)
				{
					return $this->lastId();
				}else if(stripos($sql,'update')!==false){
					return  mysql_affected_rows();
				}
				return $result;
			}
			return false;
		}
		else
		{

			if(is_resource($result))
			{

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)) $rows[]=$row;
				mysql_free_result($result);
				return $rows;
			}
			else if(DEBUG)
			{

				throw new Exception("SQLError:{$sql} ,".mysql_error()."", E_USER_ERROR);
			}
			return array();
		}
	}

    public function beginTransaction(){
    }

    public function rollBack(){
    }

    public function commit(){
    }

    /**
     * 最后一条ID
     *
     * @access public
     * @return mixed
     */
	public function lastId()
	{
		return ($id = mysql_insert_id(self::$conn)) >= 0 ? $id : @mysql_result(mysql_query("SELECT last_insert_id()"), 0);
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
