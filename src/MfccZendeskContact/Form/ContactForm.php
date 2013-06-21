<?php
/**
 * Copyright (c) 2011-2013 Jan Tlapak <jan@mfcc.cz>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  ZendeskContact
 * @package   Mfcc
 * @author    Jan Tlapak <jan@mfcc.cz>
 * @copyright 2011-2013 Jan Tlapak
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 */
namespace MfccZendeskContact\Form;

use Zend\Stdlib\Hydrator\ArraySerializable;

use Zend\InputFilter\Input;

use Zend\InputFilter\InputFilter;
//use Zend\Validator;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Validator\Hostname as HostnameValidator;
//use MfccZendeskContact\Validator\IsEmpty as EmptyValidator;

/**
 * The Contact-Form
 */
class ContactForm extends Form
{
    /**
     * Initialize the form
     *
     * @return ContactForm
     */
    public function init()	
    {
        $this->setName('contact');
        
        $this->setHydrator(new ArraySerializable);
        
        $this->add(array(
        		'name' => 'from',
        		'type' => 'Zend\Form\Element\Text',
        		'options' => array(
                	'label'     => 'Email:',
                	'required'  => true,
                	'validators' => array(
        				array(
        					'name' => 'EmailAddress',
        					'options' => array(
	                        	'allow'  => HostnameValidator::ALLOW_DNS,
	                        	'domain' => true,
        					),
        				),
        			),
        		)
        	));
        
        $this->add(array(
        		'name' => 'name',
        		'type' => 'Zend\Form\Element\Text',
        		'options' => array(
                	'label'     => 'Name:',
                	'required'  => true,
        		)
        	));

        $this->add(array(
        		'name' => 'body',
        		'type' => 'Zend\Form\Element\Textarea',
        		'options' => array(
	                'label'    => 'Your message:',
	                'required' => true,
	                'cols'     => 80,
	                'rows'     => 10,
        		)
        ));

        $this->add(array(
        		'name' => 'country',
        		'type' => 'Zend\Form\Element\Text',
        		'options' => array(
	                'value'          => '',
	                'class'          => 'zonkos',
	                'label'          => 'SPAM-Protection: Please leave this field as it is!',
	            ),                
        ));

        $this->add(new Element\Csrf('csrf'));
        
        $this->add(array(
        		'name' => 'Send',
        		'type' => 'Zend\Form\Element\Submit',
        		'attributes' => array(
        			'value' => 'Send'
        		)
        ));
        
        
        $from = new Input('from');
        $from->isRequired(true);
        $from->setAllowEmpty(false);
        $from->getValidatorChain()
        	 ->addByName('EmailAddress');
        
        $country = new Input('country');
        $country->isRequired(true);
        $country->setAllowEmpty(true);
        $country->getValidatorChain()
                ->addByName('Identical', array('token'=>''));
        
        $subject = new Input('name');
        $subject->isRequired(true);
        $subject->setAllowEmpty(false);
        
        $body = new Input('body');
        $body->isRequired(true);
        $body->setAllowEmpty(false);
        
        $filter = new InputFilter();
        $filter->add($from);
        $filter->add($subject);
        $filter->add($body);
        $filter->add($country);
        
        $this->setInputFilter($filter);

        
        return $this;
    }
}