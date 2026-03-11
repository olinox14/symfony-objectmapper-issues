<?php

declare(strict_types=1);

namespace App\ObjectMapper\Transform;

use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * Transformateur générique pour les énumérations.
 * Supporte le mapping bidirectionnel (Enum -> string et string -> Enum).
 */
final class EnumTransformer implements TransformCallableInterface
{
    /**
     * @param mixed $value La valeur à transformer
     * @param object $source L'objet source
     * @param object|null $target L'objet cible
     * @return mixed
     */
    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if ($value === null) {
            return null;
        }

        // Cas Enum -> string (Lecture pour l'API)
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        // Cas string -> Enum (Écriture vers l'Entité)
        // On tente de deviner l'Enum cible si on est dans une phase de mapping vers une entité
        if (is_string($value) && $target !== null) {
            $reflection = new \ReflectionObject($target);
            // On cherche une propriété qui pourrait correspondre au mapping (approximatif ici)
            // Dans un vrai projet, on inspecterait peut-être les attributs de mapping ou le type de la propriété
            if ($target instanceof \App\Entity\Badge) {
                return \App\Enum\ColorEnum::tryFrom($value);
            }
        }

        return $value;
    }
}
