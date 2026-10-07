#!/usr/bin/env python3
"""Bande orizzontali senza inchiostro, misurate sui pixel.

Il rilevatore basato sul DOM non trovava niente: gli elementi annidati
riempiono ogni intervallo. Quel che il titolare vede e' invece una fascia di
pagina in cui non c'e' nulla di disegnato — e quella si misura guardando i
pixel, non l'albero.

Una riga e' "vuota" se tutti i suoi pixel stanno entro una tolleranza dal
colore di fondo di quella riga (cosi' funziona anche sulle fasce scure).
"""
import sys, glob, os, json
from PIL import Image

TOLL = 10        # scostamento massimo dal fondo perche' la riga sia "vuota"
PASSO = 2        # campiona una riga ogni due: dimezza il lavoro, non cambia l'esito

def bande_vuote(path, min_h):
    im = Image.open(path).convert('RGB')
    W, H = im.size
    px = im.load()
    campioni = list(range(0, W, max(1, W // 60)))   # ~60 colonne per riga
    vuote = []
    y = 0
    while y < H:
        base = px[campioni[0], y]
        piatta = all(
            abs(px[x, y][0] - base[0]) <= TOLL and
            abs(px[x, y][1] - base[1]) <= TOLL and
            abs(px[x, y][2] - base[2]) <= TOLL
            for x in campioni
        )
        vuote.append(piatta)
        y += PASSO
    # raggruppa
    fasce, inizio = [], None
    for i, v in enumerate(vuote):
        if v and inizio is None:
            inizio = i
        elif not v and inizio is not None:
            h = (i - inizio) * PASSO
            if h >= min_h:
                fasce.append({'y': inizio * PASSO, 'h': h})
            inizio = None
    if inizio is not None:
        h = (len(vuote) - inizio) * PASSO
        if h >= min_h:
            fasce.append({'y': inizio * PASSO, 'h': h})
    return fasce, H

if __name__ == '__main__':
    d = sys.argv[1]
    out = {}
    for f in sorted(glob.glob(os.path.join(d, '*.png'))):
        nome = os.path.basename(f)[:-4]
        w = int(nome.rsplit('-', 1)[1])
        pagina = nome.rsplit('-', 1)[0]
        lim = 160 if w >= 1024 else 96
        fasce, H = bande_vuote(f, lim)
        if fasce:
            out.setdefault(pagina, {})[w] = {'alt': H, 'fasce': fasce}
    print(json.dumps(out, indent=1))
