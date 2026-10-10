# ARCHITECTURE — Studio Odontoiatrico D'Ambrosio

Architettura dell'informazione e modello dei contenuti. Scritta **prima** del codice,
come impone la sezione DESIGN FIRST di `CLAUDE.md`, ed estesa al contenuto.

Data: 2026-10-06 · Iterazione 4 · Vale per tutto il sito, non solo per la homepage.

---

## 1. Che cos'e' il sito oggi

Quattordici pagine e tre articoli. Ma il numero che conta e' un altro:

> **Il menu ha 45 voci di secondo livello. Tutte e 45 puntano a un'ancora dentro
> una di sette pagine monolitiche.**

| Pagina | URL | Parole | Ancore dentro |
|---|---|---:|---:|
| Implantologia | `/implantologia/` | 1.089 | 5 + una FAQ |
| Ortodonzia | `/ortodonzia/` | 489 | 4 + i casi |
| Estetica Dentale | `/estetica-dentale/` | 752 | 6 + i casi |
| Trattamenti | `/trattamenti/` | 1.762 | **12** |
| Denti e postura | `/denti-e-postura/` | 371 | 3 |
| Tecnologie | `/tecnologie/` | 1.215 | 8 |
| Casi Studi | `/casi-studi/` | 9 | 8 gallerie before/after |
| Il Nostro Studio | `/lo-studio/` | 189 | 1 |
| Home Page (vecchia) | `/studio-dentistico/` | 244 | — |
| Home (nuova) | `/` | pattern | — |
| 404 | `/404-2/` | 0 | — |
| 70 anni attivita | `/70-anni-attivita/` | **0** | pagina vuota, pubblicata |

Piu' tre articoli — `/magnetic-mallet/`, `/tooth-transformer/`, `/impianti-dentali/` —
che non compaiono in nessun menu.

### Che cosa non va

1. **Un trattamento non ha un indirizzo.** «Carico Immediato» vive dentro
   `/implantologia/#carico-inmediato`. Non e' una pagina: non si puo' condividere,
   Google non la indicizza come risorsa a se', non ha un titolo, non ha una
   descrizione, non ha un'immagine sociale.
2. **La pagina `/trattamenti/` e' lunga 13.352 pixel** a 1440px. Dodici trattamenti
   diversissimi fra loro — protesi, endodonzia, laser, chirurgia — in un rotolo solo.
3. **Le relazioni non esistono.** La Dott.ssa Stefania fa ortodonzia; i quattro casi
   ortodontici sono suoi. Il sito lo dice tre volte, a mano, in tre punti diversi,
   e se domani cambia qualcosa bisogna ricordarsi di tutti e tre.
4. **Aggiungere un trattamento oggi significa modificare una pagina da 46 KB di
   blocchi**, trovare il punto giusto, duplicare un gruppo, cambiare l'ancora, poi
   aprire il menu e aggiungere la voce. Per un cliente non tecnico e' proibitivo.
5. **Sette ancore del menu puntano a URL doppi** (`/implantologia/implantologia/#…`)
   e una a una pagina che non esiste (`/estetica/`). Oggi rispondono 301 per la
   correzione automatica di WordPress, non perche' qualcuno le abbia sistemate.

### La conclusione

Il sito non ha bisogno di pagine piu' belle. Ha bisogno che **i suoi contenuti siano
contenuti**: un trattamento e' una cosa, una tecnologia e' una cosa, un medico e' una
cosa, un caso clinico e' una cosa. Le pagine vengono dopo, e si costruiscono da sole.

---

## 2. Il modello dei contenuti

Quattro tipi e **una sola tassonomia**. La parsimonia non e' estetica: ogni tipo e ogni
campo in piu' e' una schermata in piu' che il cliente deve capire.

```
                          ┌──────────────────────┐
                          │  sd_area  (tassonomia)│
                          │  Implantologia        │
                          │  Ortodonzia           │
                          │  Estetica Dentale     │
                          │  Denti e postura      │
                          │  Trattamenti          │
                          └───┬────────┬────────┬─┘
            ┌─────────────────┘        │        └───────────────┐
            │                          │                        │
  ┌─────────▼─────────┐   ┌────────────▼───────────┐  ┌─────────▼────────┐
  │  sd_trattamento   │   │   sd_professionista    │  │     sd_caso      │
  │       30 voci     │   │         3 voci         │  │      8 voci      │
  └─────────┬─────────┘   └────────────┬───────────┘  └─────────┬────────┘
            │ _sd_tecnologie           │ _sd_trattamenti        │ _sd_professionista
            │                          └────────────────────────┤ _sd_bafg
  ┌─────────▼─────────┐                                         │
  │   sd_tecnologia   │                                         │
  │       8 voci      │                            galleria BEAF esistente
  └───────────────────┘
```

