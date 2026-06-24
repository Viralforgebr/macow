# Macow — SSOS

**Documentação vivente do projecto.** Actualizar neste ficheiro quando o comportamento, o deploy ou o âmbito mudar materialmente.

**Versão:** 0.4.0  
**Última actualização:** 2026-06-03  
**Pasta oficial:** `C:\Users\orlan\macow`  
**Remoto:** `https://github.com/Viralforgebr/macow.git` (branch `master`)

---

## Visão

**Landing page** do artista **Macow** e da sua discografia. Servida por **PHP** + **CSS** (`css/macow.css`). Upload **manual** para o domínio — sem pipeline de frontend nem API.

**Referência visual (só inspiração):** landing do ViralForge (`C:\Users\orlan\viralforge\frontend\app\page.tsx`) — fundo escuro, roxo/rosa, Inter, cards com borda, nav com blur. **Não copiar** stack React/Next.

---

## Stack (fechada)

| Usar | Não usar |
|------|----------|
| **PHP** — ficheiro único `index.php` | Next.js, React, Vue, SPA com build |
| **CSS** — `css/macow.css` + Inter (Google Fonts) | FastAPI, Node backend, Supabase |
| Assets locais (`css/`, `imagens/`) | Servidor frontend (`npm run dev`) |
| Upload manual para hosting | Deploy automático (salvo ordem futura) |

**Runtime:** Apache + PHP no domínio de produção.

---

## Desenvolvimento local

- **Pasta de trabalho:** `C:\Users\orlan\macow`
- **XAMPP:** cópia em `C:\xampp\htdocs\macow` (usar `publicar-xampp.bat` após editar).
- **URL local:** `http://localhost/macow/`
- **Alternativa:** `php -S localhost:8080` na pasta (sem Apache).

---

## Deploy

1. Desenvolver em `C:\Users\orlan\macow`.
2. Testar em `http://localhost/macow/`.
3. **Upload manual** de `index.php`, `css/` e `imagens/` para a raiz pública do site.
4. Após upload, **Ctrl+Shift+R** no browser se assets antigos ficarem em cache.

---

## Estrutura

```
macow/
├── docs/
│   └── SSOS.md
├── index.php              ← landing única (PHP + HTML + JS mínimo)
├── css/
│   └── macow.css
└── imagens/
    ├── header.png         ← banner topo
    ├── macow.mp4          ← canvas do artista (vídeo ~26 s)
    ├── amazon-music.png   ← ícone Amazon (excluído do scan de músicas)
    └── *.jpg|png|…        ← capas das músicas (nome do ficheiro ≈ título)
```

**Regra:** manter plano simples — uma página, sem `partials/`, sem scripts auxiliares, salvo ordem explícita.

---

## Layout (desktop-first)

```
[NAV fixa — logo Macow + ícones redes (WhatsApp destacado)]

[========== banner topo (header.*) — 300px altura, object-position top ==========]

[Faixa: catálogo para artistas/editoras + links diretos por faixa]

Linha 1:  [M][M][M] | [MACOW 2×2 + ícones redes]
Linha 2:  [M][M][M] | [      continua 2×2      ]
Linha 3+: [M][M][M][M][M]  (só músicas, 5 colunas)

[Footer — © Macow · Instagram]
```

- Grid **5 colunas**, unidades relativas (`vw`, `clamp`, `aspect-ratio`).
- Cada música: **card** → título → ícones → capa 1×1.
- Bloco artista: **card** → «Acesse minhas redes sociais» → ícones → canvas **2×2**.
- **1920px:** gaps e padding ajustados para evitar scroll horizontal (`box-sizing: border-box`, `overflow-x: clip`).

### Ordem fixa das 10 músicas (esquerda→direita, cima→baixo)

1. Mais Um Clique  
2. Lef It For Tomorrow  
3. Noisy Guitar  
4. The Only Road I Know  
5. Girl On My Phone  
6. Not Yet  
7. Gardenia  
8. No Tomorrow  
9. Hold My Shaking Hand  
10. Reaching For The Light  

---

## UI (polimento ViralForge, simples)

| Elemento | Comportamento |
|----------|----------------|
| **Nav** | Sticky, blur, borda inferior roxa; ícones das redes (maiores que nos cards) |
| **Banner** | Overlay gradiente na base |
| **Faixa** | Texto catálogo + «links diretos em cada faixa» |
| **Cards** | Borda, fundo semi-transparente, hover leve |
| **Capas** | Borda roxa, sombra, zoom subtil no hover |
| **Footer** | Linha separadora + link Instagram |

---

## Canvas do artista (`macow.mp4`)

- Vídeo: `<video autoplay muted loop playsinline>` — **sempre mudo**, loop contínuo.
- **Áudio:** `<audio preload="none">` — só carrega e toca **ao clicar** no canvas.
- **Correcção loop:** MP4 cortado a ~25,875 s; removido `macow.webm`.
- **Não** manipular `currentTime` em JS durante playback.

