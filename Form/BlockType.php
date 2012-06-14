<?php

namespace Xi\Bundle\ArticleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BlockType extends AbstractType
{

    public function getName()
    {
        return 'xi_articlebundle_blocktype';
    }
}
