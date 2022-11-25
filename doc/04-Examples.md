# Examples

Below you can find a working example for JIRA and OTRS.

```
[jira]
pattern = "/([A-Z]+-[0-9]{1,9})/i"
url = "https://jira.example.com/browse/$1"

[otrs]
pattern = "/#([0-9]{1,9})/"
url = "http://otrs.example.com/otrs/index.pl?Action=AgentTicketSearch&Subaction=Search&TicketNumber=$1"
```