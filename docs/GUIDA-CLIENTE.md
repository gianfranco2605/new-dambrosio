# Guida per lo studio — come aggiornare il sito

Questa guida e' per chi lavora in studio, non per chi programma. Non serve
sapere niente di tecnico: tutto quello che c'e' qui si fa dal pannello di
WordPress, con il mouse.

Aggiornata il 2026-10-07.

---

## Che cosa e' cambiato, in una riga

Prima un trattamento era un pezzo di una pagina lunghissima. **Ora ogni
trattamento e' una scheda a se'**, con il suo indirizzo, il suo titolo e la
sua fotografia — e quando se ne aggiunge uno compare da solo in tutti i posti
in cui deve comparire.

Nel menu di sinistra del pannello, sotto «Pagine», trovate quattro voci nuove:

| Voce | Che cosa contiene | Quante ce ne sono oggi |
|---|---|---|
| **Trattamenti** | le prestazioni dello studio | 30 |
| **Tecnologie** | gli strumenti e le tecniche | 8 |
| **Professionisti** | i medici | 3 |
| **Casi Clinici** | i prima/dopo | 8 |

E una voce **Aree**: Implantologia, Ortodonzia, Estetica Dentale, Denti e
postura, Trattamenti. L'area e' il grande capitolo a cui una cosa appartiene,
ed e' il collante di tutto il sito.

---

## 1. Aggiungere un trattamento

1. Menu di sinistra → **Trattamenti** → **Aggiungi nuovo**.
2. **Titolo**: il nome della prestazione, come volete che si legga.
   Esempio: `Sbiancamento Professionale`.
3. **Testo**: scrivete nel corpo della pagina come fareste in un documento.
4. A destra, riquadro **Immagine**: scegliete la fotografia. E' quella che
   compare negli elenchi, quindi e' importante.
5. A destra, riquadro **Aree**: spuntate **una** area. Questa scelta decide
   anche l'indirizzo della pagina — con «Ortodonzia» diventera'
   `sorrisodambrosio.it/ortodonzia/nome-del-trattamento/`.
6. In fondo alla pagina, riquadro **Tecnologie collegate**: spuntate le
   tecnologie che quel trattamento impiega. Se non ne usa nessuna, lasciate
   tutto vuoto: il sito semplicemente non mostrera' quella sezione.
7. **Pubblica**.

**Dove compare, da solo, senza fare altro:**

- nella pagina della sua area (`/ortodonzia/`, per esempio)
- nell'indice di tutti i trattamenti (`/prestazioni/`)
- in fondo alle schede degli altri trattamenti della stessa area
- nella scheda di ogni tecnologia che avete collegato, sotto «Dove la usiamo»
- nella scheda di ogni medico a cui lo assegnate (vedi il punto 2)
- nei dati che Google legge per capire che cosa fa lo studio

**L'unica cosa che resta da fare a mano e' il menu**, e lo e' apposta: un menu
che cresce da solo diventa illeggibile dopo dieci voci. Vedi il punto 6.

### Cambiare l'ordine

Negli elenchi i trattamenti escono nell'ordine che decidete voi. Aprite il
trattamento, cercate in fondo **Attributi della pagina → Ordine** e mettete un
numero: 1 esce per primo, 2 per secondo, e cosi' via.

---

## 2. Aggiungere o modificare un medico

1. Menu di sinistra → **Professionisti** → **Aggiungi nuovo**.
2. **Titolo**: nome e cognome con il titolo. Esempio: `Dott.ssa Maria Rossi`.
3. **Testo**: la biografia.
4. A destra, **Immagine**: il ritratto. Viene ritagliato in verticale, quindi
   scegliete una fotografia in cui il viso sta in alto.
5. A destra, **Aree**: l'area principale di cui si occupa.
6. In fondo, riquadro **Si occupa di**: spuntate i trattamenti. Sono
   raggruppati per area, cosi' si trovano in fretta.
7. **Pubblica**.

La scheda compare su `/professionisti/`, e il medico appare in fondo alla
pagina di ogni trattamento che avete spuntato, sotto «Chi se ne occupa».

---

## 3. Aggiungere un caso prima/dopo

Si fa in **due passaggi**, perche' la galleria scorrevole la gestisce un
programma a parte, che c'era gia' e non e' cambiato.

**Primo: la galleria.**

1. Menu di sinistra → **Before and After Slider** → **Add New**
   (e' in inglese: e' il programma della galleria).
2. Caricate la fotografia **prima** e quella **dopo**.
3. Nel campo del titolo della galleria scrivete come si chiama il caso —
   per esempio `Paziente "M. R." 38 anni`.
4. Pubblicate e **tenete a mente il nome**.

**Secondo: la scheda del caso.**

