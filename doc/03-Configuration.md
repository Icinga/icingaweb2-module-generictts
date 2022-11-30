# Configuration

After enabling `generictts`,
you can access its configuration in Icinga Web via the module's configuration tab.
But you can also change its configuration manually.
`generictts` maintains a configuration file that is normally located under the following path:

```
/etc/icingaweb2/modules/generictts/config.ini
```

## Example Configuration

You need to understand regular expressions for the configuration.
A pattern has to be specified that captures a ticket reference for use in a link to your ticket system.

In the following example, every time we encounter an acknowledgement, downtime, or comment
that contains a hash `#` followed by digits, that number is stored in the capturing group `$1`
and replaced with a link that references that specific ticket in your ticket system.

```ini
[my-ticket-system]
pattern = "/#([0-9]+)/"
url = "https://my.ticket.system/tickets/id=$1"
```
