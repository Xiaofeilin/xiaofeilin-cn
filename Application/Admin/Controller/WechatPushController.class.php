<?php
namespace Admin\Controller;

class WechatPushController extends CommonController {
	public function __construct(){
			parent::__construct();
			$this->model = D('XflWechatArticles');
		}

	public function index(){
		if(IS_POST){
			$keytype = $_POST['keytype'];
			$mindate = $_POST['mindate'];
			$maxdate = $_POST['maxdate'];
			$search = $_POST['search'];
			$map = array();
			if (!empty($keytype)) {
				$map[$keytype] = array('like','%'.$search.'%');
			}else{
				$nmap['title'] = $nmap['description'] = $nmap['description'] = array('like','%'.$search.'%');
				$nmap['_logic'] = 'OR';
				$map['_complex'] = $nmap;
			}
			if (!empty($mindate) && !empty($maxdate)) {
				$mindate .= ' 0:0:0';
				$maxdate .= ' 23:59:59';
				$map['addtime'] = array('between',array($mindate,$maxdate));
			}
		}
		parent::page($map);
		$this->display();
	}

	public function add(){
		if (IS_POST) {
			parent::add();
		}
		$this->display();
	}

	public function edit(){
		$id = I('get.id','');
		if(!empty($_FILES['img'])){
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
		    }
		}
		parent::edit('',array('id'=>$id));
		$data = $this->model->getOne($id);
		$this->assign('msgList',$data);
		$this->display();
	}

	public function del(){
		$id = I('get.id','');
		$res = $this->model->delete($id);
		if ($res) {
			echo $res;
		}
	}

	public function datadel(){
		$id = I('post.id','');
		$res = $this->model->delete($id);
		if ($res) {
			echo $res;
		}
	}

	public function isuse(){
		$data = array();
		$data['id'] = I('get.id','');
		$data['state'] = I('get.state','');
		$res = $this->model->save($data);
		if ($res) {
			echo $res;
		}
	}

	public function look(){
		$id = I('get.id','');
		$data = $this->model->getOne($id);
		$this->assign('msgList',$data);
		$this->display();
	}
}
