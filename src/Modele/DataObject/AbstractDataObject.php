<?php

namespace App\PlusCourtChemin\Modele\DataObject;

abstract class AbstractDataObject
{

    public abstract function exporterEnFormatRequetePreparee(): array;

}