<?php
namespace Admin\Model;
use Think\Model;
class XflWechatKeymsgModel extends Model{

		public function getOne($id){
			$msgList = $this->where("id = {$id}")->find();
			return $msgList;
		}
}
