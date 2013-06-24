<?php

namespace MfccZendeskContact\View\Helper;

use Zend\View\Helper\AbstractHelper;
use MfccZendeskContact\Form\ContactForm as ContactForm;
use Zend\View\Model\ViewModel;

class MfccContactWidget extends AbstractHelper
{
    /**
     * Contact Form
     * @var ContactForm
     */
    protected $contactForm;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;
    
    /**
     * __invoke
     *
     * @access public
     * @param array $options array of options
     * @return string
     */
    public function __invoke($options = array())
    {
        $vm = new ViewModel(array(
            'form' => $this->getContactForm(),
        ));
        $vm->setTemplate('mfcc-zendesk-contact/contact/index.phtml');
        
        return $this->getView()->render($vm);
    }

    /**
     * Retrieve Contact Form Object
     * @return ContactForm
     */
    public function getContactForm()
    {
        return $this->contactForm;
    }

    /**
     * Inject Contact Form Object
     * @param ContactForm $contactForm
     * @return MfccContactWidget
     */
    public function setContactForm(ContactForm $contactForm)
    {
        $this->contactForm = $contactForm;
        return $this;
    }
    
    /**
     * @param string $viewTemplate
     * @return MfccContactWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }
    
}
