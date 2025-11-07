..  include:: /Includes.rst.txt

..  _typoscript:

============
Typoscript Reference
============

.. important::
   Make sure powermail spamshield is enabled::

    plugin.tx_powermail.settings.setup.spamshield._enable = 1


This is an overview of the available Typoscript constants. They are also available in the Constant Manager.

.. contents::

Enable / disable
-----------------------------

..  confval:: plugin.tx_powermailcaptcha.enable
    :type: bool
    :default: true

    ..  versionadded:: 1.3.0

    In case you wish to disable the captcha temporarily, just add the following line to your typoscript constants:
    `plugin.tx_powermailcaptcha.enable = 0`

    You can also find this option in the Constant Editor.

Sitelanguage
-----------------------------

.. confval:: plugin.tx_powermailcaptcha.useSiteLanguage
   :type: bool
   :default: true

    ..  versionadded:: 1.1.0

    The current language is added to the captcha via language parameter which is taken from your Site Configuration.

    To disable this behavior, add the following to your Typoscript Constants:

    `plugin.tx_powermailcaptcha.useSiteLanguage = 0`

Captcha API Keys
-----------------------------

.. confval:: plugin.tx_powermailcaptcha.captchaMethod
   :type: string
   :required: true

   Available values:

   - Google Recaptcha `recaptcha`
   - Friendly Captcha `friendlycaptcha`
   - hCaptcha `hcaptcha`
   - Cloudflare Turnstile `cloudflare`
   - Prosopo Procaptcha `procaptcha`

   See :ref:`Supported Captcha Types <captchaTypes>`

.. confval:: plugin.tx_powermailcaptcha.sitekey
   :type: string
   :required: true

    Captcha Sitekey/ Application ID

.. confval:: plugin.tx_powermailcaptcha.secretkey
    :type: string
    :required: true

    Captcha Secretkey / API key


