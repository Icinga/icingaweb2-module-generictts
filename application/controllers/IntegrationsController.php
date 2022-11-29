<?php

/* Icinga Web 2 | (c) 2016 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Generictts\Controllers;

use Icinga\Exception\NotFoundError;
use Icinga\Forms\ConfirmRemovalForm;
use Icinga\Module\Generictts\Forms\Config\TtsIntegrationConfigForm;
use Icinga\Web\Controller;
use Icinga\Web\Notification;

/**
 * Manage trouble ticket system integrations
 */
class IntegrationsController extends Controller
{
    /**
     * List trouble ticket system integrations
     */
    public function indexAction()
    {
        $this->getTabs()->add('integrations', array(
            'active'    => true,
            'label'     => $this->translate('Integrations'),
            'url'       => $this->getRequest()->getUrl()
        ));
        $this->view->integrations = $this->Config();
    }

    /**
     * Integrate a new trouble ticket system
     */
    public function newAction()
    {
        $this->getTabs()->add('new-integration', array(
            'active'    => true,
            'label'     => $this->translate('New Integration'),
            'url'       => $this->getRequest()->getUrl()
        ));

        $integrations = new TtsIntegrationConfigForm();
        $integrations
            ->setIniConfig($this->Config())
            ->setRedirectUrl('generictts/integrations')
            ->handleRequest();

        $this->view->form = $integrations;
    }

    /**
     * Remove a trouble ticket system integration
     */
    public function removeAction()
    {
        $integration = $this->params->getRequired('integration');

        $this->getTabs()->add('remove-integration', array(
            'active'    => true,
            'label'     => $this->translate('Remove Integration'),
            'url'       => $this->getRequest()->getUrl()
        ));

        $integrations = new TtsIntegrationConfigForm();
        try {
            $integrations
                ->setIniConfig($this->Config())
                ->bind($integration);
        } catch (NotFoundError $e) {
            $this->httpNotFound($e->getMessage());
        }

        $confirmation = new ConfirmRemovalForm(array(
            'onSuccess' => function (ConfirmRemovalForm $confirmation) use ($integration, $integrations) {
                $integrations->remove($integration);
                if ($integrations->save()) {
                    Notification::success(mt('generictts', 'TTS integration removed'));
                    return true;
                }
                return false;
            }
        ));
        $confirmation
            ->setRedirectUrl('generictts/integrations')
            ->setSubmitLabel($this->translate('Remove Integration'))
            ->handleRequest();

        $this->view->form = $confirmation;
    }

    /**
     * Update a trouble ticket system integration
     */
    public function updateAction()
    {
        $integration = $this->params->getRequired('integration');

        $this->getTabs()->add('update-integration', array(
            'active'    => true,
            'label'     => $this->translate('Update Integration'),
            'url'       => $this->getRequest()->getUrl()
        ));

        $integrations = new TtsIntegrationConfigForm();
        try {
            $integrations
                ->setIniConfig($this->Config())
                ->bind($integration);
        } catch (NotFoundError $e) {
            $this->httpNotFound($e->getMessage());
        }
        $integrations
            ->setRedirectUrl('generictts/integrations')
            ->handleRequest();

        $this->view->form = $integrations;
    }
}
