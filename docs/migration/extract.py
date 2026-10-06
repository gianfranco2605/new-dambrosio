#!/usr/bin/env python3
"""Estrae le sezioni ancorate dalle vecchie pagine monolitiche.

Uso:  python3 extract.py <file-contenuto.html> [--json]

Ogni sezione di primo livello che porta un id HTML diventa una voce:
anchor, titolo, id delle immagini, paragrafi (HTML letterale), pulsanti.
Il testo non viene mai riscritto: si copia carattere per carattere.
"""
import json
import re
import sys


def toplevel_blocks(content):
    """Restituisce (nome, attributi, html_interno, html_completo) dei blocchi di primo livello."""
    out = []
    depth = 0
    start = None
    name0 = None
    attr0 = None
    inner_start = None
    pat = re.compile(r'<!-- (/?)wp:([a-z0-9/-]+)(?:\s+(\{.*?\}))?\s*(/?)-->')
    for m in pat.finditer(content):
        close, name, attrs, selfc = m.group(1), m.group(2), m.group(3), m.group(4)
        if close:
            depth -= 1
            if depth == 0:
                out.append((name0, attr0, content[inner_start:m.start()], content[start:m.end()]))
            continue
        if depth == 0 and not selfc:
            start, name0, attr0, inner_start = m.start(), name, attrs, m.end()
        if not selfc:
            depth += 1
        elif depth == 0:
            out.append((name, attrs, '', content[m.start():m.end()]))
    return out


def text_of(html):
    t = re.sub(r'<!--.*?-->', '', html, flags=re.S)
    t = re.sub(r'<[^>]+>', ' ', t)
    t = re.sub(r'\s+', ' ', t)
    return t.strip()


def sections(content):
    res = []
    for name, attrs, inner, full in toplevel_blocks(content):
        anchor = re.search(r'<div[^>]*\bid="([a-z0-9][a-z0-9-]*)"', full)
        heads = re.findall(r'<h([1-6])[^>]*>(.*?)</h\1>', full, re.S)
        paras = re.findall(r'<p[^>]*>(.*?)</p>', full, re.S)
        imgs = re.findall(r'wp:image\s+\{"id":(\d+)', full)
        btns = re.findall(r'<a class="wp-block-button__link[^"]*"[^>]*href="([^"]*)"[^>]*>(.*?)</a>', full, re.S)
        res.append({
            'block': name,
            'anchor': anchor.group(1) if anchor else None,
            'heading': text_of(heads[0][1]) if heads else None,
            'headings': [text_of(h[1]) for h in heads],
            'images': imgs,
            'paragraphs': [p.strip() for p in paras if text_of(p)],
            'buttons': [{'href': b[0], 'label': text_of(b[1])} for b in btns],
            'words': len(text_of(full).split()),
            'raw': full,
        })
    return res


if __name__ == '__main__':
    data = sections(open(sys.argv[1]).read())
    if '--json' in sys.argv:
        print(json.dumps(data, indent=2, ensure_ascii=False))
    else:
        for i, s in enumerate(data):
            print(f"[{i}] {s['block']:12} #{s['anchor'] or '-':28} img={','.join(s['images']) or '-':12} "
                  f"p={len(s['paragraphs'])} w={s['words']:5}  {(s['heading'] or '')[:55]}")