1. Menu di sinistra → **Casi Clinici** → **Aggiungi nuovo**.
2. **Titolo**: lo stesso nome del caso.
3. A destra, riquadro **Galleria e medico**:
   - **Galleria prima/dopo**: scegliete dal menu a tendina quella che avete
     appena creato;
   - **Medico**: chi ha seguito il caso.
4. A destra, **Aree**: l'area del caso.
5. **Pubblica**.

Il caso compare su `/casi-studi/`, nella pagina della sua area e nella scheda
del medico che l'ha seguito.

> **Attenzione alla privacy.** Le fotografie cliniche di un paziente si
> pubblicano solo con il suo consenso scritto. I casi gia' sul sito usano le
> iniziali e l'eta': conviene continuare cosi'.

---

## 4. Aggiungere una tecnologia

Menu di sinistra → **Tecnologie** → **Aggiungi nuovo**: titolo, testo,
immagine, **Pubblica**. Compare su `/tecnologie/`.

Per collegarla a un trattamento si fa **dal trattamento**, non da qui: aprite
il trattamento e spuntatela nel riquadro «Tecnologie collegate». E' una scelta
voluta — l'informazione sta in un posto solo, cosi' non puo' succedere che un
trattamento dica una cosa e la tecnologia ne dica un'altra.

---

## 5. Cambiare i recapiti o gli orari

**Questa e' l'unica cosa che richiede uno sviluppatore.**

Telefoni, email, indirizzo e orari sono scritti in un file solo del sito
(`plugins/sorriso-dambrosio-core/includes/class-studio.php`). Da li' li
leggono **tutti** i punti in cui compaiono: l'intestazione, la fotografia
grande della home, la fascia degli orari, la pagina dei contatti, il fondo
pagina e i dati che legge Google.

Il vantaggio: si cambiano una volta e cambiano ovunque, senza il rischio che
un vecchio numero resti nascosto in un angolo. Lo svantaggio: serve chi ci
metta mano.

Se vi capita spesso di cambiarli, chiedete di aggiungere una schermata di
impostazioni: e' un lavoro breve e non tocca nient'altro.

---

## 6. Il menu

Menu di sinistra → **Aspetto** → **Editor** → **Modelli** → l'icona della
matita, poi cliccate sul menu in alto.

Il menu ha **sette voci principali**. E' un numero misurato, non scelto a
caso: con otto la prima voce finiva sopra il logo su uno schermo da portatile.
Prima di aggiungerne una, conviene chiedersi se non possa stare dentro una
tendina.

Quando aggiungete un trattamento, se volete anche nel menu: aprite la tendina
della sua area e aggiungete la voce cercando il nome del trattamento.

---

## 7. Le pagine vecchie

Le sette pagine lunghe di prima (**Implantologia**, **Ortodonzia**, **Estetica
Dentale**, **Trattamenti**, **Denti e postura**, **Tecnologie**, **Casi
Studi**) **non sono state cancellate**: sono in **bozza**. Le trovate in
«Pagine», e nessuno le vede sul sito.

Servono da rete di sicurezza: se qualcosa non convincesse, si rimettono in
linea e il sito torna com'era. **Non cancellatele.**

---

## 8. Cose da sapere

- **Non serve toccare gli indirizzi.** Tutti i vecchi link continuano a
  funzionare: chi arriva da Google o da un vecchio messaggio atterra dove si
  aspetta.
- **Le fotografie si scelgono dalla Libreria Media**, come sempre. Quando ne
  caricate una nuova, compilate il campo **Testo alternativo**: e' la
  descrizione che leggono i non vedenti ed e' quella che Google usa per capire
  che cosa c'e' nell'immagine.
- **Il testo del sito e' quello che e'.** Se volete cambiarlo potete: siete i
  proprietari. Tenete pero' presente che in Italia la pubblicita' sanitaria
  deve avere **tono informativo**: si puo' dire che cosa si fa e come, non
  promettere risultati. Alcune frasi gia' sul sito andrebbero riviste con
  questo criterio — l'elenco e' in `docs/DA-VERIFICARE.md` §5.
- **Se una pagina sembra vecchia dopo una modifica**, ricaricatela tenendo
  premuto Maiusc: il sito tiene una copia in memoria per andare piu' veloce.

---

## Chi chiamare, e per che cosa

| Serve | Si fa da soli | Serve lo sviluppatore |
|---|---|---|
| Aggiungere o modificare un trattamento, una tecnologia, un medico, un caso | ✔ | |
| Cambiare testi e fotografie | ✔ | |
| Riordinare gli elenchi | ✔ | |
| Aggiungere una voce di menu | ✔ | |
| Cambiare telefono, email, indirizzo, orari | | ✔ |
| Aggiungere un'area nuova | | ✔ (l'indirizzo va registrato) |
| Cambiare l'impaginazione di una pagina | | ✔ |
