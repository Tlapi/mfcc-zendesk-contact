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
namespace MfccZendeskContact\Service;

use MfccZendeskContact\Controller\ContactController;
//use MfccZendeskContact\Form\ContactForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * The Contact-Controller Factory
 */
class ContactControllerFactory implements FactoryInterface
{
	/**
	 * Create the ContactController
	 * 
	 * @param ServiceLocator $services The ServiceLocator
	 * 
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 * @return ContactController
	 */
 	public function createService(ServiceLocatorInterface $services)
 	{
 		$serviceLocator = $services->getServiceLocator();
 		
	 	$form      = $serviceLocator->get('MfccZendeskContact\Form\ContactForm');
 		$zendeskService = $serviceLocator->get('zendeskService');
 		
 		$controller = new ContactController();
 		$controller->setContactForm($form);
 		$controller->setZendeskService($zendeskService);
 		
 		return $controller;
 	}
}