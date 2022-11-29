<?php

/* Icinga Web 2 | (c) 2016 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Generictts\Forms\Config;

use Zend_Validate_Callback;
use Icinga\Exception\AlreadyExistsException;
use Icinga\Exception\IcingaException;
use Icinga\Exception\NotFoundError;
use Icinga\Forms\ConfigForm;
use Icinga\Web\Form\Validator\UrlValidator;
use Icinga\Web\Notification;

/**
 * Form for managing trouble ticket system integrations
 */
class TtsIntegrationConfigForm extends ConfigForm
{
    /**
     * Name of the integration if the form is bound to one
     *
     * @var string
     */
    protected $boundIntegration;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setName('form_config_generictts_tts_integrations');
    }

    /**
     * {@inheritdoc}
     */
    public function createElements(array $formData)
    {
        $this->addElement(
            'text',
            'name',
            array(
                'description'   => $this->translate('The name of the TTS integration'),
                'label'         => $this->translate('Name'),
                'required'      => true
            )
        );

        $patternValidator = new Zend_Validate_Callback(function ($value) {
            return @preg_match($value, '') !== false;
        });
        $patternValidator->setMessage(
            $this->translate('"%value%" is not a valid regular expression.'),
            Zend_Validate_Callback::INVALID_VALUE
        );
        $this->addElement(
            'text',
            'pattern',
            array(
                'value'         => '/this-will-not-match(\d{3-6})/',
                'label'         => $this->translate('Ticket Pattern'),
                'description'   => $this->translate(
                    'The pattern to extract ticket IDs from comments.'
                    . ' The ticket pattern must be a valid regular expression'
                ),
                'required'      => true,
                'validators'    => array($patternValidator)
            )
        );

        $urlValidator = new Zend_Validate_Callback(function ($value) {
            return strpos($value, '$1') !== false;
        });
        $urlValidator->setMessage(
            $this->translate('The URL must contain the placeholder $1 to be substituted with a ticket ID.'),
            Zend_Validate_Callback::INVALID_VALUE
        );
        $this->addElement(
            'text',
            'url',
            array(
                'value'         => 'http://no-such-domain.example.com/ticket?id=$1',
                'label'         => $this->translate('TTS Ticket URL'),
                'description'   => $this->translate(
                    'The URL pointing to the TTS with $1 as the placeholder for the ticket ID'
                ),
                'required'      => true,
                'validators'    => array($urlValidator, new UrlValidator())
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSubmitLabel()
    {
        if (($submitLabel = parent::getSubmitLabel()) === null) {
            if ($this->boundIntegration === null) {
                $submitLabel = $this->translate('Integrate');
            } else {
                $submitLabel = $this->translate('Update Integration');
            }
        }
        return $submitLabel;
    }

    /**
     * {@inheritdoc}
     */
    public function onRequest()
    {
        // The base class implementation does not make sense here. We're not populating the whole configuration but
        // only a section
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function onSuccess()
    {
        $name = $this->getElement('name')->getValue();
        $values = array(
            'pattern'   => $this->getElement('pattern')->getValue(),
            'url'       => $this->getElement('url')->getValue()
        );
        if ($this->boundIntegration === null) {
            $successNotification = $this->translate('TTS integrated');
            try {
                $this->add($name, $values);
            } catch (AlreadyExistsException $e) {
                $this->addError($e->getMessage());
                return false;
            }
        } else {
            $successNotification = $this->translate('TTS integration updated');
            try {
                $this->update($name, $values, $this->boundIntegration);
            } catch (IcingaException $e) {
                // Exception may be AlreadyExistsException or NotFoundError
                $this->addError($e->getMessage());
                return false;
            }
        }
        if ($this->save()) {
            Notification::success($successNotification);
            return true;
        }
        return false;
    }

    /**
     * Add a TTS integration
     *
     * @param   string  $name           The name of the integration
     * @param   array   $values
     *
     * @return  $this
     *
     * @throws  AlreadyExistsException  If the integration to add already exists
     */
    public function add($name, array $values)
    {
        if ($this->config->hasSection($name)) {
            throw new AlreadyExistsException(
                $this->translate('Can\'t add integration \'%s\'. Integration already exists'),
                $name
            );
        }
        $this->config->setSection($name, $values);
        return $this;
    }

    /**
     * Bind integration to this form
     *
     * @param   string  $name   The name of the integration
     *
     * @return  $this
     *
     * @throws  NotFoundError   If the given integration does not exist
     */
    public function bind($name)
    {
        if (! $this->config->hasSection($name)) {
            throw new NotFoundError(
                $this->translate('Can\'t load integration \'%s\'. Integration does not exist'),
                $name
            );
        }
        $this->boundIntegration = $name;
        $integration = $this->config->getSection($name)->toArray();
        $integration['name'] = $name;
        $this->populate($integration);
        return $this;
    }

    /**
     * Remove a TTS integration
     *
     * @param   string  $name   The name of the integration
     *
     * @return  $this
     *
     * @throws  NotFoundError   If the role does not exist
     */
    public function remove($name)
    {
        if (! $this->config->hasSection($name)) {
            throw new NotFoundError(
                $this->translate('Can\'t remove integration \'%s\'. Integration does not exist'),
                $name
            );
        }
        $this->config->removeSection($name);
        return $this;
    }

    /**
     * Update a TTS integration
     *
     * @param   string  $name       The possibly new name of the integration
     * @param   array   $values
     * @param   string  $oldName    The name of the integration to update
     *
     * @return  $this
     *
     * @throws  NotFoundError       If the integration to update does not exist
     */
    public function update($name, array $values, $oldName)
    {
        if ($name !== $oldName) {
            // The integration got a new name
            $this->remove($oldName);
            $this->add($name, $values);
        } else {
            if (! $this->config->hasSection($name)) {
                throw new NotFoundError(
                    $this->translate('Can\'t update integration \'%s\'. Integration does not exist'),
                    $name
                );
            }
            $this->config->setSection($name, $values);
        }
        return $this;
    }
}
