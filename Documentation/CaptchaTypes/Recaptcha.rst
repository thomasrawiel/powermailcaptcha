..  include:: /Includes.rst.txt

.. _recaptcha:

============
Google reCAPTCHA
============

- Register your domain to https://www.google.com/recaptcha/about/
- Add sitekey and secretkey to the :ref:`Typoscript constants <typoscript>`

reCAPTCHA-Type: V2 (Checkbox)
-------------

- In TypoScript Constants, set the captcha method to `recaptcha` (This is the default)

.. note::

    reCAPTCHA Version 2 (invisible) is currently not supported by this extension.


.. versionchanged:: 3.4.0

When using reCAPTCHA-Type:V2, in addition to the captcha response, the current hostname is compared with Google's siteverify response

reCAPTCHA-Type: V3
-------------

.. versionadded:: 3.4.0

- In TypoScript Constants, set the captcha method to `recaptcha3` (This is the default)

There are some additional options that you can change to your liking:

- Minimum score `minScore` (0.0-1.0)
- Expected action `action`

See https://developers.google.com/recaptcha/docs/v3

When using reCAPTCHA-Type:V3, in addition to the captcha response, the current hostname, the score and the action is evaluated and compared with Google's siteverify response.

