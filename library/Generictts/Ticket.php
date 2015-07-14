<?php

namespace Icinga\Module\Generictts;

use Icinga\Application\Config;
use Icinga\Web\Hook\TicketHook;
use Icinga\Web\Url;
use Icinga\Exception\ConfigurationError;

class Ticket extends TicketHook
{
    protected $pattern = '/this-will-not-match(\d{3-6})/';
    protected $url = 'http://no-such-domain.example.com/ticket?id=$1';

    protected function init()
    {
        $config = Config::module('generictts');
        $pattern = $config->get('ticket', 'pattern');
        $url = $config->get('ticket', 'url');
        if ($pattern === null || $url === null) {
            return;
        }
        $this->pattern = $pattern;
        $this->url = $url;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function createLink($id)
    {
        return '<a href="'
            . preg_replace('/\$1/', urlencode($id[1]), $this->url)
            . '" target="_blank">' . $id[0] . '</a>';
    }
}
