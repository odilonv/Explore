<?php

$tab = (new \App\PlusCourtChemin\Modele\Repository\NoeudCommuneRepository())->getCommune($ville);

echo json_encode($tab);
