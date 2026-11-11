<?php

/*
 * ==========================================================================
 *  LA MASMORRA — EL TEU FITXER
 * ==========================================================================
 *  Aquest és l'ÚNIC fitxer que has de modificar.
 *
 *  COM ESTÀ FET CADA REPTE
 *  -----------------------
 *  1. A dalt de cada funció hi ha la FITXA del repte: quan s'utilitza dins
 *     del joc, què rep, què retorna, les regles, exemples i les funcions de
 *     PHP que et poden anar bé.
 *
 *  2. A dins de la funció hi ha els PASSOS explicats amb paraules. El codi
 *     l'escrius TU: posa'l just a sota de cada PAS. Abans de començar a
 *     escriure, llegeix tots els passos de la funció.
 *
 *  3. Al final de cada funció hi ha la línia  pendent(N);
 *     ESBORRA-LA quan hagis acabat la funció. Mentre hi sigui, el joc
 *     s'aturarà i et dirà que aquest repte encara no està fet.
 *
 *  COM HO PROVES
 *  -------------
 *    php comprova.php 1     -> comprova només el repte 1 (et dona pistes)
 *    php comprova.php       -> comprova tots els reptes
 *    php jugar.php          -> juga!
 *
 *  Si surt un "Error de sintaxi", mira la línia que t'indica: segurament hi
 *  falta un ; una } o unes cometes.
 *
 *  Tens més explicacions al fitxer REPTES.md.
 * ==========================================================================
 */


/* ##########################################################################
 *  FASE 1 — L'HEROI
 * ########################################################################## */


/*
 * --------------------------------------------------------------------------
 *  REPTE 1 — netejarNom()                                        Dificultat: *
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: al principi del joc, quan el jugador escriu el seu nom.
 *
 *  QUÈ REP:      $nom   el text que ha escrit el jugador (string)
 *  QUÈ RETORNA:  el nom "net" (string)
 *
 *  QUÈ HA DE FER:
 *    - Treure els espais del principi i del final.
 *    - Si després de treure els espais no queda res, retornar "Anònim".
 *    - Si no, retornar el nom amb la primera lletra en majúscula i la resta
 *      en minúscula.
 *
 *  EXEMPLES:
 *    netejarNom("laia")       ->  "Laia"
 *    netejarNom("  pere  ")   ->  "Pere"
 *    netejarNom("MARTA")      ->  "Marta"
 *    netejarNom("   ")        ->  "Anònim"
 *
 *  FUNCIONS DE PHP QUE NECESSITES:
 *    trim("  hola  ")     ->  "hola"   treu els espais del principi i del final
 *    strtolower("HOLA")   ->  "hola"   ho passa tot a minúscules
 *    ucfirst("hola")      ->  "Hola"   posa la primera lletra en majúscula
 */
