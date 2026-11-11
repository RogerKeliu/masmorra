<?php

/*
 * ==========================================================================
 *  COMPROVADOR DE REPTES                      NO CAL TOCAR AQUEST FITXER
 * ==========================================================================
 *  php comprova.php       -> comprova tots els reptes
 *  php comprova.php 4     -> comprova només el repte 4
 * ==========================================================================
 */

try {
    require_once __DIR__ . "/motor.php";
} catch (ParseError $e) {
    echo "\n" . explicarError($e) . "\n\n";
    exit(1);
}

$fallades = 0;

function mostra(mixed $valor): string
{
    if (is_string($valor)) {
        return '"' . $valor . '"';
    }
    if (is_bool($valor)) {
        return $valor ? "true" : "false";
    }
    if (is_null($valor)) {
        return "null";
    }
    if (is_array($valor)) {
        return json_encode($valor, JSON_UNESCAPED_UNICODE);
    }
    return (string) $valor;
}

function comprova(string $descripcio, mixed $obtingut, mixed $esperat, string $pista = ""): void
{
    global $fallades;

    if ($obtingut === $esperat) {
        echo "   [ OK ]  $descripcio -> " . mostra($esperat) . "\n";
        return;
    }

    $fallades++;
    echo "   [FALLA] $descripcio\n";
    echo "           esperava: " . mostra($esperat) . "\n";
    echo "           has donat: " . mostra($obtingut) . "\n";
    if ($pista !== "") {
        echo "           pista: $pista\n";
    }
}

function comprovaExcepcio(string $descripcio, callable $codi, string $classe, string $pista = ""): void
{
    global $fallades;

    try {
        $codi();
    } catch (RepteNoFet $e) {
        throw $e;
    } catch (Throwable $e) {
        if ($e instanceof $classe) {
            echo "   [ OK ]  $descripcio -> $classe (\"" . $e->getMessage() . "\")\n";
        } else {
            $fallades++;
            echo "   [FALLA] $descripcio\n";
            echo "           esperava una $classe, però ha saltat " . get_class($e) . ": " . $e->getMessage() . "\n";
        }
        return;
    }

    $fallades++;
    echo "   [FALLA] $descripcio\n";
    echo "           esperava una $classe, però no s'ha llançat cap excepció.\n";
    if ($pista !== "") {
        echo "           pista: $pista\n";
    }
}

// Dades de prova (independents de dades.php)
function monstresDeProva(): array
{
    return [
        ["nom" => "Rata",     "nivell" => 1, "vida" => 30, "atac" => 8,  "defensa" => 1, "or" => 6,  "exp" => 20],
        ["nom" => "Slime",    "nivell" => 1, "vida" => 36, "atac" => 7,  "defensa" => 2, "or" => 7,  "exp" => 22],
        ["nom" => "Goblin",   "nivell" => 2, "vida" => 45, "atac" => 11, "defensa" => 3, "or" => 12, "exp" => 35],
        ["nom" => "Esquelet", "nivell" => 2, "vida" => 50, "atac" => 12, "defensa" => 4, "or" => 14, "exp" => 38],
        ["nom" => "Orc",      "nivell" => 3, "vida" => 72, "atac" => 16, "defensa" => 6, "or" => 22, "exp" => 55],
    ];
}

function botigaDeProva(): array
{
    return [
        1 => ["nom" => "Poció",  "preu" => 15, "millora" => "pocions", "quantitat" => 1],
        2 => ["nom" => "Espasa", "preu" => 40, "millora" => "atac",    "quantitat" => 3],
        3 => ["nom" => "Escut",  "preu" => 35, "millora" => "defensa", "quantitat" => 2],
        4 => ["nom" => "Amulet", "preu" => 30, "millora" => "vidaMax", "quantitat" => 20],
    ];
}

