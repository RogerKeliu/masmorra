<?php

/*
 * ==========================================================================
 *  MOTOR DEL JOC — NO CAL TOCAR AQUEST FITXER
 * ==========================================================================
 *  Fa funcionar la partida (torns, combat, botiga...) i crida les funcions
 *  que tu escrius a funcions.php. El fan servir tant la versió de terminal
 *  (jugar.php) com la versió web (web/index.php).
 *
 *  Tota la partida es guarda en un array associatiu, $partida:
 *    "fase"        => "passadis" | "combat" | "botiga" | "final"
 *    "heroi"       => l'array que crea la teva funció crearHeroi()
 *    "monstre"     => el monstre del combat actual (o null)
 *    "sala"        => número de la sala on ets
 *    "derrotats"   => llista dels monstres que has vençut
 *    "resultat"    => "victoria" | "derrota" | null
 *    "missatges"   => textos que s'han de mostrar al jugador
 * ==========================================================================
 */

// Excepció que es llança quan una funció de funcions.php encara no està feta.
class RepteNoFet extends Exception
{
}

// Les funcions de funcions.php criden pendent(N) fins que les completes.
function pendent(int $repte): never
{
    $traca = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $funcio = $traca[1]["function"] ?? "?";

    throw new RepteNoFet("Repte $repte pendent: completa la funció $funcio() a funcions.php", $repte);
}

// Converteix un error de PHP en un missatge més entenedor.
function explicarError(Throwable $e): string
{
    $on = basename($e->getFile()) . ", línia " . $e->getLine();

    if ($e instanceof ParseError) {
        return "Error de sintaxi a $on: " . $e->getMessage() . "\n"
            . "Revisa els punts i comes, les claus { } i les cometes. "
            . "Pots comprovar el fitxer amb: php -l funcions.php";
    }
    if ($e instanceof TypeError) {
        return "Error de tipus a $on: " . $e->getMessage() . "\n"
            . "Potser t'has deixat un return, o la funció retorna un tipus que no toca.";
    }
    if ($e instanceof DivisionByZeroError) {
        return "Divisió per zero a $on: " . $e->getMessage();
    }

    return get_class($e) . " a $on: " . $e->getMessage();
}

// Càrrega de les dades i de les funcions (les teves o, amb --solucio, les del professor)
require_once __DIR__ . "/dades.php";

if (in_array("--solucio", $_SERVER["argv"] ?? [], true) || getenv("MASMORRA_SOLUCIO") === "1") {
    require_once __DIR__ . "/../professor/funcions_solucio.php";
} else {
    require_once __DIR__ . "/funcions.php";
}


/* ---------------------------- Partida ---------------------------- */

function missatge(array &$partida, string $text): void
{
    $partida["missatges"][] = $text;
}

function crearPartida(string $nomEscrit, string $opcioClasse): array
{
    $nom = netejarNom($nomEscrit);
    $opcio = validarOpcio($opcioClasse, 1, count(CLASSES));
    $heroi = crearHeroi($nom, CLASSES[$opcio]);

    $partida = [
        "fase" => "passadis",
        "heroi" => $heroi,
        "monstre" => null,
        "sala" => 0,
        "especialUsat" => false,
        "derrotats" => [],
        "resultat" => null,
        "missatges" => [],
    ];

    missatge($partida, "$nom ({$heroi['classe']}) entra a la masmorra...");
    missatge($partida, "Has de travessar " . SALES_TOTALS . " sales i derrotar el " . DRAC["nom"] . ".");

    return $partida;
}

function esSalaDelDrac(array $partida): bool
{
    return $partida["sala"] > SALES_TOTALS;
}

