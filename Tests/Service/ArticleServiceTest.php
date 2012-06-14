<?php

namespace Xi\Bundle\ArticleBundle\Tests\Service;

use PHPUnit_Framework_Testcase,
    Xi\Doctrine\Fixtures\FieldDef,
    \DateTime,
    Symfony\Component\Form\FormFactory,    
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityManager,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\ArticleBundle\Service\ArticleService;

/**
 * @group service
 * @group article
 */
class ArticleServiceTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var ArticleService
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();
        
        $this->service = new ArticleService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $this->getContainer()->get('form.factory'),  
            $this->getContainer()->get('xi_article.repository.article'),
            $this->getContainer()
        );
        $this->repository = $this->getContainer()->get('xi_article.repository.article');

    }
    
    protected function setUpFixtures()
    {        
        parent::setUpFixtures();
    }
    
    
    /**
     * @test
     */
    public function addArticle()
    {
        $article = $this->createArticle('test');
        $article = $this->service->saveArticle($article);          
        $this->assertEquals($article->getId(), ($this->service->getArticle($article->getId())->getId()));
    }
    
    /**
     * @test
     */
    public function getArticles()
    {
        $this->service->saveArticle($this->createArticle('article1')); 
        $this->service->saveArticle($this->createArticle('article2'));   
        $this->assertEquals(2, count($this->service->getArticles()));
    }
    
    /**
     * @test
     */
    public function getArticleForm()
    {  
        $form = $this->service->getArticleForm($this->createArticle('test'));
        $this->assertEquals('Symfony\Component\Form\Form', get_class($form));   
    }

    /**
     * @test
     */
    public function getArticle()
    {  
        $article = $this->createArticle('article 1');
        $this->service->saveArticle($article ); 
        
        $articleById = $this->service->getArticle($article->getId());
        $this->AssertSame($article, $articleById); 
        
        $articleBySlug = $this->service->getArticle('article-1');
        $this->AssertSame($article, $articleBySlug);        
    }    
    
    /**
     * @test
     */  
    public function removeArticle()
    {
        $article = $this->service->saveArticle($this->createArticle('article'));
        $this->service->removeArticle($article);
        $this->assertEquals(0, count($this->service->getArticles()));
    }
    /**
     * @test
     */
    public function saveArticleByForm()
    {
        $form = $this->service->getArticleForm($this->createArticle('test'));
        $article = $this->service->saveArticleByForm($form);
        $savedArticle = $this->service->getArticle($article->getId());
        $this->assertEquals('test', $savedArticle->getTitle());
    }
      
    /**
     * @param string $name
     * @return Article 
     */
    private function createArticle($name)
    {
        $article= new Article();
        $article->setTitle($name)
                ->setIntroduction('introduction for '.$name)
                ->setContent('content for '.$name);
        return $article;
    }


}