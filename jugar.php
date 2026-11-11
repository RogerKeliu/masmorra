<?php

/*
 * ==========================================================================
 *  LA MASMORRA — versió de terminal          NO CAL TOCAR AQUEST FITXER
 * ==========================================================================
 *  Per jugar:   php jugar.php
 *  Si una funció de funcions.php encara no està feta, el joc s'atura i
 *  t'indica quin repte has de completar.
 * ==========================================================================
 */

try {
    require_once __DIR__ . "/motor.php";
} catch (ParseError $e) {
    echo "\n" . explicarError($e) . "\n\n";
    exit(1);
}

function llegir(string $pregunta): string
{
    $entrada = readline($pregunta);

    if ($entrada === false) {   // Ctrl+D
        echo "\nFins aviat!\n";
        exit(0);
    }

    return $entrada;
}

function linia(): void
{
    echo str_repeat("=", 64) . "\n";
}

function pintarMissatges(array &$partida): void
{
    $missatges = treureMissatges($partida);
    if (count($missatges) > 0) {
        echo "\n";
        foreach ($missatges as $text) {
            echo "  > $text\n";
        }
    }
}

function pintarHeroi(array $heroi): void
{
    $necessaria = $heroi["nivell"] * 40;
    echo "  {$heroi['nom']} ({$heroi['classe']}, nivell {$heroi['nivell']})"
        . "   Exp {$heroi['exp']}/$necessaria   Or {$heroi['or']}   Pocions {$heroi['pocions']}\n";
    echo "  Vida " . barraVida($heroi["vida"], $heroi["vidaMax"])
        . "   Atac {$heroi['atac']}  Defensa {$heroi['defensa']}\n";
}

function pintarMonstre(array $monstre): void
{
    echo "  {$monstre['nom']} (nivell {$monstre['nivell']})"
        . "   Atac {$monstre['atac']}  Defensa {$monstre['defensa']}\n";
    echo "  Vida " . barraVida($monstre["vida"], $monstre["vidaMax"]) . "\n";
}

function pintarPantalla(array &$partida): void
{
    pintarMissatges($partida);
    echo "\n";
    linia();

    $sala = min($partida["sala"], SALES_TOTALS);

    switch ($partida["fase"]) {
        case "passadis":
            echo "  PASSADÍS  (sales superades: $sala/" . SALES_TOTALS . ")\n";
            linia();
            pintarHeroi($partida["heroi"]);
            break;

        case "combat":
            if (esSalaDelDrac($partida)) {
                echo "  COMBAT FINAL\n";
            } else {
                echo "  COMBAT  (sala {$partida['sala']}/" . SALES_TOTALS . ")\n";
            }
            linia();
            pintarHeroi($partida["heroi"]);
            echo "                         -- contra --\n";
            pintarMonstre($partida["monstre"]);
            break;

        case "botiga":
            echo "  BOTIGA  (tens {$partida['heroi']['or']} d'or)\n";
            linia();
            pintarHeroi($partida["heroi"]);
            break;
    }

    linia();
    foreach (opcions($partida) as $numero => $text) {
        echo "  $numero. $text\n";
    }
}

function pintarFinal(array &$partida): void
{
    pintarMissatges($partida);
    echo "\n";
    linia();

    if ($partida["resultat"] === "victoria") {
        echo "  VICTÒRIA! {$partida['heroi']['nom']} ha derrotat el " . DRAC["nom"] . ".\n";
        echo "  La masmorra torna a estar en pau.\n";
    } else {
        echo "  GAME OVER. Has arribat a la sala {$partida['sala']}.\n";
    }

    linia();
    $resum = resumFinal($partida);

    if ($resum === null) {
        echo "  (Completa el repte 13 per veure el resum de la partida.)\n";
    } else {
        echo "  Monstres derrotats: {$resum['total']}\n";
        echo "  Or guanyat:         {$resum['or']}\n";
        echo "  Experiència:        {$resum['exp']}\n";
        echo "  Rival més perillós: {$resum['mesFort']}\n";
    }
    linia();
}

function pintarBloqueig(RepteNoFet $e): void
{
    echo "\n";
    linia();
    echo "  JOC ATURAT\n";
    echo "  " . $e->getMessage() . "\n";
    echo "  Quan l'hagis fet, comprova-la amb:  php comprova.php {$e->getCode()}\n";
    linia();
    echo "\n";
}


/* ----------------------------- Partida ----------------------------- */

echo "\n";
echo "  +--------------------------------------------------+\n";
echo "  |                                                  |\n";
echo "  |            L A    M A S M O R R A                |\n";
echo "  |                                                  |\n";
echo "  |     Travessa 8 sales i derrota el drac final     |\n";
echo "  +--------------------------------------------------+\n";
echo "\n";

try {
    $nom = netejarNom(llegir("Com es diu el teu heroi? "));
    echo "\nHola, $nom! Tria la teva classe:\n\n";

    foreach (CLASSES as $numero => $classe) {
        echo "  $numero. {$classe['nom']}  (vida {$classe['vida']}, atac {$classe['atac']}, "
            . "defensa {$classe['defensa']}) — {$classe['descripcio']}\n";
    }

    $partida = null;
    while ($partida === null) {
        $opcio = llegir("\nClasse: ");
        try {
            $partida = crearPartida($nom, $opcio);
        } catch (InvalidArgumentException $e) {
            echo "Avís: " . $e->getMessage() . "\n";
        }
    }

    while ($partida["fase"] !== "final") {
        pintarPantalla($partida);
        $entrada = llegir("\nQuè fas? ");

        try {
            jugarTorn($partida, $entrada);
        } catch (InvalidArgumentException $e) {
            missatge($partida, "Avís: " . $e->getMessage());
        }
    }

    pintarFinal($partida);
} catch (RepteNoFet $e) {
    pintarBloqueig($e);
} catch (Throwable $e) {
    echo "\n" . explicarError($e) . "\n\n";
    exit(1);
}
