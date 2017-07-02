<?php
namespace Admin\Controller;

class WechatAutoMsgController extends CommonController {
	public function __construct(){
			parent::__construct();
			$this->model = D('XflWechatAutomsg');
		}

	public function index(){
		$res = $this->model->where('id=1')->find();
		$this->assign('data',$res['msg']);
		$this->display();
	}

	public function update(){
		parent::edit('WechatAutoMsg/index');
	}
}
