<?php

namespace MfccZendeskContact;

use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\StaticEventManager;
use Zend\Mvc\ModuleRouteListener;

/**
 * Class Module
 * @package MfccZendeskContact
 */
class Module
{
	/**
	 * @param $e
	 */
	public function onBootstrap($e)
    {
    	$e->getApplication()->getServiceManager()->get('translator');
    	$eventManager        = $e->getApplication()->getEventManager();
    	$moduleRouteListener = new ModuleRouteListener();
    	$moduleRouteListener->attach($eventManager);
    }

	/**
	 * @return mixed
	 */
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }

	/**
	 * @return array
	 */
    public function getAutoloaderConfig()
    {
    	return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
    	);
    }

	/**
	 * @return array
	 */
    public function getViewHelperConfig()
    {
    	return array(
			'factories' => array(
				'mfccContactWidget' => function ($sm) {
					$locator = $sm->getServiceLocator();
					$viewHelper = new View\Helper\MfccContactWidget;
					$viewHelper->setContactForm($locator->get('MfccZendeskContact\Form\ContactForm'));
					return $viewHelper;
				},
			),
    	);
    }
}
