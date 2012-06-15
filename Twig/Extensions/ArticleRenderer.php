<?php

namespace Xi\Bundle\ArticleBundle\Twig\Extensions;

use \Twig_Environment,
    Xi\Bundle\SearchBundle\Service\Search\Result\SearchResult,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\SearchBundle\Twig\Extensions\SearchRenderer;

class ArticleRenderer extends \Twig_Extension implements SearchRenderer
{

    /**
     * @var Twig_Environment
     */
    protected $twig;
  
    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig         = $twig;
    }
  
    /**
     *  @return array
     */
    public function getFunctions()
    {
        return array(
            'xi_article_search_row_renderer' => new \Twig_Function_Method(
            $this, 'searchRenderer', array('is_safe' => array('html'))),
        );
    }
    
    /**
     * @param mixed $data
     * @param array $options
     * @return string
     */
    public function searchRenderer($data, $options = array())
    {
        if($data instanceof SearchResult){
            $data = $this->convertToArticle($data->getSource());
        } 
        return $this->twig->render('XiArticleBundle:SearchResultRenderer:search-result-row.html.twig',
            array('article' => $data, 'options' => $options, ));      


    }

    /**
     * @param SearchResult $data
     * @return \Xi\Bundle\ArticleBundle\Entity\Article 
     */
    private function convertToArticle($data)
    {
        $article = new Article();
        foreach($data as $name => $value) {
            $method_name = "set".ucfirst($name);
            if(method_exists($article, $method_name)){
                $article->$method_name($value);
            }          
        }
        return $article;   
    }
    
    
    /**
     * @return string 
     */
    public function getName()
    {
        return 'article_renderer_extension';
    }
}