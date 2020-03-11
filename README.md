## Paga ExpressCheckout Magento 2 Plugin

Paga ExpressCheckout payment gateway Magento2 extension

## Install

- FTP/SCP into your hosting server and copy the unzipped file into the app/code/Magento folder.

* Enter following commands to enable module:

```bash
php bin/magento module:enable Paga_ExpressCheckout --clear-static-content
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
php bin/magento cache:flush
```

- Enable and configure `Paystack` in _Magento Admin_ under `Stores/Configuration/Payment` Methods

[ico-version]: https://img.shields.io/packagist/v/pstk/paystack-magento2-module.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pstk/paystack-magento2-module.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/pstk/paystack-magento2-module
[link-downloads]: https://packagist.org/packages/pstk/paystack-magento2-module

## Documentation

- [Paga Documentation](https://developer-docs.paga.com/docs/introduction)
- [Paga Developer Community](https://developer.paga.com/)

## Support

For bug reports and feature requests directly related to this plugin, please use the [issue tracker](https://github.com/pagadevcomm/paga-magento-expresscheckout-plugin/issues).

For general support or questions about your Paystack account, you can reach out by sending a message from [our website](https://developer.paga.com/).
