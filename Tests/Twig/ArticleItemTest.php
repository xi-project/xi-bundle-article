<?php

namespace Xi\Bundle\ArticleBundle\Tests\Service;

use \Twig_Environment,
    Doctrine\ORM\EntityManager,
    PHPUnit_Framework_Testcase,
    Xi\Bundle\ArticleBundle\Twig\Extensions\ArticleItem,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\TagBundle\Service\TagService;

/**
 * @group twig
 * @group article
 * @group tag
 */
class ArticleItemTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var TagService
     */
    protected $articleItem;

    public function setUp()
    {
        parent::setUp();
        
        $this->articles = array(
            $this->createArticle(1),
            $this->createArticle(2),
            $this->createArticle(3),
            $this->createArticle('diibadaaba')
        );
        
        $this->tagService = $this->getMockBuilder('Xi\Bundle\TagBundle\Service\TagService')->disableOriginalConstructor()->getMock();
        $this->tagService->expects($this->any())
            ->method('getResources')
            ->will($this->returnValue(
                array('article' => $this->articles)
            ));

        $twig = $this->getMock('Twig_Environment');
        $config = array('acl_roles' => array('edit' => array('ROLE_ADMIN')));
        $this->articleItem = new ArticleItem($twig, $this->tagService, $config);
    }

    /**
     * @test
     */
    public function retrievesArticles()
    {
        $articles = $this->articleItem->articleList('tussi');
        $this->assertCount(4, $articles);
    }

    /**
     * @test
     */
    public function articleList()
    {
        $this->assertNull($this->articleItem->articleItem(array()));
        $article = $this->articleItem->articleItem($this->articles);
     
    }

    /**
     * @test
     * test
     */
    public function articleItem()
    {
        $article = $this->articleItem->articleItem($this->articles, 'slugdiibadaaba');
        $this->assertEquals('slugdiibadaaba', $article->getSlug());
        $article = $this->articleItem->articleItem($this->articles, 'iddiibadaaba');
        $this->assertEquals('iddiibadaaba', $article->getId());     
     }

    /**
     * @param string $name
     * @return Article 
     */
    private function createArticle($name)
    {
        $article= $this->getMockBuilder('Xi\Bundle\ArticleBundle\Entity\Article')->setMethods(array('getId', 'getSlug'))->getMock();
        $article->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('id'.$name));
        $article->expects($this->any())
            ->method('getSlug')
            ->will($this->returnValue('slug'.$name));
        
        $article->setTitle($name)        
                ->setIntroduction('introduction for '.$name)
                ->setContent('content for '.$name);
        return $article;
    }
}