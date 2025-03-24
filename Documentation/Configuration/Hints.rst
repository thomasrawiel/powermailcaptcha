..  include:: /Includes.rst.txt

.. _hints:

=========
Hints
=========

**Required**: Make sure powermail spamshield is enabled::

    plugin.tx_powermail.settings.setup.spamshield._enable = 1

---

**Optional**: Keep up to date if powermail recognize spam (TypoScript setup)::

    # Get an email if spam was recognized
    plugin.tx_powermail.settings.setup.spamshield.email = spamreceiver@yourdomain.de

    # Write to a logfile when spam was recognized
    plugin.tx_powermail.settings.setup.spamshield.logfileLocation = typo3temp/logs/powermailSpam.log

---

**Optional**: Remove the default captcha field by adding the following to your Page TSConfig::

    TCEFORM.tx_powermail_domain_model_field.type.removeItems := addToList(captcha)

---

..  versionadded:: 1.1.0

**Required**: If you override powermail's partial `Partial/Form/Page.html` in your own extension, make sure to add the variable `languageIso` to the f:render of the Fields::

    <f:render partial="Form/Field/{vh:String.Upper(string:field.type)}" arguments="{field:field, languageIso:languageIso}"/>



