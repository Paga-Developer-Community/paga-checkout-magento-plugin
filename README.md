<!-- [![Latest Version on Packagist][link-packagist]] -->
<!--
[![Software License][ico-license]](LICENSE)
[![Total Downloads][ico-downloads]][link-downloads] -->

# Paga ExpressCheckout Magento 2 Plugin

Paga ExpressCheckout payment gateway Magento2 extension

## Install

- ###### FTP/SCP into your hosting server and copy the unzipped file into the app/code/Magento folder or use

```bash
composer require paga-checkout/checkout:dev-master
```

- ###### Enable the plugin:

```bash
php bin/magento module:enable Paga_ExpressCheckout --clear-static-content
```

- ###### Execute the update scripts:

```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
```

- ###### Flush and clean cache storage

```bash
php bin/magento cache:clean
php bin/magento cache:flush
```

- ###### From your magenta root folder, open the file app/etc/config.php.

  Add the line “‘Paga_ExpressCheckout’ => 1,” at the end of the list

- ###### Configure `Paga ExpressCheckout` plugin in Magento

  1. Log in to your Magento admin panel.
  2. In the left navigation bar, go to **Stores > Configuration**.
  3. In the menu, go to **Sales > Payment Methods**.
  4. Click Required Settings and fill out the following fields:

  | Field           | Description                                    |
  | --------------- | ---------------------------------------------- |
  | Enabled         | Yes                                            |
  | Title           | Payments (powered by Paga)                     |
  | Test Public Key | Test publicId (business organization publicId) |
  | Test Secret Key | Test password or credential                    |
  | Live Public Key | Live publicId (business organization publicId) |
  | Live Secret Key | Live password or credential                    |
  | Test Mode       | Yes(For test account) and No(for live account) |

[ico-version]: https://img.shields.io/packagist/v/pstk/paystack-magento2-module.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-OSL3.0-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/paga-checkout/checkout-module.svg?style=flat-square
[link-packagist]: https://packagist.org/packages/paga-checkout/checkout
[link-downloads]: https://packagist.org/packages/paga-checkout/checkout

## Documentation

- [Paga Documentation](https://developer-docs.paga.com/docs/introduction)
- [Paga Developer Community](https://developer.paga.com/)

## Support

For bug reports and feature requests directly related to this plugin, please use the [issue tracker](https://github.com/pagadevcomm/paga-magento-expresscheckout-plugin/issues).

For general support or questions about your Paga account, you can reach out by sending a mail to [mailto](service@mypaga.com).
