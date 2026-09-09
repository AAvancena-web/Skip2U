#!/usr/bin/env python3
"""Extract the standalone design's CSS, convert rem to px, scope under .s2u."""
import io, re, sys

src_path, out_path = sys.argv[1], sys.argv[2]
src = io.open(src_path, encoding='utf-8').read()
css = re.search(r'<style>(.*?)</style>', src, re.S).group(1)
css = re.sub(r'(-?\d*\.?\d+)rem\b', lambda m: ('%g' % (float(m.group(1)) * 16)) + 'px', css)

def blocks(t):
    out, i, n = [], 0, len(t)
    while i < n:
        j = t.find('{', i)
        if j < 0:
            out.append(('raw', t[i:])); break
        sel, d, k = t[i:j], 1, j + 1
        while k < n and d:
            if t[k] == '{': d += 1
            elif t[k] == '}': d -= 1
            k += 1
        out.append(('rule', sel, t[j + 1:k - 1])); i = k
    return out

S = '.s2u'
def pref(sel):
    m = re.match(r'^((?:\s|/\*.*?\*/)*)', sel, re.S)
    lead = m.group(1) if m else ''
    sel, done = sel[len(lead):], []
    for p in [x.strip() for x in sel.split(',') if x.strip()]:
        if p in (':root', 'body'): done.append(S)
        elif p == 'html': done.append(None)
        elif p == 'body.nav-open': done.append('body.s2u-nav-open')
        elif p in ('*', '*::before', '*::after'): done.append(S + ' ' + p)
        elif p.startswith('body '): done.append(S + ' ' + p[5:])
        else: done.append(S + ' ' + p)
    done = [d for d in done if d]
    return lead + ', '.join(done) if done else None

def walk(t):
    out = []
    for b in blocks(t):
        if b[0] == 'raw':
            out.append(b[1]); continue
        sel, body = b[1], b[2]; st = sel.strip()
        if st.startswith('@media') or st.startswith('@supports'):
            out.append(sel + '{' + walk(body) + '}')
        elif st.startswith('@keyframes') or st.startswith('@font-face'):
            out.append(sel + '{' + body + '}')
        else:
            ns = pref(sel)
            if ns: out.append(ns + '{' + body + '}')
    return ''.join(out)

scoped = walk(css).replace(
    '.s2u h1,.s2u h2,.s2u h3,.s2u h4,.s2u h5{',
    '.s2u h1,.s2u h2,.s2u h3,.s2u h4,.s2u h5{\n  font-family:"Axiforma","Inter",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;')

hdr = """/*
 * Skip 2 U Tas redesign.
 *
 * Generated from the standalone design by build-css.py. Do not edit by hand.
 *
 * Every selector is scoped under .s2u so this cannot reach legacy page
 * content, and all lengths are px rather than rem because the child theme
 * sets a fluid html{font-size:calc(...)} (about 12px at 1440, 11.2px under
 * 1190) that would otherwise rescale every value here.
 *
 * Headings use Axiforma, the brand face the child theme already self-hosts.
 */
"""
io.open(out_path, 'w', encoding='utf-8').write(hdr + scoped)
print("css: %d rules, %d rem remaining" % (scoped.count('{'), len(re.findall(r'\d(?:\.\d+)?rem\b', scoped))))
