<?php

namespace Xi\Bundle\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Component\Validator\Constraints as Assert,
    Gedmo\Mapping\Annotation as Gedmo,
    Xi\Bundle\ArticleBundle\Entity\Block,
    DoctrineExtensions\Taggable\Taggable;

/**
 * Xi\Bundle\ArticleBundle\Entity\Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="Xi\Bundle\ArticleBundle\Repository\ArticleRepository")
 */ 
class Article implements Taggable
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
   
    /**
     * @var string $title 
     * 
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message="article.validation.title.notblank")
     * @Assert\MinLength(limit="3", message="article.validation.title.short")
     * @Assert\MaxLength(limit="255", message="article.validation.title.long")
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @Doctrine\ORM\Mapping\Column(length=255, unique=true)
     */
    private $slug;
    
    /**
     * @var string $content
     * 
     * @ORM\Column(name="introduction", type="text")
     * @Assert\NotBlank(message="article.validation.introduction.notblank")
     */
    private $introduction; 
    
    /**
     * @var string $content
     * 
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message="article.validation.content.notblank")
     */
    private $content;
    
    /**
     * @var datetime $updated
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @var datetime $created
     * 
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created; 
    
    /**
     * @var datetime $created
     * 
     * @ORM\Column(name="publish_date", type="datetime", nullable=true)
     */    
    protected $publishDate;
      
    /**
     * @var datetime $created
     * 
     * @ORM\Column(name="expiration_date", type="datetime", nullable=true)
     */     
    protected $expirationDate;
    
    /**
     * @ORM\OneToMany(targetEntity="Xi\Bundle\ArticleBundle\Entity\Block", mappedBy="article")
     */
    protected $blocks;

    
    protected $tags;
    
    public function __construct()
    {
        $this->blocks = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }    
    
    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param string $title
     * @return Article 
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @param string $content
     * @return Article 
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @param string $introduction
     * @return Article 
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }
 
    /**
     * @param datetime $updated
     * @return Article 
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @param datetime $publishDate
     * @return Article 
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
        return $this;
    }
    
    /**
     * @return datetime 
     */    
    public function getPublishDate()
    {
        return $this->publishDate;
    }
    
    /**
     * @param datetime $expirationDate
     * @return Article 
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }
    
    /**
     * @return datetime 
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
    
    /**
     * @return datetime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }    
    
    /**
     * @param  Block $block
     * @return Article
     */
    public function addBlock(Block $block)
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks->add($block);
            $block->setArticle($this);           
        }
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param Block $block
     * @return Article 
     */
    public function removeBlock(Block $block)
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
            $block->removeArticle();
        }
        return $this;
    }
    
    /**
     * @return Article 
     */
    public function removeBlocks()
    {
        foreach($this->blocks as $block){
            $this->removeBlock($block);
        }
        return $this;
    }
  
    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * @param type ArrayCollection
     */
    public function setTags($tags)
    {   
        $this->tags = $tags;
    }
      
    /**
     * @return string
     */
    public function getTaggableType()
    {
        return 'article';
    }

    /**
     * @return integer
     */
    public function getTaggableId()
    {
        return $this->getId();
    }    
    
    /**
     * resets slug. This is obsolete in doctrine 2.3 where you can just use ->setSlug('') 
     */
    public function resetSlug()
    {
        $this->slug = '';
    }
}