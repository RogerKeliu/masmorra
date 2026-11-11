<?php

/*
 * ==========================================================================
 *  LA MASMORRA — versió web                   NO CAL TOCAR AQUEST FITXER
 * ==========================================================================
 *  Des de la carpeta masmorra/, executa:
 *      php -S localhost:8000 -t web
 *  i obre http://localhost:8000 al navegador.
 *
 *  Fa servir les MATEIXES funcions que has escrit a funcions.php.
 * ==========================================================================
 */

session_start();

$errorCarrega = null;
try {
    require_once __DIR__ . "/../motor.php";
} catch (ParseError $e) {
    $errorCarrega = explicarError($e);
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, "UTF-8");
}

function iconaClasse(string $nomClasse): string
{
    foreach (CLASSES as $classe) {
        if ($classe["nom"] === $nomClasse) {
            return $classe["icona"];
        }
    }
    return "🙂";
}

function percentatge(int $vida, int $vidaMax): int
{
    if ($vidaMax <= 0) {
        return 0;
    }
    return (int) max(0, min(100, round($vida / $vidaMax * 100)));
}


/* ---------------------- Accions (formularis) ---------------------- */

if ($errorCarrega === null && $_SERVER["REQUEST_METHOD"] === "POST") {
    $accio = $_POST["accio"] ?? "";

    try {
        if ($accio === "nova") {
            unset($_SESSION["partida"], $_SESSION["registre"]);
        } elseif ($accio === "crear") {
            $_SESSION["partida"] = crearPartida($_POST["nom"] ?? "", $_POST["classe"] ?? "");
            $_SESSION["registre"] = [];
        } elseif ($accio === "opcio" && isset($_SESSION["partida"])) {
            jugarTorn($_SESSION["partida"], $_POST["opcio"] ?? "");
        }
    } catch (InvalidArgumentException $e) {
        $_SESSION["avis"] = $e->getMessage();
    } catch (RepteNoFet $e) {
        $_SESSION["bloqueig"] = ["text" => $e->getMessage(), "repte" => $e->getCode()];
    } catch (Throwable $e) {
        $_SESSION["error"] = explicarError($e);
    }

    // Post/Redirect/Get: si es recarrega la pàgina no es repeteix l'acció
    header("Location: " . strtok($_SERVER["REQUEST_URI"], "?"), true, 303);
    exit;
}


/* ------------------------ Dades per pintar ------------------------ */

$avis = $_SESSION["avis"] ?? null;
$bloqueig = $_SESSION["bloqueig"] ?? null;
$error = $_SESSION["error"] ?? $errorCarrega;
unset($_SESSION["avis"], $_SESSION["bloqueig"], $_SESSION["error"]);

$partida = null;
$vista = [];

if ($errorCarrega === null && isset($_SESSION["partida"])) {
    $partida = &$_SESSION["partida"];

    $nous = treureMissatges($partida);
    if (count($nous) > 0) {
        $_SESSION["registre"][] = $nous;
        $_SESSION["registre"] = array_slice($_SESSION["registre"], -5);
    }

    try {
        $heroi = $partida["heroi"];
        $vista["barraHeroi"] = barraVida($heroi["vida"], $heroi["vidaMax"]);

        if ($partida["fase"] === "combat") {
            $monstre = $partida["monstre"];
            $vista["barraMonstre"] = barraVida($monstre["vida"], $monstre["vidaMax"]);
        }

        $vista["opcions"] = opcions($partida);

        if ($partida["fase"] === "final") {
            $vista["resum"] = resumFinal($partida);
        }
    } catch (RepteNoFet $e) {
        $bloqueig = ["text" => $e->getMessage(), "repte" => $e->getCode()];
        $vista = [];
    } catch (Throwable $e) {
        $error = explicarError($e);
        $vista = [];
    }
}

