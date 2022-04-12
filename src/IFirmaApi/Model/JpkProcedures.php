<?php

namespace IFirmaApi\Model;

class JpkProcedures extends Base
{
    public function __construct(array $jpkProcedures = [])
    {
        foreach ($jpkProcedures as $jpkProcedureKey) {
            $this->data[$jpkProcedureKey] = true;
        }
    }
}
