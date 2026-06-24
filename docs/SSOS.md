# Macow — SSOS

**Documentação vivente do projecto.** Actualizar neste ficheiro quando o comportamento, o deploy ou o âmbito mudar materialmente.

**Versão:** 0.3.0  
**Última actualização:** 2026-06-03  
**Pasta oficial:** `C:\Users\orlan\macow`  
**Remoto (previsto):** `https://github.com/Viralforgebr/macow.git` (branch `master`)

---

## Visão

**Landing page** do artista **Macow** e da sua discografia. Servida por **PHP** + **Tailwind CSS** (CDN). Upload **manual** para o domínio — sem pipeline de frontend nem API.

**Referência visual (só inspiração):** landing do ViralForge (`C:\Users\orlan\viralforge\frontend\app\page.tsx`) — fundo escuro, roxo/rosa, Inter. **Não copiar** stack React/Next.

---

## Stack (fechada)

| Usar | Não usar |
|------|----------|
| **PHP** — ficheiro único `index.php` | Next.js, React, Vue, SPA com build |
| **Tailwind CSS** (CDN) + `css/macow.css` | FastAPI, Node backend, Supabase |
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
[========== banner topo (header.*) — 300px altura, object-position top ==========]

Linha 1:  [M][M][M] | [MACOW 2×2 + ícones redes]
Linha 2:  [M][M][M] | [      continua 2×2      ]
Linha 3+: [M][M][M][M][M]  (só músicas, 5 colunas)
```

- Grid **5 colunas**, unidades relativas (`vw`, `clamp`, `aspect-ratio`).
- Cada música: **título** → **ícones** → **capa 1×1**.
- Bloco artista: título «Acesse minhas redes sociais» → ícones → canvas **2×2**.

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

## Canvas do artista (`macow.mp4`)

- Vídeo: `<video autoplay muted loop playsinline>` — **sempre mudo**, loop contínuo.
- **Áudio:** `<audio preload="none">` — só carrega e toca **ao clicar** no canvas.
- **Correcção loop:** MP4 exportado com ~60 s de metadados mas vídeo congelava aos ~26 s (DaVinci). Ficheiro **cortado a ~25,875 s**; removido `macow.webm` (PHP preferia o último ficheiro e quebrava loop).
- **Não** manipular `currentTime` em JS durante playback.

---

## Ícones e links

Ordem (artista e músicas): **Spotify · YouTube · YouTube Music · Apple Music · TikTok · Instagram · Amazon Music · Facebook**

| Área | Plataformas com link |
|------|----------------------|
| **Artista** | Todas (8 ícones) |
| **Músicas** | **Spotify, YouTube, YouTube Music, Apple Music, Amazon Music** (10 faixas cada) |
| **Músicas (sem link)** | TikTok, Instagram, Facebook — ícones visíveis, URL por activar |

YouTube e YouTube Music são **ícones e URLs separados**. Não há ícone iTunes (só Apple Music).

Links definidos em `$artistLinks` e `$songLinks` no topo de `index.php`. Funções: `macow_icon()`, `macow_platform_icons()`.

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

### Músicas — plataformas ligadas (10 faixas)

| Plataforma | Notas |
|------------|-------|
| **Spotify** | Link por faixa |
| **YouTube** | Singles: playlist; faixas EP: `youtu.be` |
| **YouTube Music** | Par com YouTube (playlist ou `watch`) |
| **Amazon Music** | Singles: URL de álbum; faixas EP: álbum `B0H6CBJ4P2` + `trackAsin` |
| **Apple Music** | URL de álbum/single ou song por faixa |

Chaves em `$songLinks` usam títulos de exibição (ex.: `'Lef It For Tomorrow'`, `'Mais Um Clique'`).

---

## Design

| Papel | Hex |
|-------|-----|
| Fundo | `#0F0F0F` |
| Primária | `#7C3AED` |
| Secundária | `#A78BFA` |
| Accent | `#EC4899` |

- Tipografia: **Inter** (Google Fonts).
- Idioma da página: `pt-BR`.

---

## Regras de trabalho (agente / Cursor)

1. **Só Macow** — não alterar Aigree (`C:\Users\orlan\aigree`) nem ViralForge salvo referência visual.
2. **Só o pedido** — sem backend, auth, CMS ou ficheiros extra sem avisar.
3. **PHP + Tailwind** — sem React/Next no Macow.
4. **Upload-friendly** — funciona após FTP/cPanel sem `npm install`.
5. **Não re-encodar `macow.mp4`** sem pedido explícito.
6. **SSOS** — actualizar este ficheiro quando mudar comportamento, links ou estrutura material.

---

## Estado actual

| Área | Estado |
|------|--------|
| Landing desktop | **Pronta** — grid, banner, vídeo em loop, áudio no clique |
| Links artista | **Completos** (8 plataformas) |
| Links músicas | **Spotify, YouTube, YT Music, Apple, Amazon** — 10 faixas; TikTok/Instagram/Facebook por activar |
| Mobile | **Por fazer** (adaptar quando pedido) |
| Git | Repositório local; remoto `Viralforgebr/macow` |

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
| 0.2.0 | 2026-06-03 | Landing funcional: grid, vídeo, ordem das músicas, links Spotify (10 faixas) + redes do artista (8 plataformas); correcção loop MP4 |
| 0.3.0 | 2026-06-03 | YouTube Music separado; iTunes removido; links músicas completos (Spotify, YouTube, YT Music, Apple, Amazon); SSOS alinhado ao código |
