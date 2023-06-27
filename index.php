<?php
$apple_link = file_get_contents("https://music.apple.com/jp/album/{$_GET['alb']}?i={$_GET['sid']}");
if($apple_link){
    $dom = new DOMDocument('1.0', 'UTF-8');
    @$dom->loadHTML($apple_link);
    $domxpath = new DOMXpath($dom);
    $song_json = json_decode($domxpath->query("//script")->item(0)->nodeValue, true);
    echo "
    <html>
        <head>
        <title>{$song_json['name']}-AppleMusic</title>
        <meta name=\"twitter:card\" content=\"summary\" />
        <meta name=\"twitter:site\" content=\"@kw_nobu\" />
        <meta name=\"twitter:creator\" content=\"@kw_nobu\" />
        <meta property=\"og:url\" content=\"{$song_json['url']}\" />
        <meta property=\"og:title\" content=\"「{$song_json['name']}」をAppleMusicで聴く\" />
        <meta property=\"og:description\" content=\"アーティスト:{$song_json['byArtist'][0]['name']} Link Created by kawa-nobu\" />
        <meta property=\"og:image\" content=\"{$song_json['image']}\" />
        <style>body{text-align: center;}</style>
        </head>
        <body>
        <h1>{$song_json['name']} - {$song_json['byArtist'][0]['name']}</h1>
        <p><a href=\"{$song_json['url']}\" target=\"_blank\" rel=\"noopener noreferrer\">AppleMusicを開く</a></p>
        <div><img src=\"{$song_json['image']}\"></div>
        <div><audio controls src=\"{$song_json['audio']['contentUrl']}\"></div>
        <div><p>Link Created by kawa-nobu <a href=\"https://github.com/kawa-nobu/AppleMusic-TwitShare\" target=\"_blank\" rel=\"noopener noreferrer\">GitHub</a></p></div>
        </body>
    </html>
    ";
}else{
    echo "Error!";
}

?>