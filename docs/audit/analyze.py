#!/usr/bin/env python3
"""Trasforma le metriche grezze in un elenco di problemi, con severita'."""
import json, sys, collections

SOGLIE = dict(
    gap_desktop=160, gap_mobile=96,
    upscale=1.5,            # naturalWidth < renderedWidth * 1.5 → sfocata su retina
    small_content_img=240,  # immagine di contenuto resa sotto questa larghezza su desktop
    line_chars=75,
    tap=44, body=16,
)

def carica(p):
    return json.load(open(p))

def problemi(data):
    out = collections.defaultdict(list)   # url -> [(sev, cat, msg)]
    comp = dict(pads=collections.Counter(), btns=collections.Counter(), h1=collections.Counter())

    for url, perw in data.items():
        for w, m in perw.items():
            w = int(w)
            if 'error' in m:
                out[url].append(('high', 'errore', f"{w}px: {m['error'][:80]}"))
                continue
            desktop = w >= 1024

            # overflow
            if m['overflow']:
                out[url].append(('high', 'overflow', f"{w}px: {', '.join(m['overflow'][:3])}"))
            if m['docW'] > m['vw'] + 1:
                out[url].append(('high', 'overflow', f"{w}px: documento {m['docW']} > viewport {m['vw']}"))

            # h1
            if m['h1'] != 1:
                out[url].append(('high', 'struttura', f"{w}px: {m['h1']} h1 in <main>"))

            # vuoti
            lim = SOGLIE['gap_desktop'] if desktop else SOGLIE['gap_mobile']
            for g in m['gaps']:
                if g['size'] > lim:
                    sev = 'high' if g['size'] > lim * 1.6 else 'medium'
                    out[url].append((sev, 'vuoto', f"{w}px: {g['size']}px fra {g['after']} e {g['before']} (y={g['from']})"))

            for e in m['emptySections']:
                out[url].append(('high', 'vuoto', f"{w}px: sezione vuota «{e}»"))

            # immagini (solo a 1440: e' li' che si giudica la risoluzione)
            if w == 1440:
                for i in m['images']:
                    if i['nw'] and i['ratio'] < SOGLIE['upscale']:
                        sev = 'high' if i['ratio'] < 1.0 else 'medium'
                        out[url].append((sev, 'immagine', f"{i['src']} resa {i['w']}×{i['h']} da {i['nw']}×{i['nh']} (×{i['ratio']}) in .{i['parent']}"))
                    if i['w'] < SOGLIE['small_content_img'] and i['parent'] and 'icon' not in i['cls']:
                        out[url].append(('medium', 'immagine', f"{i['src']} resa solo {i['w']}px in .{i['parent']}"))
                # proporzioni diverse nella stessa griglia
                per_parent = collections.defaultdict(set)
                for i in m['images']:
                    if i['parent']:
                        # si confronta la cornice, che e' quel che si vede
                        per_parent[i['parent']].add(i.get('far') or i['ar'])
                for par, ars in per_parent.items():
                    if len(ars) > 1:
                        out[url].append(('medium', 'immagine', f"proporzioni diverse in .{par}: {sorted(ars)}"))

                for l in m['longLines']:
                    out[url].append(('medium', 'tipografia', f"riga da ~{l['chars']} caratteri ({l['px']}px) in .{l['cls']}"))

                # orfani nei titoli: ultima parola corta e sola su una riga probabile
                for h in m['headings']:
                    if h['lvl'] <= 2 and h['words'] >= 4 and len(h['last']) <= 4:
                        out[url].append(('low', 'tipografia', f"possibile orfano in h{h['lvl']} «{h['t']}» (ultima parola «{h['last']}»)"))

            # mobile
            if w == 390:
                for t in m['tapSmall']:
                    out[url].append(('medium', 'mobile', f"bersaglio {t['w']}×{t['h']}px «{t['t']}» .{t['cls']}"))
                for s in m['smallText']:
                    out[url].append(('medium', 'mobile', f"testo {s['px']}px «{s['t']}» .{s['cls']}"))

            if m['iconsMax'] > 56:
                out[url].append(('medium', 'icone', f"{w}px: icona resa a {m['iconsMax']}px (max 56)"))

            # consistenza dei componenti
            if desktop:
                for s in m['sectionPads']:
                    comp['pads'][(s['cls'] or '(senza classe)', s['pt'], s['pb'])] += 1
                for b in m['buttons']:
                    comp['btns'][(b['cls'], b['h'], b['r'], b['fs'])] += 1
    return out, comp

if __name__ == '__main__':
    data = carica(sys.argv[1])
    out, comp = problemi(data)
    sev_order = {'high': 0, 'medium': 1, 'low': 2}
    tot = collections.Counter()
    for url in sorted(out):
        for sev, cat, msg in out[url]:
            tot[sev] += 1
    print(json.dumps({'per_url': {k: v for k, v in out.items()},
                      'componenti': {k: [list(a) + [b] for a, b in v.most_common()] for k, v in comp.items()},
                      'totali': dict(tot)}, indent=1, ensure_ascii=False))
