<?php  
header("Content-type:text/html;Charset=utf-8");
class Common{
	public static function autoload($class_name){
		if (file_exists("./".$class_name.".class.php")) 
			include './'.$class_name.'.class.php';
		else{
			echo "没有找到相关的类文件<br>";
			return false;
		}
	}

	public static function register(){
		spl_autoload_register('self::autoload');
	}
}

Common::register();
$stu = new Student;
?>