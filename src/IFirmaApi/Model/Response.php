<?php

namespace IFirmaApi\Model;

class Response extends Base {

    public function __construct($string) {

        $json = json_decode($string, true);

        if( isset($json['response']) ) {

            $this->data = $json['response'];
            if( isset( $this->data['Kod'] ) && $this->data['Kod'] != 0 ) {
                throw new \Exception( 'IFirma Api Error: '. $this->data['Informacja'], $this->data['Kod'] );
            }

        } else {

            throw new \Exception( 'IFirma Api Error: No response. Received: '. $string );
        }

    }

}