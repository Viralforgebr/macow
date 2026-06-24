<?php
/**
 * Macow — landing page (ficheiro único).
 * Imagens: pasta imagens/ (header.* / hero.* / banner.* = banner topo; macow.* = artista; resto = músicas).
 */

$imagensDir = __DIR__ . '/imagens';
$artist = null;
$songs = [];
$hero = null;
$exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$videoExts = ['mp4', 'webm'];

if (is_dir($imagensDir)) {
    foreach (scandir($imagensDir) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $path = $imagensDir . '/' . $file;
        if (!is_file($path)) {
            continue;
        }
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, $exts, true) && !in_array($ext, $videoExts, true)) {
            continue;
        }
        $base = pathinfo($file, PATHINFO_FILENAME);
        $slug = mb_strtolower($base);
        $rel = 'imagens/' . rawurlencode($file);

        if ($slug === 'header' || $slug === 'hero' || $slug === 'banner') {
            $hero = $rel;
            continue;
        }
        if ($slug === 'amazon-music') {
            continue;
        }
        if ($slug === 'macow') {
            if (in_array($ext, $videoExts, true)) {
                if (!$artist || $ext === 'mp4') {
                    $artist = ['name' => 'Macow', 'src' => $rel, 'media' => 'video'];
                }
            } elseif (in_array($ext, $exts, true) && (!$artist || ($artist['media'] ?? '') !== 'video')) {
                $artist = ['name' => 'Macow', 'src' => $rel, 'media' => 'image'];
            }
            continue;
        }
        if (!in_array($ext, $exts, true)) {
            continue;
        }
        $title = str_replace(['_', '-'], ' ', $base);
        $title = mb_convert_case($title, MB_CASE_TITLE, 'UTF-8');
        $songs[] = ['name' => $title, 'image' => $rel, 'key' => mb_strtolower(preg_replace('/\s+/', ' ', trim($title)))];
    }

    $orderTitles = [
        'Mais Um Clique',
        'Lef It For Tomorrow',
        'Noisy Guitar',
        'The Only Road I Know',
        'Girl On My Phone',
        'Not Yet',
        'Gardenia',
        'No Tomorrow',
        'Hold My Shaking Hand',
        'Reaching For The Light',
    ];

    $indexed = [];
    foreach ($songs as $song) {
        $indexed[$song['key']] = $song;
    }

    $songs = [];
    foreach ($orderTitles as $title) {
        $key = mb_strtolower(preg_replace('/\s+/', ' ', trim($title)));
        if (!isset($indexed[$key])) {
            continue;
        }
        $song = $indexed[$key];
        $song['name'] = $title;
        unset($song['key']);
        $songs[] = $song;
        unset($indexed[$key]);
    }
    foreach ($indexed as $song) {
        unset($song['key']);
        $songs[] = $song;
    }
}

$headSongs = array_slice($songs, 0, 6);
$tailSongs = array_slice($songs, 6);
$artistVideo = $artist && ($artist['media'] ?? '') === 'video';
$artistVideoSrc = $artistVideo ? $artist['src'] : null;
if ($artistVideoSrc) {
    $videoPath = __DIR__ . '/' . $artistVideoSrc;
    if (is_file($videoPath)) {
        $artistVideoSrc .= '?v=' . filemtime($videoPath);
    }
}

