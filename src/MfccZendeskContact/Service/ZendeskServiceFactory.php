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

use Traversable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

/**
 * Zendesk Service Factory
 */

class ZendeskServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $services)
	{
		$config  = $services->get('config');
		if ($config instanceof Traversable) {
			$config = ArrayUtils::iteratorToArray($config);
		}
		$config  = $config['MfccZendeskContact']['zendesk'];

		$zendeskService = new ZendeskService();
		
		// set zendesk keys
		$zendeskService->setZdApiKey($config['apiKey']);
		$zendeskService->setZdUser($config['user']);
		$zendeskService->setZdUrl($config['url']);
		
		// set ticket defaults
		if(isset($config['ticket']['default_locale'])){
			$zendeskService->setLocale($config['ticket']['default_locale']);
		}
		if(isset($config['ticket']['default_priority'])){
			$zendeskService->setPriority($config['ticket']['default_priority']);
		}
		if(isset($config['ticket']['default_type'])){
			$zendeskService->setType($config['ticket']['default_type']);
		}
		if(isset($config['ticket']['default_status'])){
			$zendeskService->setStatus($config['ticket']['default_status']);
		}
		if(isset($config['ticket']['default_group_id'])){
			$zendeskService->setGroupId($config['ticket']['default_group_id']);
		}
		if(isset($config['ticket']['default_assignee_id'])){
			$zendeskService->setAsigneeId($config['ticket']['default_assignee_id']);
		}
		if(isset($config['ticket']['default_collaborators'])){
			$zendeskService->setCollaborators($config['ticket']['default_collaborators']);
		}
		if(isset($config['ticket']['default_tags'])){
			$zendeskService->setTags($config['ticket']['default_tags']);
		}
		if(isset($config['ticket']['default_custom_fields'])){
			$zendeskService->setCustomFields($config['ticket']['default_custom_fields']);
		}
		if(isset($config['ticket']['default_subject'])){
			$zendeskService->setSubject($config['ticket']['default_subject']);
		}

		return $zendeskService;
	}
}