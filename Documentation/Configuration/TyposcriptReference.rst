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

   - Google Recaptcha v2 (Checkbox) `recaptcha`
   - Google Recaptcha v3 `recaptcha3`
   - Friendly Captcha v1 `friendlycaptcha`
   - Friendly Captcha v2 `friendlycaptcha2`
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

Additional configuration for friendlycaptcha
-----------------------------

.. confval:: plugin.tx_powermailcaptcha.endpoint
    :type: string
    :default: default

    .. versionadded:: 3.1.2

    .. versionadded:: 3.3.0

    As a premium feature friendlycaptcha offers a dedicated endpoint hosted in Germany as an additional guarantee that the personal information (i.e. visitor IP addresses) never leave the EU

    To use the EU-endpoint change the value to `eu`

    Allowed values: `default|eu`

    Available for both v1 and v2

Additional configuration for Google recaptcha v3
-----------------------------

.. confval:: plugin.tx_powermailcaptcha.minScore
    :type: number
    :default: 0.8

    .. versionadded:: 3.4.0

    Defines the minimum score required for a reCAPTCHA v3 response to be considered legitimate.

    reCAPTCHA v3 assigns a score between 0.0 and 1.0 to indicate the likelihood that an interaction is legitimate. Higher scores indicate a higher likelihood of a human interaction, while lower scores indicate a higher likelihood of a bot.

    See the `Google reCAPTCHA v3 documentation <https://developers.google.com/recaptcha/docs/v3#interpreting_the_score>`__ for details on interpreting scores.


.. confval:: plugin.tx_powermailcaptcha.action
    :type: string
    :default: powermail_form_submit

    .. versionadded:: 3.4.0

    Defines the expected action name for the reCAPTCHA v3 response. The returned action must match this value for the response to be considered valid.

    See the `Google reCAPTCHA v3 documentation <https://developers.google.com/recaptcha/docs/v3#actions>`__ for details on action names and verification.