function macow_icon(string $kind, ?string $href = null): string
{
    if ($kind === 'amazon') {
        $inner = '<span class="macow-icon macow-icon-img macow-icon-amazon" aria-hidden="true"><img src="imagens/amazon-music.png" alt="" /></span>';
    } else {
        $icons = [
            'spotify' => '<svg viewBox="0 0 24 24" fill="#1DB954"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.49 17.32a.75.75 0 01-1.032.258 7.213 7.213 0 00-8.916 0 .75.75 0 11-.774-1.29 8.713 8.713 0 0110.464 0 .75.75 0 01.258 1.032zm1.603-3.495a.937.937 0 01-1.289.308 9.784 9.784 0 00-11.608 0 .937.937 0 11-1.032-1.546 11.656 11.656 0 0113.672 0 .937.937 0 01.257 1.238zm1.622-3.528a1.125 1.125 0 01-1.546.37 12.356 12.356 0 00-14.132 0 1.125 1.125 0 11-1.237-1.874 14.606 14.606 0 0116.606 0 1.125 1.125 0 01.309 1.504z"/></svg>',
            'apple' => '<svg viewBox="0 0 24 24" fill="#FA243C"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>',
            'youtube' => '<svg viewBox="0 0 24 24" fill="#FF0000"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
            'youtube-music' => '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#FF0000"/><circle cx="12" cy="12" r="6.25" fill="none" stroke="#ffffff" stroke-width="1.4"/><path fill="#ffffff" d="M10.2 8.1v7.8l6.6-3.9z"/></svg>',
            'tiktok' => '<svg viewBox="0 0 24 24" fill="#ffffff"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>',
            'instagram' => '<svg viewBox="0 0 24 24" fill="#E4405F"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>',
            'facebook' => '<svg viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'whatsapp' => '<svg viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>',
        ];
        if (!isset($icons[$kind])) {
            return '';
        }
        $iconClass = 'macow-icon' . ($kind === 'whatsapp' ? ' macow-icon-whatsapp' : '');
        $inner = '<span class="' . $iconClass . '" aria-hidden="true">' . $icons[$kind] . '</span>';
    }

    if ($href === null || $href === '') {
        return $inner;
    }

    $labels = [
        'spotify' => 'Spotify',
        'youtube' => 'YouTube',
        'youtube-music' => 'YouTube Music',
        'apple' => 'Apple Music',
        'tiktok' => 'TikTok',
        'instagram' => 'Instagram',
        'amazon' => 'Amazon Music',
        'facebook' => 'Facebook',
        'whatsapp' => 'WhatsApp',
    ];
    $label = $labels[$kind] ?? ucfirst($kind);
    $linkClass = 'macow-icon-link' . ($kind === 'whatsapp' ? ' macow-icon-link-whatsapp' : '');

    return '<a class="' . $linkClass . '" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" aria-label="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '">' . $inner . '</a>';
}

function macow_platform_icons(array $links = [], ?array $order = null): string
{
    $order = $order ?? ['spotify', 'youtube', 'youtube-music', 'apple', 'tiktok', 'instagram', 'amazon', 'facebook', 'whatsapp'];
    $html = '';
    foreach ($order as $kind) {
        $html .= macow_icon($kind, $links[$kind] ?? null);
    }
    return $html;
}

$artistLinks = [
    'spotify' => 'https://open.spotify.com/intl-pt/artist/65HMJLIyqBq54cypaerMQ7?si=CDvppnYxRz-eQcRUnjxJdQ',
    'youtube' => 'https://www.youtube.com/@macow_official',
    'youtube-music' => 'https://music.youtube.com/@macow_official',
    'apple' => 'https://music.apple.com/us/artist/macow/6772775909',
    'tiktok' => 'https://www.tiktok.com/@macow_official',
    'facebook' => 'https://www.facebook.com/MacowOfficial',
    'instagram' => 'https://www.instagram.com/macow_official/',
    'amazon' => 'https://music.amazon.com/artists/B08R79YXKN/macow',
    'whatsapp' => 'https://wa.me/5561995717544?text=' . rawurlencode('Gostaria de saber mais sobre seu catálogo de músicas'),
];

