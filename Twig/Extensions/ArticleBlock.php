<?php

namespace Xi\Bundle\ArticleBundle\Twig\Extensions;

use \Twig_Environment,
     Xi\Bundle\ArticleBundle\Entity\Block,
     Xi\Bundle\ArticleBundle\Service\BlockService;

class ArticleBlock extends \Twig_Extension
{
    const TYPE_INTRODUCTION = 'introduction';
    const TYPE_CONTENT =      'content';
    
    /**
     * @var Twig_Environment
     */
    protected $twig;
    
    /**
     * @var BlockService
     */
    protected $blockService;

     
    /**
     * @var array 
     */
    protected $config;
    
    /**
     * @param Twig_Environment $twig
     * @param BlockService $blockService
     * @param array $config 
     */
    public function __construct(Twig_Environment $twig, BlockService $blockService, $config)
    {
        $this->twig         = $twig;
        $this->blockService = $blockService;
        $this->config       = $config;
    }
    
    /**
     * @return array 
     */
    public function getGlobals()
    {
        return array(
            'articleAdminParentLayout' => $this->config['admin_parent_layout'],
            'articleParentLayout'      => $this->config['parent_layout'],
        );
    }
    
    /**
     *  @return array
     */
    public function getFunctions()
    {
        return array(
            'xi_article_block_introduction' => new \Twig_Function_Method(
            $this, 'articleBlockIntroduction', array('is_safe' => array('html'))
            ),
            'xi_article_block_introduction_preview' => new \Twig_Function_Method(
            $this, 'articleBlockIntroductionPreview', array('is_safe' => array('html'))
            ),   
            'xi_article_block_content' => new \Twig_Function_Method(
            $this, 'articleBlockContent', array('is_safe' => array('html'))
            ),
            'xi_article_block_content_preview' => new \Twig_Function_Method(
            $this, 'articleBlockContentPreview', array('is_safe' => array('html'))
            ),
            'xi_article_wrap_article_to_new_block' => new \Twig_Function_Method(
            $this, 'wrapArticleToNewBlock', array('is_safe' => array('html'))
            ),
            'xi_content_block' => new \Twig_Function_Method(
            $this, 'contentBlock', array('is_safe' => array('html'))
            ),            
        );
    }
    
    private function renderBlock($block, $params)
    {
        return $this->twig->render('XiArticleBundle:Block:block.html.twig',
            array('block' => $this->fetchOrCreateBlock($block), 'params' => $params));
    }
    
    /**
     * @param string/Block $block
     * @param string $class 
     */
    public function articleBlockIntroduction($block, $class = '')
    {
        $params = array(
            'element'   => 'article',
            'class'     => 'article-block '.$class,
            'adminMenu' => true, 
            'config'    => $this->config, 
            'type'      => self::TYPE_INTRODUCTION,       
        );        
        return $this->renderBlock($block, $params);
    }

    /**
     * @param string/Block $block
     * @param string $class 
     */
    public function articleBlockIntroductionPreview($block, $class = '')
    {
        $params = array(
            'element'   => 'article',
            'class'     => 'article-block '.$class,
            'adminMenu' => false, 
            'config'    => $this->config, 
            'type'      => self::TYPE_INTRODUCTION,       
        );        
        return $this->renderBlock($block, $params);    
    }
    
    /**
     * @param string/Block $block
     * @param string $class 
     */
    public function articleBlockContent($block, $class = '')
    {
        $params = array(
            'element'   => 'article',
            'class'     => 'article-block '.$class,
            'adminMenu' => true, 
            'config'    => $this->config, 
            'type'      => self::TYPE_CONTENT,       
        );        
        return $this->renderBlock($block, $params);
    }

    /**
     * @param string/Block $block
     * @param string $class 
     */
    public function articleBlockContentPreview($block, $class = '')
    {
        $params = array(
            'element'   => 'article',
            'class'     => 'article-block '.$class,
            'adminMenu' => false, 
            'config'    => $this->config, 
            'type'      => self::TYPE_CONTENT,       
        );        
        return $this->renderBlock($block, $params);  
    }   
    
    /**
     * @param string/Block $block
     * @param string $class 
     */
    public function contentBlock($block, $class = '')
    {
        $params = array(
            'element'   => 'div',
            'class'     => 'content-block '.$class,
            'adminMenu' => true, 
            'config'    => $this->config, 
            'type'      => self::TYPE_CONTENT,       
        );        
        return $this->renderBlock($block, $params);}
    
    /**
     * @param type $article
     * @return \Xi\Bundle\ArticleBundle\Entity\Block 
     */
    public function wrapArticleToNewBlock($article)
    {
        $block = new Block();
        $block->setArticle($article);
        return $block;
    }
    
    /**
     * @param string/Block $block
     * @return Block 
     */    
    public function fetchOrCreateBlock($blockOrId)
    {
        if($blockOrId instanceof Block){
            return $blockOrId;
        } elseif($block = $this->blockService->getBlockById($blockOrId)) {
            return $block;
        } 
        return $this->blockService->createBlock($blockOrId);
    }
    
    public function getName()
    {
        return 'article_block_extension';
    }
}