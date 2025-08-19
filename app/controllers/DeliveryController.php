<?php

namespace App\Controllers;

class DeliveryController extends BaseController
{
    public function generate(int $id)
    {
        echo "Lieferschein für Auftrag #$id wird generiert...";
    }
}