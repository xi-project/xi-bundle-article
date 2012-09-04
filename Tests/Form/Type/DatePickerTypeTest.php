<?php

use \PHPUnit_Framework_TestCase,
	Xi\Bundle\ArticleBundle\Form\Type\DatePickerType;

class DatePickerTypeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Created mainly for 2.0 -> 2.1 migration
	 * @test
	 */
    public function DatePickerTypeInitializes()
    {
        $datePickerType = new DatePickerType(array());

        $this->assertInstanceOf('Xi\Bundle\ArticleBundle\Form\Type\DatePickerType', $datePickerType);
    }
}


