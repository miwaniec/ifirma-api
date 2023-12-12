# IFirmaApi
The wrapper in PHP for official [iFirma API](http://api.ifirma.pl/) (in Polish). IFirma is one of the most popular internet accounting systems in Poland.
API functions and fields are in Polish, names in my code are in English for better understanding.
Created at the end of 2017 for internal projects, published in March 2019 here.
If you need help, please create an issue in this repository.
It is part of most useful API functions.
Check examples below (more in [example.php](src/example.php)).

Installation - https://packagist.org/packages/miwaniec/ifirma-api
------------
```bash
composer require miwaniec/ifirma-api
```

Usage
-----
1. Get accountancy month (current value in system)
```
$account = new \IFirmaApi\Account('login', 'key');
$response = $account->getAccountancyMonth();
echo 'Accountancy month: ' . $response->get('MiesiacKsiegowy') . '/' . $response->get('RokKsiegowy');
```

2. Add invoice - a simple example for an existing contractor
```
$invoice = new \IFirmaApi\Invoice('login', 'key');
$invoceDomestic = new \IFirmaApi\Model\InvoiceDomestic('123456789', '2019-01-01', 7);
$invoceDomestic->addItem( new \IFirmaApi\Model\Item('IT support', 100, 3));
$response = $invoice->add( $invoceDomestic );
echo 'Invoice ID: '. $response->get('Identyfikator');
```

3. Download invoice
```
$invoice = new \IFirmaApi\Invoice('login', 'key');
$invoice->getAsPdf('1/1/2019');
```
