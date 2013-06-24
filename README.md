-----------------
MfccZendeskContact
-----------------

This Modules provides a simple contact-form with spam-protection using a
honeypot and Zendesk API for ticket creation. It is based on the OrgHeiglContact module by Andreas Heigl (https://github.com/heiglandreas/OrgHeiglContact).

The idea of the honeypot is based on a blogpost by Lorna Jane Mitchell (according to Andreas Heigl)

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

Extend base contact form using init listeners like this:

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

Note that you need to render and process form for yourself if you extend it.

To process your customized form, you can use zendesk service. Customize your zendesk service with ``$this->zendeskService->addTag($tag)``, ``$this->zendeskService->addCustomField($key, $value)`` etc. See ``ZendeskService.php``

And create ticket like this:

	$this->zendeskService->createTicket($fromName, $fromEmail, $yourCustomizedBody);


