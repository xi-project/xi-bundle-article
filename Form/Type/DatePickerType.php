<?php

namespace Xi\Bundle\ArticleBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilder;
use Xi\Bundle\TagBundle\Form\DataTransformer\TagTransformer;
use Doctrine\Common\Persistence\ObjectManager;

// this class is not ready yet. Waiting for symfony2 bugfix..
class DatePickerType extends DateType
{
    private $config;
    
    public function __construct($config)
    {
       $this->config = $config;
    }       
    
    public function getName()
    {
        return 'datepicker';
    }  
    
    public function getDefaultOptions(array $options)
    {
        $newoptions = array(

            'widget'    => $this->config['date_widget'],
            'format'    => 1,
        //    'format'    => 'dd.MM.yyyy',  doesn't work now. Possible symfony2 bug...
            'attr'      => array('class' =>  $this->config['datepicker_class']),
            'required'  => false,
        );  
        return array_merge(parent::getDefaultOptions($options), $newoptions);
    }

}