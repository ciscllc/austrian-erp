<?php

namespace App\Controllers;

class InvoiceController extends BaseController
{
    public function generate(int $id)
    {
        echo "Rechnung für Auftrag #$id wird generiert...";
    }
}