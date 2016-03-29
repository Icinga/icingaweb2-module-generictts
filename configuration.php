<?php
/* Icinga Web 2 | (c) 2015 Icinga Development Team | GPLv2+ */

/** @var $this \Icinga\Application\Modules\Module */

$this->provideConfigTab('integrations', array(
    'label' => $this->translate('TTS Integrations'),
    'title' => $this->translate('Manage trouble ticket system integrations'),
    'url'   => 'integrations'
));
