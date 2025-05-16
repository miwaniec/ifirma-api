<?php

namespace IFirmaApi\Model;

class InvoiceProforma extends InvoiceBase {

    const PATH = 'fakturaproformakraj';

    /**
     * https://api.ifirma.pl/wystawianie-faktury-proforma/
     * @param string $clientNip
     * @param string $date format Y-m-d
     * @param int $daysForPay
     * @param string $clientNipPrefix
     * @param string $clientId
     */
    public function __construct($clientNip, $date = 'today', $daysForPay = NULL, $clientNipPrefix = '', $clientId = '' ) {

        if( $date == 'today' ) {
            $date = date('Y-m-d');
        }

        // required
        $this->data['LiczOd'] = 'NET';
        $this->data['TypFakturyKrajowej'] = 'SPRZ';
        $this->data['DataWystawienia'] = $date;
        $this->data['SposobZaplaty'] = 'PRZ';
        $this->data['RodzajPodpisuOdbiorcy'] = 'BPO';
        $this->data['WidocznyNumerGios'] = false;
        $this->data['Numer'] = NULL;
        $this->data['SplitPayment'] = false;

        $this->data['PrefiksUEKontrahenta'] = $clientNipPrefix;
        $this->data['NIPKontrahenta'] = $clientNip;
        $this->data['IdentyfikatorKontrahenta'] = $clientId;

        if( $daysForPay != NULL ) {
            $this->data['TerminPlatnosci'] = date('Y-m-d', strtotime($date . '+'. $daysForPay .' days'));
        }

        // optional
        $this->data['NumerKontaBankowego'] = '';
        $this->data['MiejsceWystawienia'] = '';
        $this->data['NazwaSeriiNumeracji'] = '';
        $this->data['NazwaSzablonu'] = '';
        $this->data['PodpisOdbiorcy'] = '';
        $this->data['PodpisWystawcy'] = '';
        $this->data['Uwagi'] = '';
        $this->data['WidocznyNumerBdo'] = '';
    }

}