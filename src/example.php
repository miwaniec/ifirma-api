<?php

    // autoloaded
    spl_autoload_register(function ($class) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    });

    $userLogin = 'login';
    $userKeys = [
        'abonent' => '1234567890123456',
        'faktura' => '1234567890123456'
    ];

    $exampleInvoiceNumber = '1/1/2019';
    $exampleInvoiceDate = '2019-01-01';

    try {

        /* Account */
        $account = new \IFirmaApi\Account($userLogin, $userKeys['abonent']);

        // Get accountancy month (current value in system)
        $response = $account->getAccountancyMonth();
        echo 'Accountancy month: ' . $response->get('MiesiacKsiegowy') . '/' . $response->get('RokKsiegowy');

        // change accountancy month (for default to the next)
        // $account->changeAccountancyMonth();

        // change to the previous month
        // $account->changeAccountancyMonth(false);

        // check api limits
        $response = $account->getLimit();
        echo 'Limits: ' . $response->get('LimitWykorzystany') . '/' . $response->get('LimitPrzyznany');
        /**/

        /* Invoice *
        $invoice = new \IFirmaApi\Invoice($userLogin, $userKeys['faktura'] );

        /* add Invoice - a simple example for an existing contractor *
        $invoceDomestic = new \IFirmaApi\Model\InvoiceDomestic('5211613104', $exampleInvoiceDate, 7);
        $invoceDomestic->addItem( new \IFirmaApi\Model\Item('IT support', 100, 3));

        $response = $invoice->add( $invoceDomestic );
        echo 'Invoice ID: '. $response->get('Identyfikator');
        /**/

        /* add Invoice - few items, new contractor *
        $invoceDomestic = new \IFirmaApi\Model\InvoiceDomestic('123456789', $exampleInvoiceDate, 7);
        $invoceDomestic->setContractor( new \IFirmaApi\Model\Contractor('Spolka z o.o.', $invoceDomestic->get('NIPKontrahenta'), 'Ulica 1/2', '01-234', 'Miasto') );
        $invoceDomestic->addItem( new \IFirmaApi\Model\Item('Buty', 99, 3, 'pary', 0.08) );
        $invoceDomestic->addItem( new \IFirmaApi\Model\Item('Dostawa', 15, 1) );

        $response = $invoice->add( $invoceDomestic );
        echo 'Invoice ID: '. $response->get('Identyfikator');
        /**/

        /* get invoice (json) *
        $response = $invoice->get($exampleInvoiceNumber);
        echo 'Invoice number: '. $response->get('PelnyNumer') . ', date: ' . $response->get('DataWystawienia') .', paid: '. $response->get('Zaplacono');
        echo ' First item name: '. $response->get('Pozycje', 0, 'NazwaPelna');
        /**/

        /* get invoice as pdf */
        // - ready to download (with headers)
        //$invoice->getAsPdf($exampleInvoiceNumber, \IFirmaApi\Model\InvoiceDomestic::PATH);
        // - as a content (if you want to save or mail)
        //echo $invoice->getAsPdf($exampleInvoiceNumber, \IFirmaApi\Model\InvoiceDomestic::PATH, false);
        /**/

        /* get invoice as xml *
        echo $invoice->getAsXml($exampleInvoiceNumber);
        /**/

        /* add payment to invoice *
        $response = $invoice->addPayment($exampleInvoiceNumber, 50);
        /**/

        /* get all invoices *
        $response = $invoice->getAllByType();
        //$response = $invoice->getAllByType( \IFirmaApi\Model\InvoiceDomestic::PATH, 10, '2019-01-01', '2019-02-01' );

        //$response = $invoice->getAll('2019-01-01');
        //$response = $invoice->getAll('2019-01-01', NULL, NULL, 50);
        /**/

        /* get contractor by fragment of name (or ID or NIP) */
        $response = $invoice->getContractors('spolkazoo');
        if( count( $response->get('Wynik') ) == 1 ) {
            /* if found, get all his invoices since 2019-01-01 */
            $response = $invoice->getAll('2019-01-01', NULL, NULL, NULL, $response->get('Wynik', 0, 'Identyfikator') );
        }
        /**/

        if( isset( $response ) ) {
            echo '<pre><hr />';
            print_r($response);
        }

    } catch (\Exception $exception) {

        echo '<h1>Error code: '. $exception->getCode() .'</h1>';
        echo '<h3>'. $exception->getMessage() .'</h3>';
        $trace = $exception->getTrace();
        $last = end($trace);
        echo '<h4>Error caused by: <b>'. $last['class'] .'::'.$last['function'] .'();</b> in '. $last['file'].':'. $last['line'] .'</h4>';

    }