$songLinks = [
    'Mais Um Clique' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/10IqYI1GypGa8nh8YNDh0h?si=9e5022c8a27c4c5e',
        'youtube' => 'https://www.youtube.com/playlist?list=OLAK5uy_ldN1SIDsaCtpMyZt_MTuL07yIrGYrehTA',
        'youtube-music' => 'https://music.youtube.com/playlist?list=OLAK5uy_ldN1SIDsaCtpMyZt_MTuL07yIrGYrehTA',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CCLFVM',
        'apple' => 'https://music.apple.com/us/album/mais-um-clique-single/6783764942',
    ],
    'Lef It For Tomorrow' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/4VFW0ApxxpLgyMrVfSiQj0?si=952bdc69bc384f42',
        'youtube' => 'https://www.youtube.com/playlist?list=OLAK5uy_n7JsElGawlu4a02kX-2ha_A2tsJGpv5-8',
        'youtube-music' => 'https://music.youtube.com/playlist?list=OLAK5uy_n7JsElGawlu4a02kX-2ha_A2tsJGpv5-8',
        'amazon' => 'https://music.amazon.com.br/albums/B0H4NQJ36C',
        'apple' => 'https://music.apple.com/us/album/left-it-for-tomorrow-single/6778604600',
    ],
    'Noisy Guitar' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/0lrmNAy65F48dp8ldqe7dl?si=4c1311b2b2734662',
        'youtube' => 'https://youtu.be/QfLvhIUHt8o?si=j3ZTnWtzx4M4Yh4D',
        'youtube-music' => 'https://music.youtube.com/watch?v=QfLvhIUHt8o&si=IXO0MOINnl6JID4P',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CBJ4P2?marketplaceId=ART4WZ8MWBX2Y&musicTerritory=BR&ref=dm_sh_C9pBebN4uJcrYdipTG9uWvoV3&trackAsin=B0H6CBXFS9',
        'apple' => 'https://music.apple.com/us/song/noyse-guitar/6783615178',
    ],
    'Not Yet' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/1GH8bHUhr0Vu1wFiVxSU0c?si=2cec2423dae749da',
        'youtube' => 'https://youtu.be/8-Bt1xuB6fE?si=IcKR3McG74xGa6D-',
        'youtube-music' => 'https://music.youtube.com/watch?v=8-Bt1xuB6fE&si=adPT8ye3PIhCGzyV',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CBJ4P2?marketplaceId=ART4WZ8MWBX2Y&musicTerritory=BR&ref=dm_sh_zKbj52iZTnRoAsBw5gZusehkL&trackAsin=B0H6CBZB14',
        'apple' => 'https://music.apple.com/us/song/not-yet/6783615180',
    ],
    'Girl On My Phone' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/4ZcGUcs7Kc0UCYxJP8Bd06?si=c42a71b7e738452e',
        'youtube' => 'https://youtu.be/pcgbK4NRQ4A?si=pNl0GAd2tIIH7lKV',
        'youtube-music' => 'https://music.youtube.com/watch?v=pcgbK4NRQ4A&si=QVhvHj6KObdgH2Jv',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CBJ4P2?marketplaceId=ART4WZ8MWBX2Y&musicTerritory=BR&ref=dm_sh_iqCKDMUy8FwrqVb5OWoSR9Dcs&trackAsin=B0H6CCRQS4',
        'apple' => 'https://music.apple.com/us/song/girl-on-my-phone/6783615181',
    ],
    'No Tomorrow' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/1eXtUMOpvCXBaWxkCvJI6U?si=e41308cbd2b44e68',
        'youtube' => 'https://youtu.be/HQS1LYw8AkA?si=zHzmgIkJoF-Xbyki',
        'youtube-music' => 'https://music.youtube.com/watch?v=HQS1LYw8AkA&si=ZkWOo6lrgXJvCF0r',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CBJ4P2?marketplaceId=ART4WZ8MWBX2Y&musicTerritory=BR&ref=dm_sh_jXrcRYXbyKc5Jo2sZxGVOxqdL&trackAsin=B0H6CC26QQ',
        'apple' => 'https://music.apple.com/us/song/no-tomorrow/6783615182',
    ],
    'Reaching For The Light' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/6GDZBSvaFeNPAfZygPp3SO?si=744a7e45fec84635',
        'youtube' => 'https://youtu.be/-amHUUObmD8?si=mLL1RJBaI2fx2M8n',
        'youtube-music' => 'https://music.youtube.com/watch?v=-amHUUObmD8&si=J_w8OOoi3BleuQHM',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CBJ4P2?marketplaceId=ART4WZ8MWBX2Y&musicTerritory=BR&ref=dm_sh_trgku1YgpZ2xORTgAaiEXFTxJ&trackAsin=B0H6C3121S',
        'apple' => 'https://music.apple.com/us/song/reaching-for-the-light/6783615183',
    ],
    'Gardenia' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/5fKmFImcZdqcfHLKO7Q2tI?si=fa60a3e580ce492f',
        'youtube' => 'https://www.youtube.com/playlist?list=OLAK5uy_mSzNN416tTqf9udwlwE6Hyuh2Mljy8GvA',
        'youtube-music' => 'https://music.youtube.com/playlist?list=OLAK5uy_mSzNN416tTqf9udwlwE6Hyuh2Mljy8GvA',
        'amazon' => 'https://music.amazon.com.br/albums/B0H2SYWW6V',
        'apple' => 'https://music.apple.com/us/album/gardenia-single/6772831323',
    ],
    'Hold My Shaking Hand' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/5X0yd23YRzb71UqLaYe987?si=803dbff5b4194192',
        'youtube' => 'https://youtu.be/OfH_92r-6PA?si=sYopk7YsVfL0f3Lu',
        'youtube-music' => 'https://music.youtube.com/watch?v=OfH_92r-6PA&si=qFTwMtm5w2v36zR8',
        'amazon' => 'https://music.amazon.com.br/albums/B0H6CBJ4P2?marketplaceId=ART4WZ8MWBX2Y&musicTerritory=BR&ref=dm_sh_B0BPMXAg4Jm1ngqxhJmsp4OdW&trackAsin=B0H6CC8ZKY',
        'apple' => 'https://music.apple.com/us/song/hold-my-shaking-hand/6783615179',
    ],
    'The Only Road I Know' => [
        'spotify' => 'https://open.spotify.com/intl-pt/track/5raaYWtX8CQl6fR3zA67DX?si=f5bf4aaac16a4203',
        'youtube' => 'https://www.youtube.com/playlist?list=OLAK5uy_neLKX3pQn10trraMfWb0TBOSH5fzsyu_o',
        'youtube-music' => 'https://music.youtube.com/playlist?list=OLAK5uy_neLKX3pQn10trraMfWb0TBOSH5fzsyu_o',
        'amazon' => 'https://music.amazon.com.br/albums/B0H3Z42PZK',
        'apple' => 'https://music.apple.com/us/album/the-only-road-i-know-single/6776461869',
    ],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Macow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/macow.css" />
