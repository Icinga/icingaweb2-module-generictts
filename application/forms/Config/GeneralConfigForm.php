<?php
/* Icinga Web 2 | (c) 2013-2015 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Generictts\Forms\Config;

use Zend_Validate_Callback;
use Icinga\Forms\ConfigForm;

class GeneralConfigForm extends ConfigForm
{
    /**
     * Initialize this form
     */
    public function init()
    {
        $this->setName('form_config_generictts_general');
        $this->setSubmitLabel(t('Save Changes'));
    }

    /**
     * {@inheritdoc}
     */
    public function createElements(array $formData)
    {
        $patternValidator = new Zend_Validate_Callback(function ($value) {
            return @preg_match($value, '') !== false;
        });
        $patternValidator->setMessage(
            t('"%value%" is not a valid regular expression.'),
            Zend_Validate_Callback::INVALID_VALUE
        );
        $this->addElement(
            'text',
            'ticket_pattern',
            array(
                'value'         => '/this-will-not-match(\d{3-6})/',
                'label'         => $this->translate('TTS Ticket Pattern'),
                'description'   => $this->translate('The pattern to extract a ticket\'s id from comments and plugin output.'),
                'requirement'   => $this->translate('The ticket pattern must be a valid regular expression.'),
                'validators'    => array($patternValidator)
            )
        );

        $urlValidator = new Zend_Validate_Callback(function ($value) {
            return strpos($value, '$1') !== false;
        });
        $urlValidator->setMessage(
            $this->translate('The url must contain the placeholder $1 to be substituted with a ticket\'s id.'),
            Zend_Validate_Callback::INVALID_VALUE
        );
        $this->addElement(
            'text',
            'ticket_url',
            array(
                'value'         => 'http://no-such-domain.example.com/ticket?id=$1',
                'label'         => $this->translate('TTS Ticket Url'),
                'description'   => $this->translate('The URL to substitute with a ticket\'s id.'),
                'requirement'   => $this->translate('Use $1 as the placeholder for the id.'),
                'validators'    => array($urlValidator)
            )
        );
    }
}
