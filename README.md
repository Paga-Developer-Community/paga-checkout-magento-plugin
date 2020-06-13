
# PagaCheckout Magento 2 Plugin

PagaCheckout payment gateway Magento2 extension

## Install

- ###### FTP/SCP into your hosting server and copy the unzipped file into the app/code/Magento folder or use

```bash
composer require paga/paga-checkout:dev-master
```

- ###### Enable the plugin:

```bash
php bin/magento module:enable Magento_PagaCheckout --clear-static-content
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

  Add the line “‘PagaCheckout’ => 1,” at the end of the list

- ###### Configure `PagaCheckout` plugin in Magento

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


## Documentation

- [Paga Documentation](https://developer-docs.paga.com/docs/introduction)
- [Paga Developer Community](https://developer.paga.com/)

## Support

For bug reports and feature requests directly related to this plugin, please use the [issue tracker](https://github.com/pagadevcomm/paga-checkout-magento-plugin/issues) to report all issues.

For general support or questions about your Paga account, you can reach out by sending a mail to [mailto](service@mypaga.com).

![Packagist Downloads](https://img.shields.io/packagist/dm/pagadevcomm/paga-checkout?style=plastic)
![Packagist Version](https://img.shields.io/packagist/v/pagadevcomm/paga-checkout?style=plastic)
![Packagist Stars](https://img.shields.io/packagist/stars/pagadevcomm/paga-checkout?style=plastic)
![Packagist Version](https://img.shields.io/packagist/v/pagadevcomm/paga-checkout?style=plastic)
![Packagist License](https://img.shields.io/packagist/l/pagadevcomm/paga-checkout)


