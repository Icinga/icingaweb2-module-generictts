<?php

namespace Icinga\Module\Generictts;

use Icinga\Application\Config;
use Icinga\Web\Hook\TicketHook;
use Icinga\Web\Url;
use Icinga\Exception\ConfigurationError;

class Ticket extends TicketHook
{
    protected $pattern;
    protected $url;

    protected function init()
    {
        $config = Config::module('generictts');
        if (isset($config->ticket)) {
            $pattern = $config->ticket->get('pattern', null);
            $url = $config->ticket->get('url', null);
            if ($pattern === null || $url === null) {
                return;
            }
            $this->pattern = $pattern;
            $this->url = $url;
        }
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
