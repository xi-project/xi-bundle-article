<?php

namespace Xi\Bundle\ArticleBundle\Tests\Service;

use PHPUnit_Framework_Testcase,
    Xi\Doctrine\Fixtures\FieldDef,
    \DateTime,
    Symfony\Component\Form\FormFactory,    
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityManager,
    Xi\Bundle\ArticleBundle\Entity\Block,
    Xi\Bundle\ArticleBundle\Service\BlockService;

class BlockServiceTest extends PHPUnit_Framework_Testcase
{
    /**
     * @var BlockService
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();
        
        $this->service = new BlockService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $this->getContainer()->get('form.factory'),  
            $this->getContainer()->get('xi_article.repository.block')
        );
        $this->repository = $this->getContainer()->get('xi_article.repository.block');
    }
    
    protected function setUpFixtures()
    {        
        parent::setUpFixtures();
    }
    
    /**
     * @test
     * @group service
     * @group block
     */  
    public function getBlocks()
    {
        $this->service->saveBlock($this->createBlock('test1')); 
        $this->service->saveBlock($this->createBlock('test2'));
        $this->service->saveBlock($this->createBlock('test3'));

        $this->assertEquals(2, $this->countNotEmptyBlocks($this->service->getBlocksByIds(array('test1','test2','test4'))));
    }

    /**
     * @param array $blocks
     * @return int 
     */
    private function countNotEmptyBlocks($blocks)
    {
        $results = 0;
        foreach($blocks as $block)
        {
            if(!empty($block)) {
                $results++;
            }
        }   
        return $results;
    }
    
    /**
     * @test
     * @group service
     * @group block
     */  
    public function getBlockForm()
    {  
        $form = $this->service->getBlockForm($this->createBlock('testing'));
        $this->assertInstanceOf('Symfony\Component\Form\Form', $form);
    }   
    
    /**
     * @test
     * @group service
     * @group block
     */  
    public function createBlockTest()
    {
        $block = $this->service->createBlock('testing');
        $this->assertEquals('testing', $this->service->getBlockById('testing')->getId());
    }
    
    /**
     * @param type $id
     * @return Block 
     */
    private function createBlock($id)
    {
        $block = new Block();
        $block->setId($id);
        return $block;
    }    
}