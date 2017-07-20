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
			$arrDpt = implode(",",$_SESSION['dptid']);
			$authDpt = " in($arrDpt) ";
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
}
?>