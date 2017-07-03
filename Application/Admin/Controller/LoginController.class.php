<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{

	public function __construct(){
		parent::__construct();
		$this->model = D('admin');
	}

	//登录
	public function login(){
		if(IS_POST){
			$Verify = new \Think\Verify();
			$res = $Verify->check(I('code'));

			if (!$res) {
				$this->error('验证码错误');
				exit;
			}
			if (empty(I('admin_name'))) {
				$this->error('请填写账号');
				exit;
			}
			if (empty(I('password'))) {
				$this->error('请填写账号');
				exit;
			}
			$map['name'] = I('admin_name');
			$pass = I('password');
			$info = $this->model->where($map)->find();
			if ($info) {
				$res = password_verify ($pass,$info['pass']);
				if ($res) {
					session('admininfo',$info);
					$this->redirect('Index/index');
				}else{
					$this->error('用户名不存在或密码错误');
				}
			}else{
				$this->error('用户名不存在或密码错误');
			}
		}else{
			$this->display();
		}
	}

	//退出登录
	public function logout(){
		session('admininfo',null);
		$this->redirect('Login/login');
	}

	//验证码
	public function code(){
		$config = array(
			'fontSize'	=>	30,
			'length'	=>	4,
			'useNoise'	=>	true,
			'useCurve'	=> false,
		);

		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}

}