function heroiDeProva(array $canvis = []): array
{
    $heroi = [
        "nom" => "Laia", "classe" => "Guerrer", "nivell" => 1, "exp" => 0,
        "vida" => 50, "vidaMax" => 120, "atac" => 12, "defensa" => 6,
        "or" => 50, "pocions" => 2,
    ];
    foreach ($canvis as $clau => $valor) {
        $heroi[$clau] = $valor;
    }
    return $heroi;
}

$PISTA_REF = "Has posat el & davant de la variable a la declaració de la funció?";


/* ------------------------------ Proves ------------------------------ */

$proves = [];

$proves[1] = ["netejarNom", function () {
    comprova('netejarNom("laia")', netejarNom("laia"), "Laia", "Mira ucfirst()");
    comprova('netejarNom("  pere  ")', netejarNom("  pere  "), "Pere", "Mira trim()");
    comprova('netejarNom("MARTA")', netejarNom("MARTA"), "Marta", "Passa-ho primer tot a minúscules amb strtolower()");
    comprova('netejarNom("   ")', netejarNom("   "), "Anònim", "Treu els espais ABANS de mirar si és buit");
    comprova('netejarNom("")', netejarNom(""), "Anònim");
}];

$proves[2] = ["validarOpcio", function () {
    comprova('validarOpcio("2", 1, 3)', validarOpcio("2", 1, 3), 2);
    comprova('validarOpcio(" 3 ", 1, 3)', validarOpcio(" 3 ", 1, 3), 3);
    comprova('validarOpcio("0", 0, 4)', validarOpcio("0", 0, 4), 0, "El 0 és una opció vàlida si el mínim és 0");
    comprova('validarOpcio("1", 1, 1)', validarOpcio("1", 1, 1), 1);
    comprovaExcepcio('validarOpcio("hola", 1, 3)', fn() => validarOpcio("hola", 1, 3),
        InvalidArgumentException::class, "Comprova is_numeric() ABANS de convertir a (int)");
    comprovaExcepcio('validarOpcio("", 1, 3)', fn() => validarOpcio("", 1, 3),
        InvalidArgumentException::class);
    comprovaExcepcio('validarOpcio("4", 1, 3)', fn() => validarOpcio("4", 1, 3),
        InvalidArgumentException::class, "4 és més gran que el màxim");
    comprovaExcepcio('validarOpcio("0", 1, 3)', fn() => validarOpcio("0", 1, 3),
        InvalidArgumentException::class, "0 és més petit que el mínim");
    comprovaExcepcio('validarOpcio("-2", 1, 3)', fn() => validarOpcio("-2", 1, 3),
        InvalidArgumentException::class);
}];

$proves[3] = ["crearHeroi", function () {
    $classe = ["nom" => "Provador", "vida" => 99, "atac" => 11, "defensa" => 5, "descripcio" => "..."];
    $heroi = crearHeroi("Laia", $classe);

    $esperat = [
        "nom" => "Laia", "classe" => "Provador", "nivell" => 1, "exp" => 0,
        "vida" => 99, "vidaMax" => 99, "atac" => 11, "defensa" => 5,
        "or" => 10, "pocions" => 2,
    ];

    foreach ($esperat as $clau => $valor) {
        comprova("heroi[\"$clau\"]", $heroi[$clau] ?? "(no hi és)", $valor,
            "Revisa la clau \"$clau\": ha d'estar escrita exactament així");
    }
}];

$proves[4] = ["barraVida", function () {
    comprova("barraVida(100, 100)", barraVida(100, 100), "[####################] 100/100");
    comprova("barraVida(50, 100)", barraVida(50, 100), "[##########----------] 50/100");
    comprova("barraVida(0, 80)", barraVida(0, 80), "[--------------------] 0/80");
    comprova("barraVida(30, 120)", barraVida(30, 120), "[#####---------------] 30/120");
    comprova("barraVida(45, 120)", barraVida(45, 120), "[########------------] 45/120",
        "45/120*20 = 7.5: fes servir round() perquè doni 8");
}];