</head>
<body class="macow-page">

    <nav class="macow-nav" aria-label="Principal">
        <p class="macow-logo">Macow</p>
        <div class="macow-icons macow-nav-icons"><?= macow_platform_icons($artistLinks) ?></div>
    </nav>

    <header class="macow-banner">
        <?php if ($hero): ?>
            <img src="<?= htmlspecialchars($hero, ENT_QUOTES, 'UTF-8') ?>" alt="Macow" />
        <?php else: ?>
            <div class="macow-banner-fallback">
                <p>Macow</p>
            </div>
        <?php endif; ?>
    </header>

    <?php if (!empty($songs) || $artist): ?>
    <section class="macow-band" aria-label="Destaque">
        <p class="macow-band-catalog">Catálogo de músicas disponíveis para artistas e editoras, mediante contato.</p>
        <p>Ouça em todas as plataformas — <span class="macow-band-accent">links diretos em cada faixa</span>.</p>
    </section>
    <?php endif; ?>

    <main class="macow-main">
        <?php if (empty($songs) && !$artist): ?>
            <p class="macow-empty">
                Coloque as capas em <code>imagens/</code> — uma por música (nome do ficheiro = título) e <code>macow.mp4</code> para o artista.
            </p>
        <?php else: ?>
        <div class="macow-section-head">
            <p class="macow-section-label">Discografia</p>
            <h2 class="macow-section-title">Todas as faixas. <span>Um clique.</span></h2>
        </div>
        <div class="macow-grid">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <?php if (isset($headSongs[$i])): ?>
                <div style="grid-column:<?= $i + 1 ?>;grid-row:1" class="macow-song-card">
                    <h3 class="macow-song-title"><?= htmlspecialchars($headSongs[$i]['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <div class="macow-icons"><?= macow_platform_icons($songLinks[$headSongs[$i]['name']] ?? []) ?></div>
                    <div class="macow-cover"><img src="<?= htmlspecialchars($headSongs[$i]['image'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy" /></div>
                </div>
                <?php endif; ?>
            <?php endfor; ?>

            <div class="macow-artist-card" style="grid-column:4 / span 2; grid-row:1 / span 2">
                <h3 class="macow-artist-title">Acesse minhas redes sociais</h3>
                <div class="macow-icons"><?= macow_platform_icons($artistLinks) ?></div>
                <?php if ($artist): ?>
                    <?php if ($artist['media'] === 'video'): ?>
                    <div id="macow-artist-canvas" class="macow-cover macow-artist-canvas" role="button" tabindex="0" aria-label="Macow — clique para activar o áudio">
                        <video id="macow-canvas-video" src="<?= htmlspecialchars($artistVideoSrc, ENT_QUOTES, 'UTF-8') ?>" autoplay muted loop playsinline></video>
                        <audio id="macow-canvas-audio" preload="none"></audio>
                    </div>
                    <?php else: ?>
                    <div class="macow-cover">
                        <img src="<?= htmlspecialchars($artist['src'], ENT_QUOTES, 'UTF-8') ?>" alt="Macow" />
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="macow-cover macow-cover-placeholder">imagens/macow.mp4</div>
                <?php endif; ?>
            </div>

            <?php for ($i = 3; $i < 6; $i++): ?>
                <?php if (isset($headSongs[$i])): ?>
                <div style="grid-column:<?= $i - 2 ?>;grid-row:2" class="macow-song-card">
                    <h3 class="macow-song-title"><?= htmlspecialchars($headSongs[$i]['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <div class="macow-icons"><?= macow_platform_icons($songLinks[$headSongs[$i]['name']] ?? []) ?></div>
                    <div class="macow-cover"><img src="<?= htmlspecialchars($headSongs[$i]['image'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy" /></div>
                </div>
                <?php endif; ?>
            <?php endfor; ?>

            <?php
            $row = 3;
            $col = 1;
            foreach ($tailSongs as $song):
            ?>
            <div style="grid-column:<?= $col ?>;grid-row:<?= $row ?>" class="macow-song-card">
                <h3 class="macow-song-title"><?= htmlspecialchars($song['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                <div class="macow-icons"><?= macow_platform_icons($songLinks[$song['name']] ?? []) ?></div>
                <div class="macow-cover"><img src="<?= htmlspecialchars($song['image'], ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy" /></div>
            </div>
            <?php
                $col++;
                if ($col > 5) { $col = 1; $row++; }
            endforeach;
            ?>
        </div>
        <?php endif; ?>
    </main>

    <footer class="macow-footer">
        <p>© Macow · <a href="<?= htmlspecialchars($artistLinks['instagram'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">@macow_official</a></p>
    </footer>
    <?php if ($artistVideo): ?>
    <script>
    (function () {
        var wrap = document.getElementById('macow-artist-canvas');
        var video = document.getElementById('macow-canvas-video');
        var audio = document.getElementById('macow-canvas-audio');
        if (!wrap || !video || !audio) return;

        var mediaSrc = video.getAttribute('src') || '';
        var audioOn = false;

        function toggleAudio() {
            audioOn = !audioOn;
            if (!audioOn) {
                audio.pause();
                return;
            }
            if (!audio.getAttribute('src') && mediaSrc) {
                audio.setAttribute('src', mediaSrc);
            }
            audio.currentTime = video.currentTime;
            audio.play().catch(function () {});
        }

        wrap.addEventListener('click', toggleAudio);
        wrap.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleAudio();
            }
        });
    })();
    </script>
    <?php endif; ?>
</body>
</html>