---

## Ícones e links

Ordem: **Spotify · YouTube · YouTube Music · Apple Music · TikTok · Instagram · Amazon Music · Facebook · WhatsApp**

| Área | Plataformas com link |
|------|----------------------|
| **Nav + artista** | Todas (9 ícones) |
| **Músicas** | Spotify, YouTube, YT Music, Apple, Amazon (10 faixas) |
| **Músicas (sem link)** | TikTok, Instagram, Facebook, WhatsApp — ícone visível |

**WhatsApp (artista/nav):** após Facebook, **2 cm** de separação, **2× tamanho** dos outros ícones. URL `wa.me/5561995717544` com mensagem pré-preenchida: *«Gostaria de saber mais sobre seu catálogo de músicas»*.

YouTube e YouTube Music são **ícones e URLs separados**. Não há ícone iTunes.

Links em `$artistLinks` e `$songLinks` no topo de `index.php`. Funções: `macow_icon()`, `macow_platform_icons()`.

### Artista — URLs actuais

| Plataforma | URL |
|------------|-----|
| Spotify | `https://open.spotify.com/intl-pt/artist/65HMJLIyqBq54cypaerMQ7` |
| YouTube | `https://www.youtube.com/@macow_official` |
| YouTube Music | `https://music.youtube.com/@macow_official` |
| Apple Music | `https://music.apple.com/us/artist/macow/6772775909` |
| TikTok | `https://www.tiktok.com/@macow_official` |
| Instagram | `https://www.instagram.com/macow_official/` |
| Amazon Music | `https://music.amazon.com/artists/B08R79YXKN/macow` |
| Facebook | `https://www.facebook.com/MacowOfficial` |
| WhatsApp | `https://wa.me/5561995717544?text=…` (mensagem catálogo) |

### Faixa de destaque (texto fixo)

1. *Catálogo de músicas disponíveis para artistas e editoras, mediante contato.*
2. *Ouça em todas as plataformas — links diretos em cada faixa.*

### Músicas — plataformas ligadas (10 faixas)

| Plataforma | Notas |
|------------|-------|
| **Spotify** | Link por faixa |
| **YouTube** | Singles: playlist; faixas EP: `youtu.be` |
| **YouTube Music** | Par com YouTube |
| **Amazon Music** | Singles: álbum; EP: `B0H6CBJ4P2` + `trackAsin` |
| **Apple Music** | URL de álbum/single ou song por faixa |

---

## Design

| Papel | Hex |
|-------|-----|
| Fundo | `#0F0F0F` |
| Primária | `#7C3AED` |
| Secundária | `#A78BFA` |
| Accent | `#EC4899` |
| WhatsApp | `#25D366` |

- Tipografia: **Inter** (Google Fonts, pesos 400–800).
- Idioma da página: `pt-BR`.

---

## Regras de trabalho (agente / Cursor)

1. **Só Macow** — não alterar Aigree nem ViralForge salvo referência visual.
2. **Só o pedido** — sem backend, auth, CMS ou ficheiros extra sem avisar.
3. **PHP + CSS** — sem React/Next no Macow.
4. **Upload-friendly** — funciona após FTP/cPanel sem `npm install`.
5. **Não re-encodar `macow.mp4`** sem pedido explícito.
6. **SSOS** — actualizar este ficheiro quando mudar comportamento, links ou estrutura material.

---

## Estado actual

| Área | Estado |
|------|--------|
| Landing desktop | **Pronta** — UI polida, grid, vídeo, áudio no clique |
| Links artista | **Completos** (9 plataformas incl. WhatsApp) |
| Links músicas | **Spotify, YouTube, YT Music, Apple, Amazon** — 10 faixas |
| Mobile | **Por fazer** |
| Git | **Sincronizado** com `origin/master` |

---

## Próximo trabalho natural (quando pedires)

1. Links das músicas em TikTok, Instagram e Facebook.
2. Regras CSS para ecrãs de telemóvel.
3. Upload para domínio de produção.

---

## Histórico do documento

| Versão | Data | Notas |
|--------|------|-------|
| 0.1.0 | 2026-06-23 | Projecto iniciado: PHP + Tailwind, deploy manual |
| 0.2.0 | 2026-06-03 | Landing funcional: grid, vídeo, links Spotify + redes artista; correcção loop MP4 |
| 0.3.0 | 2026-06-03 | YouTube Music separado; links músicas em 5 plataformas; git inicial |
| 0.4.0 | 2026-06-03 | UI estilo ViralForge (nav, cards, faixa, footer); WhatsApp destacado com mensagem; CSS puro; ajuste 1920px sem scroll horizontal |
