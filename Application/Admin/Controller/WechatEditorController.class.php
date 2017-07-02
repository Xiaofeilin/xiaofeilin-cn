<?php
namespace Admin\Controller;

class WechatEditorController extends CommonController {

	public function __construct(){
			parent::__construct();
			$this->model = D('XflWechatArticles');
		}

    public function index(){
    	$this->display();
    }

    public function edit(){
		$id = I('get.id','');
		parent::edit('',array('id'=>$id));
		$data = $this->model->getOne($id);
		$this->assign('msgList',$data);
		$this->display();
    }

    public function upload(){    
	    $upload = new \Think\Upload();// 实例化上传类    
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->saveName = array('date','Y-m-d-H-i-s');
	    $upload->autoSub = true;
	    $upload->subName = array('date','Ymd');    
	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型    
	    $upload->rootPath  =      './Public/Uploads/'; // 设置附件上传根目录    
	    $upload->savePath  =      ''; // 设置附件上传目录    
	    // 上传文件     
    	$info   =   $upload->uploadOne($_FILES['img']);   
	    if(!$info) {// 上传错误提示错误信息        
			$this->error($upload->getError());    
	    }else{// 上传成功        
	    	$_POST['img'] = $info['savepath'].$info['savename'];
	    	parent::add('WechatEditor/index');
	    }
	}
}
