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
     */
    public function __construct($name, $price, $quantity = 1, $unit = 'usł.', $vat = 0.23, $pkwiu = '', $vatType = 'PRC') {

        $this->data['StawkaVat'] = $vat;
        $this->data['Ilosc'] = $quantity;
        $this->data['CenaJednostkowa'] = $price;
        $this->data['NazwaPelna'] = $name;
        $this->data['Jednostka'] = $unit;
        $this->data['PKWiU'] = $pkwiu;
        $this->data['TypStawkiVat'] = $vatType;
    }

}