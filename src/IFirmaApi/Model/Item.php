<?php

namespace IFirmaApi\Model;

class Item extends Base {

    /**
     * @param string $name
     * @param float $price
     * @param int $quantity
     * @param string $unit
     * @param float $vat
     * @param string $pkwiu
     * @param string $vatType PRC or ZW
     * @param string $gtu
     */
    public function __construct($name, $price, $quantity = 1, $unit = 'usł.', $vat = 0.23, $pkwiu = '', $vatType = 'PRC', $gtu = 'BRAK', $base = '') {

        $this->data['StawkaVat'] = $vat;
        $this->data['Ilosc'] = $quantity;
        $this->data['CenaJednostkowa'] = $price;
        $this->data['NazwaPelna'] = $name;
        $this->data['Jednostka'] = $unit;
        $this->data['PKWiU'] = $pkwiu;
        $this->data['TypStawkiVat'] = $vatType;
        $this->data['GTU'] = $gtu;

        if(!empty($base))
          $this->data['PodstawaPrawna'] = $base;
    }

    /**
     * @param string $gtu
     */
    public function setGtu($gtu = 'BRAK') {
        $this->data['GTU'] = $gtu;

        return $this;
    }

}
