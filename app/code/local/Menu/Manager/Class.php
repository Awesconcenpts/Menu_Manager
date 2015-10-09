<?php
class Menu_Manager_Class
{
	function toHtml(){
			$categories = Mage::getModel('catalog/category')
			->getCollection()
			->addAttributeToSelect('*')
			->addIsActiveFilter();
			$alldata=array();
			foreach($categories as $category){
			$alldata[]=array('name'=>$category->getName().' - ('.$category->getId().')	','type'=>'category','id'=>$category->getId());
			}
			$cmss = Mage::getModel('cms/page')->getCollection()
			  ->addFieldToFilter('is_active',1)
			  ->addFieldToFilter('identifier',array(array('nin'=>array('no-route','enable-cookies'))));
			foreach($cmss as $cms){
			$alldata[]=array('name'=>$cms->getTitle(),'type'=>'cms','id'=>$cms->getId());
			}
			$htm="<div  data-json='".json_encode($alldata)."'><ul>";
			foreach($alldata as $row){
			$htm.='<li class="auto"><a href="javascript:void(0)" nam="'.$row['name'].'" type="'.$row['type'].'" id="'.$row['id'].'">'.$row['name'].'</a></li>';
			}
			$htm.="</ul></div>";
			$htm.='<script type="text/javascript">
			$$("#url").invoke("observe","keyup",function(){
			$("menu_data").value="";
			var value=this.value;
			var bool=false;
			$$("li.auto").each(
			function (index) {
			var keys=index.down(0).readAttribute("nam");
			var s=new RegExp(value.toLowerCase(),"i");
			if(keys.toLowerCase().search(s)>=0){
			if(bool==false)bool=true;
			index.setStyle({display: "block"})//).invoke("setStyle", {display: "block"})
			}else{
			index.setStyle({display: "none"})//).invoke("setStyle", {display: "block"})
			}
			}
			);
			if(bool==true){
			
			$$("[data-json]").invoke("setStyle", {display: "block"})
			}
			});
			$$("li.auto>a").invoke("observe","click",function(){
			console.log(this);
			var name=this.readAttribute("nam");
			var type=this.readAttribute("type");
			var id=this.readAttribute("id");
			$("url").value=name;
			$("menu_data").value=type+"-"+id;
			$$("[data-json]").invoke("setStyle", {display: "none"})
			});
			</script>
			<style type="text/css">
			[data-json]{
			position:relative;
			display:none;
			}
			[data-json]>ul{
			height: auto;
			max-height:150px;
			width: 278px;
			float: left;
			overflow:hidden;
			overflow-y: auto;
			position: absolute;
			background-color: #FFF;
			border: 1px solid #999;
			
			}
			[data-json]>ul>li{
			margin:0px!important;
			padding:0px!important;
			display:none;
			}
			[data-json]>ul>li>a{
			padding:5px;
			float:left;
			height:auto;
			width:100%;
			text-decoration:none;
			
			}
			[data-json]>ul>li>a:hover{
			background-color:#ebebeb;
			text-decoration:none;
			}
			</style>
			
			
		';
        return $htm;
	}

}
?>