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