$proves[5] = ["triarMonstre", function () {
    $llista = monstresDeProva();

    $nivells = [];
    for ($i = 0; $i < 60; $i++) {
        $m = triarMonstre($llista, 1);
        $nivells[] = $m["nivell"] ?? "?";
    }
    comprova("amb nivell 1 només surten monstres de nivell 1", array_values(array_unique($nivells)), [1],
        "Revisa la condició del filtre (<= i >=)");

    $noms = [];
    $nivells = [];
    for ($i = 0; $i < 80; $i++) {
        $m = triarMonstre($llista, 3);
        $noms[] = $m["nom"] ?? "?";
        $nivells[] = $m["nivell"] ?? "?";
    }
    sort($nivells);
    comprova("amb nivell 3 surten monstres de nivell 2 i 3", array_values(array_unique($nivells)), [2, 3],
        "Han de sortir els del nivell de l'heroi I els del nivell anterior");
    comprova("el monstre és un array sencer de la llista", in_array($m, $llista, true), true,
        "Retorna el monstre sencer, no només el nom");

    $m = triarMonstre($llista, 9);
    comprova("amb nivell 9 (no n'hi ha cap) retorna igualment un monstre", in_array($m, $llista, true), true,
        "Si la llista filtrada queda buida, tria entre tots els monstres");
}];

$proves[6] = ["calcularDany", function () {
    comprova("calcularDany(12, 4, 3)", calcularDany(12, 4, 3), 11);
    comprova("calcularDany(10, 3, 5)", calcularDany(10, 3, 5), 12);
    comprova("calcularDany(12, 4, 6)", calcularDany(12, 4, 6), 28, "Amb un 6 és crític: el dany es multiplica per 2");
    comprova("calcularDany(8, 8, 6)", calcularDany(8, 8, 6), 12);
    comprova("calcularDany(5, 10, 2)", calcularDany(5, 10, 2), 1, "El dany mínim és 1");
    comprova("calcularDany(8, 8, 1)", calcularDany(8, 8, 1), 1);
}];

$proves[7] = ["rebreDany", function () use ($PISTA_REF) {
    $p = ["nom" => "Goblin", "vida" => 50];
    rebreDany($p, 20);
    comprova("vida 50, dany 20", $p["vida"], 30, $PISTA_REF);

    $p = ["nom" => "Goblin", "vida" => 10];
    rebreDany($p, 25);
    comprova("vida 10, dany 25", $p["vida"], 0, "La vida no pot quedar negativa");

    $p = ["nom" => "Goblin", "vida" => 40];
    rebreDany($p, 40);
    comprova("vida 40, dany 40", $p["vida"], 0);
}];

$proves[8] = ["estaViu", function () {
    comprova('estaViu(["vida" => 10])', estaViu(["vida" => 10]), true);
    comprova('estaViu(["vida" => 1])', estaViu(["vida" => 1]), true);
    comprova('estaViu(["vida" => 0])', estaViu(["vida" => 0]), false, "Amb 0 de vida ja no és viu: > o >= ?");
}];

$proves[9] = ["beurePocio", function () use ($PISTA_REF) {
    $h = heroiDeProva(["vida" => 50, "pocions" => 2]);
    $curat = beurePocio($h);
    comprova("vida 50/120: retorna", $curat, 40);
    comprova("vida 50/120: vida final", $h["vida"], 90, $PISTA_REF);
    comprova("vida 50/120: pocions que queden", $h["pocions"], 1);

    $h = heroiDeProva(["vida" => 100, "pocions" => 1]);
    $curat = beurePocio($h);
    comprova("vida 100/120: retorna", $curat, 20, "No es pot passar de vidaMax: mira min()");
    comprova("vida 100/120: vida final", $h["vida"], 120);

    $h = heroiDeProva(["vida" => 50]);
    comprova("beurePocio(\$h, 10): retorna", beurePocio($h, 10), 10, "Fes servir el paràmetre \$cura, no el número 40");

    $h = heroiDeProva(["pocions" => 0]);
    comprovaExcepcio("sense pocions", function () use (&$h) { beurePocio($h); }, RangeException::class);
    comprova("sense pocions: la vida no canvia", $h["vida"], 50);

    $h = heroiDeProva(["vida" => 120]);
    comprovaExcepcio("amb la vida plena", function () use (&$h) { beurePocio($h); }, RangeException::class);
    comprova("amb la vida plena: no gasta la poció", $h["pocions"], 2);
}];