// Opcions que pot triar el jugador segons la fase. Clau = número a escriure.
function opcions(array $partida): array
{
    $heroi = $partida["heroi"];
    $opcions = [];

    switch ($partida["fase"]) {
        case "passadis":
            $seguent = $partida["sala"] + 1;
            if ($seguent > SALES_TOTALS) {
                $opcions[1] = "Obrir la porta del drac";
            } else {
                $opcions[1] = "Entrar a la sala $seguent";
            }
            $opcions[2] = "Beure una poció (en tens {$heroi['pocions']})";
            break;

        case "combat":
            $opcions[1] = "Atacar";
            $opcions[2] = "Beure una poció (en tens {$heroi['pocions']})";
            $opcions[3] = "Fugir";
            if (!$partida["especialUsat"]) {
                $opcions[4] = "Atac especial (un cop per combat)";
            }
            break;

        case "botiga":
            try {
                $assequibles = articlesAssequibles(BOTIGA, $heroi["or"]);
            } catch (RepteNoFet $e) {
                $assequibles = null; // repte extra no fet: no marquem res
            }

            $opcions[0] = "Sortir de la botiga";
            foreach (BOTIGA as $numero => $article) {
                $text = "{$article['nom']} — {$article['preu']} d'or ({$article['descripcio']})";
                if ($assequibles !== null && !isset($assequibles[$numero])) {
                    $text .= "  [no t'arriba]";
                }
                $opcions[$numero] = $text;
            }
            break;
    }

    return $opcions;
}

// Executa l'opció que ha escrit el jugador.
// Si l'opció no és vàlida, validarOpcio() llança una InvalidArgumentException.
function jugarTorn(array &$partida, string $entrada): void
{
    $opcions = opcions($partida);
    if (count($opcions) === 0) {
        return;
    }

    $opcio = validarOpcio($entrada, min(array_keys($opcions)), max(array_keys($opcions)));

    switch ($partida["fase"]) {
        case "passadis":
            if ($opcio === 1) {
                entrarSala($partida);
            } else {
                intentarBeurePocio($partida);
            }
            break;

        case "combat":
            if ($opcio === 1) {
                atacar($partida);
            } elseif ($opcio === 2) {
                if (intentarBeurePocio($partida)) {
                    tornDelMonstre($partida);
                }
            } elseif ($opcio === 3) {
                fugir($partida);
            } else {
                ferAtacEspecial($partida);
            }
            break;

        case "botiga":
            if ($opcio === 0) {
                missatge($partida, "Surts de la botiga.");
                $partida["fase"] = "passadis";
            } else {
                comprarArticle($partida, BOTIGA[$opcio]);
            }
            break;
    }
}

function entrarSala(array &$partida): void
{
    $partida["sala"]++;
    $partida["especialUsat"] = false;

    if (esSalaDelDrac($partida)) {
        $monstre = DRAC;
        missatge($partida, "Obres una porta enorme... El {$monstre['nom']} et mira amb ulls de foc!");
    } else {
        $monstre = triarMonstre(MONSTRES, $partida["heroi"]["nivell"]);
        missatge($partida, "Sala {$partida['sala']}: apareix un enemic! -> {$monstre['nom']}");
    }

    $monstre["vidaMax"] = $monstre["vida"];
    $partida["monstre"] = $monstre;
    $partida["fase"] = "combat";
}

function atacar(array &$partida): void
{
    $tirada = random_int(1, 6);
    $dany = calcularDany($partida["heroi"]["atac"], $partida["monstre"]["defensa"], $tirada);
    rebreDany($partida["monstre"], $dany);

    $text = "Ataques (dau: $tirada) i fas $dany de dany.";
    if ($tirada === 6) {
        $text = "CRÍTIC! " . $text;
    }
    missatge($partida, $text);

    resoldreTorn($partida);
}

function ferAtacEspecial(array &$partida): void
{
    $tirada = random_int(1, 6);

    try {
        $dany = atacEspecial($partida["heroi"], $partida["monstre"], $tirada);
    } catch (RepteNoFet $e) {
        missatge($partida, "(Bloquejat) L'atac especial és el repte 14 (extra).");
        return;
    }

    $partida["especialUsat"] = true;

    if ($dany <= 0) {
        missatge($partida, "Atac especial (dau: $tirada)... però falles!");
    } else {
        rebreDany($partida["monstre"], $dany);
        missatge($partida, "ATAC ESPECIAL (dau: $tirada)! Fas $dany de dany.");
    }

    resoldreTorn($partida);
}

