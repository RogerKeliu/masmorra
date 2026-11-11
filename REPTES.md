# La Masmorra — reptes pas a pas

> Avui no farem exercicis solts: acabarem un **videojoc**.
> El joc ja està gairebé fet: el motor, la pantalla, els monstres i la botiga
> funcionen. Però li falten les peces més importants: **les funcions que
> decideixen què passa**. Les escriuràs tu, una a una.

```text
  +--------------------------------------------------+
  |            L A    M A S M O R R A                |
  |     Travessa 8 sales i derrota el drac final     |
  +--------------------------------------------------+
```

## Índex

0. [Abans de començar](#0-abans-de-començar)
1. [Fase 1 — L'heroi](#fase-1--lheroi) (reptes 1–4)
2. [Fase 2 — El combat](#fase-2--el-combat) (reptes 5–8) → **el joc ja es pot jugar**
3. [Fase 3 — Millores](#fase-3--millores) (reptes 9–12)
4. [Fase 4 — El final](#fase-4--el-final) (repte 13)
5. [Reptes extra](#reptes-extra) (14, 15 i reptes lliures)
6. [Si alguna cosa falla](#si-alguna-cosa-falla)
7. [El meu progrés](#el-meu-progrés)

---

## 0. Abans de començar

### Els fitxers

| Fitxer | Què és | L'has de tocar? |
|---|---|---|
| `funcions.php` | Les funcions del joc, buides | ✏️ **SÍ, només aquest** |
| `REPTES.md` | Aquest document | No |
| `dades.php` | Classes, monstres i botiga | Només llegir-lo (i als reptes extra) |
| `jugar.php` | El joc per al terminal | No |
| `comprova.php` | Comprova si les teves funcions estan bé | No |
| `motor.php` | El motor: torns, combat, botiga... | No (però el pots llegir) |
| `web/` | El mateix joc, però al navegador | No |

### Com funciona

Obre `funcions.php`. Cada funció té una línia com aquesta:

```php
function netejarNom(string $nom): string
{
    pendent(1);
}
```

`pendent(1)` vol dir: *"el repte 1 encara no està fet"*. Mentre aquesta línia
hi sigui, quan el joc arribi a aquesta funció **s'aturarà** i et dirà:

```text
  JOC ATURAT
  Repte 1 pendent: completa la funció netejarNom() a funcions.php
  Quan l'hagis fet, comprova-la amb:  php comprova.php 1
```

**Quan facis una funció, esborra la línia `pendent(N);` i escriu el teu codi.**

### El cicle de treball (per a cada repte)

1. Llegeix el repte en aquest document.
2. Escriu el codi a `funcions.php` i esborra el `pendent(N);`.
3. Comprova'l des de la carpeta `masmorra/`:

   ```bash
   php comprova.php 1
   ```

   ```text
   Repte 1 — netejarNom
      [ OK ]  netejarNom("laia") -> "Laia"
      [FALLA] netejarNom("  pere  ")
              esperava: "Pere"
              has donat: "  pere  "
              pista: Mira trim()
   ```

4. Quan surti **SUPERAT!**, juga i mira què ha canviat:

   ```bash
   php jugar.php
   ```

5. Passa al repte següent.

> 💡 `php comprova.php` (sense número) comprova tots els reptes i et diu
> quin és el següent que et toca.

### La versió web (quan tinguis la fase 2)

El mateix joc funciona al navegador, **amb les teves funcions**. Des de la
carpeta `masmorra/`:

```bash
php -S localhost:8000 -t web
```

Obre <http://localhost:8000>. Per aturar el servidor, prem `Ctrl + C`.

### Què cal saber de l'heroi

Tot el joc treballa amb **arrays associatius**. L'heroi és així:

```php
$heroi = [
    "nom" => "Laia",
    "classe" => "Guerrer",
    "nivell" => 1,
    "exp" => 0,          // punts d'experiència
    "vida" => 120,
    "vidaMax" => 120,
    "atac" => 12,
    "defensa" => 6,
    "or" => 10,
    "pocions" => 2,
];
```

I un monstre de `dades.php`:

```php
["nom" => "Goblin", "icona" => "👺", "nivell" => 2, "vida" => 45,
 "atac" => 11, "defensa" => 3, "or" => 12, "exp" => 35]
```

---

## Fase 1 — L'heroi

### Repte 1 — `netejarNom()` ⭐

**Objectiu.** El jugador escriu el seu nom com vol (`"  laia "`, `"PERE"`...).
Aquesta funció el deixa net.

| Rep | Retorna |
|---|---|
| `string $nom`: el que ha escrit el jugador | `string`: el nom net |

| Crida | Ha de retornar |
|---|---|
| `netejarNom("laia")` | `"Laia"` |
| `netejarNom("  pere  ")` | `"Pere"` |
| `netejarNom("MARTA")` | `"Marta"` |
| `netejarNom("   ")` | `"Anònim"` |

**Pas a pas**

1. Treu els espais del principi i del final amb `trim()` i guarda el resultat
   a la mateixa variable: `$nom = trim($nom);`
2. Si `$nom` és igual a `""`, retorna `"Anònim"`.
3. Passa tot el nom a minúscules amb `strtolower()`.
4. Posa la primera lletra en majúscula amb `ucfirst()` i retorna el resultat.

> ⚠️ L'ordre importa: si mires si és buit **abans** del `trim()`, `"   "` no
> serà buit.

**Comprova-ho:** `php comprova.php 1` → i després `php jugar.php`: ara el joc et
saluda pel nom i s'atura al repte 2.

**Si vas ràpid:** si el nom té més de 12 caràcters, retalla'l amb
`substr($nom, 0, 12)`.

---

### Repte 2 — `validarOpcio()` ⭐⭐

**Objectiu.** Tots els menús del joc (triar classe, atacar, comprar...) fan
servir aquesta funció per comprovar que el jugador ha escrit una opció vàlida.

| Rep | Retorna |
|---|---|
| `string $entrada`: el que ha escrit el jugador | `int`: l'opció triada |
| `int $minim`, `int $maxim`: el rang d'opcions vàlides | o **llança** una `InvalidArgumentException` |

| Crida | Resultat |
|---|---|
| `validarOpcio("2", 1, 3)` | `2` |
| `validarOpcio("0", 0, 4)` | `0` |
| `validarOpcio("hola", 1, 3)` | 💥 `InvalidArgumentException("Has d'escriure un número.")` |
| `validarOpcio("7", 1, 3)` | 💥 `InvalidArgumentException("Tria una opció entre 1 i 3.")` |

**Pas a pas**

1. Fes `trim()` de l'entrada.
2. **Primer** comprova que és un número amb `is_numeric()`. Si no ho és,
   llança l'excepció:

   ```php
   throw new InvalidArgumentException("Has d'escriure un número.");
   ```

3. **Després** converteix-la a enter: `$opcio = (int) $entrada;`
4. Si `$opcio` és més petita que `$minim` **o** més gran que `$maxim`, llança
   una altra `InvalidArgumentException` amb el missatge
   `"Tria una opció entre $minim i $maxim."`
5. Si ha passat tots els controls, retorna `$opcio`.

> ⚠️ **Error típic dels exercicis anteriors:** fer `(int)` abans de validar.
> `(int) "hola"` val `0`, i llavors `is_numeric()` sempre diu que sí.

> 💡 No has de fer `try/catch`: la funció només **llança** l'excepció. El
> motor del joc la captura i mostra el missatge `Avís: ...`.

**Comprova-ho:** `php comprova.php 2`. Al joc, prova d'escriure `hola` o `9`
quan et demana la classe.

---

### Repte 3 — `crearHeroi()` ⭐

**Objectiu.** Crear l'array de l'heroi a partir del nom i la classe triada.

| Rep | Retorna |
|---|---|
| `string $nom` | `array`: l'heroi |
| `array $classe`: una classe de `CLASSES` (mira `dades.php`) | |

Per exemple, si `$classe` és:

```php
["nom" => "Guerrer", "icona" => "🛡️", "vida" => 120, "atac" => 12, "defensa" => 6, "descripcio" => "..."]
```

`crearHeroi("Laia", $classe)` ha de retornar exactament l'array de l'apartat
[Què cal saber de l'heroi](#què-cal-saber-de-lheroi).

**Pas a pas**

1. Retorna directament un array associatiu: `return [ ... ];`
2. Posa-hi les 10 claus, **escrites exactament així** (majúscules incloses):
   `nom`, `classe`, `nivell`, `exp`, `vida`, `vidaMax`, `atac`, `defensa`,
   `or`, `pocions`.
3. Els valors de vida, atac i defensa surten de `$classe`: `$classe["vida"]`...
4. `"classe"` és el **nom** de la classe: `$classe["nom"]`.
5. Comença amb nivell 1, 0 d'experiència, 10 d'or i 2 pocions.

> 💡 `vida` i `vidaMax` comencen igual. `vida` baixarà quan et facin mal;
> `vidaMax` és el límit quan et curis.

**Comprova-ho:** `php comprova.php 3`.

---

### Repte 4 — `barraVida()` ⭐⭐

**Objectiu.** Dibuixar la barra de vida que surt a la pantalla.

| Rep | Retorna |
|---|---|
| `int $vida`, `int $vidaMax` | `string`: la barra |

| Crida | Ha de retornar |
|---|---|
| `barraVida(100, 100)` | `"[####################] 100/100"` |
| `barraVida(50, 100)` | `"[##########----------] 50/100"` |
| `barraVida(30, 120)` | `"[#####---------------] 30/120"` |
| `barraVida(0, 80)` | `"[--------------------] 0/80"` |

**Pas a pas**

1. Calcula quantes caselles han d'estar plenes (de 20) i guarda-ho a `$plenes`:
   vida dividit per vida màxima, per 20. Arrodoneix-ho amb `round()` i
   converteix-ho a enter amb `(int)`.

2. Comença la cadena amb un claudàtor: `$barra = "[";`
3. Fes un `for` de l'1 al 20. A cada volta, si `$i <= $plenes` afegeix `"#"`;
   si no, afegeix `"-"`. Per afegir text al final d'una cadena: `$barra .= "#";`
4. Tanca el claudàtor i afegeix la vida en números: `"] $vida/$vidaMax"`.
5. Retorna `$barra`.

> ⚠️ Compte amb el `for`: si comences pel 0 i fas `<= 20`, en sortiran 21.

**Comprova-ho:** `php comprova.php 4`. Ara al joc ja veus la fitxa de l'heroi
amb la barra.

**Si vas ràpid:** fes-ho sense `for`, amb `str_repeat()`:
`str_repeat("#", 3)` retorna `"###"`.

---

## Fase 2 — El combat

### Repte 5 — `triarMonstre()` ⭐⭐⭐

**Objectiu.** Quan entres en una sala, apareix un monstre a l'atzar que sigui
del teu nivell o d'un nivell menys.

| Rep | Retorna |
|---|---|
| `array $monstres`: la llista `MONSTRES` de `dades.php` | `array`: un monstre |
| `int $nivell`: el nivell de l'heroi | |

| Nivell de l'heroi | Poden sortir monstres de nivell... |
|---|---|
| 1 | 1 (i 0, però no n'hi ha) |
| 2 | 1 o 2 |
| 3 | 2 o 3 |
| 9 | no n'hi ha cap → qualsevol monstre de la llista |

**Pas a pas**

1. Crea un array buit: `$possibles = [];`
2. Recorre `$monstres` amb un `foreach ($monstres as $monstre)`.
3. Si `$monstre["nivell"] <= $nivell` **i** `$monstre["nivell"] >= $nivell - 1`,
   afegeix-lo: `$possibles[] = $monstre;`
4. Després del bucle, si `count($possibles)` és 0, fes que `$possibles` sigui
   tota la llista `$monstres`.
5. Tria'n un a l'atzar i retorna'l. `array_rand($possibles)` no et dona el
   monstre sinó una **clau** a l'atzar: fes servir aquesta clau per agafar el
   monstre de `$possibles`. (També es pot fer amb `random_int()` i `count()`.)

> ⚠️ Si fas servir `array_filter()`, les claus **no es renumeren** (poden
> quedar 2, 3, 4). Llavors `random_int(0, ...)` pot demanar una clau que no
> existeix: `Undefined array key`. Amb `array_rand()` no passa.

**Comprova-ho:** `php comprova.php 5`. Al joc, ara pots entrar a la sala 1 i
veure-hi un monstre.

---

### Repte 6 — `calcularDany()` ⭐⭐

**Objectiu.** Calcular quant de mal fa un atac. Serveix tant per a l'heroi com
per als monstres.

| Rep | Retorna |
|---|---|
| `int $atac`: atac de qui ataca | `int`: el dany |
| `int $defensa`: defensa de qui rep | |
| `int $tirada`: el resultat d'un dau (de 1 a 6) | |

| Crida | Ha de retornar | Per què |
|---|---|---|
| `calcularDany(12, 4, 3)` | `11` | 12 + 3 − 4 |
| `calcularDany(12, 4, 6)` | `28` | (12 + 6 − 4) × 2, crític! |
| `calcularDany(5, 10, 2)` | `1` | dona −3, però el mínim és 1 |

**Pas a pas**

1. `$dany = $atac + $tirada - $defensa;`
2. Si `$tirada` és 6, multiplica `$dany` per 2.
3. Si `$dany` és més petit que 1, fes que valgui 1.
4. Retorna `$dany`.

> 💡 El dau el tira el motor amb `random_int(1, 6)` i te'l passa com a
> paràmetre. Així la funció es pot comprovar sense atzar.

**Si vas ràpid:** el pas 3 es pot fer en una línia amb `max()`.

---

### Repte 7 — `rebreDany()` ⭐⭐

**Objectiu.** Restar vida a un personatge (heroi o monstre).

| Rep | Retorna |
|---|---|
| `array &$personatge`: **per referència** | res (`void`) |
| `int $dany` | |

```php
$p = ["nom" => "Goblin", "vida" => 50];
rebreDany($p, 20);   // ara $p["vida"] val 30
rebreDany($p, 99);   // ara $p["vida"] val 0 (mai negatiu)
```

**Pas a pas**

1. Resta `$dany` a `$personatge["vida"]`.
2. Si la vida ha quedat per sota de 0, posa-la a 0.
3. No cal `return`.

> 💡 Fixa't en el `&` de `array &$personatge`: vol dir que la funció
> modifica **l'array original**, no una còpia. Sense el `&`, el monstre no
> perdria mai vida. Repassa l'apartat *Pas d'arguments per referència* dels
> apunts.

---

### Repte 8 — `estaViu()` ⭐

**Objectiu.** Saber si un personatge segueix viu.

Retorna `true` si `$personatge["vida"]` és més gran que 0, i `false` si no.

> 💡 Es pot fer en una sola línia: `return ... > 0;`

**Comprova-ho:** `php comprova.php 8`.

### 🎉 Fites: el joc ja funciona!

Amb els reptes 1–8 fets, **pots jugar una partida sencera**:

```bash
php jugar.php
```

I prova la versió web: `php -S localhost:8000 -t web`.

Veuràs missatges com `(Bloquejat) No cobres res: Repte 10 pendent...`: són
les parts que encara has de fer. El joc continua igualment.

---

## Fase 3 — Millores

### Repte 9 — `beurePocio()` ⭐⭐⭐

**Objectiu.** Beure una poció per recuperar vida.

| Rep | Retorna |
|---|---|
| `array &$heroi` (per referència) | `int`: la vida que ha recuperat |
| `int $cura = 40`: valor per defecte | o llança una `RangeException` |

| Situació | Resultat |
|---|---|
| vida 50/120, 2 pocions | vida 90, 1 poció, retorna `40` |
| vida 100/120, 1 poció | vida 120, 0 pocions, retorna `20` (no es passa de 120) |
| 0 pocions | 💥 `RangeException("No et queden pocions!")` |
| vida 120/120 | 💥 `RangeException("Ja tens tota la vida.")` i **no gasta la poció** |

**Pas a pas**

1. Si `$heroi["pocions"]` és 0 o menys, llança
   `new RangeException("No et queden pocions!")`.
2. Si `$heroi["vida"]` és igual a `$heroi["vidaMax"]`, llança
   `new RangeException("Ja tens tota la vida.")`.
3. Calcula quanta vida pots recuperar sense passar-te del màxim: primer quanta
   vida li falta (vida màxima menys vida) i després el més petit entre `$cura`
   i el que falta, amb `min()`. Guarda-ho a `$curat`.

4. Suma `$curat` a la vida i resta 1 a les pocions (`$heroi["pocions"]--;`).
5. Retorna `$curat`.

> ⚠️ Fes servir el paràmetre `$cura`, no el número 40 escrit a mà: el
> comprovador també prova `beurePocio($heroi, 10)`.

---

### Repte 10 — `cobrarRecompensa()` ⭐

**Objectiu.** Quan guanyes un combat, et quedes l'or i l'experiència del
monstre.

Suma `$monstre["or"]` a `$heroi["or"]` i `$monstre["exp"]` a `$heroi["exp"]`.
Recorda que `$heroi` arriba per referència (`&`).

> 💡 `$a += 5;` és el mateix que `$a = $a + 5;`

---

### Repte 11 — `pujarNivell()` ⭐⭐

**Objectiu.** Amb prou experiència, l'heroi puja de nivell.

Per passar de nivell calen **`nivell * 40`** punts d'experiència (nivell 1 →
40 punts; nivell 2 → 80 punts...).

| Abans | Retorna | Després |
|---|---|---|
| nivell 1, exp 30 | `false` | no canvia res |
| nivell 1, exp 45, vida 20/120, atac 12, defensa 6 | `true` | nivell 2, exp 5, vida 135/135, atac 15, defensa 7 |

**Pas a pas**

1. Calcula `$necessaria = $heroi["nivell"] * 40;`
2. Si `$heroi["exp"]` és més petit que `$necessaria`, retorna `false`.
3. Si no:
   - resta `$necessaria` a l'experiència;
   - nivell +1, vidaMax +15, atac +3, defensa +1;
   - la vida passa a ser igual que la vidaMax (es cura del tot);
   - retorna `true`.

**Si vas ràpid:** si l'heroi guanya tanta experiència de cop que podria pujar
dos nivells, només en puja un. Fes que en pugi tants com calgui (pista: `while`).

---

### Repte 12 — `comprar()` ⭐⭐⭐

**Objectiu.** Després de les sales 3 i 6 apareix una botiga. Aquesta funció fa
la compra.

| Rep | Retorna |
|---|---|
| `array &$heroi` (per referència) | res, o llança una `RangeException` |
| `array $article`: un article de `BOTIGA` | |

Un article és així:

```php
["nom" => "Espasa esmolada", "preu" => 40, "millora" => "atac", "quantitat" => 3, "descripcio" => "+3 d'atac"]
```

`"millora"` diu **quina clau de l'heroi** puja, i `"quantitat"`, quant.

**Pas a pas**

1. Si l'heroi té menys or que el preu, llança
   `new RangeException("No tens prou or.")`.
2. Resta el preu de l'or de l'heroi.
3. Guarda la clau que millora: `$millora = $article["millora"];`
4. Suma la quantitat de l'article a la clau de l'heroi que indica `$millora`.
   Entre els claudàtors de `$heroi` no hi posis un text entre cometes, sinó la
   variable `$millora`: així una sola línia serveix per a tots els articles.
5. Cas especial: si `$millora` és `"vidaMax"`, suma la mateixa quantitat a
   `"vida"`.

> ⚠️ Si tens **exactament** l'or que costa, sí que ho pots comprar. Pensa si
> has de fer servir `<` o `<=`.

**Si vas ràpid:** fes que el missatge digui quant costa i quant en tens:
`"No tens prou or: costa 40 i en tens 12."`

---

## Fase 4 — El final

### Repte 13 — `resumPartida()` ⭐⭐⭐

**Objectiu.** En acabar la partida (guanyis o perdis), es mostra un resum.

| Rep | Retorna |
|---|---|
| `array $derrotats`: llista dels monstres que has vençut | `array` amb `total`, `or`, `exp` i `mesFort` |

```php
resumPartida([$goblin, $orc, $rata]);
// ["total" => 3, "or" => 40, "exp" => 110, "mesFort" => "Orc"]

resumPartida([]);
// ["total" => 0, "or" => 0, "exp" => 0, "mesFort" => "Cap"]
```

`mesFort` és el **nom** del monstre amb l'atac més alt.

**Pas a pas**

1. Crea l'array del resum amb els valors inicials:
   `total` = `count($derrotats)`, `or` = 0, `exp` = 0, `mesFort` = `"Cap"`.
2. Crea una variable `$atacMesAlt = -1;`
3. Recorre `$derrotats` amb `foreach`. Per a cada monstre:
   - suma el seu or i la seva exp al resum;
   - si el seu atac és més gran que `$atacMesAlt`, actualitza `$atacMesAlt`
     i guarda el seu nom a `mesFort`.
4. Retorna el resum.

**Si vas ràpid:** fes les sumes sense bucle:
`array_sum(array_column($derrotats, "or"))`. `array_column()` treu una
columna d'un array multidimensional.

### 🏆 Has acabat el joc!

`php comprova.php` hauria de dir `Reptes superats: 13/13`. Ara intenta
guanyar el drac... no és fàcil!

---

## Reptes extra

No són obligatoris: el joc funciona sense ells.

### Repte 14 — `atacEspecial()` ⭐⭐

Cada classe té un atac especial que es pot fer **un cop per combat** (opció 4).
Fes servir un `switch` amb `$heroi["classe"]`:

| Classe | Dany |
|---|---|
| `"Guerrer"` | `atac * 2 - defensa del monstre` |
| `"Mag"` | `atac + tirada * 3` (ignora la defensa) |
| `"Lladre"` | si la tirada és 4 o més: `(atac + tirada) * 3 - defensa`; si no: `0` (falla) |
| qualsevol altra | `0` |

El dany mai pot ser negatiu (pista: `max(0, $dany)`).

### Repte 15 — `articlesAssequibles()` ⭐⭐

Retorna només els articles de la botiga que l'heroi es pot permetre
(`preu <= or`). La botiga marcarà la resta amb `[no t'arriba]`.

**Has de mantenir les claus originals** (1, 2, 3, 4). Pots fer-ho amb un
`foreach ($botiga as $clau => $article)` o amb `array_filter()` i una funció
fletxa:

```php
array_filter($botiga, fn($article) => ...);
```

### Reptes lliures (a `dades.php`)

- **El teu monstre.** Afegeix un monstre nou a `MONSTRES`, amb totes les
  claus. Busca una icona (emoji) per a la versió web.
- **Una classe nova.** Afegeix una classe 4 a `CLASSES` (paladí, arquera...).
  Recorda afegir-la també al `switch` del repte 14.
- **Un article nou** a la `BOTIGA`. Ha de millorar una clau que ja tingui l'heroi.
- **Equilibra el joc.** Canvia la vida i l'atac del drac. És massa fàcil? Massa
  difícil?

---

## Si alguna cosa falla

| Missatge o símptoma | Què vol dir | Què has de fer |
|---|---|---|
| `JOC ATURAT ... Repte N pendent` | Encara hi ha la línia `pendent(N);` | Fes el repte i esborra la línia |
| `Error de sintaxi a funcions.php, línia 42` | Falta un `;`, una `}` o unes cometes | Mira la línia indicada i l'anterior. Prova `php -l funcions.php` |
| `Return value must be of type string, none returned` | T'has deixat el `return` | Afegeix `return ...;` al final |
| `Return value must be of type array, null returned` | Retornes una cosa que no existeix | Normalment és una clau que no existeix (mira el repte 5) |
| `Undefined array key "Vida"` | Una clau mal escrita | Les claus distingeixen majúscules: `vida` ≠ `Vida` |
| El monstre no perd mai vida | Falta el `&` a `rebreDany` | `array &$personatge` |
| La funció "fa coses" però el comprovador diu que no | Fas `echo` en lloc de `return` | Les funcions han de **retornar** el resultat |
| Falla només un cas límit (40 d'or justos, 0 de vida...) | `<` en lloc de `<=` (o al revés) | Prova a mà el cas límit exacte |

---

## El meu progrés

- [ ] Repte 1 — `netejarNom()`
- [ ] Repte 2 — `validarOpcio()`
- [ ] Repte 3 — `crearHeroi()`
- [ ] Repte 4 — `barraVida()`
- [ ] Repte 5 — `triarMonstre()`
- [ ] Repte 6 — `calcularDany()`
- [ ] Repte 7 — `rebreDany()`
- [ ] Repte 8 — `estaViu()` → 🎉 **primera partida sencera**
- [ ] Repte 9 — `beurePocio()`
- [ ] Repte 10 — `cobrarRecompensa()`
- [ ] Repte 11 — `pujarNivell()`
- [ ] Repte 12 — `comprar()`
- [ ] Repte 13 — `resumPartida()` → 🏆 **joc complet**
- [ ] Extra 14 — `atacEspecial()`
- [ ] Extra 15 — `articlesAssequibles()`
- [ ] He derrotat el drac
