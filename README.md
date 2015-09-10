# Generic TTS - Icinga Web 2 module

This module is a basic sample implementation of the Icinga Web 2 ticket hook. In contrast to specialised modules deeply integrating with specific trouble ticketing systems (TTS), this module only wants to recognize specific patterns (e.g. #12345) and replace them with a link pointing to the related ticket.

## Installation

Like with any other Icinga Web 2 module just drop me to one of your module folders and enable the `generictts` module in your web frontend or on CLI. Of course the `monitoring` module needs to be enabled and that's it, we have no farther dependencies.

## Configuration

Once installed, nothing will happen, Icinga Web 2 continues to work as it did before. Things change once you create a configuration in

    ICINGAWEB\_CONFIGDIR/modules/generictts/config.ini

The following sample should perfectly explain all the available settings:

```ini
[ticket]
pattern = "/#(\d{4,6})/"
url = "https://my.ticket.system/tickets/id=$1"
```

You need to understand regular expressions for this configuration. What happens here is that whenever we stumble over a text containing # followed by four to six digits, that number will be replaced by a link pointing to that specific ticket in your TTS.

## TODO

This module has still alpha quality, but works fine so far. A dedicated config tab with some hints and examples would be great, not everybody speaks regex. I'd also like to see support for multiple patterns at once, allowing one to support multiple TTS systems in parallel with a single Icinga Web installation.





