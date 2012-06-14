<?php

namespace Xi\Bundle\ArticleBundle\Service;

use Xi\Doctrine\ORM\Repository,
    \DateTime,
    Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityManager,        
    Xi\Bundle\ArticleBundle\Entity\Block,
    Xi\Bundle\ArticleBundle\Entity\Article,        
    Xi\Bundle\ArticleBundle\Form\BlockType,    
    Xi\Bundle\ArticleBundle\Repository\BlockRepository;

class BlockService 
{
    /**
     * @var EntityManager
     */
    protected $em;
        
    /**
     * @var BlockRepository
     */
    protected $repository;
    
    /**
     * @var FormFactory
     */
    protected $formFactory;
    
    /**
     * @param EntityManager $em
     * @param FormFactory $formFactory
     * @param BlockRepository $repository 
     */
    public function __construct(
            EntityManager $em,
            FormFactory $formFactory,
            BlockRepository $repository)
    {
        $this->em           = $em;        
        $this->repository   = $repository;
        $this->formFactory  = $formFactory;
    }
       
    /**
     * @param int $id
     * @return Block
     */
    public function getBlockById($id)
    {        
        return $this->repository->find($id);     
    }
    
    /**
     * @param array $ids
     * @return array (Blocks) 
     */
    public function getBlocksByIds(array $ids)
    {
        $self = $this; 
        return array_map(function($id) use ($self) {
            return $self->getBlockById($id);
        }, $ids);
      
    }

    /**
     * @param string $id
     * @return Block 
     */
    public function createBlock($id)
    {
        $block = new Block();
        $block->setId($id);
        return $this->saveBlock($block);
    }
    
    /**
     * @param Block $block
     * @return Block 
     */
    public function saveBlock(Block $block)
    {       
        $this->em->persist($block);
        $this->em->flush();
        
        return $block;
    }    
    
    /**
     * @param Block $block
     * @param Article $article
     * @return Block 
     */
    public function updateBlocksArticle(Block $block, Article $article){
        $block->setArticle($article);
        return $this->saveBlock($block);
    }
    
    /**
     * @param Block $block
     * @return Form
     */
    public function getBlockForm(Block $block)
    {
        return $this->formFactory->create(
            new BlockType(get_class($block)), $block
        );
    }
    
}