<?php

include_once(dirname(__FILE__).'/../../classes/controllers/FrontController.php');
if (!class_exists( 'DorImageBase' )) {     
    require_once (_PS_ROOT_DIR_.'/override/Dor/DorImageBase.php');
}
class smartblogtagpostModuleFrontController extends smartblogModuleFrontController
{
	public $ssl = true;
    public $phpself = 'dorblogs';
	public function init(){
            parent::init();
	}
        
	public function initContent(){
           parent::initContent();
            $blogcomment = new Blogcomment();
                $result = '';
                $keyword = Tools::getValue('tag');
                
                $id_lang = (int)$this->context->language->id;
                $title_category = '';
                $posts_per_page = Configuration::get('smartpostperpage');
                $limit_start = 0;
                $limit = $posts_per_page;
                
                if((boolean)Tools::getValue('page')){
	            $c = (int)Tools::getValue('page');
                    $limit_start = $posts_per_page * ($c - 1);
	        }
                
                    $keyword = Tools::getValue('tag');
                    if($keyword){
                        $keyword = str_replace("-", " ", $keyword);
                    }
                    $id_lang = (int)$this->context->language->id;
                $result  =  SmartBlogPost::tagsPost($keyword,$id_lang);
                $total = count($result);
                $totalpages = ceil($total/$posts_per_page);
                $i = 0;
            if(!empty($result)){
                $dataItems = array();
                foreach($result as $item){
                    $pathImg = "smartblog/images/".$item['post_img'].".jpg";
                    $width=850;$height=450;
                    $images = DorImageBase::renderThumb($pathImg,$width,$height);
                    $item['thumb_image'] = $images;
                    $to[$i] = $blogcomment->getToltalComment($item['id_post']);
                    $dataItems[$i] = $item;
                   $i++;
                }
                
                $result = $dataItems;
                $j = 0;
                foreach($to as $item){
                    if($item == ''){
                        $result[$j]['totalcomment'] = 0;
                    }else{
                        $result[$j]['totalcomment'] = $item;
                    }
                    $j++;
                }
            }

            $this->context->smarty->assign(array(
                                            'page_name'=>"dorSmartBlogs",
                                            'postcategory'=>$result,
                                            'modules_dir'=>_PS_MODULE_DIR_,
                                            'title_category'=>$title_category,
                                            'smartshowauthorstyle'=>Configuration::get('smartshowauthorstyle'),
                                            'limit'=>isset($limit) ? $limit : 0,
                                            'limit_start'=>isset($limit_start) ? $limit_start : 0,
                                            'c'=>isset($c) ? $c : 1,
                                            'total'=>$total,
                                            'smartshowviewed' => Configuration::get('smartshowviewed'),
                                            'smartcustomcss' => Configuration::get('smartcustomcss'),
                                            'smartshownoimg' => Configuration::get('smartshownoimg'),
                                            'smartshowauthor'=>Configuration::get('smartshowauthor'),
                                            'smartblogliststyle' => Configuration::get('smartblogliststyle'),
                                            'post_per_page'=>$posts_per_page,
                                            'pagenums' => $totalpages - 1,
                                            'totalpages' =>$totalpages
                                            ));

       $template_name  = 'smartblog/tagresult.tpl';

            $this->setTemplate($template_name);
	}
}