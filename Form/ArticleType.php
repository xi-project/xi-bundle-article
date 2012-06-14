<?php

namespace Xi\Bundle\ArticleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Xi\Bundle\TagBundle\Form\Type\TagType;
use Xi\Bundle\ArticleBundle\Form\Type\DatePickerType;

class ArticleType extends AbstractType
{
    
    public function buildForm(FormBuilder $builder, array $options)
    {     
        $builder
            ->add('title',      'text',         array('label' => 'article.form.title'))
            ->add('introduction',    'textarea',     array(
                'label' => 'article.form.introduction',
                'attr'  => array('class' => 'editor'),
                'required'  => false
            ))                
            ->add('content',    'textarea',     array(
                'label' => 'article.form.content',
                'attr'  => array('class' => 'editor'),
                'required'  => false
            ))

            ->add('publishDate', 'datepicker', array(    
                'label'     => 'article.form.publish_date',
                'format'    => 'dd.MM.yyyy',
            ))
                  
            ->add('expirationDate', 'datepicker', array(
                'label'     => 'article.form.expiration_date',
                'format'    => 'dd.MM.yyyy',
            ))
                             
            ->add('tags', 'tag_selector');
    }

    public function getName()
    {
        return 'xi_articlebundle_articletype';
    }
    
  
}
