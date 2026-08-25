..  include:: /Includes.rst.txt

.. _events:

=========
PSR-14 events
=========

.. versionadded:: 1.8.0
.. versionadded:: 2.2.0

The PSR-14 event `BeforeVerifyRequestEvent` has been added in order to manipulate the additional request options
that are sent to the Site-Verify-URL

.. versionadded:: 3.2.0

The `CaptchaConfigurationEvent` has been introduced to make the default CAPTCHA provider configuration extensible, allowing listeners to adjust or extend provider-specific settings (e.g. endpoints, parameter names, request methods) before they are applied.

.. versionadded:: 3.2.0

The `ExpectedResponseEvent` has been introduced to map provider-specific response formats to a unified boolean outcome, ensuring consistent validation handling across different CAPTCHA methods.


`Implementing an event listener in your extension <https://docs.typo3.org/permalink/t3coreapi:eventdispatcherimplementation>`_
