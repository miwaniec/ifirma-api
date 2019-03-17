<?php

namespace IFirmaApi\Model;

class Contractor extends Base {

    public function __construct($name, $nip, $street, $postcode, $city, $email = NULL, $phone = NULL) {

        $this->data['Nazwa'] = $name;
        $this->data['NIP'] = $nip;
        $this->data['Ulica'] = $street;
        $this->data['KodPocztowy'] = $postcode;
        $this->data['Miejscowosc'] = $city;
        $this->data['Email'] = $email;
        $this->data['Telefon'] = $phone;

        // optional
        $this->data['Nazwa2'] = NULL;
        $this->data['Identyfikator'] = NULL;
        $this->data['PrefiksUE'] = NULL;
        $this->data['Kraj'] = NULL;
        $this->data['OsobaFizyczna'] = NULL;
        $this->data['JestOdbiorca'] = NULL;
        $this->data['JestDostawca'] = NULL;
    }

}