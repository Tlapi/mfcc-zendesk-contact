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
namespace MfccZendeskContact\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use MfccZendeskContact\Form\ContactForm;
use Zend\View\Model\ViewModel;
use MfccZendeskContact\Service\ZendeskService;

/**
 * The Contact-Form Controller
 *
 * @category  ContactForm
 * @package   MfccZendeskContact
 */
class ContactController extends AbstractActionController
{
    /**
     * The storage of the form-object
     *
     * @var ContactForm $form
     */
    protected $form;

    /**
     * Zendesk service
     *
     * @var ZendeskService $zendeskService
     */
    protected $zendeskService;


    /**
     * Create the Controller-Instance
     * 
     * @param ContactForm $form
     */
    public function __construct(ContactForm $form = null)
    {
        if (null !== $form) {
             $this->setContactForm($form);
        }
    }

    /**
     * Set the given form as contact-form
     *
     * @param ContactForm $form
     *
     * @return ContactController
     */
    public function setContactForm(ContactForm $contactForm)
    {
        $this->form = $contactForm;
        $this->form->init();
        return $this;
    }
    
    /**
     * Set zendesk service
     *
     * @param ZendeskService $zendeskService
     *
     * @return ContactController
     */
    public function setZendeskService(ZendeskService $zendeskService)
    {
        $this->zendeskService = $zendeskService;
        return $this;
    }

    /**
     * Display a contact-form
     *
     * @return void
     */
    public function indexAction()
    {
        return array('form' => $this->form);
    }

    /**
     * Process the form
     *
     * @return void
     */
    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('contact');
        }
        $post = $this->request->getPost();
        $form = $this->form;
        $form->setData($post);
        if (!$form->isValid()) {
            $view = new ViewModel(array(
                        'error' => true,
                        'form'  => $form
            ));
            $view->setTemplate('mfcc-zendesk-contact/contact/index');
            return $view;
        }
        // create ticket
        //$this->createTicket($form->getData());
        return $this->redirect()->toRoute('contact/thank-you');
    }

    /**
     * Create zendesk ticket
     *
     * @param array $params The parameters to include in the ticket
     *
     * @return boolean
     */
    protected function createTicket($values)
    {
        $from    = $values['from'];
        //$subject = '[Contact Form] ' . $values['subject'];
        $name    = $values['name'];
        $body    = $values['body'];

        return $this->zendeskService->createTicket($name, $from, $body);
    }

    /**
     * Display a thank-you message
     *
     * @return void
     */
    public function thankYouAction()
    {
    	/*
        $headers = $this->request->getHeaders();
        if (!$headers->has('Referer')
            || !preg_match('#/contact$#',
        $headers->get('Referer')->getFieldValue())
        ) {
            return $this->redirect()->toRoute('contact');
        }*/
    	// TODO check referer

        return array();

    }
}
