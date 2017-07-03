<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
	protected $model;

	/**
	*[常规数据添加]
	*/
	public function add($url='',$arr=array(),$trans=0){
			if(IS_POST){	
				if($data = $this->model->create()){
					if( $this->model->add($data) ){
						if($trans)
							$this->model->commit();
						$this->success('添加成功',U($url,$arr));
						exit;
					}
				}
				$error = $this->model->getError();
				$this->error($error);
				exit;
			}
	}


	/**
	*[常规数据修改]
	*/
	public function edit( $url='', $arr=array(),$trans=0 ){
		if(IS_POST){
			$p = I('post.p',0);
			if($data = $this->model->create()){
				if( $this->model->save($data)!==false ){
					if($trans)
						$this->model->commit();
					$arr = array_merge(array('p'=>$p),$arr);
					$this->success('修改成功',U($url, $arr) );
					exit;
				}
				$error = $this->model->getError();
				$this->error($error);
				exit;
			}
		}
	}

	/**
	*[分页]
	*/
	public function page($where = '',$order = ''){

		if (!empty($_GET['map'])){
			$map = urldecode($_GET['map']);
			$map = json_decode($map,true);
			$where = $map;
		}

		$count      = $this->model->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list = $this->model->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();

		$Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录 第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');  
	    $Page->setConfig('prev', '上一页');  
	    $Page->setConfig('next', '下一页');  
	    $Page->setConfig('last', '末页');  
	    $Page->setConfig('first', '首页');  
	    $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');  
	    $Page->lastSuffix = false;//最后一页不显示为总页数 

	    $arr['map'] = urlencode(json_encode($where));
		$Page->parameter = $arr;

	    $show       = $Page->show();// 分页显示输出
		$this->assign('msgList',$list);// 赋值数据集
		$this->assign('count',$count);
		$this->assign('page',$show);// 赋值分页输出
	}

	// 判断是否登录
		public function _initialize()
	   { 
	   		//做权限的认证
	   		//判断用户是否已经登录
	   		if(!session('?admininfo')) { 
	   			$this->redirect('Login/login');
	   		}
	   }
}
