# Generic TTS - Icinga Web 2 module

Generic TTS implements Icinga Web 2's ticket hook for replacing ticket patterns
with links to your trouble ticket system (TTS).
Icinga Web 2's core module `monitoring` for example uses the ticket hook for
acknowledgements, downtimes and comments.
Other modules may use the ticket hook for all kinds of text too.

## Installation

Like with any other Icinga Web 2 module just drop `generictts` into the modules directory and enable
the module in your web frontend or via Web 2's CLI. It is important that the module directory is `Generictts` or `generictts`, otherwise Icingaweb2 will throw errors on multiple occasions.

This module has no dependencies.

## Configuration

After you've enabled `generictts` you reach its configuration in Icinga Web 2 via the module's configuration tab. 
But you may also change its configuration manually.
`generictts` maintains a configuration file which is normally located at:

```
/etc/icingaweb2/modules/generictts/config.ini
```

The following sample should perfectly explain all the available settings:

```
[my-ticket-system]
pattern = "/#([0-9]{4,6})/"
url = "https://my.ticket.system/tickets/id=$1"
```

You have to understand regular expressions for this configuration. What happens
here is that whenever we stumble over a text containing a # followed by four to
six digits, that number will be replaced by a link pointing to that specific
ticket in your TTS.

## TODO

Configuration hints and examples would be great because not everybody speaks regex.