function fugir(array &$partida): void
{
    if (esSalaDelDrac($partida)) {
        missatge($partida, "No pots fugir del drac! La porta s'ha tancat darrere teu.");
        tornDelMonstre($partida);
        return;
    }

    if (random_int(1, 100) <= PROBABILITAT_FUGIR) {
        missatge($partida, "Fuges corrents i tanques la porta. No guanyes res.");
        $partida["monstre"] = null;
        $partida["fase"] = "passadis";
    } else {
        missatge($partida, "Intentes fugir, però et talla el pas!");
        tornDelMonstre($partida);
    }
}

// Després del teu atac: si el monstre ha caigut has guanyat; si no, et toca rebre.
function resoldreTorn(array &$partida): void
{
    if (!estaViu($partida["monstre"])) {
        victoria($partida);
    } else {
        tornDelMonstre($partida);
    }
}

function tornDelMonstre(array &$partida): void
{
    $monstre = $partida["monstre"];
    $tirada = random_int(1, 6);
    $dany = calcularDany($monstre["atac"], $partida["heroi"]["defensa"], $tirada);
    rebreDany($partida["heroi"], $dany);

    $text = "{$monstre['nom']} t'ataca (dau: $tirada) i et fa $dany de dany.";
    if ($tirada === 6) {
        $text = "CRÍTIC! " . $text;
    }
    missatge($partida, $text);

    if (!estaViu($partida["heroi"])) {
        missatge($partida, "Caus a terra... La masmorra s'ha cobrat una altra víctima.");
        $partida["fase"] = "final";
        $partida["resultat"] = "derrota";
    }
}

function victoria(array &$partida): void
{
    $monstre = $partida["monstre"];
    missatge($partida, "Has derrotat: {$monstre['nom']}!");
    $partida["derrotats"][] = $monstre;

    try {
        cobrarRecompensa($partida["heroi"], $monstre);
        missatge($partida, "Guanyes {$monstre['or']} d'or i {$monstre['exp']} punts d'experiència.");
    } catch (RepteNoFet $e) {
        missatge($partida, "(Bloquejat) No cobres res: " . $e->getMessage());
    }

    try {
        if (pujarNivell($partida["heroi"])) {
            missatge($partida, "PUGES DE NIVELL! Ara ets de nivell {$partida['heroi']['nivell']} i recuperes tota la vida.");
        }
    } catch (RepteNoFet $e) {
        missatge($partida, "(Bloquejat) No pots pujar de nivell: " . $e->getMessage());
    }

    $partida["monstre"] = null;

    if (esSalaDelDrac($partida)) {
        $partida["fase"] = "final";
        $partida["resultat"] = "victoria";
    } elseif (in_array($partida["sala"], SALES_AMB_BOTIGA)) {
        missatge($partida, "Darrere el monstre trobes una botiga amagada.");
        $partida["fase"] = "botiga";
    } else {
        $partida["fase"] = "passadis";
    }
}

// Retorna true si s'ha pogut beure la poció.
function intentarBeurePocio(array &$partida): bool
{
    try {
        $curat = beurePocio($partida["heroi"]);
        missatge($partida, "Beus una poció i recuperes $curat punts de vida.");
        return true;
    } catch (RangeException $e) {
        missatge($partida, $e->getMessage());
    } catch (RepteNoFet $e) {
        missatge($partida, "(Bloquejat) " . $e->getMessage());
    }

    return false;
}

function comprarArticle(array &$partida, array $article): void
{
    try {
        comprar($partida["heroi"], $article);
        missatge($partida, "Has comprat: {$article['nom']}.");
    } catch (RangeException $e) {
        missatge($partida, "La botiguera diu: " . $e->getMessage());
    } catch (RepteNoFet $e) {
        missatge($partida, "(Bloquejat) La botiga està tancada. " . $e->getMessage());
        $partida["fase"] = "passadis";
    }
}

// Resum del final de la partida, o null si el repte 13 no està fet.
function resumFinal(array $partida): ?array
{
    try {
        return resumPartida($partida["derrotats"]);
    } catch (RepteNoFet $e) {
        return null;
    }
}

// Retorna els missatges pendents de mostrar i els esborra de la partida.
function treureMissatges(array &$partida): array
{
    $missatges = $partida["missatges"];
    $partida["missatges"] = [];
    return $missatges;
}
