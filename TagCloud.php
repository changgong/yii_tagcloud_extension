<?php
Yii::import('zii.widgets.CPortlet');
class TagCloud extends CPortlet{

	//控件宽度
	public $width = '152';
	//控件高度
	public $height = '170';
	//背景颜色
	public $bgcolor = '#ffffff';
	//默认的标签颜色
	public $tcolor = '0x1e73d5';
	//设定为true的时候标签会均匀的分布在球的表面
	public $distr = 'true';
	//鼠标放在标签上的颜色
	public $hicolor = '0x111111';
	//转动的速度，数默认就是100
	public $tspeed = 300;
	//背景透明
	public $transparent = true;
	//标签字体大小
	public $fontSize = 20;
	//标题
	public $title = '标签云';
	//标签的字段名称
	public $name = 'name';
	//标签的url连接地址
	public $url = 'url';
	//标签url连接是否新建页
	public $target = 'target';
	//标签数组
	public $tags;

	protected $assets;


	public function init(){
		parent::init();
		$assets_files = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
		$this->assets = Yii::app()->assetManager->publish($assets_files);
		//注册script file
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile($this->assets.'/swfobject.js');
	}
	
		
	protected function renderContent(){
		echo '<div id="animateTagCloud">if you can see this ,it means there are some errors.</div>';
		//判断tags数组是否为空
		if(!empty($this->tags)){
			$jstags = $this->getTags($this->tags);
			$jsScripts = 'var so = new SWFObject("'.$this->assets.'/tagcloud.swf","tagcloud","'.$this->width.'","'.$this->height.'","7","'.$this->bgcolor.'");';
			if($this->transparent){
				$jsScripts .= 'so.addParam("wmode","transparent");';
			} 
			$jsScripts .= 'so.addVariable("mode","tags");';
			$jsScripts .= 'so.addVariable("distr", '.$this->distr.');';
			$jsScripts .= 'so.addVariable("tcolor","'.$this->tcolor.'");';
			$jsScripts .= 'so.addVariable("hicolor","'.$this->hicolor.'");';
			$jsScripts .= 'so.addVariable("tspeed","'.$this->tspeed.'");';
			$jsScripts .= 'so.addVariable("tagcloud","'.$jstags.'");';
			$jsScripts .= 'so.write("animateTagCloud");';

			Yii::app()->clientScript->registerScript('tagcloud',$jsScripts);
		}
	}

	private function getTags($tags){
		$tagscript = '<tags>';
		$name = '未设置';
		$url = '';
		$target = '';
		foreach($tags as $tag){
			//获取target
			if(isset($tag[$this->target])){
				$target = $tag[$this->target] ? '_blank': '';
			}
			//获取标签名
			if(isset($tag[$this->name])){
				$name = $tag[$this->name];
			}
			//获取标签地址
			if(isset($tag[$this->url])){
				$url = $tag[$this->url];
			}
			$tagscript .= "<a href='http://".$url."' style='".$this->fontSize."' target='".$target."'>".$name."</a>";
		}
		$tagscript .= '</tags>';
		return urlencode($tagscript);
	}
}

