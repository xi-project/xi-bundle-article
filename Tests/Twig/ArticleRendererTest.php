<?php

namespace Xi\Bundle\ArticleBundle\Tests\Twig\Extensions;

use     PHPUnit_Framework_TestCase,
        Xi\Bundle\ArticleBundle\Twig\Extensions\ArticleRenderer,
        Xi\Bundle\SearchBundle\Service\Search\Result\SearchResult,
        Xi\Bundle\ArticleBundle\Entity\Article,
        \Twig_Environment,
        \Twig_Error_Runtime;

/**
 * @group twig-extensionf
 * @group search
 */
class ArticleRendererTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();
       
        $this->service = $this->getMockBuilder('Xi\Bundle\SearchBundle\Service\SearchService')->disableOriginalConstructor()->getMock();
        $this->environment = $this->getMock('\Twig_Environment');      
        $this->articleRenderer = new ArticleRenderer($this->environment, $this->service);        
        $this->searchResult = $this->getMockBuilder('Xi\Bundle\SearchBundle\Service\Search\Result\SearchResult')->disableOriginalConstructor()->getMock();     
    }
    
        
    /**
     * @test
     */
    public function  searchRowRenderer()
    {
        $this->searchResult->expects($this->once())->method('getSource')->with()->will($this->returnValue(array('title' => 'title')));
        $this->environment->expects($this->once())->method('render')->will($this->returnValue('template'));  
        $this->articleRenderer->searchRowRenderer($this->searchResult);
    }      

    
}