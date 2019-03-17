<?php

namespace IFirmaApi;

use IFirmaApi\Model\InvoiceBase;
use IFirmaApi\Model\InvoiceDomestic;

class Invoice extends IFirmaApi {

    public function __construct($login, $key) {

        $this->apiKeyName = 'faktura';
        parent::__construct($login, $key);

    }

    public function add(InvoiceBase $invoice) {

        return $this->request($invoice::PATH, self::REQUEST_TYPE_POST, $invoice->toJSON() );

    }

    public function addPayment($idOrNumber, $amount, $type = InvoiceDomestic::PAYMENT_PATH) {

        $id = self::encodeNumber($idOrNumber);

        return $this->request('faktury/wplaty/' . $type . '/' . $id, self::REQUEST_TYPE_POST, [
            'Kwota' => $amount
        ] );

    }

    public function get($idOrNumber, $type = InvoiceDomestic::PATH) {

        $id = self::encodeNumber($idOrNumber);

        return $this->request($type . '/'. $id );
    }

    public function getAsXml($idOrNumber, $type = InvoiceDomestic::PATH) {

        $id = self::encodeNumber($idOrNumber);

        return $this->request($type . '/'. $id, self::REQUEST_TYPE_GET, NULL, self::RESPONSE_TYPE_XML);
    }

    public function getAsPdf($idOrNumber, $type = InvoiceDomestic::PATH, $addHeaders = true, $fileName = 'fv_#.pdf', $mode = 'single') {

        $id = self::encodeNumber($idOrNumber);

        if( $addHeaders ) {
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename='. str_replace('#', $id, $fileName) );
        }

        $response = $this->request($type . '/'. $id .'.pdf.'. $mode, self::REQUEST_TYPE_GET, NULL, self::RESPONSE_TYPE_PDF);

        if( $addHeaders ) {
            echo $response;
        } else {
            return $response;
        }

    }

    public function getAllByType($type = InvoiceDomestic::PATH, $limit = 10, $dateFrom = '', $dateTo = '') {

        $params = [];
        $params['limit'] = $limit;
        if( $dateFrom != '' ) {
            $d = explode('-', $dateFrom);
            $params['dzienOd'] = $d[2];
            $params['miesiacOd'] = $d[1];
            $params['rokOd'] = $d[0];
        }
        if( $dateTo != '' ) {
            $d = explode('-', $dateTo);
            $params['dzienDo'] = $d[2];
            $params['miesiacDo'] = $d[1];
            $params['rokDo'] = $d[0];
        }

        return $this->request($type . '/list', self::REQUEST_TYPE_GET, $params);
    }

    public function getAll($dateFrom, $dateTo = NULL, $minAmount = NULL, $maxAmount = NULL, $contractor = NULL) {

        $params = [];
        $params['dataOd'] = $dateFrom;
        if( $dateTo != NULL ) {
            $params['dataDo'] = $dateTo;
        }
        if( $minAmount != NULL ) {
            $params['kwotaOd'] = $minAmount;
        }
        if( $maxAmount != NULL ) {
            $params['kwotaDo'] = $maxAmount;
        }
        if( $contractor != NULL ) {
            $params['kontrahent'] = $contractor;
        }

        return $this->request('faktury', self::REQUEST_TYPE_GET, $params);
    }

    /**
     * http://api.ifirma.pl/wyszukiwanie-kontrahentow/
     * @param string $idOrNipOrName
     * @return Response
     */
    public function getContractors($idOrNipOrName) {

        return $this->request('kontrahenci/' . urlencode($idOrNipOrName) );
    }

    static private function encodeNumber($number) {

        return str_replace('/', '_', trim( $number ));

    }

}