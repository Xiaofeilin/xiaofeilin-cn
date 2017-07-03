<?php
namespace Admin\Controller;

class IndexController extends CommonController {
    public function index(){
        $this->display();
    }

    public function welcome(){
        $this->display();
    }

    public function editor(){
    	$this->display();
    }

    //清理缓存 
	private function _deleteDir($R){
		$handle = opendir($R);
		while(($item = readdir($handle)) !== false){
			if($item != '.' and $item != '..'){
				if(is_dir($R.'/'.$item)){
					$this->_deleteDir($R.'/'.$item);
				}else{
					if(!unlink($R.'/'.$item))
					die('error!');
				}
			}
		}
		closedir( $handle );
		return rmdir($R); 
	}
	//清理缓存 执行方法
	public function clearRuntime(){
		$R = $_GET['path'] ? $_GET['path'] : RUNTIME_PATH;
		if($this->_deleteDir($R))
			$this->success('清理完成!');
	}
}
