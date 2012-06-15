<?php

namespace SBA\ArticleBundle\Twig\Extensions;

use \Twig_Environment,
    SBA\SearchBundle\Service\Search\Result\SearchResult,
    SBA\ArticleBundle\Entity\Article,
    SBA\SearchBundle\Twig\Extensions\SearchRenderer;

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
     * @param mixed $data
     * @param array $options
     * @return string
     */
    public function searchRenderer($data, $options)
    {
        if($data instanceof SearchResult){
            $data = $this->convertToArticle($data->getSource());
        } 
        return $this->twig->render('SBAArticleBundle:SearchResultRenderer:entity-row.html.twig',
            array('article' => $data, 'options' => $options, ));      


    }

    /**
     * @param SearchResult $data
     * @return \SBA\ArticleBundle\Entity\Article 
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