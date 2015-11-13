<?php
require_once (dirname(__FILE__) . '/WeChatCallBack.php');
/**
 * echo server implemention
 *
 */

class WeChatCallBackJSZL extends WeChatCallBack{
       private $_event;         
       private $_content;       
       private $_eventKey;      
                    
       public function init($postObj) {                       
            if (false == parent::init($postObj)) {             
                interface_log ( ERROR, EC_OTHER, "init fail!");
                return false;        
            }         
                                         
             //获取event和eventkey
            if ($this->_msgType == 'event') {
                $this->_event = (string)$postObj->Event;
                $this->_eventKey = (string)$postObj->EventKey;
            } 

            //获取文本内容
            if ($this->_msgType == 'text') {
                $this->_content = (string)$postObj->Content;
            }

            return true;
       }

       public function process() {
           //只处理文本消息和自定义菜单消息
           if (!($this->_msgType != 'text' || $this->_msgType == 'event' )) {
               interface_log(DEBUG, 0, "msgType:" . $this->_msgType);
               return $this->makeHint ( "你发的不是文字或菜单消息" );
           }
           try {
               $STO = new SingleTableOperation("userinput", "ES");
               if ($this->_msgType == 'event' && $this->_event == 'CLICK') {
                   $mode = $this->_eventKey;

                   //更新用户mode
                   $ret = $STO->getObject(array("userId" => $this->_fromUserName));
                   if (!empty($ret)) {
                       $STO->updataObject(array('mode' => $mode), array("userId" => $this->_fromUserName));
                   } else {
                       $STO->addObject(array("userId" => $this->_fromUserName, 'mode' => $mode));
                   }

                   return $this->makeHint("模式设置成：" . $mode);
               } else {
                   $text = $this->_content;
                   $ret = $STO->getObject(array("userId" => $this->_fromUserName));
                   if (empty($ret)) {
                       $STO->addObject(array("userId" => $this->_fromUserName ));
                       return $this->makeHint($text);
                   } else {
                       $mode = $ret[0]['mode'];
                       $STO->updataObject(array('input' => $ret[0]['input']), array("userId" => $this->_fromUserName));

                       return $this->makeHint($text);
                   }
               }
           } catch (DB_Exception $e) {
			    interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
           }
       }




/*88	
	public function process(){
		if ($this->_msgType != 'text') {
			return $this->makeHint ( "你发的不是文字" );
		}
		try {
			$db = DbFactory::getInstance('ES');
			$sql = "insert into userinput (userId, input) values(\"" . $this->_fromUserName . "\", \"" . $this->_postObject->Content . "\")";
			interface_log(DEBUG, 0, "sql:" . $sql);			
			$db->query($sql);
			$STO = new SingleTableOperation("userinput", "ES");
			$ret = $STO->getObject(array("userId" => $this->_fromUserName));
			$out = "";
			foreach ($ret as $item) {
				$out .= $item['input'] . ", ";
			}
		} catch (DB_Exception $e) {
			interface_log(ERROR, EC_DB_OP_EXCEPTION, "query db error" . $e->getMessage());
		}
		return $this->makeHint ($out);
	}	

//输入啥返回啥
    public function process(){
        if($this->_msgType != 'text') {
            return $this->makeHint("只支持文本消息");
        }
        return $this->makeHint($this->_postObject->Content);
        //return $this->makeHint("shiwenzi");
    }
 */
}

?>