**La tassonomia `sd_area` e' condivisa da tutti e tre i tipi che hanno un'area.**
E' la scelta che fa la differenza per il cliente: assegnare «Ortodonzia» a un
trattamento, a un medico e a un caso e' sempre lo stesso gesto, nella stessa casella.
E una pagina d'area puo' mostrare in un colpo solo i suoi trattamenti, i suoi medici e
i suoi casi senza che nessuno abbia dovuto collegarli a mano.

### 2.1 I tipi

| Tipo | Etichetta nell'admin | Voci | Icona | Archivio |
|---|---|---:|---|---|
| `sd_trattamento` | Trattamenti | 30 | `dashicons-heart` | `/prestazioni/` |
| `sd_tecnologia` | Tecnologie | 8 | `dashicons-visibility` | `/tecnologie/` |
| `sd_professionista` | Professionisti | 3 | `dashicons-groups` | `/professionisti/` |
| `sd_caso` | Casi Clinici | 8 | `dashicons-images-alt2` | `/casi-studi/` |

Tutti con `show_in_rest`, immagine in evidenza, editor a blocchi, `menu_order`
(cosi' il cliente riordina trascinando, senza toccare date o titoli).

### 2.2 I campi

Nessun ACF Pro installato (controllato: i plugin attivi sono undici, non c'e').
Quindi **post meta nativo** con `register_post_meta( …, show_in_rest => true )`:

- non aggiunge una dipendenza commerciale a un sito che dovra' sopravvivere da solo;
- i campi finiscono nella REST API, quindi nell'editor, nei blocchi e nei futuri
  strumenti, gratis;
- i valori scalari si rendono nei template con la **Block Bindings API**, senza PHP
  nel tema.

| Tipo | Campo | Che cos'e' | Come lo vede il cliente |
|---|---|---|---|
| `sd_trattamento` | `_sd_tecnologie` | `int[]` — tecnologie usate | riquadro «Tecnologie collegate», caselle |
| `sd_trattamento` | `_sd_ancora_legacy` | `string` — la vecchia ancora | sola lettura, serve ai vecchi link |
| `sd_tecnologia` | `_sd_ancora_legacy` | `string` | sola lettura |
| `sd_professionista` | `_sd_trattamenti` | `int[]` — di che cosa si occupa | riquadro «Si occupa di», caselle |
| `sd_caso` | `_sd_professionista` | `int` — chi ha seguito il caso | riquadro «Medico», menu a tendina |
| `sd_caso` | `_sd_bafg` | `int` — id della galleria BEAF | riquadro «Galleria prima/dopo», tendina |

I riquadri sono `add_meta_box` con etichette in italiano: compaiono in fondo
all'editor a blocchi, non chiedono di imparare niente.

**Perche' le relazioni stanno su un lato solo.** `_sd_tecnologie` sta sul trattamento,
non anche sulla tecnologia: il verso opposto si ricava con una query
(`meta_query` su `_sd_tecnologie`). Due copie dello stesso fatto divergono sempre.

### 2.3 Le relazioni, e da dove vengono

**Tutte derivano dal testo o dalla struttura gia' pubblicati.** Nessuna e' inventata.
Quelle marcate *inferita* sono deduzioni mie e **vanno confermate dal titolare**:
sono elencate una per una in `DA-VERIFICARE.md` §11.

| Relazione | Da dove | Forza |
|---|---|---|
| trattamento → area | dalla pagina in cui la sezione viveva | strutturale |
| tecnologia (nessuna area) | — | — |
| Stefania → area Ortodonzia | la pagina `/ortodonzia/` conteneva il suo titolo e i suoi casi | strutturale |
| Giuseppe → area Estetica Dentale | la pagina `/estetica-dentale/` conteneva il suo titolo e i suoi casi | strutturale |
| 4 casi → Stefania, area Ortodonzia | erano sotto il suo titolo in `/casi-studi/` e in `/ortodonzia/` | strutturale |
| 4 casi → Giuseppe, area Estetica | idem in `/casi-studi/` e in `/estetica-dentale/` | strutturale |
| Chirurgia Computer-guidata → Software per la Chirurgia Guidata | «grazie all'utilizzo di un software innovativo… progettazione computerizzata» | testuale |
| Senza Incisione Lembo e Suture → Software per la Chirurgia Guidata | «nota come chirurgia computer-guidata» | testuale |
| Senza Incisione Lembo e Suture → TC Cone-Beam | «i risultati della Cone-Beam (radiografia 3D)» | testuale |
| Laser Terapia → Chirurgia Piezo Elettrica | la scheda Piezo descrive il laser a diodi in uso «sin dal 1998» | *inferita* |
| Sbiancamento Professionale → Chirurgia Piezo Elettrica | «sbiancamento dei denti con il laser», stessa scheda | *inferita* |
| Implantologia Post Estrattiva → RX Digitale Intraorale | «un attento studio radiografico» | *inferita* |
| Chirurgia dei Cannini Inclusi → Ortopantomografo | «esami radiografici»; la scheda Ortopantomografo cita i «denti inclusi» | *inferita* |
| Nicoletta → Parodontologia | «si distingue nell'ambito della Parodontologia» | testuale |
| Nicoletta → area Estetica Dentale | «e dell'estetica del sorriso» | testuale |
| Stefania → Ortodonzia Fissa / Linguale / Invisibile | «tecniche tradizionali, linguali e invisibili» | testuale |
| Giuseppe → area Implantologia | «eccelle nei campi dell'Implantologia» | testuale |
| Giuseppe → Chirurgia Rigenerativa | «Chirurgia Orale e Osseo-Ricostruttiva» | *inferita* |
| Giuseppe → Parodontologia, Protesi Fissa Estetica | «Parodontologia e Protesi Estetica» | testuale |

«Ortodonzia Mobile» **non** e' attribuita a Stefania: la sua biografia nomina tre
tecniche e quella non c'e'. Dedurla sarebbe inventare.

---

## 3. Gli URL

Il vincolo e' «nessun vecchio URL deve diventare un 404», e la preferenza e'
**tenere gli indirizzi esistenti**. Qui si tengono tutti e otto, senza nemmeno
un redirect: le vecchie pagine passano in bozza e l'URL viene **raccolto** da un
archivio o da una pagina di tassonomia che mostra lo stesso contenuto, arricchito.

| URL | Prima | Dopo | Esito |
|---|---|---|---|
| `/implantologia/` | pagina 32 | termine `sd_area` | 200, invariato |
| `/ortodonzia/` | pagina 34 | termine `sd_area` | 200, invariato |
| `/estetica-dentale/` | pagina 36 | termine `sd_area` | 200, invariato |
| `/trattamenti/` | pagina 38 | termine `sd_area` | 200, invariato |
| `/denti-e-postura/` | pagina 40 | termine `sd_area` | 200, invariato |
| `/tecnologie/` | pagina 46 | archivio `sd_tecnologia` | 200, invariato |
| `/casi-studi/` | pagina 1619 | archivio `sd_caso` | 200, invariato |
| `/lo-studio/` | pagina 48 | **resta una pagina**, reimpaginata | 200, invariato |
| `/studio-dentistico/` | vecchia home 57 | invariata, pubblicata | 200, invariato |
| `/magnetic-mallet/` e gli altri 2 articoli | articoli | invariati | 200 |
| `/estetica/` | voce di menu rotta | **301** → `/estetica-dentale/` | esplicito, non piu' indovinato |
| `/implantologia/implantologia/` | voce di menu rotta | **301** → `/implantologia/` | idem |
| `/ortodonzia/ortodonzia/` | idem | **301** → `/ortodonzia/` | idem |
| `/trattamenti/trattamenti/` | idem | **301** → `/trattamenti/` | idem |

**Nuovi indirizzi** (non sostituiscono niente, quindi non perdono niente):

```
/implantologia/carico-immediato/          un trattamento sotto la sua area
/tecnologie/tc-cone-beam/                 una tecnologia
/professionisti/                          la squadra
/professionisti/stefania-dambrosio/       un medico
/casi-studi/paziente-f-n-41-anni/         un caso
/prestazioni/                             tutti i trattamenti, tutte le aree
/contatti/                                la pagina contatti (non esisteva)
```

### 3.1 Come si tengono gli URL d'area senza jolly

Una tassonomia con `rewrite => ['slug' => '']` ingoierebbe **ogni** indirizzo di
primo livello del sito. Invece il plugin scrive **una regola per ciascun termine
esistente**, generata dall'elenco reale dei termini:

```php
add_rewrite_rule( '^implantologia/?$', 'index.php?sd_area=implantologia', 'top' );
```

Niente jolly, niente ambiguita', e se domani il cliente aggiunge un'area la regola
nasce da sola (le regole vengono rigenerate quando un termine `sd_area` cambia).

I **singoli trattamenti** stanno sotto la loro area (`/implantologia/carico-immediato/`):
`rewrite => ['slug' => '%sd_area%']` piu' un filtro `post_type_link`. E' l'unica
gerarchia che un paziente si aspetta, ed e' la stessa che il menu gia' suggeriva.

L'archivio dei trattamenti **non** puo' stare su `/trattamenti/` — li' c'e' l'area
omonima — e sta su `/prestazioni/`, parola gia' in uso sul sito (e' l'occhiello della
sezione «Nostri servizi» in homepage).

