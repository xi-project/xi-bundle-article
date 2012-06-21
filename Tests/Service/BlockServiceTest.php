<?php

namespace Xi\Bundle\ArticleBundle\Tests\Service;

use PHPUnit_Framework_Testcase,
    Xi\Doctrine\Fixtures\FieldDef,
    \DateTime,
    Symfony\Component\Form\FormFactory,    
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityManager,
    Xi\Bundle\ArticleBundle\Entity\Block,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\ArticleBundle\Service\BlockService;

/**
 * @group service
 * @group block 
 */
class BlockServiceTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var BlockService
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();

        $this->em =                 $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->formFactory =        $this->getMockBuilder('Symfony\Component\Form\FormFactory')->disableOriginalConstructor()->getMock();              
        $this->blockRepository =    $this->getMockBuilder('Xi\Bundle\ArticleBundle\Repository\blockRepository')->disableOriginalConstructor()->getMock();

        $this->service = new BlockService(
            $this->em,
            $this->formFactory,
            $this->blockRepository
        );

    }
    
    protected function setUpFixtures()
    {        
        parent::setUpFixtures();
    }
    
    /**
     * @test 
     */
    public function getBlockById()
    {    
        $this->blockRepository->expects($this->once())->method('find')->with(12);
        $this->service->getBlockById(12); 
    }
    
    /**
     * @test
     */
    public function getBlocksByIds()
    {
        $this->blockRepository->expects($this->any())->method('find')->will($this->returnValue('tussi'));
        $blocks = $this->service->getBlocksByIds(array(1,2,3)); 
   
        $this->assertCount(3, $blocks);
    }
    
    
    /**
     * @test
     */
    public function createBlock()
    {
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');  
        $block = $this->service->createBlock('tussi');
        $this->assertEquals('tussi', $block->getId());

    }
    
    /**
     * @test 
     */
    public function updateBlocksArticle()
    {
        
        $block = new Block();
        $article = new Article();
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');  
        $updatedBlock = $this->service->updateBlocksArticle($block, $article);
        $this->assertEquals($block, $updatedBlock);
    }
 
    /**
     * @test 
     */    
    public function getBlockForm()
    {
        $this->formFactory->expects($this->once())->method('create');
        $this->service->getBlockForm(new Block());
    }
     
}