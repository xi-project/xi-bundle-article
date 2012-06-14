<?php

namespace Xi\Bundle\ArticleBundle\Twig\Extensions;

use \Twig_Environment,
     Xi\Bundle\TagBundle\Service\TagService,
     Xi\Bundle\ArticleBundle\Entity\Article;

class ArticleItem extends \Twig_Extension
{   
    /**
     * @var Twig_Environment
     */
    protected $twig;
    
    /**
     * @var TagService
     */
    protected $tagService;

    /**
     * @var array
     */
    protected $articleConf;

    /**
     * @param Twig_Environment $twig
     * @param TagService       $tagService
     * @param array            $articleConf
     */
    public function __construct(Twig_Environment $twig, TagService $tagService, array $articleConf)
    {
        $this->twig         = $twig;
        $this->tagService   = $tagService;
        $this->articleConf  = $articleConf;
    }
    
    /**
     *  @return array
     */
    public function getFunctions()
    {
        return array(
            'xi_article_list' => new \Twig_Function_Method(
            $this, 'articleList', array('is_safe' => array('html'))),
            'xi_article_item' => new \Twig_Function_Method(
            $this, 'articleItem', array('is_safe' => array('html'))),
            'xi_article_show_content' => new \Twig_Function_Method(
            $this, 'articleShowContent', array('is_safe' => array('html')))
        );
    }
    
    /**
     * @param  String $tag
     * @return array
     */
    public function articleList($tag)
    {
        $resources = $this->tagService->getResources(array('article' => array('callback' => 'getPublishedArticlesByIds')), array($tag));

        if(isset($resources['article']) && !empty($resources['article'])) {
            return $resources['article'];
        }else{
            return array();
        }

    }

    /**
     * Find current article from an array, or choose the first one
     * @param  array $articles
     * @param  int|string $identifier id or slug
     * @return Article|null
     */
    public function articleItem($articles, $identifier = null)
    {

        if(count($articles)){
            $currentArticle = $articles[0];
        }else{
            return null;
        }

        if($identifier) {
            foreach($articles as $article) {

                if ($article->getId() == $identifier) {
                        $currentArticle = $article;
                        break;

                } else if ($article->getSlug() == $identifier) {
                        $currentArticle = $article;
                        break;

                }

            }
        }

        return $currentArticle;
    }

    /**
     * @param  Article $article
     * @return string
     */
    public function articleShowContent(Article $article)
    {
        return $this->twig->render('XiArticleBundle:Article:show-content.html.twig',
            array('article' => $article, 'editRoles' => $this->articleConf['acl_roles']['edit']));
    }
    
    public function getName()
    {
        return 'article_items_extension';
    }
}