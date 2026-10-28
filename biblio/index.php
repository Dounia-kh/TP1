<?php
require __DIR__ . '/autoload.php';

$biblio = new Bibliotheque();
$membres = [];

function lire(string $invite): string {
    echo $invite;
    return trim((string) fgets(STDIN));
}

function obtenirMembre(array &$membres): Membre {
    $id = (int) lire("Id du membre : ");
    if (!isset($membres[$id])) {
        $nom = lire("Nom du membre : ");
        $membres[$id] = new Membre($id, $nom);
    }
    return $membres[$id];
}

do {
    echo "\n=== BIBLIO ===\n";
    echo "1 Ajouter\n2 Lister\n3 Rechercher\n4 Emprunter\n5 Rendre\n0 Quitter\n";
    $choix = lire("Votre choix : ");

    try {
        switch ($choix) {
            case '1':
                $isbn = lire("ISBN : ");
                $titre = lire("Titre : ");
                $auteur = lire("Auteur : ");
                $biblio->ajouter(new Livre($isbn, $titre, $auteur));
                echo "Livre ajouté.\n";
                break;
            case '2':
                if ($biblio->compter() === 0) echo "Aucun livre.\n";
                foreach ($biblio->tous() as $l) {
                    echo $l . ($l->estDisponible() ? " - disponible" : " - emprunté") . "\n";
                }
                break;
            case '3':
                $mot = lire("Mot à chercher : ");
                $res = $biblio->rechercher($mot);
                if (!$res) echo "Aucun résultat.\n";
                foreach ($res as $l) echo $l . "\n";
                break;
            case '4':
                $membre = obtenirMembre($membres);
                $livre = $biblio->trouver(lire("ISBN du livre : "));
                if ($livre === null) { echo "Livre introuvable.\n"; break; }
                $membre->emprunter($livre);
                echo "Emprunt enregistré.\n";
                break;
            case '5':
                $membre = obtenirMembre($membres);
                $livre = $biblio->trouver(lire("ISBN du livre : "));
                if ($livre === null) { echo "Livre introuvable.\n"; break; }
                $membre->rendre($livre);
                echo "Livre rendu.\n";
                break;
            case '0':
                echo "Au revoir.\n";
                break;
            default:
                echo "Choix invalide.\n";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage() . "\n";
    }
} while ($choix !== '0');