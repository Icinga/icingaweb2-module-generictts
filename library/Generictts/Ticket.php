<?php
/* Icinga Web 2 | (c) 2014 Icinga Development Team | GPLv2+ */

namespace Icinga\Module\Generictts;

use Icinga\Application\Config;
use Icinga\Application\Hook\TicketHook;

/**
 * GenericTTS TicketHook implementation
 */
class Ticket extends TicketHook
{
    /**
     * GenericTTS configuration
     *
     * @var \Icinga\Application\Config
     */
    protected $config;

    /**
     * Configured trouble ticket system integrations
     *
     * @var \Icinga\Application\Hook\Ticket\TicketPattern[]
     */
    protected $ticketPatterns;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $config = Config::module('generictts');
        $this->config = $config;

        $ticketPatterns = array();
        foreach ($config as $section => $values) {
            if ($values->get('url')) { // Skip integrations that don't contain a URL
                $ticketPattern = $this->createTicketPattern($section, $values->get('pattern'));
                if ($ticketPattern->isValid()) { // Skip integrations that don't contain a pattern
                    $ticketPatterns[$section] = $ticketPattern;
                }
            }
        }
        $this->ticketPatterns = $ticketPatterns;
    }

    /**
     * {@inheritdoc}
     *
     * @return  \Icinga\Application\Hook\Ticket\TicketPattern[]
     */
    public function getPattern()
    {
        return $this->ticketPatterns;
    }

    /**
     * {@inheritdoc}
     */
    public function createLink($match)
    {
        /** @var \Icinga\Application\Hook\Ticket\TicketPattern $match */
        return sprintf(
            '<a href="%s" target="_blank">%s</a>',
            preg_replace('/\$1/', rawurlencode($match[1]), $this->config->get($match->getName(), 'url')),
            $match[0]
        );
    }
}