function netejarNom(string $nom): string
{
     $nom = trim($nom);

    if ($nom === "") {
        return "Anònim";
    }

    return ucfirst(strtolower($nom));
    // PAS 1: El text pot arribar amb espais al davant i al darrere
    //        (per exemple "  pere  "). Aplica trim() a $nom i guarda el
    //        resultat a la MATEIXA variable $nom. A partir d'aquí ja
    //        treballes amb el nom sense espais.


    // PAS 2: Comprova si $nom ha quedat buit, és a dir, si és igual a un
    //        text sense res (dues cometes seguides). Si és així, la funció
    //        s'acaba aquí: retorna el text "Anònim".
    //
    //        Important: aquest pas va DESPRÉS del trim. Si el fessis abans,
    //        "   " no seria buit (té espais) i no detectaries que el jugador
    //        no ha escrit res.


    // PAS 3: Si el programa arriba aquí, és que hi ha un nom. Fes dues coses:
    //          a) passa tot el nom a minúscules amb strtolower();
    //          b) al resultat d'això, aplica-hi ucfirst() perquè la primera
    //             lletra quedi en majúscula.
    //        Retorna el resultat final.
    //
    //        Ho pots fer en dues línies (guardant el resultat intermedi en
    //        una variable) o en una de sola, posant una funció dins de l'altra.
    //        Pensa per què cal el strtolower: sense ell, "MARTA" quedaria "MARTA".


    pendent(1); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 2 — validarOpcio()                                     Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: a TOTS els menús del joc (triar classe, atacar, comprar...)
 *  per saber si el jugador ha escrit una opció vàlida.
 *
 *  QUÈ REP:      $entrada   el que ha escrit el jugador (string, p. ex. "2")
 *                $minim     el número d'opció més petit permès (int)
 *                $maxim     el número d'opció més gran permès (int)
 *  QUÈ RETORNA:  l'opció convertida a número enter (int)
 *                O BÉ llança una InvalidArgumentException si no és vàlida.
 *
 *  EXEMPLES:
 *    validarOpcio("2", 1, 3)     ->  2
 *    validarOpcio(" 3 ", 1, 3)   ->  3
 *    validarOpcio("0", 0, 4)     ->  0
 *    validarOpcio("hola", 1, 3)  ->  InvalidArgumentException("Has d'escriure un número.")
 *    validarOpcio("7", 1, 3)     ->  InvalidArgumentException("Tria una opció entre 1 i 3.")
 *
 *  SOBRE LES EXCEPCIONS:
 *    Quan fas un throw, la funció s'acaba en aquell moment (com un return).
 *    NO cal fer try/catch: el motor del joc ja captura l'excepció i mostra
 *    "Avís: <el teu missatge>".
 *
 *  FUNCIONS DE PHP QUE NECESSITES:
 *    is_numeric("12")    ->  true      diu si un text és un número
 *    is_numeric("hola")  ->  false
 *    (int) "12"          ->  12        converteix un text a enter
 *
 *  ATENCIÓ (error típic): comprova is_numeric() ABANS de convertir a (int).
 *  (int) "hola" dona 0, i llavors ja no sabries que no era un número.
 */
function validarOpcio(string $entrada, int $minim, int $maxim): int
{
    // PAS 1: Treu els espais de $entrada amb trim() i guarda-ho a la mateixa
    //        variable. Així " 3 " passarà a ser "3".


    // PAS 2: Comprova si $entrada NO és un número. Per saber-ho tens
    //        is_numeric(), que retorna true o false. Per dir "NO" en PHP es
    //        posa un signe d'exclamació davant de la condició.
    //
    //        Si no és un número, llança una InvalidArgumentException amb el
    //        missatge exacte: Has d'escriure un número.


    // PAS 3: Si el programa arriba aquí, ja saps segur que és un número.
    //        Ara sí: converteix $entrada a enter amb (int) i guarda-ho en una
    //        variable nova, per exemple $opcio.


    // PAS 4: Comprova si $opcio està FORA del rang permès, és a dir:
    //          - és més petita que $minim,  O BÉ
    //          - és més gran que $maxim.
    //        Les dues condicions van dins del mateix if, unides amb l'operador
    //        "o" de PHP (dues barres verticals).
    //
    //        Si està fora del rang, llança una InvalidArgumentException amb el
    //        missatge: Tria una opció entre X i Y.
    //        on X és el valor de $minim i Y el de $maxim. Pista: dins de cometes
    //        dobles pots escriure les variables directament.
    //
    //        Compte: els extrems SÍ que són vàlids. validarOpcio("1", 1, 3) i
    //        validarOpcio("3", 1, 3) han de funcionar. Pensa si has de fer
    //        servir < o <=.


    // PAS 5: Si cap dels if anteriors ha llançat una excepció, l'opció és
    //        bona. Retorna $opcio (el número enter, no el text $entrada).


    pendent(2); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 3 — crearHeroi()                                        Dificultat: *
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: just després de triar la classe, per crear el personatge.
 *
 *  QUÈ REP:      $nom      el nom de l'heroi (ja net)
 *                $classe   l'array de la classe triada. Per exemple:
 *                  [
 *                      "nom" => "Guerrer",
 *                      "icona" => "🛡️",
 *                      "vida" => 120,
 *                      "atac" => 12,
 *                      "defensa" => 6,
 *                      "descripcio" => "Molta vida i bona armadura...",
 *                  ]
 *                (Les tens totes a dades.php, a CLASSES.)
 *
 *  QUÈ RETORNA:  un array associatiu amb AQUESTES 10 claus, escrites
 *                EXACTAMENT així (majúscules incloses):
 *
 *      clau        valor
 *      ---------   ------------------------------------------
 *      "nom"       el nom que rep la funció
 *      "classe"    el NOM de la classe (p. ex. "Guerrer")
 *      "nivell"    1
 *      "exp"       0
 *      "vida"      la vida de la classe
 *      "vidaMax"   també la vida de la classe
 *      "atac"      l'atac de la classe
 *      "defensa"   la defensa de la classe
 *      "or"        10
 *      "pocions"   2
 *
 *  EXEMPLE: crearHeroi("Laia", <classe Guerrer>) retorna
 *      [
 *          "nom" => "Laia", 
 *          "classe" => "Guerrer", 
 *          "nivell" => 1, 
 *          "exp" => 0,
 *          "vida" => 120, 
 *          "vidaMax" => 120, 
 *          "atac" => 12, 
 *          "defensa" => 6,
 *          "or" => 10, "pocions" => 2]
 *
 *  PER QUÈ "vida" I "vidaMax"? "vida" baixa quan et fan mal; "vidaMax" és
 *  el límit fins on et pots curar.
 */
function crearHeroi(string $nom, array $classe): array
{
    // PAS 1: Aquesta funció només ha de fer una cosa: retornar un array
    //        associatiu nou. Pots escriure directament un return seguit de
    //        l'array entre claudàtors, amb una parella clau => valor per
    //        línia, separades per comes.


    // PAS 2: Les claus són les 10 de la taula de dalt. Escriu-les exactament
    //        igual: una lletra diferent (per exemple "vidamax" en lloc de
    //        "vidaMax") farà que el joc falli més endavant.


    // PAS 3: Decideix el valor de cada clau:
    //          - "nom": és el paràmetre $nom tal com arriba.
    //          - "classe": NO és tot l'array $classe, sinó només el seu nom.
    //            L'has de llegir de dins de $classe, amb la clau "nom".
    //          - "vida", "atac" i "defensa": es llegeixen de dins de $classe,
    //            amb la clau que es diu igual.
    //          - "vidaMax": el mateix valor que la vida de la classe.
    //          - "nivell", "exp", "or" i "pocions": són números fixos que tens
    //            a la taula. Tots els herois comencen igual.
    //
    //        La "icona" i la "descripcio" de la classe no es fan servir: no
    //        les posis a l'heroi.


    pendent(3); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 4 — barraVida()                                        Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: cada cop que es mostra la fitxa de l'heroi o d'un monstre.
 *
 *  QUÈ REP:      $vida      la vida actual (int)
 *                $vidaMax   la vida màxima (int)
 *  QUÈ RETORNA:  un text (string) amb:
 *                  - un "[" al principi
 *                  - 20 caselles: les plenes amb "#" i les buides amb "-"
 *                  - un "]", un espai i la vida en números: "50/100"
 *
 *  EXEMPLES:
 *    barraVida(100, 100)  ->  "[####################] 100/100"
 *    barraVida(50, 100)   ->  "[##########----------] 50/100"
 *    barraVida(30, 120)   ->  "[#####---------------] 30/120"
 *    barraVida(0, 80)     ->  "[--------------------] 0/80"
 *
 *  QUANTES CASELLES PLENES?
 *    plenes = vida / vidaMax * 20, arrodonit.
 *    Exemple: 30 / 120 * 20 = 5      -> 5 "#" i 15 "-"
 *    Exemple: 45 / 120 * 20 = 7.5    -> round(7.5) = 8 "#" i 12 "-"
 *
 *  RECORDA:
 *    round(7.5)        ->  8        arrodoneix (dona un float: 8.0)
 *    (int) 8.0         ->  8        el converteix a enter
 *    .=                             afegeix text al final d'una variable
 *                                   (si $frase val "Hola", després de fer
 *                                   .= "!" val "Hola!")
 */
function barraVida(int $vida, int $vidaMax): string
{
    // PAS 1: Calcula quantes caselles han d'anar plenes i guarda-ho en una
    //        variable (per exemple $plenes). Fes servir la fórmula de dalt:
    //        vida dividit per vida màxima, multiplicat per 20. El resultat
    //        pot tenir decimals (7.5), així que arrodoneix-lo amb round() i
    //        converteix-lo a enter amb (int).


    // PAS 2: Crea una variable de text (per exemple $barra) que de moment
    //        només contingui el claudàtor d'obertura "[". Hi aniràs
    //        enganxant al final tota la resta.


    // PAS 3: Fes un bucle for que doni exactament 20 voltes. El comptador ha
    //        d'anar de 1 a 20 (tots dos inclosos). A cada volta decideix
    //        quina casella toca:
    //          - si el número de la volta és més petit o igual que $plenes,
    //            afegeix "#" al final de $barra;
    //          - si no, afegeix "-".
    //
    //        Comprova-ho de cap: amb $plenes = 5, les voltes 1, 2, 3, 4 i 5
    //        posen "#" i de la 6 a la 20 posen "-".


    // PAS 4: Quan s'acabi el bucle (és a dir, FORA del for), afegeix a $barra
    //        el claudàtor de tancament "]", un espai, la vida, una barra "/"
    //        i la vida màxima. Ha de quedar com als exemples: "] 50/100".


    // PAS 5: Retorna $barra.
    //
    //        Error típic: si la barra et surt amb 19 o amb 21 caselles, revisa
    //        on comença el comptador i la condició del for (< o <=).


    pendent(4); // <- ESBORRA aquesta línia quan hagis acabat
}


/* ##########################################################################
 *  FASE 2 — EL COMBAT
 * ########################################################################## */


/*
 * --------------------------------------------------------------------------
 *  REPTE 5 — triarMonstre()                                    Dificultat: ***
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: cada cop que entres en una sala nova.
 *
 *  QUÈ REP:      $monstres   la llista de tots els monstres (MONSTRES de
 *                            dades.php). Cada monstre és un array:
 *                  ["nom" => "Goblin", "nivell" => 2, "vida" => 45, "atac" => 11, ...]
 *                $nivell     el nivell de l'heroi (int)
 *  QUÈ RETORNA:  UN monstre (array) triat a l'atzar d'entre els que
 *                compleixen les DUES condicions:
 *                  - nivell del monstre <= nivell de l'heroi
 *                  - nivell del monstre >= nivell de l'heroi - 1
 *                Si cap monstre les compleix, tria'n un de tota la llista.
 *
 *  EXEMPLES (amb els monstres de dades.php):
 *    nivell de l'heroi 1  ->  surt un de nivell 1 (Rata, Slime o Ratpenat)
 *    nivell de l'heroi 2  ->  surt un de nivell 1 o 2
 *    nivell de l'heroi 3  ->  surt un de nivell 2 o 3
 *    nivell de l'heroi 9  ->  cap compleix -> qualsevol monstre de la llista
 *
 *  FUNCIONS DE PHP QUE NECESSITES:
 *    $llista[] = $valor;     afegeix $valor al final de $llista
 *    count($llista)          quants elements té
 *    array_rand($llista)     retorna una CLAU a l'atzar de $llista
 */
function triarMonstre(array $monstres, int $nivell): array
{
    // PAS 1: Crea un array buit, per exemple $possibles. Aquí hi aniràs
    //        guardant els monstres que serveixen per al nivell de l'heroi.


    // PAS 2: Recorre $monstres amb un foreach. A cada volta tindràs UN
    //        monstre (que és un array); el seu nivell el llegeixes amb la
    //        clau "nivell".
    //
    //        Dins del foreach, escriu un if que comprovi les dues condicions
    //        alhora, unides amb l'operador "i" de PHP (dos &):
    //          - el nivell del monstre és més petit o igual que $nivell
    //          - el nivell del monstre és més gran o igual que $nivell menys 1
    //
    //        Si les compleix, afegeix el monstre SENCER (no només el nom) al
    //        final de $possibles.
    //
    //        Comprova-ho de cap: heroi de nivell 3 -> serveixen els monstres
    //        de nivell 2 i 3, però no els de nivell 1 ni els de nivell 4.


    // PAS 3: Després del foreach, mira si $possibles ha quedat buit (és a
    //        dir, si count() dona 0). Si és així, cap monstre és del nivell
    //        adequat: en aquest cas, fes que $possibles sigui la llista
    //        sencera $monstres.


    // PAS 4: Tria'n un a l'atzar. Compte: array_rand($possibles) NO et dona
    //        el monstre, et dona una CLAU (la posició d'un monstre). Guarda
    //        aquesta clau en una variable i, fent-la servir entre claudàtors,
    //        retorna el monstre d'aquella posició de $possibles.


    pendent(5); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 6 — calcularDany()                                     Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: a cada atac del combat, tant del teu heroi com dels
 *  monstres.
 *
 *  QUÈ REP:      $atac      l'atac de qui ataca (int)
 *                $defensa   la defensa de qui rep el cop (int)
 *                $tirada    el que ha sortit en un dau de 6 cares (1 a 6)
 *                           (el dau el tira el motor: tu només el reps)
 *  QUÈ RETORNA:  el dany que fa l'atac (int)
 *
 *  REGLES (en aquest ordre):
 *    1. dany = atac + tirada - defensa
 *    2. Si la tirada és un 6, és un CRÍTIC: el dany es multiplica per 2.
 *    3. El dany mínim és 1: si ha sortit menys d'1, el dany és 1.
 *
 *  EXEMPLES:
 *    calcularDany(12, 4, 3)  ->  11      12 + 3 - 4 = 11
 *    calcularDany(10, 3, 5)  ->  12      10 + 5 - 3 = 12
 *    calcularDany(12, 4, 6)  ->  28      (12 + 6 - 4) * 2 = 28   CRÍTIC!
 *    calcularDany(5, 10, 2)  ->  1       5 + 2 - 10 = -3  ->  mínim 1
 */
function calcularDany(int $atac, int $defensa, int $tirada): int
{
    // PAS 1: Aplica la regla 1: suma l'atac i la tirada i resta-hi la
    //        defensa. Guarda el resultat en una variable, per exemple $dany.


    // PAS 2: Aplica la regla 2: comprova si la tirada és exactament 6
    //        (compara amb tres iguals). Si ho és, multiplica $dany per 2 i
    //        guarda el resultat a la mateixa variable $dany.
    //        Si no és 6, no s'ha de fer res: no cal cap else.


    // PAS 3: Aplica la regla 3: si $dany és més petit que 1, fes que valgui 1.
    //
    //        L'ordre és important: primer el crític i després el mínim.
    //        Exemple: atac 5, defensa 20, tirada 6 -> 5 + 6 - 20 = -9;
    //        el doble és -18; el mínim el deixa a 1. Si ho fessis al revés
    //        et sortiria 2.


    // PAS 4: Retorna $dany.


    pendent(6); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 7 — rebreDany()                                        Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: després de calcularDany(), per treure vida a qui rep
 *  el cop (pot ser l'heroi o el monstre).
 *
 *  QUÈ REP:      &$personatge   l'array del personatge, PER REFERÈNCIA
 *                $dany          el dany que rep (int)
 *  QUÈ RETORNA:  res (void). Modifica directament la "vida" de $personatge.
 *
 *  QUÈ VOL DIR EL & ?
 *    Sense &, la funció rebria una CÒPIA de l'array i, en acabar, el
 *    personatge de veritat no hauria perdut vida. Amb &, la funció treballa
 *    amb l'array ORIGINAL. El & ja està posat: no l'esborris!
 *
 *  REGLES:
 *    - Resta el dany a la vida.
 *    - La vida no pot quedar en negatiu: si queda per sota de 0, posa-la a 0.
 *
 *  EXEMPLES:
 *    $p = ["nom" => "Goblin", "vida" => 50];
 *    rebreDany($p, 20);    // ara $p["vida"] val 30
 *    rebreDany($p, 99);    // ara $p["vida"] val 0  (no -69)
 */
function rebreDany(array &$personatge, int $dany): void
{
    // PAS 1: La vida està dins de $personatge, a la clau "vida". Resta-hi
    //        $dany i guarda el resultat a la MATEIXA posició de l'array
    //        (no en una variable nova). Ho pots fer amb una resta normal o
    //        amb l'operador -= .


    // PAS 2: Comprova si la vida del personatge ha quedat per sota de 0.
    //        Si és així, posa-la a 0.


    // No has de fer return (la funció és void) ni crear cap array nou: gràcies
    // al &, el que canviïs a $personatge es canvia al personatge original.
    //
    // Error típic: guardar el resultat en una variable a part (per exemple
    // $vida). Llavors l'array no canvia i el personatge no perd vida.


    pendent(7); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 8 — estaViu()                                           Dificultat: *
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: després de cada atac, per saber si el combat s'ha acabat.
 *
 *  QUÈ REP:      $personatge   l'array d'un personatge (heroi o monstre)
 *  QUÈ RETORNA:  true si la seva "vida" és MÉS GRAN que 0, false si no.
 *
 *  EXEMPLES:
 *    estaViu(["vida" => 10])  ->  true
 *    estaViu(["vida" => 1])   ->  true
 *    estaViu(["vida" => 0])   ->  false
 */
function estaViu(array $personatge): bool
{
    // PAS ÚNIC: Llegeix la vida del personatge (clau "vida") i compara-la
    //           amb 0. Si és més gran que 0 has de retornar true; si no
    //           (0 o menys), has de retornar false.
    //
    //           Ho pots fer de dues maneres:
    //             - Amb un if / else i un return a cada banda.
    //             - En una sola línia: una comparació ja dona true o false
    //               per si mateixa, així que pots retornar directament el
    //               resultat de la comparació.
    //
    //           Compte: has de RETORNAR el booleà, no fer-ne un echo.
    //           I amb vida 0 el personatge està mort: pensa si cal > o >=.


    pendent(8); // <- ESBORRA aquesta línia quan hagis acabat
}

/*
 *  >>> Quan tinguis els reptes 1 a 8, el joc ja funciona de principi a fi!
 *      Prova:  php jugar.php
 *      I al navegador:  php -S localhost:8000 -t web
 */


/* ##########################################################################
 *  FASE 3 — MILLORES
 * ########################################################################## */


/*
 * --------------------------------------------------------------------------
 *  REPTE 9 — beurePocio()                                      Dificultat: ***
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: quan el jugador tria "Beure una poció".
 *
 *  QUÈ REP:      &$heroi   l'heroi, PER REFERÈNCIA
 *                $cura     quanta vida cura la poció. Té un VALOR PER
 *                          DEFECTE de 40: si la criden com beurePocio($heroi),
 *                          $cura val 40; si fan beurePocio($heroi, 10), val 10.
 *  QUÈ RETORNA:  els punts de vida que ha recuperat DE VERITAT (int)
 *                O BÉ llança una RangeException si no es pot beure.
 *
 *  REGLES:
 *    - Si no té pocions (0 o menys):  RangeException("No et queden pocions!")
 *    - Si ja té tota la vida:         RangeException("Ja tens tota la vida.")
 *      (i en aquests dos casos no es gasta cap poció)
 *    - La vida no pot passar de vidaMax.
 *    - Es gasta una poció.
 *
 *  EXEMPLES:
 *    vida 50/120,  2 pocions  ->  vida 90,  1 poció,  retorna 40
 *    vida 100/120, 1 poció    ->  vida 120, 0 pocions, retorna 20  (no 40!)
 *    vida 50/120,  0 pocions  ->  RangeException
 *    vida 120/120, 2 pocions  ->  RangeException, continua amb 2 pocions
 *
 *  FUNCIÓ DE PHP QUE NECESSITES:
 *    min(40, 20)  ->  20      retorna el més petit dels dos
 */
function beurePocio(array &$heroi, int $cura = 40): int
{
    // PAS 1: Comprova si l'heroi té 0 pocions o menys (clau "pocions").
    //        Si és així, llança una RangeException amb el missatge:
    //        No et queden pocions!


    // PAS 2: Comprova si la vida de l'heroi ja és igual a la seva vida
    //        màxima (claus "vida" i "vidaMax"). Si és així, llança una
    //        RangeException amb el missatge: Ja tens tota la vida.
    //
    //        Aquests dos primers passos van AL PRINCIPI, abans de modificar
    //        res de l'heroi. Així, si es llança l'excepció, l'heroi no perd
    //        cap poció.


    // PAS 3: Calcula quanta vida li falta per arribar al màxim: la vida
    //        màxima menys la vida actual. Guarda-ho en una variable, per
    //        exemple $falta.


    // PAS 4: La vida que es cura de veritat és la MÉS PETITA entre $cura i
    //        $falta. Calcula-la amb min() i guarda-la en una variable, per
    //        exemple $curat.
    //          vida 100/120 -> falten 20 -> el més petit entre 40 i 20 és 20
    //          vida 50/120  -> falten 70 -> el més petit entre 40 i 70 és 40
    //
    //        Fes servir la variable $cura, no el número 40: si el motor crida
    //        beurePocio($heroi, 10), la poció ha de curar 10.


    // PAS 5: Ara modifica l'heroi:
    //          - suma $curat a la seva vida;
    //          - resta-li una poció.


    // PAS 6: Retorna $curat (la vida curada de veritat, no $cura).


    pendent(9); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 10 — cobrarRecompensa()                                 Dificultat: *
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: quan guanyes un combat.
 *
 *  QUÈ REP:      &$heroi    l'heroi, PER REFERÈNCIA
 *                $monstre   el monstre que has derrotat
 *  QUÈ RETORNA:  res (void)
 *
 *  QUÈ HA DE FER:
 *    - Sumar l'"or" del monstre a l'"or" de l'heroi.
 *    - Sumar l'"exp" del monstre a l'"exp" de l'heroi.
 *
 *  EXEMPLE:
 *    heroi amb 10 d'or i 0 d'exp + derrota un Goblin (12 d'or, 35 d'exp)
 *    ->  heroi amb 22 d'or i 35 d'exp
 *
 *  RECORDA:  $a += 5;   és el mateix que   $a = $a + 5;
 */
function cobrarRecompensa(array &$heroi, array $monstre): void
{
    // PAS 1: Llegeix l'or del monstre (clau "or" de $monstre) i suma'l a
    //        l'or de l'heroi (clau "or" de $heroi). El resultat s'ha de
    //        quedar guardat dins de $heroi. L'operador += ho fa en un sol pas.


    // PAS 2: Fes exactament el mateix amb l'experiència (clau "exp").
    //
    //        No cal return, i el monstre no s'ha de modificar.


    pendent(10); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 11 — pujarNivell()                                     Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: després de cobrar la recompensa d'un combat.
 *
 *  QUÈ REP:      &$heroi   l'heroi, PER REFERÈNCIA
 *  QUÈ RETORNA:  true si ha pujat de nivell, false si no.
 *
 *  REGLES:
 *    Per pujar de nivell calen  nivell * 40  punts d'experiència.
 *      nivell 1 -> calen 40 punts
 *      nivell 2 -> calen 80 punts
 *      nivell 3 -> calen 120 punts...
 *
 *    Si NO en té prou: no canvia res i retorna false.
 *    Si en té prou (o més):
 *      - resta els punts necessaris a "exp"
 *      - "nivell"   +1
 *      - "vidaMax"  +15
 *      - "atac"     +3
 *      - "defensa"  +1
 *      - "vida" passa a valer el mateix que "vidaMax" (es cura del tot)
 *      - retorna true
 *
 *  EXEMPLE:
 *    Abans:   nivell 1, exp 45, vida 20, vidaMax 120, atac 12, defensa 6
 *    Retorna: true
 *    Després: nivell 2, exp 5,  vida 135, vidaMax 135, atac 15, defensa 7
 */
function pujarNivell(array &$heroi): bool
{
    // PAS 1: Calcula quants punts d'experiència calen per pujar: el nivell
    //        actual de l'heroi multiplicat per 40. Guarda-ho en una variable,
    //        per exemple $necessaria.


    // PAS 2: Si l'experiència de l'heroi és MÉS PETITA que $necessaria, no
    //        pot pujar: retorna false sense haver canviat res.
    //
    //        Compte amb el cas just: amb exp 40 i nivell 1 SÍ que puja.
    //        Pensa si cal < o <=.


    // PAS 3: Si el programa arriba aquí, l'heroi puja de nivell. Fes, un per
    //        un, tots els canvis de la llista de REGLES:
    //          - resta $necessaria a l'"exp". NO la posis a 0: els punts que
    //            sobren es queden (a l'exemple, 45 - 40 = 5).
    //          - suma 1 al "nivell"
    //          - suma 15 a la "vidaMax"
    //          - suma 3 a l'"atac"
    //          - suma 1 a la "defensa"
    //          - fes que la "vida" valgui el mateix que la "vidaMax".
    //            Fes aquest canvi el DARRER, quan la vidaMax ja és la nova
    //            (135 i no 120).


    // PAS 4: Retorna true.


    pendent(11); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 12 — comprar()                                        Dificultat: ***
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: a la botiga (apareix després de les sales 3 i 6).
 *
 *  QUÈ REP:      &$heroi    l'heroi, PER REFERÈNCIA
 *                $article   l'article que vol comprar. Per exemple:
 *                  [
 *                      "nom" => "Espasa esmolada",
 *                      "preu" => 40,
 *                      "millora" => "atac",      <- QUINA clau de l'heroi puja
 *                      "quantitat" => 3,         <- QUANT puja
 *                      "descripcio" => "+3 d'atac",
 *                  ]
 *                (Els tens tots a dades.php, a BOTIGA.)
 *  QUÈ RETORNA:  res (void), o llança una RangeException si no té prou or.
 *
 *  REGLES:
 *    - Si l'heroi té MENYS or que el preu: RangeException("No tens prou or.")
 *      (Si en té exactament el preu, SÍ que pot comprar.)
 *    - Resta el preu a l'or de l'heroi.
 *    - Suma la "quantitat" a la clau de l'heroi que indica "millora".
 *    - Cas especial: si la millora és "vidaMax", suma també la quantitat
 *      a "vida" (si no, la vida nova quedaria buida).
 *
 *  EXEMPLES:
 *    or 50, compra Poció (15, "pocions", +1)  -> or 35, pocions +1
 *    or 40, compra Espasa (40, "atac", +3)    -> or 0,  atac +3
 *    or 50, compra Amulet (30, "vidaMax", +20)-> or 20, vidaMax +20 i vida +20
 *    or 10, compra Espasa (40)                -> RangeException, no canvia res
 *
 *  TRUC: LA CLAU D'UN ARRAY POT ESTAR GUARDADA EN UNA VARIABLE
 *    $dia = "dilluns";
 *    echo $horari[$dia];       // és el mateix que  echo $horari["dilluns"];
 */
function comprar(array &$heroi, array $article): void
{
    // PAS 1: Compara l'or de l'heroi amb el preu de l'article (clau "preu"
    //        de $article). Si l'heroi en té MENYS, llança una RangeException
    //        amb el missatge: No tens prou or.
    //
    //        Compte: amb exactament el preu SÍ que pot comprar. Pensa si cal
    //        < o <=. I fes aquest pas ABANS de tocar res de l'heroi.


    // PAS 2: Cobra: resta el preu de l'article a l'or de l'heroi.


    // PAS 3: Mira quina clau de l'heroi millora aquest article. La tens dins
    //        de $article, a la clau "millora" (per exemple "atac" o
    //        "pocions"). Guarda aquest text en una variable, per exemple
    //        $millora.


    // PAS 4: Suma la quantitat de l'article (clau "quantitat") a la clau de
    //        l'heroi que indica $millora. Aquí fas servir el TRUC de dalt:
    //        entre els claudàtors de $heroi no hi escrius un text entre
    //        cometes, sinó la variable $millora.
    //
    //        Així UNA sola línia serveix per a tots els articles de la
    //        botiga: no cal fer un if per a cada article.


    // PAS 5: Cas especial: si $millora és "vidaMax", suma també la quantitat
    //        de l'article a la "vida" de l'heroi.


    pendent(12); // <- ESBORRA aquesta línia quan hagis acabat
}


/* ##########################################################################
 *  FASE 4 — EL FINAL
 * ########################################################################## */


/*
 * --------------------------------------------------------------------------
 *  REPTE 13 — resumPartida()                                   Dificultat: ***
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: a la pantalla final (tant si guanyes com si perds).
 *
 *  QUÈ REP:      $derrotats   la llista dels monstres que has vençut
 *                             (un array d'arrays de monstre)
 *  QUÈ RETORNA:  un array associatiu amb 4 claus:
 *                  "total"    quants monstres has derrotat
 *                  "or"       la suma de l'"or" de tots
 *                  "exp"      la suma de l'"exp" de tots
 *                  "mesFort"  el NOM del monstre amb l'"atac" més alt
 *                             ("Cap" si la llista és buida)
 *
 *  EXEMPLES:
 *    $derrotats = [Goblin (or 12, exp 35, atac 11),
 *                  Orc    (or 22, exp 55, atac 16),
 *                  Rata   (or 6,  exp 20, atac 8)];
 *    resumPartida($derrotats)
 *      ->  ["total" => 3, "or" => 40, "exp" => 110, "mesFort" => "Orc"]
 *
 *    resumPartida([])
 *      ->  ["total" => 0, "or" => 0, "exp" => 0, "mesFort" => "Cap"]
 *
 *  IDEA: és com buscar la nota més alta d'una llista. Guardes "la més alta
 *  fins ara" en una variable i, a cada volta, si en trobes una de més alta,
 *  l'actualitzes.
 */
function resumPartida(array $derrotats): array
{
    // PAS 1: Crea l'array del resum (per exemple $resum) amb les 4 claus i
    //        els seus valors inicials:
    //          - "total": quants monstres hi ha a $derrotats (fes servir count()).
    //          - "or" i "exp": 0, perquè després hi aniràs sumant.
    //          - "mesFort": "Cap". Si la llista és buida, el foreach no farà
    //            cap volta i es quedarà així, que és el que volem.


    // PAS 2: Crea una variable per recordar l'atac més alt que has trobat
    //        fins ara (per exemple $atacMesAlt) i dona-li el valor -1. Així
    //        segur que el primer monstre el supera.


    // PAS 3: Recorre $derrotats amb un foreach. A cada volta tens un monstre.
    //        Dins del foreach fes dues coses:
    //          a) Suma l'or del monstre a la clau "or" del resum, i la seva
    //             exp a la clau "exp" del resum.
    //          b) Compara l'atac del monstre amb $atacMesAlt. Si és MÉS GRAN:
    //               - guarda l'atac del monstre a $atacMesAlt (és el nou rècord);
    //               - guarda el NOM del monstre a la clau "mesFort" del resum.
    //
    //        Comprova-ho de cap amb l'exemple de dalt:
    //          Goblin (atac 11) -> 11 > -1 -> rècord 11, mesFort "Goblin"
    //          Orc    (atac 16) -> 16 > 11 -> rècord 16, mesFort "Orc"
    //          Rata   (atac 8)  ->  8 > 16? no -> no canvia res


    // PAS 4: Quan s'acabi el foreach, retorna el resum.


    pendent(13); // <- ESBORRA aquesta línia quan hagis acabat
}


/* ##########################################################################
 *  REPTES EXTRA (opcionals: el joc funciona sense ells)
 * ########################################################################## */


/*
 * --------------------------------------------------------------------------
 *  REPTE 14 (extra) — atacEspecial()                            Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: opció 4 del combat. Es pot fer un sol cop per combat.
 *
 *  QUÈ REP:      $heroi     l'heroi (aquí NO cal &: només el llegim)
 *                $monstre   el monstre contra qui lluites
 *                $tirada    el resultat del dau (1 a 6)
 *  QUÈ RETORNA:  el dany de l'atac especial (int). Mai negatiu.
 *
 *  REGLES SEGONS LA CLASSE ($heroi["classe"]):
 *    "Guerrer"  ->  atac * 2 - defensa del monstre
 *    "Mag"      ->  atac + tirada * 3                  (ignora la defensa)
 *    "Lladre"   ->  si la tirada és 4 o més: (atac + tirada) * 3 - defensa
 *                   si no: 0 (ha fallat)
 *    una altra  ->  0
 *
 *  EXEMPLES (monstre amb defensa 4):
 *    Guerrer amb atac 12, tirada 3   ->  12 * 2 - 4        = 20
 *    Mag amb atac 17, tirada 2       ->  17 + 2 * 3        = 23
 *    Lladre amb atac 14, tirada 5    ->  (14 + 5) * 3 - 4  = 53
 *    Lladre amb atac 14, tirada 2    ->  0
 *
 *  RECORDA: switch compara un valor amb diversos casos. No t'oblidis
 *  del break; al final de cada case.
 *    max(0, -5)  ->  0       retorna el més gran dels dos
 *    max(0, 20)  ->  20
 */
function atacEspecial(array $heroi, array $monstre, int $tirada): int
{
    // PAS 1: Fes un switch sobre la classe de l'heroi (clau "classe" de
    //        $heroi). Necessites un case per a cada classe ("Guerrer", "Mag"
    //        i "Lladre") i un default per a qualsevol altra. Recorda el break
    //        al final de cada case.


    // PAS 2: Dins de cada case, calcula el dany i guarda'l en una variable
    //        (per exemple $dany), seguint la taula de REGLES:
    //          - Guerrer: l'atac de l'HEROI per 2, menys la defensa del
    //            MONSTRE (clau "defensa" de $monstre).
    //          - Mag: l'atac de l'heroi més la tirada per 3. La multiplicació
    //            es fa abans que la suma, que és just el que volem.
    //          - Lladre: aquí cal un if dins del case. Si la tirada és 4 o
    //            més, el dany és (atac + tirada) per 3, menys la defensa del
    //            monstre: aquí SÍ que calen parèntesis. Si no, el dany és 0.
    //          - default: el dany és 0.


    // PAS 3: Després del switch, retorna el dany, però assegura't que no sigui
    //        negatiu. Fes servir max() amb 0 i el dany: si el dany és
    //        negatiu retornarà 0, i si no, el mateix dany.


    pendent(14); // <- ESBORRA aquesta línia quan hagis acabat
}


/*
 * --------------------------------------------------------------------------
 *  REPTE 15 (extra) — articlesAssequibles()                     Dificultat: **
 * --------------------------------------------------------------------------
 *  QUAN S'UTILITZA: a la botiga, per marcar amb [no t'arriba] els articles
 *  que no et pots permetre.
 *
 *  QUÈ REP:      $botiga   l'array de la botiga (BOTIGA de dades.php).
 *                          Les claus són els números del menú: 1, 2, 3, 4.
 *                $or       l'or que té l'heroi (int)
 *  QUÈ RETORNA:  un array només amb els articles que tenen preu <= $or,
 *                MANTENINT les claus originals.
 *
 *  EXEMPLE (preus: 1 => 15, 2 => 40, 3 => 35, 4 => 30):
 *    articlesAssequibles($botiga, 35)  ->  articles amb claus 1, 3 i 4
 *    articlesAssequibles($botiga, 10)  ->  []  (array buit)
 *
 *  Per què cal mantenir les claus? Perquè el motor mira si existeix la
 *  clau 2 del resultat per saber si l'article 2 et fa o no.
 */
function articlesAssequibles(array $botiga, int $or): array
{
    // Ho pots fer de dues maneres. Tria'n UNA.
    //
    // OPCIÓ A — amb un foreach
    //
    //   PAS 1: Crea un array buit per al resultat.
    //
    //   PAS 2: Recorre $botiga amb el foreach que et dona la CLAU i el VALOR
    //          a cada volta (el que porta la fletxa =>). La clau és el número
    //          de l'article (1, 2, 3...) i el valor és l'article.
    //
    //   PAS 3: Dins del foreach, si el preu de l'article és més petit o igual
    //          que $or, afegeix l'article al resultat AMB LA MATEIXA CLAU que
    //          tenia a la botiga.
    //          Compte: no l'afegeixis amb claudàtors buits; si ho fas, les
    //          claus es tornen a numerar des de 0 i el motor es confon.
    //
    //   PAS 4: Quan s'acabi el foreach, retorna el resultat.
    //
    // OPCIÓ B — amb array_filter
    //
    //   array_filter rep un array i una funció. Crida la funció per a cada
    //   element: si la funció retorna true, l'element es queda; si retorna
    //   false, es descarta. Les claus es mantenen soles.
    //
    //   Com a funció fes servir una funció fletxa (fn) que rebi un article i
    //   retorni si el seu preu és més petit o igual que $or. Dins d'una funció
    //   fletxa pots fer servir $or directament. Retorna el que dona array_filter.


    pendent(15); // <- ESBORRA aquesta línia quan hagis acabat
}
