<?php

namespace Xi\Bundle\ArticleBundle\Controller;

use Xi\Bundle\AjaxBundle\Controller\JsonResponseController,
    Xi\Bundle\ArticleBundle\Entity\Block;

/**
 * Article controller.
 *
 */
class BlockController extends JsonResponseController
{
    
    public function articleSelectionAction($id)
    {
        $block     = $this->getBlockService()->getBlockById($id);
        $articles   = $this->getArticleService()->getArticles();
        
        return $this->render('XiArticleBundle:Block:change.html.twig', array(      
            'block' => $block, 'articles' => $articles
        ));       
    }
    
    public function updateArticleSelectionAction($block, $article)
    {
        if($this->getBlockService()->updateBlocksArticle(
                $this->getBlockService()->getBlockById($block), 
                $this->getArticleService()->getArticle($article)))
        {
            $this->setFlash('success', 'article.block.change.success');
            return $this->createJsonSuccessReloadResponse();
        }
        return $this->createJsonFailureResponse($this->getTranslator()->trans('article.block.change.failure'));
    }
   
   
    /**
     * @return Xi\Bundle\ArticleBundle\Service\ArticleService
     */
    private function getArticleService()
    {
        return $this->container->get('xi_article.service.article');
    }    
 
    /**
     * @return Xi\Bundle\ArticleBundle\Service\BlockService
     */   
    private function getBlockService()
    {
        return $this->container->get('xi_article.service.block');        
    }

    /**
     * Set flash message that shows up next time when page is loaded
     * @param string $action
     * @param string $value 
     */
    public function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
    }      
}
