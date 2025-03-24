..  include:: /Includes.rst.txt

.. _recaptcha:

============
Google reCAPTCHA
============

- Register your domain to https://www.google.com/recaptcha/about/
- Add sitekey and secretkey to the :ref:`Typoscript constants <typoscript>`
- In TypoScript Constants, set the captcha method to `recaptcha` (This is the default)

.. important::

    Currently only reCAPTCHA Version 2 is supported