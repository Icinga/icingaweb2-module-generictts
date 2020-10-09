# Generic TTS - Icinga Web 2 module

Generic TTS implements Icinga Web 2's ticket hook for replacing ticket patterns
with links to your trouble ticket system (TTS).
Icinga Web 2's core module `monitoring` for example uses the ticket hook for
acknowledgements, downtimes and comments.
Other modules may use the ticket hook for all kinds of text too.

## Installation

Like with any other Icinga Web 2 module just drop `generictts` into the modules directory and enable
the module in your web frontend or via Web 2's CLI.

This module has no dependencies.

## Configuration

After you've enabled `generictts` you reach its configuration in Icinga Web 2 via the module's configuration tab. 
But you may also change its configuration manually.
`generictts` maintains a configuration file which is normally located at:

```
/etc/icingaweb2/modules/generictts/config.ini
```

You have to understand regular expressions for this configuration. A pattern
must be provided that captures the relevant value into capture group `$1`.

What happens in the following example here is that whenever we stumble over a
text containing a # followed by four to six digits, that number will be
replaced by a link pointing to that specific ticket in your TTS.

```ini
[my-ticket-system]
pattern = "/#([0-9]{4,6})/"
url = "https://my.ticket.system/tickets/id=$1"
```




## Examples

Below you can find a working example for JIRA and OTRS.

```
[jira]
pattern = "/([A-Z]+-[0-9]{1,9})/i"
url = "https://jira.example.com/browse/$1"

[otrs]
pattern = "/#([0-9]{1,9})/"
url = "http://otrs.example.com/otrs/index.pl?Action=AgentTicketSearch&Subaction=Search&TicketNumber=$1"
```
