<?php
namespace Admin\Controller;

class WechatFollowMsgController extends CommonController {
	public function __construct(){
			parent::__construct();
			$this->model = D('XflWechatFollowmsg');
		}

	public function index(){
		$res = $this->model->where('id=1')->find();
		$this->assign('data',$res['msg']);
		$this->display();
	}

	public function update(){
		parent::edit('WechatFollowMsg/index');
	}
}