### 3.2 Le vecchie ancore non si perdono

Le 45 ancore del menu (`#carico-inmediato`, `#flapes`, `#piezo`…) restano come
attributo `id` sulle righe dell'elenco nella pagina d'area. Un vecchio link esterno
atterra ancora sul punto giusto. Gli **slug** pero' sono scritti bene: `flapes`
diventa `senza-lembo-e-suture`, `carico-inmediato` diventa `carico-immediato`.
Un refuso dentro un'ancora e' invisibile; dentro un URL resta per sempre.

### 3.3 La mappa dei redirect

Nel plugin, una mappa `array<string,string>` letta su `template_redirect`. Quattro
voci oggi. Niente plugin di redirect: quattro righe non giustificano una dipendenza.

---

## 4. I template

Tutti nel **tema figlio** (`themes/sorriso-dambrosio/templates/`), tutti costruiti
sul sistema di `DESIGN-SPEC.md`: contenitore 1320px, intestazione di sezione a
filetto, fondi alternati, nessuna griglia di card uniformi.

| File | Serve | Impianto |
|---|---|---|
| `front-page.html` | la home | i nove pattern, invariata |
| `single-sd_trattamento.html` | un trattamento | hero stretto + testo 62ch + tecnologie + medici + casi + CTA |
| `taxonomy-sd_area.html` | `/implantologia/` e le altre quattro | intro d'area + elenco a filetti dei trattamenti + medici + casi |
| `archive-sd_trattamento.html` | `/prestazioni/` | le cinque aree, ciascuna col suo elenco |
| `single-sd_tecnologia.html` | una tecnologia | split 38/62, immagine fuori dal bordo |
| `archive-sd_tecnologia.html` | `/tecnologie/` | colonna fissa + elenco a filetti (come in home) |
| `single-sd_professionista.html` | un medico | ritratto 30/70, biografia, cosa fa, i suoi casi |
| `archive-sd_professionista.html` | `/professionisti/` | tre righe redazionali (come in home) |
| `single-sd_caso.html` | un caso | la galleria BEAF a tutta larghezza + medico + area |
| `archive-sd_caso.html` | `/casi-studi/` | casi raggruppati per medico |
| `page.html` | pagina generica (privacy, cookie, 70 anni) | titolo + testo a 62ch + CTA |
| `page-lo-studio.html` | `/lo-studio/` | impianto redazionale proprio |
| `page-contatti.html` | `/contatti/` | modulo CF7 + orari + mappa |
| `single.html` | i tre articoli | come una tecnologia |
| `404.html` | — | messaggio + aree |

