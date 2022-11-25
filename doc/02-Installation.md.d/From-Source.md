# Installing Icinga Web Generic TTS Integration from Source

These are the instructions for manual Generic TTS installations.

Please see the Icinga Web documentation on
[how to install modules](https://icinga.com/docs/icinga-web-2/latest/doc/08-Modules/#installation) from source.
Make sure you use `generictts` as the module name. The following requirements must also be met.

## Requirements

* [Icinga Web](https://github.com/Icinga/icingaweb2)

## Installing from Release Tarball

Download the [latest version](https://github.com/Icinga/icingaweb2-module-generictts/releases)
and extract it to a folder named `generictts` in one of your Icinga Web module path directories.

You might want to use a script as follows for this task:

```shell
MODULE_VERSION="2.0.0"
ICINGAWEB_MODULEPATH="/usr/share/icingaweb2/modules"
REPO_URL="https://github.com/icinga/icingaweb2-module-generictts"
TARGET_DIR="${ICINGAWEB_MODULEPATH}/generictts"
URL="${REPO_URL}/archive/v${MODULE_VERSION}.tar.gz"

install -d -m 0755 "${TARGET_DIR}"
wget -q -O - "$URL" | tar xfz - -C "${TARGET_DIR}" --strip-components 1
icingacli module enable generictts
```

## Installing from Git Repository

Another convenient method is to install directly from our Git repository.
Simply clone the repository in one of your Icinga Web module path directories.

You might want to use a script as follows for this task:

```shell
MODULE_VERSION="2.0.0"
ICINGAWEB_MODULEPATH="/usr/share/icingaweb2/modules"
REPO_URL="https://github.com/icinga/icingaweb2-module-generictts"
TARGET_DIR="${ICINGAWEB_MODULEPATH}/generictts"

git clone "${REPO_URL}" "${TARGET_DIR}" --branch v${MODULE_VERSION}
icingacli module enable generictts
```

<!-- {% include "02-Installation.md" %} -->
