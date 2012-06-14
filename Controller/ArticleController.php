<?php

namespace Xi\Bundle\ArticleBundle\Controller;

use Xi\Bundle\AjaxBundle\Controller\JsonResponseController,
    Xi\Bundle\ArticleBundle\Entity\Article,
    Xi\Bundle\ArticleBundle\Entity\Block,
    Xi\Bundle\ArticleBundle\Form\ArticleType;

/**
 * Article controller.
 *
 */
class ArticleController extends JsonResponseController
{

    public function listAction()
    {
        return $this->render('XiArticleBundle:Article:list.html.twig', array(
            'articles' => $this->getArticleService()->getArticles()
        ));
    }

    public function newAction()
    {
         return $this->render('XiArticleBundle:Article:new.html.twig', array(
            'form' => $this->getArticleService()->getArticleForm(new Article())->createView()
        ));       
    }
    
    public function createAction()
    {
        $self = $this;
        $service = $this->getArticleService();
        
        return $this->processForm($service->getArticleForm(new Article()), function($form) use($self, $service) {
            $article = $service->saveArticleByForm($form);
            $self->setFlash('success', 'article.form.create.success');                          
            return $self->createJsonSuccessRedirectResponse('xi_article_article');
        });
    }   

    public function editAction($id)
    {
        $article = $this->getArticleService()->getArticle($id);  
        $form = $this->getArticleService()->getArticleForm($article);
        
        return $this->render('XiArticleBundle:Article:edit.html.twig', array(
            'form'      => $this->getArticleService()->getArticleForm($article)->createView(),
            'article'   => $article
        ));       
    }
    
    public function updateAction($id)
    {
        $self = $this;
        $service = $this->getArticleService();
        $article = $service->getArticle($id);

        return $this->processForm($service->getArticleForm($article), function($form) use($self, $service) {
            $article = $service->saveArticleByForm($form);
            $self->setFlash('success', 'article.form.edit.success');                          
            return $self->createJsonSuccessRedirectResponse('xi_article_article');
        });
    }    
    
    public function deleteAction($id)
    {
        $article = $this->getArticleService()->getArticle($id);
        $this->getArticleService()->removeArticle($article);
        $this->setFlash('success', 'article.remove.success');      
        return $this->createJsonSuccessRedirectResponse('xi_article_article');
    }

    public function previewAction($id)
    {
        $article = $this->getArticleService()->getArticle($id);
        $block = new Block();
        $block->setArticle($article);
        return $this->render('XiArticleBundle:Article:preview.html.twig', array(
            'block'   => $block
        ));      
    }
 
    public function showAction($id)
    {
        
        $article = $this->getArticleService()->getArticle($id);
        $config = $this->container->getParameter('xi_article');
        return $this->render('XiArticleBundle:Article:show.html.twig', array(
            'article'       => $article,
            'editRoles'     => $config['acl_roles']['edit']
        ));    
    }    
    
    /**
     * @return Xi\Bundle\ArticleBundle\Service\ArticleService
     */
    private function getArticleService()
    {
        return $this->container->get('xi_article.service.article');
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