$registre = $_SESSION["registre"] ?? [];
?>
<!doctype html>
<html lang="ca">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>La Masmorra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="estil.css">
</head>
<body>
<main class="pantalla">

    <header class="capcalera">
        <h1>La Masmorra</h1>
        <?php if ($partida !== null): ?>
            <form method="post">
                <button class="boto boto-petit" name="accio" value="nova">Nova partida</button>
            </form>
        <?php endif; ?>
    </header>

    <?php if ($error !== null): ?>
        <section class="avis avis-error">
            <strong>Error de PHP</strong>
            <pre><?= e($error) ?></pre>
            <a class="boto boto-petit" href="">Tornar a provar</a>
        </section>
    <?php endif; ?>

    <?php if ($bloqueig !== null): ?>
        <section class="avis avis-bloqueig">
            <div class="cadenat">🔒</div>
            <div>
                <strong>Joc aturat — Repte <?= (int) $bloqueig["repte"] ?></strong>
                <p><?= e($bloqueig["text"]) ?></p>
                <p class="petit">Quan l'hagis fet: <code>php comprova.php <?= (int) $bloqueig["repte"] ?></code> i torna a provar-ho aquí.</p>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($avis !== null): ?>
        <section class="avis avis-groc"><?= e($avis) ?></section>
    <?php endif; ?>

    <?php if ($errorCarrega === null && $partida === null): ?>
        <!-- ======================= INICI ======================= -->
        <form method="post" class="inici">
            <input type="hidden" name="accio" value="crear">
            <p class="historia">Diuen que al fons de la masmorra dorm un drac ancestral.
                Travessa <?= SALES_TOTALS ?> sales plenes de monstres i derrota'l.</p>

            <label class="camp">
                <span>Nom de l'heroi</span>
                <input type="text" name="nom" maxlength="30" placeholder="Escriu el teu nom" autofocus>
            </label>

            <div class="classes">
                <?php foreach (CLASSES as $numero => $classe): ?>
                    <label class="classe">
                        <input type="radio" name="classe" value="<?= $numero ?>">
                        <span class="targeta">
                            <span class="icona-gran"><?= $classe["icona"] ?></span>
                            <strong><?= e($classe["nom"]) ?></strong>
                            <span class="petit"><?= e($classe["descripcio"]) ?></span>
                            <span class="stats">
                                <span>❤️ <?= $classe["vida"] ?></span>
                                <span>⚔️ <?= $classe["atac"] ?></span>
                                <span>🛡️ <?= $classe["defensa"] ?></span>
                            </span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>

            <button class="boto boto-gran">Entrar a la masmorra</button>
        </form>

    <?php elseif ($partida !== null && count($vista) > 0): ?>
        <?php $heroi = $partida["heroi"]; ?>

        <!-- ======================= PROGRÉS ======================= -->
        <ol class="progres">
            <?php for ($s = 1; $s <= SALES_TOTALS + 1; $s++): ?>
                <?php
                $classeSala = "";
                if ($s < $partida["sala"] || ($s === $partida["sala"] && $partida["fase"] !== "combat")) {
                    $classeSala = "feta";
                } elseif ($s === $partida["sala"]) {
                    $classeSala = "actual";
                }
                ?>
                <li class="<?= $classeSala ?>"><?= $s > SALES_TOTALS ? "🐉" : $s ?></li>
            <?php endfor; ?>
        </ol>

        <!-- ======================= ESCENA ======================= -->
        <section class="escena">
            <article class="fitxa">
                <div class="icona-gran"><?= iconaClasse($heroi["classe"]) ?></div>
                <h2><?= e($heroi["nom"]) ?></h2>
                <p class="petit"><?= e($heroi["classe"]) ?> · nivell <?= (int) $heroi["nivell"] ?></p>
                <div class="barra"><span style="width: <?= percentatge($heroi["vida"], $heroi["vidaMax"]) ?>%"></span></div>
                <code class="barra-text"><?= e($vista["barraHeroi"]) ?></code>
                <div class="stats">
                    <span title="Atac">⚔️ <?= (int) $heroi["atac"] ?></span>
                    <span title="Defensa">🛡️ <?= (int) $heroi["defensa"] ?></span>
                    <span title="Or">🪙 <?= (int) $heroi["or"] ?></span>
                    <span title="Pocions">🧪 <?= (int) $heroi["pocions"] ?></span>
                    <span title="Experiència">✨ <?= (int) $heroi["exp"] ?>/<?= (int) $heroi["nivell"] * 40 ?></span>
                </div>
            </article>

            <?php if ($partida["fase"] === "combat"): ?>
                <?php $monstre = $partida["monstre"]; ?>
                <div class="vs">VS</div>
                <article class="fitxa fitxa-monstre">
                    <div class="icona-gran"><?= $monstre["icona"] ?? "👾" ?></div>
                    <h2><?= e($monstre["nom"]) ?></h2>
                    <p class="petit">nivell <?= (int) $monstre["nivell"] ?></p>
                    <div class="barra barra-monstre"><span style="width: <?= percentatge($monstre["vida"], $monstre["vidaMax"]) ?>%"></span></div>
                    <code class="barra-text"><?= e($vista["barraMonstre"]) ?></code>
                    <div class="stats">
                        <span title="Atac">⚔️ <?= (int) $monstre["atac"] ?></span>
                        <span title="Defensa">🛡️ <?= (int) $monstre["defensa"] ?></span>
                    </div>
                </article>
            <?php elseif ($partida["fase"] === "botiga"): ?>
                <article class="fitxa fitxa-botiga">
                    <div class="icona-gran">🏪</div>
                    <h2>Botiga amagada</h2>
                    <p class="petit">"Passa, passa! Què et cal per a la propera sala?"</p>
                </article>
            <?php elseif ($partida["fase"] === "final"): ?>
                <article class="fitxa fitxa-final <?= $partida["resultat"] === "victoria" ? "guanya" : "perd" ?>">
                    <div class="icona-gran"><?= $partida["resultat"] === "victoria" ? "🏆" : "💀" ?></div>
                    <h2><?= $partida["resultat"] === "victoria" ? "Victòria!" : "Game over" ?></h2>
                    <?php if ($vista["resum"] === null): ?>
                        <p class="petit">Completa el repte 13 per veure el resum de la partida.</p>
                    <?php else: ?>
                        <ul class="resum">
                            <li>Monstres derrotats: <strong><?= (int) $vista["resum"]["total"] ?></strong></li>
                            <li>Or guanyat: <strong><?= (int) $vista["resum"]["or"] ?></strong></li>
                            <li>Experiència: <strong><?= (int) $vista["resum"]["exp"] ?></strong></li>
                            <li>Rival més perillós: <strong><?= e((string) $vista["resum"]["mesFort"]) ?></strong></li>
                        </ul>
                    <?php endif; ?>
                </article>
            <?php endif; ?>
        </section>

        <!-- ======================= REGISTRE ======================= -->
        <?php if (count($registre) > 0): ?>
            <section class="registre">
                <?php foreach ($registre as $i => $grup): ?>
                    <div class="<?= $i === count($registre) - 1 ? "ultim" : "" ?>">
                        <?php foreach ($grup as $text): ?>
                            <p><?= e($text) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <!-- ======================= OPCIONS ======================= -->
        <?php if ($partida["fase"] === "final"): ?>
            <form method="post" class="opcions">
                <button class="boto boto-gran" name="accio" value="nova">Tornar a jugar</button>
            </form>
        <?php else: ?>
            <form method="post" class="opcions">
                <input type="hidden" name="accio" value="opcio">
                <?php foreach ($vista["opcions"] as $numero => $text): ?>
                    <button class="boto <?= str_contains($text, "[no t'arriba]") ? "apagat" : "" ?>"
                            name="opcio" value="<?= $numero ?>">
                        <span class="num"><?= $numero ?></span> <?= e($text) ?>
                    </button>
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    <?php endif; ?>

</main>
</body>
</html>
