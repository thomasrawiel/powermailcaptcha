# powermailcaptcha

Captcha Extension for TYPO3 powermail to prevent spam

## Dependencies

* powermail >= 8.0
* TYPO3 10 or 11


## Installation

Just install this extension via composer `composer require traw/powermailcaptcha`

There are currently 3 possible captcha methods to use: 


### Google Recaptcha
- Register your domain to [www.google.com/recaptcha/](https://www.google.com/recaptcha/about/)
- Works with reCAPTCHA, Version 2
- Add sitekey and secretkey to TypoScript Constants (see example below)
- In TypoScript Constants, set the captcha method to `recaptcha` (This is the default)

### Friendly Captcha
- Register your domain to [friendlycaptcha.com](https://docs.friendlycaptcha.com/#/installation)
- Add sitekey and secretkey to TypoScript Constants (see example below)
- In TypoScript Constants, set the captcha method to `friendlycaptcha`

### hCaptcha
- Register your domain to [hcaptcha.com](https://docs.hcaptcha.com/)
- Add sitekey and secretkey to TypoScript Constants (see example below)
- In TypoScript Constants, set the captcha method to `hcaptcha`


### General
- Ensure that spamshield is enabled (see below)
- Add a field of Type Powermailcaptcha to your powermail form
- Have fun

Example for TypoScript Constants:

```
plugin.tx_powermailcaptcha.captchaMethod = recaptcha
plugin.tx_powermailcaptcha.captchaMethod = friendlycaptcha
plugin.tx_powermailcaptcha.captchaMethod = hcaptcha
plugin.tx_powermailcaptcha.sitekey = 6LdsBBUTAAAAAKMhI67inzeAvzBh5JdRRxlCwbTz
plugin.tx_powermailcaptcha.secretkey = 6LdsBBUTAAAAAKMhaaaainzeAvzBh5JdRRxlCwbyy
```

### Disable captcha
From versions >= 1.3.0

In case you wish to disable the captcha temporarily, just add the following line to your typoscript constants:
`plugin.tx_powermailcaptcha.enable = 0`

You can also find this option in the Constant Editor.

### Captcha Language
From Versions >=1.1.0

The current language is added to the captcha via language parameter which is taken from your Site Configuration.

To disable this behavior, add the following to your Typoscript Constants:
```
plugin.tx_powermailcaptcha.useSiteLanguage = 0
```

Depending on your website language, not all languages are supported for every Captcha-Method.
Read
* [Friendly Captcha](http://docs.friendlycaptcha.com/#/widget_api?id=data-lang-attribute)
* [HCaptcha](https://docs.hcaptcha.com/languages/)
* [Google Recaptcha](https://developers.google.com/recaptcha/docs/language)

for further information on supported language codes.

## Common pitfalls and best practice

spamshield must be enabled in powermail (TypoScript setup):

```
plugin.tx_powermail.settings.setup.spamshield._enable = 1
```

Keep up to date if powermail recognize spam (TypoScript setup):

```
# Get an email if spam was recognized
plugin.tx_powermail.settings.setup.spamshield.email = spamreceiver@yourdomain.de

# Write to a logfile when spam was recognized
plugin.tx_powermail.settings.setup.spamshield.logfileLocation = typo3temp/logs/powermailSpam.log
```

Remove the default captcha field by adding the following to your Page TSConfig:
`TCEFORM.tx_powermail_domain_model_field.type.removeItems = captcha`



## Upgrade from 1.0.x to >=1.1.x
If you override powermail's partial `Partial/Form/Page.html` in your own extension, make sure to add the variable `languageIso` to the f:render of the Fields.

e.g.
```
<f:render partial="Form/Field/{vh:String.Upper(string:field.type)}" arguments="{field:field, languageIso:languageIso}"/>
```


If you override powermail's FormController in your own extension, please see `EXT:powermailcaptcha/Classes/Controller/FormController.php` on how this extension adds the language variable.



## Credits
This extension is based on [EXT:powermailrecaptcha](https://github.com/einpraegsam/powermailrecaptcha) by einpraegsam.


## Changelog

| Version | Date       | Description                                                                                         |
|---------|------------|-----------------------------------------------------------------------------------------------------|
| 1.3.0   | 2023-06-05 | Feature: add option to disable frontend output
| 1.2.0   | 2023-06-02 | Code Maintenance: simplifiy controller code                                                                                      |
| 1.1.0   | 2023-05-11 | Add option to force website language onto the Captcha                                                                                     |
| 1.0.0   | 2022-10-18 | Initial release                                                                                     |
