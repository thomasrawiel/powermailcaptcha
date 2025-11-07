..  include:: /Includes.rst.txt

.. _recaptcha:

============
Cloudflare Turnstile
============

.. versionadded:: 1.6.0

- Register your domain to https://www.cloudflare.com/products/turnstile/
- Add sitekey and secretkey to the :ref:`Typoscript constants <typoscript>`
- In TypoScript Constants, set the captcha method to `cloudflare`