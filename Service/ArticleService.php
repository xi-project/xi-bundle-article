<?php

namespace Xi\Bundle\ArticleBundle\Service;

use Xi\Doctrine\ORM\Repository,
    \DateTime,
    Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityManager,        
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\ArticleBundle\Form\ArticleType,
    Xi\Bundle\ArticleBundle\Repository\ArticleRepository,
    Xi\Bundle\TagBundle\Service\AbstractTaggableService,
    Symfony\Component\DependencyInjection\Container;


class ArticleService extends AbstractTaggableService
{
    /**
     * @var EntityManager
     */
    protected $em;
        
    /**
     * @var ArticleRepository
     */
    protected $repository;
    
    /**
     * @var FormFactory
     */
    protected $formFactory;
   

    /*
     * @param EntityManager $em
     * @param FormFactory $formFactory
     * @param ArticleRepository $repository
     * @param Container $container
     */
    public function __construct(
            EntityManager $em,
            FormFactory $formFactory,
            ArticleRepository $repository, 
            Container $container)
    {
        $this->em           = $em;        
        $this->repository   = $repository;
        $this->formFactory  = $formFactory;
        $this->container    = $container;
    }
       
    /**
     * @return array
     */
    public function getArticles()
    {
        return $this->repository->findBy(array(), array('updated' => 'DESC'));
    }
    
    /**
     * @param Article $article
     * @return Article 
     */
    public function saveArticle(Article $article)
    {   
        $this->em->persist($article);
        $this->em->flush();
        
        return $article;
    }    

    /**
     * @param Article $article
     * @return Form
     */
    public function getArticleForm(Article $article)
    {
        return $this->formFactory->create(
            new ArticleType(), $article
        );
    }
    
    /**
     * @param Form $form
     * @return Article
     */
    public function saveArticleByForm(Form $form)
    {
        $article = $form->getData();
        $article->resetSlug();      // change to setSlug('') in doctrine 2.3
        $this->saveArticle($article);
        $this->getTagService()->getTagManager()->saveTagging($article);
        return $article;
    }
   
    /**
     * finds article by its id or slug
     * 
     * @param integer/string $articleReference
     * @return Article 
     */
    public function getArticle($articleReference)
    {
        if(is_numeric($articleReference)) {
            $article =  $this->getArticleById($articleReference);
        } else {
            $article = $this->repository->findBySlug($articleReference);
        }

        if($article) {
            $this->getTagService()->getTagManager()->loadTagging($article);
            return $article;
        }
    }
    
    /**
     * @param int $id
     * @return Article 
     */
    private function getArticleById($id)
    {        
        return $this->repository->find($id);
    }
    
    /**
     * @param Article $article
     * @return ArticleService 
     */
    public function removeArticle(Article $article)
    {
        if(count($article->getBlocks())){
            $article->removeBlocks();
        }
        $this->getTagService()->getTagManager()->deleteTagging($article);
        $this->em->remove($article);      
        $this->em->flush();
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getTaggableType()
    {
        return 'article';
    }
    
    /**
     *  these should be replaced by query object landing method
     * 
     * @param array $ids
     * @param array $options
     * @param array $tagNames
     * @return array 
     */
    public function getTaggedResourcesByIds(array $ids, array $options, array $tagNames)
    {
        return $this->repository->findById($ids);
    }
    
    /**
     *  these should be replaced by query object landing method
     * 
     * @param array $ids
     * @param array $options
     * @param array $tagNames 
     * @return array 
     */     
    public function getPublishedArticlesByIds(array $ids, array $options, array $tagNames)
    {
        if(!empty($ids)) {
            return $this->repository->findPublishedArticlesByIds($ids, new DateTime());
        }
        return array();
    }
    
}