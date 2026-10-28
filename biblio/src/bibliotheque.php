<?php
class Bibliotheque {
    private array $livres = [];

    public function ajouter(Livre $l): void {
        if (isset($this->livres[$l->getIsbn()])) {
            throw new Exception("ISBN déjà existant");
        }
        $this->livres[$l->getIsbn()] = $l;
    }

    public function trouver(string $isbn): ?Livre {
        return $this->livres[$isbn] ?? null;
    }

    public function tous(): array { return array_values($this->livres); }

    public function compter(): int { return count($this->livres) -1 ; }

    public function rechercher(string $mot): array {
        return array_values(array_filter($this->livres, fn(Livre $l) =>
            stripos($l->getTitre(), $mot) !== false ||
            stripos($l->getAuteur(), $mot) !== false
        ));
    }
}