**Ogni pagina interna finisce con lo stesso blocco di prenotazione** (`sd/cta-prenota`)
e **comincia con le briciole di pane** (`sd/briciole`). Una volta, non quindici.

### 4.1 I blocchi dinamici del plugin

La presentazione sta nel tema, ma **la logica che sceglie i contenuti sta nel plugin**:
cosi' sopravvive a un cambio di tema. Sono blocchi con resa server, registrati da
`block.json`, usati dentro i template `.html`:

| Blocco | Che cosa mette in pagina |
|---|---|
| `sd/briciole` | le briciole di pane, piu' il JSON-LD `BreadcrumbList` |
| `sd/elenco-trattamenti` | i trattamenti dell'area corrente (o di un'area scelta) |
| `sd/tecnologie-collegate` | le tecnologie di un trattamento, o i trattamenti di una tecnologia |
| `sd/professionisti` | i medici, filtrabili per area o per trattamento |
| `sd/casi` | i casi, filtrabili per area o per medico; rende lo shortcode BEAF |
| `sd/orari` | **la tabella degli orari — unica fonte** |
| `sd/contatti` | i recapiti — unica fonte |
| `sd/cta-prenota` | la fascia «Prenota un appuntamento» |
| `sd/aree` | i pannelli fotografici delle aree |

### 4.2 Gli orari, una volta sola

Oggi gli orari stanno in `sorriso_dambrosio_hours()` nel `functions.php` **del tema**.
Si spostano nel plugin (`SD\Core\Studio::hours()`) e li' restano: sono contenuto, non
presentazione. L'helper del tema continua a esistere e delega al plugin, con una copia
di scorta se il plugin e' spento — un tema che va in bianco perche' manca un plugin e'
un tema fragile.

