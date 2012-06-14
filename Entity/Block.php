<?php

namespace Xi\Bundle\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Component\Validator\Constraints as Assert,
    Gedmo\Mapping\Annotation as Gedmo,
    Xi\Bundle\ArticleBundle\Entity\Article;

/**
 * Xi\Bundle\ArticleBundle\Entity\Block
 *
 * @ORM\Table(name="block")
 * @ORM\Entity(repositoryClass="Xi\Bundle\ArticleBundle\Repository\BlockRepository")
 */ 
class Block
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     */
    private $id;
   
    /**
     * @ORM\ManyToOne(targetEntity="Xi\Bundle\ArticleBundle\Entity\Article", inversedBy="blocks")
     */
    protected $article;
    
    /**
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }    
    
    /**
     * @param string $id 
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     *
     * @param Article $article
     * @return Block 
     */
    public function setArticle(Article $article)
    {
        if ($this->article !== $article) {
            $this->article = $article;
            $this->article->addBlock($this);
        }
        return $this;
    }
    
    /**
     * @return Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
    
    /**
     * @return Block 
     */
    public function removeArticle()
    {
        if($this->article) {
            $this->article->removeBlock($this);
            $this->article = null;           
        }
        return $this;
    }
}