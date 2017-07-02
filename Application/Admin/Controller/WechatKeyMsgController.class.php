<?php
namespace Admin\Controller;

class WechatKeyMsgController extends CommonController {
	public function __construct(){
			parent::__construct();
			$this->model = D('XflWechatKeymsg');
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
				$nmap['msg'] = $nmap['key'] = array('like','%'.$search.'%');
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

	public function test(){
		dump($_POST);
	}
}
