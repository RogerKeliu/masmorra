<?php

/*
 * ==========================================================================
 *  DADES DEL JOC
 * ==========================================================================
 *  Aquí hi ha les classes de personatge, els monstres i la botiga.
 *  Pots llegir-ho tot. Als reptes extra hi afegiràs coses teves.
 *
 *  Són constants (define): es poden llegir des de qualsevol lloc,
 *  també des de dins de les funcions.
 * ==========================================================================
 */

define("SALES_TOTALS", 8);            // sales amb monstres abans del drac
define("SALES_AMB_BOTIGA", [3, 6]);   // després d'aquestes sales apareix la botiga
define("PROBABILITAT_FUGIR", 50);     // % de probabilitat d'escapar d'un combat

// Classes que pot triar el jugador (la clau és el número del menú)
define("CLASSES", [
    1 => [
        "nom" => "Guerrer",
        "icona" => "🛡️",
        "vida" => 120,
        "atac" => 12,
        "defensa" => 6,
        "descripcio" => "Molta vida i bona armadura. Difícil de tombar.",
    ],
    2 => [
        "nom" => "Mag",
        "icona" => "🔮",
        "vida" => 90,
        "atac" => 17,
        "defensa" => 3,
        "descripcio" => "Fa molt de mal, però és fràgil.",
    ],
    3 => [
        "nom" => "Lladre",
        "icona" => "🗡️",
        "vida" => 100,
        "atac" => 14,
        "defensa" => 4,
        "descripcio" => "Equilibrat i amb sort als cops crítics.",
    ],
]);

// Monstres de la masmorra. El "nivell" indica a partir de quan poden aparèixer.
define("MONSTRES", [
    ["nom" => "Rata gegant",     "icona" => "🐀", "nivell" => 1, "vida" => 30, "atac" => 8,  "defensa" => 1,  "or" => 6,  "exp" => 20],
    ["nom" => "Slime verd",      "icona" => "🟢", "nivell" => 1, "vida" => 36, "atac" => 7,  "defensa" => 2,  "or" => 7,  "exp" => 22],
    ["nom" => "Ratpenat vampir", "icona" => "🦇", "nivell" => 1, "vida" => 26, "atac" => 9,  "defensa" => 0,  "or" => 5,  "exp" => 18],
    ["nom" => "Goblin",          "icona" => "👺", "nivell" => 2, "vida" => 45, "atac" => 11, "defensa" => 3,  "or" => 12, "exp" => 35],
    ["nom" => "Esquelet",        "icona" => "💀", "nivell" => 2, "vida" => 50, "atac" => 12, "defensa" => 4,  "or" => 14, "exp" => 38],
    ["nom" => "Llop ombrívol",   "icona" => "🐺", "nivell" => 3, "vida" => 60, "atac" => 15, "defensa" => 4,  "or" => 18, "exp" => 50],
    ["nom" => "Orc",             "icona" => "👹", "nivell" => 3, "vida" => 72, "atac" => 16, "defensa" => 6,  "or" => 22, "exp" => 55],
    ["nom" => "Bruixot fosc",    "icona" => "🧙", "nivell" => 4, "vida" => 68, "atac" => 20, "defensa" => 5,  "or" => 30, "exp" => 70],
    ["nom" => "Golem de pedra",  "icona" => "🗿", "nivell" => 4, "vida" => 95, "atac" => 17, "defensa" => 10, "or" => 35, "exp" => 75],
]);

// El monstre final, a l'última sala
define("DRAC", [
    "nom" => "Drac Ancestral", "icona" => "🐉", "nivell" => 5,
    "vida" => 170, "atac" => 22, "defensa" => 9, "or" => 100, "exp" => 0,
]);

// Articles de la botiga (la clau és el número del menú; el 0 és "sortir")
// "millora" és la clau de l'heroi que augmenta i "quantitat", quant augmenta.
define("BOTIGA", [
    1 => ["nom" => "Poció de vida",       "preu" => 15, "millora" => "pocions", "quantitat" => 1,  "descripcio" => "+1 poció"],
    2 => ["nom" => "Espasa esmolada",     "preu" => 40, "millora" => "atac",    "quantitat" => 3,  "descripcio" => "+3 d'atac"],
    3 => ["nom" => "Escut de roure",      "preu" => 35, "millora" => "defensa", "quantitat" => 2,  "descripcio" => "+2 de defensa"],
    4 => ["nom" => "Amulet de vitalitat", "preu" => 30, "millora" => "vidaMax", "quantitat" => 20, "descripcio" => "+20 de vida màxima"],
]);
