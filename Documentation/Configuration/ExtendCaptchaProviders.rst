..  include:: /Includes.rst.txt

.. _extend:

=========
Extend Captcha Providers
=========

.. versionadded:: 3.2.0

CAPTCHA validation can be extended and customized via two events:

CaptchaConfigurationEvent
===========

The CAPTCHA provider configuration is extensible via the CaptchaConfigurationEvent. New providers can be added or existing ones overridden as long as they follow the expected configuration structure defined below.

Adjust or add providers: Use this event to modify the available CAPTCHA providers or their configuration before validation is executed.

Typical use cases:

- Add a custom CAPTCHA provider
- Override endpoints or request parameters
- Change request methods (GET/POST)

.. code-block:: php
    :caption: EXT:my_ext/Classes/EventListener/MyCaptchaConfigurationListener.php

    final class MyCaptchaConfigurationListener
    {
        public function __invoke(CaptchaConfigurationEvent $event): void
        {
            $configuration = $event->getConfiguration();

            $configuration['mycaptcha'] = [
                'siteVerifyUri' => 'https://example.com/verify',
                'verifyMethod' => 'POST',
                'responseKey' => 'my-captcha-response',
                'secretParameter' => 'secret',
                'responseParameter' => 'token',
            ];

            $event->setConfiguration($configuration);
        }
    }


CAPTCHA provider configuration
-----------

Each CAPTCHA provider is defined via a configuration array that controls how the request is built and how the response is processed. These values allow the validation logic to remain provider-agnostic.

Available configuration keys

.. confval:: siteVerifyUri
   :name: siteVerifyUri
   :required: true
   :type: string

    Example: https://api.prosopo.io/siteverify

    Defines the endpoint URL used for server-side verification of the CAPTCHA response.

    This URI is called during the verification request to the external CAPTCHA provider.

.. confval:: verifyMethod
   :name: verifyMethod
   :required: true
   :type: string

    Allowed values: GET, POST

    Defines the HTTP method used when sending the verification request to the provider.

    GET: Parameters are appended to the query string
    POST: Parameters are sent as form_params in the request body


.. confval:: responseKey
   :name: responseKey
   :required: true
   :type: string

    Example: procaptcha-response

    Defines the name of the field in the incoming frontend request that contains the CAPTCHA response token.

    This value is used to extract the CAPTCHA response from the submitted form data.

.. confval:: secretParameter
   :name: secretParameter
   :required: true
   :type: string

    Example: secret

    Defines the parameter name used to send the server-side secret key to the CAPTCHA provider.

    This key is included in the verification request payload.

.. confval:: responseParameter
   :name: responseParameter
   :required: true
   :type: string

    Example: token

    Defines the parameter name under which the CAPTCHA response token is sent to the provider during verification.

    This allows different providers to use different expected parameter names.

Examples can be found in the default provider configration shipped with the extension:

.. code-block:: php
   :caption: EXT:powermailcaptcha/Classes/Domain/Validator/SpamShield/CaptchaMethod.php

   $defaultCaptchaConfiguration = [
       'recaptcha' => [
           'siteVerifyUri' => 'https://www.google.com/recaptcha/api/siteverify',
           'verifyMethod' => 'GET',
           'responseKey' => 'g-recaptcha-response',
           'secretParameter' => 'secret',
           'responseParameter' => 'response',
       ],
       'friendlycaptcha' => [
           'siteVerifyUri' => 'https://api.friendlycaptcha.com/api/v1/siteverify',
           'verifyMethod' => 'POST',
           'responseKey' => 'frc-captcha-solution',
           'secretParameter' => 'secret',
           'responseParameter' => 'solution',
       ],
       'hcaptcha' => [
           'siteVerifyUri' => 'https://hcaptcha.com/siteverify',
           'verifyMethod' => 'POST',
           'responseKey' => 'h-captcha-response',
           'secretParameter' => 'secret',
           'responseParameter' => 'response',
       ],
       'cloudflare' => [
           'siteVerifyUri' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
           'verifyMethod' => 'POST',
           'responseKey' => 'cf-turnstile-response',
           'secretParameter' => 'secret',
           'responseParameter' => 'response',
       ],
       'procaptcha' => [
           'siteVerifyUri' => 'https://api.prosopo.io/siteverify',
           'verifyMethod' => 'POST',
           'responseKey' => 'procaptcha-response',
           'secretParameter' => 'secret',
           'responseParameter' => 'token',
       ],
   ];


ExpectedResponseEvent
===========

Define validation logic: Use this event to control how the raw provider response is interpreted and converted into a boolean validation result.

Typical use cases:

- Implement provider-specific validation logic
- Override default success criteria
- Handle non-standard API responses

Example:

.. code-block:: php
    :caption: EXT:my_ext/Classes/EventListener/MyExpectedResponseListener.php

    final class MyExpectedResponseListener
    {
        public function __invoke(ExpectedResponseEvent $event): void
        {
            $configuration = $event->getConfiguration();
            $result = $event->getResult();

            if (($configuration['captchaMethod'] ?? null) === 'mycaptcha') {
                $event->setResponse(
                    ($result->status ?? null) === 'valid'
                    && ($result->score ?? 0) > 0.5
                );
            }
        }
    }


How both events work together
-------------------------

`CaptchaConfigurationEvent` defines how a provider is called

`ExpectedResponseEvent` defines how the response is interpreted

This separation allows you to fully integrate custom CAPTCHA providers without modifying core logic.