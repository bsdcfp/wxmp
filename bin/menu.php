<?php
require_once dirname(__FILE__) . '/../common/Common.php';
require_once dirname(__FILE__) . '/../class/menuStub.php';

interface_log(DEBUG, 0, "***start menu**");
$menuData = array(
	'button'=>array(
		array(
            'type' => 'view',
			'name' => "首页",
            'url' => 'http://123.57.86.121'
		),
		array(
			'name' => "推荐",
			'sub_button' => array(
				array(
					'type' => 'click',
					'name' => '毛遂自荐',
					'key' => 'V1001_HELLO_WORLD'
				),
				array(
					'type' => 'click',
					'name' => '推荐他人',
					'key' => 'V1001_GOOD'
				)
			)
		),
		array(
					'type' => 'click',
					'name' => '我们',
					'key' => 'us'
		),
	)
);


/****
$menuData = array(
	'button'=>array(
		array(
			'type' => 'click',
			'name' => 'append模式',
			'key' => 'APPEND'
		),
		array(
			'type' => 'click',
			'name' => '正常模式',
			'key' => 'NORMAL'
		),
		
	)
);
$menuData = array(
	'button'=>array(
		array(
			'type' => 'click',
			'name' => '下一步',
			'key' => 'NEXT'
		),
		array(
			'name' => '下注',
			'sub_button' => array(
				array(
					'type' => 'click',
					'name' => '100金币',
					'key' => 'CHIPIN_100'
				),
				array(
					'type' => 'click',
					'name' => '50金币',
					'key' => 'CHIPIN_50'
				),
				array(
					'type' => 'click',
					'name' => '20金币',
					'key' => 'CHIPIN_20'
				),
				array(
					'type' => 'click',
					'name' => '10金币',
					'key' => 'CHIPIN_10'
				),
			)
		),
		array(
			'name' => "使用道具",
			'sub_button' => array(
				array(
					'type' => 'click',
					'name' => '死神之门',
					'key' => 'PUTMAGIC_SSZM'
				),
				array(
					'type' => 'click',
					'name' => '重获新生',
					'key' => 'PUTMAGIC_CHXS'
				),
				array(
					'type' => 'click',
					'name' => '壶底抽薪',
					'key' => 'PUTMAGIC_HDCX'
				),
				array(
					'type' => 'click',
					'name' => '邪神附体',
					'key' => 'PUTMAGIC_XSFT'
				),
			)
		),
	)
);
*****/


$ret = menuStub::create('ABC', $menuData);
if(false === $ret) {
	interface_log(DEBUG, 0, "create menu fail!");
	echo "create menu fail!\n";
} else {
	interface_log(DEBUG, 0, "creat menu success");
	echo "create menu success!\n";
}
interface_log(DEBUG, 0, "***end menu***");

?>
