<?php  
class CommonService{
	public static function autoload(){
		spl_autoload_register(function ($class_name) {
		    if (file_exists("./model/".$class_name.".class.php")) 
				include_once './model/'.$class_name.'.class.php';
			else{
				echo "没有找到相关的类文件<br>";
				return false;
			}
		});
	}

	public static function autoloadController(){
		spl_autoload_register(function ($class_name) {
		    if (file_exists("./../model/".$class_name.".class.php")) 
				include_once './../model/'.$class_name.'.class.php';
			else{
				echo "没有找到相关的类文件<br>";
				return false;
			}
		});
	}

	public static function getAuth(){
		if ($_SESSION['user'] == 'admin') 
			$authDpt = "";
		else{
			if(!empty($_SESSION['dptid'])){
				$arrDpt = implode(",",$_SESSION['dptid']);
				$authDpt = " in($arrDpt) ";
			}else{
				echo "未分配管理部门权限,请联系计量室,修改用户配置
				<a href='172.20.32.79/dev'>重新登录</a>
				";
				die;
			}
		}
		return $authDpt;
	}

	public static function getCookieval($key){
		if (empty($_COOKIE[$key])) {
			return "";
		}else{
			return $_COOKIE[$key];
		}
	}

	//验证是否登录，若未登录则返回登录页面
	public static function checkValidate(){
		if(empty($_SESSION['user'])){
			header("location:./login.php");
			exit();
		}	
	}

	public static function sqlTgther($_arr, $arr){
		foreach ($arr as $k => $v) {
			array_push($_arr, $v == '' ? "`$k` = null" : "`$k` = '{$v}'");
		}
		return implode(',',$_arr);
	}

	public static function dump($arr){
		echo "<pre>";
		print_R($arr);
		die;
	}

	public static function downInstr(){
		$file_name = "1.pdf";     //下载文件名    
		$file_dir = dirname(__file__)."/xls/"; //下载文件存放目录    
		//检查文件是否存在        
	    header("Content-Type: application/force-download");//强制下载
		//给下载的内容指定一个名字
		header("Content-Disposition: attachment; filename=仪表管理系统说明书.pdf"); 
		readfile($file_dir.$file_name); 
	}

	public static function getTheMonth($date){
	   $firstday = date('Y-m-01', strtotime($date));
	   $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
	   return ['first' => $firstday, 'last' => $lastday];
	}

	public static function mergeCountAndMonth($arr){
		$_arr = [];
		foreach ($arr as $v) {
			$_arr[$v['month']] = $v['count']; 
		}
		return $_arr;
	}


}
?>