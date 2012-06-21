<?php

namespace Xi\Bundle\ArticleBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Xi\Bundle\ArticleBundle\Entity\Article;

class ArticleRepository extends EntityRepository
{

    /**
     * find by slug
     * @param string $articleReference
     * @return article
     */
    public function findBySlug($articleReference)
    {
        $result = parent::findBySlug($articleReference);
        return isset($result[0]) ? $result[0] : null;
    }
    
    /**
     * @param array $ids
     * @param date $publishDate
     * @param date $expirationDate
     * @return array
     */
    public function findPublishedArticlesByIds($ids, $publishDate = null, $expirationDate = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a')
           ->from('Xi\Bundle\ArticleBundle\Entity\Article', 'a')
           ->where($qb->expr()->in('a.id', '?1')) 
           ->orderBy('a.publishDate', 'DESC')
           ->setParameter(1, $ids);
        if($publishDate) {
            $qb->andWhere('a.publishDate <= ?2')
               ->setParameter(2, $publishDate);
        }
        if($expirationDate) {        
            $qb->andWhere('a.expirationDate >= ?3')             
               ->setParameter(3, $expirationDate);
        }
        return $qb->getQuery()->getResult();
    }
}