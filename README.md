[![Total Downloads](https://poser.pugx.org/mfcc/zendesk-contact/downloads.png)](https://packagist.org/packages/rinomau/mva-crud)

-----------------
MfccZendeskContact
-----------------

This Modules provides a simple contact-form with spam-protection using a
honeypot and Zendesk API for ticket creation. It is based on the OrgHeiglContact module by Andreas Heigl (https://github.com/heiglandreas/OrgHeiglContact).

The idea of the honeypot is based on a blogpost by Lorna Jane Mitchell (according to Andreas Heigl)

Install:
------

The suggested installation method is via [composer](http://getcomposer.org/):

```sh
php composer.phar require mfcc/zendesk-contact:dev-master
```

Usage:
------

1. In your application.conf-file add the Module to the list of modules
2. Configure your settings
3. Link to the Form using ``$this->url('contact')``
4. There is no step four.

View plugin:
------

Use ``echo $this->mfccContactWidget()`` to show contact form anywhere in your views

Extend form:
------

Extend base contact form using init listeners in your Module.php like this:

	public function onBootstrap(MvcEvent $e)
    	{
	        $eventManager        = $e->getApplication()->getEventManager();
	        $moduleRouteListener = new ModuleRouteListener();
	        $moduleRouteListener->attach($eventManager);
	        
		$em = $eventManager->getSharedManager();
		$em->attach(
			'MfccZendeskContact\Form\ContactForm',
			'init',
			function($e)
			{
				$form = $e->getTarget();
				$form->add(
					array(
						'name' => 'username',
						'options' => array(
							'label' => 'Username',
						),
						'attributes' => array(
							'type'  => 'text',
						),
					)
				);
			}
		);
	}

Note that you need to render and process form for yourself if you extend it.

To process your customized form, you can use zendesk service. Customize your zendesk service with 
``$this->getServiceLocator()->get('zendeskService')->addTag($tag)``
``$this->getServiceLocator()->get('zendeskService')->addCustomField($key, $value)`` 
etc. 
See ``ZendeskService.php``

And create ticket like this:

	$this->getServiceLocator()->get('zendeskService')->createTicket($fromName, $fromEmail, $yourCustomizedBody);