$proves[10] = ["cobrarRecompensa", function () use ($PISTA_REF) {
    $h = heroiDeProva(["or" => 10, "exp" => 0]);
    $monstre = monstresDeProva()[2];
    cobrarRecompensa($h, $monstre);
    comprova("or després de vèncer un Goblin", $h["or"], 22, $PISTA_REF);
    comprova("exp després de vèncer un Goblin", $h["exp"], 35);
    cobrarRecompensa($h, $monstre);
    comprova("or després de vèncer-ne dos", $h["or"], 34, "Has de SUMAR, no substituir");
}];

$proves[11] = ["pujarNivell", function () use ($PISTA_REF) {
    $h = heroiDeProva(["exp" => 30]);
    comprova("nivell 1 amb 30 exp: retorna", pujarNivell($h), false);
    comprova("nivell 1 amb 30 exp: nivell", $h["nivell"], 1);

    $h = heroiDeProva(["exp" => 45, "vida" => 20]);
    comprova("nivell 1 amb 45 exp: retorna", pujarNivell($h), true, "Calen nivell * 40 punts");
    comprova("nivell", $h["nivell"], 2, $PISTA_REF);
    comprova("exp que sobra", $h["exp"], 5, "Resta els punts que has gastat");
    comprova("vidaMax", $h["vidaMax"], 135);
    comprova("vida (recuperada del tot)", $h["vida"], 135);
    comprova("atac", $h["atac"], 15);
    comprova("defensa", $h["defensa"], 7);

    $h = heroiDeProva(["nivell" => 2, "exp" => 80]);
    comprova("nivell 2 amb 80 exp (just el que cal)", pujarNivell($h), true, "Amb exactament els punts necessaris també puja: >=");

    $h = heroiDeProva(["nivell" => 2, "exp" => 79]);
    comprova("nivell 2 amb 79 exp", pujarNivell($h), false);
}];

$proves[12] = ["comprar", function () use ($PISTA_REF) {
    $botiga = botigaDeProva();

    $h = heroiDeProva(["or" => 50, "pocions" => 2]);
    comprar($h, $botiga[1]);
    comprova("comprar una poció amb 50 d'or: or", $h["or"], 35, $PISTA_REF);
    comprova("comprar una poció: pocions", $h["pocions"], 3, "Suma la quantitat a la clau que diu \"millora\": \$heroi[\$article[\"millora\"]]");

    $h = heroiDeProva(["or" => 40, "atac" => 12]);
    comprar($h, $botiga[2]);
    comprova("comprar l'espasa amb 40 d'or justos: atac", $h["atac"], 15);
    comprova("comprar l'espasa amb 40 d'or justos: or", $h["or"], 0);

    $h = heroiDeProva(["or" => 50, "vida" => 80, "vidaMax" => 100]);
    comprar($h, $botiga[4]);
    comprova("comprar l'amulet: vidaMax", $h["vidaMax"], 120);
    comprova("comprar l'amulet: vida", $h["vida"], 100, "Amb la millora \"vidaMax\" també puja la vida");

    $h = heroiDeProva(["or" => 10, "atac" => 12]);
    comprovaExcepcio("comprar l'espasa amb 10 d'or", function () use (&$h, $botiga) { comprar($h, $botiga[2]); },
        RangeException::class);
    comprova("sense prou or: l'or no canvia", $h["or"], 10);
    comprova("sense prou or: l'atac no canvia", $h["atac"], 12);
}];

