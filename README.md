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


## Credits
This extension is based on [EXT:powermailrecaptcha](https://github.com/einpraegsam/powermailrecaptcha) by einpraegsam. 


## Changelog

| Version    | Date       | Description                                                                                         |
| ---------- |------------|-----------------------------------------------------------------------------------------------------|
| 1.0.0      | 2022-10-18 | Initial release                                                                                     |
