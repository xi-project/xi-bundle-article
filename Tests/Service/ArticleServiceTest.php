<?php

namespace Xi\Bundle\ArticleBundle\Tests\Service;

use PHPUnit_Framework_Testcase,
    Xi\Doctrine\Fixtures\FieldDef,
    \DateTime,
    Symfony\Component\Form\FormFactory,    
    Symfony\Component\Form\Form,
    Symfony\Component\DependencyInjection\Container,
    Doctrine\ORM\EntityManager,
    Xi\Bundle\TagBundle\Service\TagService,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\ArticleBundle\Entity\Block,
    Xi\Bundle\ArticleBundle\Service\ArticleService,
    Xi\Bundle\ArticleBundle\Repository\ArticleRepository;
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

            $this->em =                 $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
            $this->formFactory =        $this->getMockBuilder('Symfony\Component\Form\FormFactory')->disableOriginalConstructor()->getMock();              
            $this->articleRepository =  $this->getMockBuilder('Xi\Bundle\ArticleBundle\Repository\ArticleRepository')->disableOriginalConstructor()->getMock();
            $this->container =          $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')->disableOriginalConstructor()->getMock();
        
        
        $this->service = new ArticleService(
            $this->em,
            $this->formFactory,
            $this->articleRepository,
            $this->container
        );

    }
    
    protected function setUpFixtures()
    {        
        parent::setUpFixtures();
    }
    

    
    
    /**
     * @test
     */
    public function getArticles()
    {
       $this->articleRepository->expects($this->once())->method('findBy');
       $this->service->getArticles();
    }
    
    /**
     * @test 
     */
    public function saveArticle()
    {
        $article = new Article();
        
        $this->em->expects($this->once())->method('persist')->with($article);
        $this->em->expects($this->once())->method('flush');   
        $this->service->saveArticle($article);
    }
    
    /**
     * @test
     * @expectedException PHPUnit_Framework_Error
     */
    public function saveArticleWithNonArticleParameter()
    {
        $article = 'not article object';  
        $this->service->saveArticle($article);
    } 
    
    /**
     * @test
     */
    public function getArticleForm()
    {
      
        $article = new Article();
        $this->formFactory->expects($this->once())->method('create')->will($this->returnValue('tussi'));
        $result = $this->service->getArticleForm($article);
        $this->assertEquals('tussi', $result);
    }
    
     /**
     * @test
     * @expectedException PHPUnit_Framework_Error
     */
    public function getArticleFormWithNonArticleParameter()
    {
        $article = 'not article object';
        $this->service->getArticleForm($article);
    }  
    
     /**
     * @test
     */
    public function saveArticleByForm()
    {
        $article = new Article();
        
        // getdata
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock(); 
        $form->expects($this->once())->method('getData')->will($this->returnValue($article));
        
        $this->expectThatTagServiceContainsTagManager();  
        $this->tagManager->expects($this->once())->method('saveTagging');
        
        // save article
        $this->em->expects($this->once())->method('persist')->with($article);
        $this->em->expects($this->once())->method('flush');   
        
        $savedArticle = $this->service->saveArticleByForm($form);
        $this->assertEquals($article, $savedArticle);
    }
  
    /**
     * @test
     * @expectedException PHPUnit_Framework_Error
     */
    public function saveArticleByFormWithNonFormParameter()
    {
        $form = 'not form object';
        $this->service->saveArticleByForm($form);
    }  
    
    /**
     * @test 
     */
    public function getArticleWithNumericId()
    {
        $article = new Article();
        
        $this->articleRepository->expects($this->once())->method('find')->with(12)->will($this->returnValue($article));
        $this->articleRepository->expects($this->never())->method('findBySlug');
        $this->expectThatTagServiceContainsTagManager();
        $this->tagManager->expects($this->once())->method('loadTagging')->with($article);
        
        $loadedArticle = $this->service->getArticle(12);
        $this->assertEquals($article, $loadedArticle);
    }

    /**
     * @test 
     */    
    public function getArticleWithSlug()
    {
        $article = new Article();
        
        $this->articleRepository->expects($this->once())->method('findBySlug')->with('slug')->will($this->returnValue($article));
        $this->articleRepository->expects($this->never())->method('find');
        $this->expectThatTagServiceContainsTagManager();
        $this->tagManager->expects($this->once())->method('loadTagging')->with($article);
        
        $loadedArticle = $this->service->getArticle('slug');
        $this->assertEquals($article, $loadedArticle);    
    }
    
    /**
     * @test
     */
    public function getArticleThatsNotFound()
    {       
        $article = new Article();       
        $this->articleRepository->expects($this->once())->method('findBySlug')->with('slug');
        $this->articleRepository->expects($this->never())->method('find');
  
        $loadedArticle = $this->service->getArticle('slug');
        $this->assertEquals(null, $loadedArticle);         
              
    }
    
    /**
     * @test
     */  
    public function removeArticleWithoutBlocks()
    {
        $article = new Article();
        $this->expectThatTagServiceContainsTagManager();
        $this->tagManager->expects($this->once())->method('deleteTagging')->with($article);
        
        $this->em->expects($this->once())->method('remove')->with($article);
        $this->em->expects($this->once())->method('flush');  
        
        $serviceRef = $this->service->removeArticle($article);
        $this->assertInstanceOf('Xi\Bundle\ArticleBundle\Service\ArticleService', $serviceRef); 
    }
    
     /**
     * @test
     */  
    public function removeArticleWithBlocks()
    {
        $article = new Article();
        $article->addBlock(new Block());

        
        $this->expectThatTagServiceContainsTagManager();
        $this->tagManager->expects($this->once())->method('deleteTagging')->with($article);
        
        $this->em->expects($this->once())->method('remove')->with($article);
        $this->em->expects($this->once())->method('flush');  
        
        $serviceRef = $this->service->removeArticle($article);
        $this->assertInstanceOf('Xi\Bundle\ArticleBundle\Service\ArticleService', $serviceRef);
        $this->assertCount(0,$article->getBlocks()); 
    }   
    
    /**
     * @test 
     */
    public function getTaggableType()
    {
        $this->assertEquals('article',$this->service->getTaggableType());
    }

    protected function expectThatTagServiceContainsTagManager(){
        // tagManager
        $this->tagManager = $this->getMockBuilder('FPN\TagBundle\Entity\TagManager')->disableOriginalConstructor()->getMock();      
        
        
        $this->tagService = $this->getMockBuilder('Xi\Bundle\TagBundle\Service\TagService')->disableOriginalConstructor()->getMock();  
         $this->tagService->expects($this->once())->method('getTagManager')->will($this->returnValue($this->tagManager));
         
        $this->service->setTagServiceIdentifier('tussi');
        $this->container->expects($this->once())->method('get')->with('tussi')->will($this->returnValue($this->tagService));       
    }
}