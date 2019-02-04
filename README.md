# ReferralCandy Integration for Magento2

A Magento2 Module providing integration with
[Referral Candy](https://www.referralcandy.com/).

## Installation

This module can be installed via composer and the Magento2 command-line tool.
For example:

    composer require do-it-well/magento2-module-referralcandy
    ./bin/magento module:enable DIW_ReferralCandy
    ./bin/magento setup:upgrade

## Configuration

By default, the module will be "enabled", but inactive. The module will not
output anything to your site until it has been configured. If nothing else,
you will need to fill in the "App ID" and "Secret Key" fields, the values for
which can be found under the "Plugin tokens" sections of your Referral Candy
account profile.

Module configuration can be found under the Magento2 Admin Panel, in the
section:

**Stores** > **Settings** > **Configuration** > **Sales** > **Referral Candy** >
**Tracking**

Configuration options are:

| Option | Description                                       | Default |
|--------|---------------------------------------------------|---------|
| Enable | Allows module output to be completely toggled on/off | Yes |
| App ID | Identifier for your Referral Candy account. Nothing will be output unless this option has been configured. | (none) |
| Secret Key | The Secret Key for your Referral Candy account. Nothing will be output unless this option has been configured. | (none) |
| Send Invoice Email Copy To | (optional) Email Address to send a copy of Invoice Emails to. Leave blank to disable. | (none) |

Note that the "Send Invoice Email Copy To" option is dependent on the
"Send Invoice Email Copy Method" option under:

**Stores** > **Settings** > **Configuration** > **Sales** > **Sales Emails** >
**Invoice**

This option defaults to "Bcc", which is correct for Referral Candy. The email
copy will not be sent unless both options are set correctly.

## License

All module code within this repository is licensed under the MIT license. See
the LICENSE.md file for details.

Do It Well Limited is not in any way affiliated with Referral Candy. This module
has been developed independently, without any direction or explicit or implicit
endorsement or vetting on the part of those services to which it attempts to
integrate.

## Support

If you encounter any problems with this module, you may open an issue on GitHub
at https://github.com/do-it-well/magento2-module-referralcandy/issues

Premium support, assistance in module installation or configuration, or other
development services, can be obtained by contacting
[Do It Well Limited](https://do-it-well.co.uk/)
