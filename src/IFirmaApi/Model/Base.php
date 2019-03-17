<?php

namespace IFirmaApi\Model;

class Base {

    protected $data = [];

    public function get() {

        $args = func_get_args();

        if( count($args) > 1 ) {

            $data = $this->data;
            foreach( $args as $key ) {
                $data = $data[ $key ];
            }
            return $data;

        } else if( count($args) == 1 ) {

            if( isset($this->data[ $args[0] ]) ) {
                return $this->data[ $args[0] ];
            } else {
                return null;
            }

        } else {
            return $this->data;
        }
    }

    public function set() {

        $args = func_get_args();

        if( count($args) == 2 ) {
            $this->data[ $args[0] ] = $args[1];
        }
    }

    public function toJSON() {

        $json = [];

        foreach( $this->data as $key => $value ) {

            if( is_array($value) ) {

                foreach( $value as $k => $v ) {
                    $json[$key][$k] = $v->get();
                }

            } else if( $value instanceof Base ) {

                $json[$key] = $value->get();

            } else if( $value !== '' ) {

                $json[$key] = $value;
            }
        }

        return json_encode($json);
    }

}