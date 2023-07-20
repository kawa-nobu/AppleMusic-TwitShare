<?php
if(parse_url($_GET['url'])['scheme'] == "https"){
    $album_id = end(explode('/', parse_url($_GET['url'])['path']));
    $song_id = end(explode('i=', parse_url($_GET['url'])['query']));
    $apple_link = file_get_contents("https://music.apple.com/jp/album/{$album_id}?i={$song_id}");
    if($apple_link){
        $dom = new DOMDocument('1.0', 'UTF-8');
        @$dom->loadHTML($apple_link);
        $domxpath = new DOMXpath($dom);
        //$song_json = json_decode($domxpath->query("//script")->item(0)->nodeValue, true);
        $song_json = json_decode($domxpath->query('//*[@id="serialized-server-data"]')->item(0)->nodeValue, true);
        //echo $domxpath->query('//*[@id="serialized-server-data"]')->item(0)->nodeValue;
        //echo $song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['name'];
        $lbart_url = str_replace(array('{w}', '{h}', '{f}'), array('99999', '99999', 'jpg'), $song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['artwork']['url']);
        $release_date = date('Y年m月d日',strtotime($song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['releaseDate']));
        echo "
        <html>
            <head>
                <title>{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['name']}-AppleMusic</title>
                <meta name=\"twitter:card\" content=\"summary\" />
                <meta name=\"twitter:site\" content=\"@kw_nobu\" />
                <meta name=\"twitter:creator\" content=\"@kw_nobu\" />
                <meta property=\"og:url\" content=\"{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['url']}\" />
                <meta property=\"og:title\" content=\"「{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['name']}」をAppleMusicで聴く\" />
                <meta property=\"og:description\" content=\"今すぐ「{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['artistName']}」の「{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['name']}」を聴こう!\" />
                <meta property=\"og:image\" content=\"{$lbart_url}\" />
                <style>body{text-align: center;}</style>
            </head>
            <body>
                <h1>{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['name']} - {$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['artistName']}</h1>
                <p><a href=\"{$song_json[0]['data']['seoData']['url']}\" target=\"_blank\" rel=\"noopener noreferrer\">{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['albumName']}</a>({$release_date}リリース)</p>
                <p>作曲者:{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['composerName']}(<a href=\"https://ja.wikipedia.org/wiki/{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['composerName']}\" target=\"_blank\" rel=\"noopener noreferrer\">ウィキペディア</a>)</p>
                <p><a href=\"{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['url']}\" target=\"_blank\" rel=\"noopener noreferrer\">AppleMusicを開く</a></p>
                <div><img src=\"{$lbart_url}\" height=\"65%\"></div>
                <div><audio controls src=\"{$song_json[0]['data']['seoData']['ogSongs'][0]['attributes']['previews'][0]['url']}\"></div>
                <div><p>Link Created by kawa-nobu <a href=\"https://github.com/kawa-nobu/AppleMusic-TwitShare\" target=\"_blank\" rel=\"noopener noreferrer\">GitHub</a></p></div>
            </body>
        </html>
        ";
    }else{
        echo "Error!";
    }
}else{
    echo "URLError!";
}
?>