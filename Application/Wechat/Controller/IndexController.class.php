<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	// 接入
	    public function valid()
	    {
			$this->wechat = new \Gaoming13\WechatPhpSdk\Wechat(array(		
				'appId' => 'wx4edc4697c515735b',	
				'token' => 	'xfl777',
			));

			// 获取微信消息
			$this->msg = $this->wechat->serve();

			// 回复微信消息
			switch ($this->msg->MsgType) {
				case 'text':
					$this->handlerTextMsg();
					break;
				
				case 'event':
					$this->handlerEventMsg();
					break;

				default:
					# code...
					break;
			}
	    }

	    // 生成菜单
	    public function makeMenu()
	    {
	    	$api = new \Gaoming13\WechatPhpSdk\Api(
				array(
				    'appId' => 'wx4edc4697c515735b',
				    'appSecret'	=> '7edd5e7dc01d1348065136d8f9b74476',
				    'get_access_token' => function(){
				        // 用户需要自己实现access_token的返回
				        return S('wechat_token');
				    },
				    'save_access_token' => function($token) {
				    	// 用户需要自己实现access_token的保存
				        S('wechat_token', $token);
				    }
				)
			);

	    	$res = $api->create_menu('
				{
				    "button":[
				        {   
				          "type":"click",
				          "name":"今日推送",
				          "key":"Propelling_Movemen"
				        },
				        {
				            "name":"菜单",
				            "sub_button":[
				                {
				                    "type":"click",
				                    "name":"人品测试",
				                    "key":"Test_Game"
				                }
				            ]
				       	},
				       	{
				            "name":"其他",
				            "sub_button":[
				                {
				                    "type":"view",
				                    "name":"网站首页",
				                    "url":"http://xiaofeilin.cn/"
				                }
				            ]
				       	}
				    ]
				}');

	    	dump($res);
	    }

	    //处理文本消息
	    protected function handlerTextMsg()
	    {
	    	switch ($this->msg->Content) {
	    		case '你好':
	    			$this->wechat->reply('你好');
	    			break;
	    		
	    		default:

	    			break;
	    	}
	    }

	    //处理事件消息
	    protected function handlerEventMsg()
	    {
	    	switch ($this->msg->Event) {
	    		case 'CLICK':
	    			$this->handlerMenuMsg();
	    			break;
	    		//用户关注了公众号
				case 'subscribe':
					$this->wechat->reply('感谢关注');
					break;
	    		default:
	    			# code...
	    			break;
	    	}
	    }

	    //判断用户点击是哪个菜单
	    protected function handlerMenuMsg()
	    {
	    	switch ($this->msg->EventKey) {
	    		case 'Propelling_Movemen':
	    			$this->wechat->reply(array(
						'type' => 'news',
						'articles' => array(
							 array(
								'title' => '蘑菇头表情包',								//可选
								'description' => '点击不能下载',						//可选
								'picurl' => 'http://xiaofeilin.cn/static/img/wechat/20170411191916.jpg',	//可选
								'url' => 'http://www.baidu.com/'						//可选
							 ),
							array(
								'title' => '滑稽天下',
								'description' => '稽毒犬',
								'picurl' => 'http://xiaofeilin.cn/static/img/wechat/$6Z9KT735@`UL6RTO7ZQ4BB.jpg',
								'url' => 'http://www.baidu.com/'
							),
							array(
								'title' => '面带微笑',
								'description' => '汪星人',
								'picurl' => 'http://xiaofeilin.cn/static/img/wechat/$2W@DW9[HO5JN[WJEH)SE@E.jpg',
								'url' => 'http://www.baidu.com/'
							)
						),
					));
	    			break;
	    		
	    		case 'Test_Game':
	    			$ran = rand(0,10000);
	    			if ($ran == 1) {
	    				$res = '恭喜,你是欧洲人!!';
	    			}else{
	    				$res = '恭喜,你是非洲人!';
	    			}
	    			$this->wechat->reply($res);
	    			break;
	    		default:
	    			# code...
	    			break;
	    	}
	    }
    }
}
