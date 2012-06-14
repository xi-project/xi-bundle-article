<?php

namespace Xi\Bundle\ArticleBundle\Tests\Service;

use \Twig_Environment,
    Doctrine\ORM\EntityManager,
    PHPUnit_Framework_Testcase,
    Xi\Bundle\ArticleBundle\Twig\Extensions\ArticleBlock,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\ArticleBundle\Entity\Block,        
    Xi\Bundle\ArticleBundle\Service\BlockService;

/**
 * @group twig
 * @group article
 */
class ArticleBlockTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var ArticleBlock
     */
    protected $articleBlock;
    
    /**
     * @var BlockService
     */
    protected $blockService;  
    
    public function setUp()
    {
        parent::setUp();

        $this->blockService = $this->getMockBuilder('Xi\Bundle\ArticleBundle\Service\BlockService')
                     ->disableOriginalConstructor()
                     ->getMock();

        $twig = $this->getMock('Twig_Environment');

        $this->articleBlock = new ArticleBlock($twig, $this->blockService, array());
    }

    /**
     * @test
     */
    public function wrapArticleToNewBlock()
    {
        $article = new Article();
        $block = $this->articleBlock->wrapArticleToNewBlock($article);
        $this->assertInstanceOf('Xi\Bundle\ArticleBundle\Entity\Block', $block);
        $this->assertSame($block->getArticle(), $article);
    }

    /**
     * @test
     */
    public function fetchOrCreateBlockWithBlockObject()
    {
        $block = new Block();
        $this->assertSame($block, $this->articleBlock->fetchOrCreateBlock($block));
    }   
        
    /**
     * @test
     */
    public function fetchOrCreateBlockWithBlockId()
    {
        $expectedBlock = new Block();
        $block = $this->blockService->expects($this->once())->method('getBlockById')->with('xoo')->will($this->returnValue($expectedBlock));
        $this->assertSame($expectedBlock, $this->articleBlock->fetchOrCreateBlock('xoo'));
    }
 
    /**
     * @test
     */
    public function createBlockWithBlockId()
    {
        $expectedBlock = new Block();        
        $this->blockService->expects($this->once())->method('getBlockById')->with('xoo')->will($this->returnValue(false));
        $this->blockService->expects($this->once())->method('createBlock')->with('xoo')->will($this->returnValue($expectedBlock));
        $this->assertSame($expectedBlock, $this->articleBlock->fetchOrCreateBlock('xoo'));
    }

}