Li leggono: la fascia `#orari` della homepage, il footer, la pagina `/contatti/` e il
JSON-LD `openingHoursSpecification`. **Quattro usi, una sola riga da cambiare.**

---

## 5. Il menu

Il blocco Navigation e' il post `wp_navigation` **ID 4** (non un menu classico:
`wp menu` non serve a nulla qui). Si riscrive il suo contenuto.

```
Lo Studio ▾            /lo-studio/
   I Professionisti    /professionisti/
   Casi Studi          /casi-studi/
Implantologia ▾        /implantologia/        5 trattamenti, URL veri
Ortodonzia ▾           /ortodonzia/           4
Estetica Dentale ▾     /estetica-dentale/     6
Denti e postura ▾      /denti-e-postura/      3
Trattamenti ▾          /trattamenti/          12
Tecnologie ▾           /tecnologie/           8
Contatti               /contatti/
```

Otto voci di primo livello, come adesso: sparisce «Home» (il logo ci porta gia') e
arriva «Contatti», che mancava. **Le 45 voci di secondo livello smettono di essere
ancore e diventano indirizzi veri.** La soglia misurata dell'header (1340px) non
cambia, perche' il numero di voci di primo livello non cambia.

---

## 6. Com'e' lavorarci, per il cliente

Questo e' il punto per cui esiste tutto il resto.

**Aggiungere un trattamento** — oggi: aprire una pagina da 46 KB di blocchi,
duplicare un gruppo, cambiare l'ancora, aggiungere la voce di menu. Domani:

1. `Trattamenti → Aggiungi nuovo`
2. titolo, testo, immagine in evidenza
3. a destra, casella **Area**: spuntare «Ortodonzia»
4. in fondo, **Tecnologie collegate**: spuntare quelle che servono
5. Pubblica

Compare da solo su `/ortodonzia/`, su `/prestazioni/`, nella scheda delle tecnologie
collegate, nella pagina dei medici che se ne occupano e nei dati strutturati.
**Il menu si aggiorna a mano** — e' l'unica cosa che resta manuale, ed e' voluto:
un menu che cresce da solo diventa illeggibile.

**Aggiungere un medico**: `Professionisti → Aggiungi nuovo`, ritratto in evidenza,
area, e in fondo «Si occupa di».

**Aggiungere un caso**: prima la galleria nel plugin before/after (come sempre), poi
`Casi Clinici → Aggiungi nuovo`, si sceglie la galleria dalla tendina, il medico e
l'area.

**Cambiare gli orari**: oggi sono in tre punti. Domani sono in un file solo del
plugin — e questo e' il limite onesto di questa scelta: **serve ancora uno
sviluppatore**. Metterli in una pagina di opzioni sarebbe stato piu' gentile; non
si e' fatto perche' cambiano una volta ogni cinque anni e una schermata di opzioni
in piu' e' una schermata in piu' da mantenere. Se il titolare li vuole modificabili,
si aggiunge dopo, senza toccare nient'altro. Vedi `DA-VERIFICARE.md` §12.

---

## 7. Dove sta che cosa, e perche'

```
plugins/sorriso-dambrosio-core/     IL CONTENUTO — sopravvive a un cambio di tema
  ├── tipi di contenuto, tassonomia, campi
  ├── regole di riscrittura e mappa dei redirect
  ├── gli orari e i recapiti (unica fonte)
  ├── i blocchi dinamici (resa server)
  └── i dati strutturati JSON-LD

themes/sorriso-dambrosio/           LA PRESENTAZIONE — si puo' rifare da capo
  ├── theme.json, style.css
  ├── templates/, parts/
  └── patterns/
```

La riga di confine e': **se cancellando il tema si perde un'informazione, quella
informazione sta nel posto sbagliato.** Oggi gli orari e le biografie dei medici sono
nel tema; dopo questa iterazione non lo sono piu'.

---

## 8. I dati strutturati

Nel plugin, da dati esistenti e basta:

- **`Dentist`** (sottotipo di `LocalBusiness`) su ogni pagina: nome, indirizzo,
  telefono, email, orari dalla fonte unica, URL, i profili social gia' pubblicati.
- **`BreadcrumbList`** sulle pagine interne, generato dalle stesse briciole che si
  vedono.
- **`MedicalProcedure`** su un trattamento, **`Physician`** su un medico: nome,
  descrizione, URL, immagine. Nient'altro.

**Nessun voto, nessun prezzo, nessuna recensione, nessun campo inventato.**
Dichiarare `aggregateRating` senza recensioni vere e' una penalizzazione garantita,
oltre che falso.