$proves[13] = ["resumPartida", function () {
    $m = monstresDeProva();

    $resum = resumPartida([$m[2], $m[4], $m[0]]);
    comprova("total", $resum["total"] ?? null, 3);
    comprova("or", $resum["or"] ?? null, 40);
    comprova("exp", $resum["exp"] ?? null, 110);
    comprova("mesFort", $resum["mesFort"] ?? null, "Orc", "El més fort és el que té l'atac més alt");

    $resum = resumPartida([]);
    comprova("llista buida: total", $resum["total"] ?? null, 0);
    comprova("llista buida: or", $resum["or"] ?? null, 0);
    comprova("llista buida: mesFort", $resum["mesFort"] ?? null, "Cap");
}];

$proves[14] = ["atacEspecial (extra)", function () {
    $monstre = ["nom" => "Prova", "vida" => 50, "atac" => 10, "defensa" => 4];
    comprova("Guerrer (atac 12)", atacEspecial(heroiDeProva(["classe" => "Guerrer", "atac" => 12]), $monstre, 3), 20);
    comprova("Mag (atac 17), tirada 2", atacEspecial(heroiDeProva(["classe" => "Mag", "atac" => 17]), $monstre, 2), 23);
    comprova("Lladre (atac 14), tirada 5", atacEspecial(heroiDeProva(["classe" => "Lladre", "atac" => 14]), $monstre, 5), 53);
    comprova("Lladre (atac 14), tirada 2", atacEspecial(heroiDeProva(["classe" => "Lladre", "atac" => 14]), $monstre, 2), 0);
    comprova("classe desconeguda", atacEspecial(heroiDeProva(["classe" => "Pirata"]), $monstre, 4), 0, "Fes servir default al switch");
}];

$proves[15] = ["articlesAssequibles (extra)", function () {
    $botiga = botigaDeProva();
    comprova("amb 35 d'or: claus", array_keys(articlesAssequibles($botiga, 35)), [1, 3, 4],
        "Has de mantenir les claus: array_filter() ho fa sol");
    comprova("amb 10 d'or: claus", array_keys(articlesAssequibles($botiga, 10)), []);
    comprova("amb 100 d'or: claus", array_keys(articlesAssequibles($botiga, 100)), [1, 2, 3, 4]);
}];


/* ------------------------------ Execució ------------------------------ */

$nomesUn = null;
foreach ($_SERVER["argv"] as $argument) {
    if (is_numeric($argument)) {
        $nomesUn = (int) $argument;
    }
}

if ($nomesUn !== null && !isset($proves[$nomesUn])) {
    echo "No hi ha cap repte $nomesUn. Els reptes van de l'1 al " . count($proves) . ".\n";
    exit(1);
}

$superats = [];
$primerPendent = null;

foreach ($proves as $numero => [$nom, $prova]) {
    if ($nomesUn !== null && $numero !== $nomesUn) {
        continue;
    }

    echo "\nRepte $numero — $nom\n";
    $fallades = 0;

    try {
        $prova();
        if ($fallades === 0) {
            $superats[] = $numero;
            echo "   SUPERAT!\n";
        } else {
            echo "   Encara hi ha $fallades comprovacions que fallen.\n";
            $primerPendent ??= $numero;
        }
    } catch (RepteNoFet $e) {
        echo "   [PENDENT] Encara no l'has fet (hi ha la línia pendent($numero);).\n";
        $primerPendent ??= $numero;
    } catch (Throwable $e) {
        echo "   [ERROR] " . str_replace("\n", "\n           ", explicarError($e)) . "\n";
        $primerPendent ??= $numero;
    }
}

if ($nomesUn === null) {
    $principals = count(array_filter($superats, fn($n) => $n <= 13));
    $extres = count(array_filter($superats, fn($n) => $n > 13));

    echo "\n" . str_repeat("=", 50) . "\n";
    echo " Reptes superats: $principals/13   Extres: $extres/2\n";
    if ($primerPendent !== null) {
        echo " Següent repte: $primerPendent\n";
    } else {
        echo " Ho tens TOT! Ets una llegenda de la masmorra.\n";
    }
    echo str_repeat("=", 50) . "\n";
}
