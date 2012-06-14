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

        $tagService = $this->getMockBuilder('Xi\Bundle\TagBundle\Service\TagService')
                     ->disableOriginalConstructor()
                     ->getMock();

        $this->articles = array(
            $this->createArticle(1),
            $this->createArticle(2),
            $this->createArticle(3),
            $this->createArticle('diibadaaba')
        );

        $this->getEntityManager()->flush();

        $tagService->expects($this->any())
            ->method('getResources')
            ->will($this->returnValue(
                array('article' => $this->articles)
            ));

        $twig = $this->getMock('Twig_Environment');

        $config = array('acl_roles' => array('edit' => array('ROLE_ADMIN')));

        $this->articleItem = new ArticleItem($twig, $tagService, $config);
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
     * @group article
     */
    public function findsSingleArticleFromSeveral()
    {
        $this->assertNull($this->articleItem->articleItem(array(), 2));

        $article = $this->articleItem->articleItem($this->articles, 2);
        $this->assertEquals($article->getId(), 2);

        $article = $this->articleItem->articleItem($this->articles, 0987987698);
        $this->assertEquals($article->getId(), 1);

        $article = $this->articleItem->articleItem($this->articles, 0987987698);
        $this->assertEquals($article->getId(), 1);
    }

    /**
     * @test
     * @group article
     */
    public function findsSingleArticleBySlug()
    {
        $article = $this->articleItem->articleItem($this->articles, 'diibadaaba');
        $this->assertEquals($article->getSlug(), 'diibadaaba');
    }

    /**
     * @param string $name
     * @return Article 
     */
    private function createArticle($name)
    {
        $article = new Article();
        $article->setTitle($name)
                ->setIntroduction('introduction for '.$name)
                ->setContent('content for '.$name);
        $this->getEntityManager()->persist($article);
        return $article;
    }
}