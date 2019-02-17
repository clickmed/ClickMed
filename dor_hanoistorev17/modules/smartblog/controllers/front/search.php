<?php

include_once(dirname(__FILE__).'/../../classes/controllers/FrontController.php');
class smartblogsearchModuleFrontController extends smartblogModuleFrontController
{
    public $ssl = true;
    public $phpself = 'dorblogs';
    public function init(){
            parent::init();
    }
        
    public function initContent(){
           
           parent::initContent();
           $rer  =  SmartBlogPost::tagsPost('asd');
           $thumbWidth = Configuration::get('blogThumbListWidth');
            $thumbHeight = Configuration::get('blogThumbListHeight');
            $dorBlogsStyle  = Tools::getValue('dorBlogsStyle',Configuration::get('dorBlogsStyle'));
            if(isset($dorBlogsStyle) && ($dorBlogsStyle == 3 || $dorBlogsStyle == 4 || $dorBlogsStyle == 5)){
                $thumbWidth = 510;
                $thumbHeight = 620;
            }else{
                $thumbWidth = $thumbWidth != ""?$thumbWidth:875;
                $thumbHeight = $thumbHeight != ""?$thumbHeight:500;
            }
            $thumbMainWidth = $thumbWidth;
            $thumbMainHeight = $thumbHeight;
            $sizeThumb = $thumbWidth."x".$thumbHeight;
            $thumbWidth2 = $thumbWidth;
            $thumbHeight2 = $thumbHeight;

            if(isset($dorBlogsStyle) && ($dorBlogsStyle == 3 || $dorBlogsStyle == 4 || $dorBlogsStyle == 5)){
                if($limitShortDesc > 200) $limitShortDesc = 150;
                if($posts_per_page < 9) $posts_per_page = 9;
            }elseif(isset($dorBlogsStyle) && $dorBlogsStyle == 2){
                $thumbWidth2 = 370;
                $thumbHeight2 = 230;
            }
            $blogcomment = new Blogcomment();
                $result = '';
                $keyword = Tools::getValue('smartsearch');
                Hook::exec('actionsbsearch', array('keyword' => Tools::getValue('smartsearch')));
                $id_lang = (int)$this->context->language->id;
                $title_category = '';
                $posts_per_page = Configuration::get('smartpostperpage');
                $limit_start = 0;
                $limit = $posts_per_page;
                
                if((boolean)Tools::getValue('page')){
                $c = (int)Tools::getValue('page');
                    $limit_start = $posts_per_page * ($c - 1);
            }
                
                    $keyword = Tools::getValue('smartsearch');
                    $id_lang = (int)$this->context->language->id;
                $result = SmartBlogPost::SmartBlogSearchPost($keyword,$id_lang,$limit_start,$limit);
                
                $total = SmartBlogPost::SmartBlogSearchPostCount($keyword,$id_lang);
                $totalpages = ceil($total/$posts_per_page);
                $i = 0;
            $dataItems = array();
            if(!empty($result)){
                foreach($result as $key=>$item){
                    $pathImg = "smartblog/images/".$item['post_img'].".jpg";
                    if($key==0){
                        $thumbWidth = $thumbMainWidth;
                        $thumbHeight = $thumbMainHeight;
                        $item['thumb_image'] = DorImageBase::renderThumb($pathImg,$thumbWidth,$thumbHeight);
                        if(isset($dorBlogsStyle) && ($dorBlogsStyle == 3 || $dorBlogsStyle == 4 || $dorBlogsStyle == 5)){
                            $item['thumb_image'] = DorImageBase::renderThumbMasonry($pathImg,$thumbWidth,$thumbHeight);
                        }
                    }else{
                        $thumbWidth = $thumbWidth2;
                        $thumbHeight = $thumbHeight2;
                        $item['thumb_image'] = DorImageBase::renderThumb($pathImg,$thumbWidth,$thumbHeight);
                        if(isset($dorBlogsStyle) && ($dorBlogsStyle == 3 || $dorBlogsStyle == 4 || $dorBlogsStyle == 5)){
                            $item['thumb_image'] = DorImageBase::renderThumbMasonry($pathImg,$thumbWidth,$thumbHeight);
                        }
                    }
                    $to[$i] = $blogcomment->getToltalComment($item['id_post']);
                    $dataItems[$i] = $item;
                    $i++;
                }
                $j = 0;
                foreach($to as $item){
                    if($item == ''){
                        $dataItems[$j]['totalcomment'] = 0;
                    }else{
                        $dataItems[$j]['totalcomment'] = $item;
                    }
                    $j++;
                }
            }
            $this->context->smarty->assign( array(
                                            'page_name'=>"dorSmartBlogs",
                                            'modules_dir'=>_PS_MODULE_DIR_,
                                            'postcategory'=>$dataItems,
                                            'dorBlogsStyleCss'=>'dorStyleBlog'.$dorBlogsStyle,
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
                                            'smartsearch'=>Tools::getValue('smartsearch'),
                                            'pagenums' => $totalpages - 1,
                                            'totalpages' =>$totalpages
                                            ));

       $template_name  = 'smartblog/searchresult.tpl';

            $this->setTemplate($template_name);
    }
}