<?php

    $docRoot = getenv("DOCUMENT_ROOT");
    
    $dir = 'auth/webpart/blockshop/';
    $path_skin = $dir.'mc/skins/';
    $path_skin_abs = 'mc/skins/';
    $username = 'Defi';

    /*$way_skif = $path_skin_abs.$username.'.png';
   

    if (!file_exists($docRoot.'/'.$dir.$way_skif)) {
        $way_skif = $path_skin_abs.'char.png';
    }
    
    echo $way_skif;*/
    

    //Создание превью скина (обязательно создайте папки 1 и 2 в вашай папке со скинами, в папку со скинами положите char.png)
    if (!empty($username)) 
    {
        $user_name = $username;
    }

    $way_skif = $path_skin.$user_name.'.png';

    if (!file_exists($docRoot.'/'.$dir.$way_skif)) 
    {
        $way_skif = $path_skin_abs.'char.png';
    }

    $skif = getimagesize($way_skif);
    $h = $skif['0'];
    $w = $skif['1'];
    $ratio = $h / 64;

    $way_skin =  $path_skin_abs.$user_name.'.png';
    $way_cloak = $path_cloak.$user_name.'.png';

    if (!file_exists($docRoot.'/'.$dir.$way_skin)) 
    {
        $way_skin = $path_skin_abs.'char.png';
    }

    if (!file_exists($docRoot.'/'.$way_cloak)) 
    {
        $way_cloak = false;
    } 
    else 
    {
        $cloak = imagecreatefrompng($way_cloak);
    }

    $skin = imagecreatefrompng($way_skin);
    $preview = imagecreatetruecolor(16 * $ratio, 32 * $ratio);
    $transparent = imagecolorallocatealpha($preview, 255, 255, 255, 127);

    imagefill($preview, 0, 0, $transparent);
    imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
    imagecopy($preview, $skin, 0 * $ratio, 8 * $ratio, 44 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imageflip($preview, $skin, 12 * $ratio, 8 * $ratio, 44 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 4 * $ratio, 8 * $ratio, 20 * $ratio, 20 * $ratio, 8 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 4 * $ratio, 20 * $ratio, 4 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imageflip($preview, $skin, 8 * $ratio, 20 * $ratio, 4 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 40 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
    $fullsize = imagecreatetruecolor(80, 160);
    imagesavealpha($fullsize, true);
    $transparent = imagecolorallocatealpha($fullsize, 255, 255, 255, 127);
    imagefill($fullsize, 0, 0, $transparent);
    imagecopyresized($fullsize, $preview, 0, 0, 0, 0, imagesx($fullsize), imagesy($fullsize), imagesx($preview), imagesy($preview));
    imagepng($fullsize, $path_skin_abs . '1/' . $username . '.png');
    imagecopy($preview, $skin, 4 * $ratio, 8 * $ratio, 32 * $ratio, 20 * $ratio, 8 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 24 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
    imageflip($preview, $skin, 0 * $ratio, 8 * $ratio, 52 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 12 * $ratio, 8 * $ratio, 52 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imageflip($preview, $skin, 4 * $ratio, 20 * $ratio, 12 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 8 * $ratio, 20 * $ratio, 12 * $ratio, 20 * $ratio, 4 * $ratio, 12 * $ratio);
    imagecopy($preview, $skin, 4 * $ratio, 0 * $ratio, 56 * $ratio, 8 * $ratio, 8 * $ratio, 8 * $ratio);
    if ($way_cloak)
        imagecopy($preview, $cloak, 3 * $ratio, 8 * $ratio, 1 * $ratio, 1 * $ratio, 10 * $ratio, 16 * $ratio);
    $fullsize = imagecreatetruecolor(80, 160);
    imagesavealpha($fullsize, true);
    $transparent = imagecolorallocatealpha($fullsize, 255, 255, 255, 127);
    imagefill($fullsize, 0, 0, $transparent);
    imagecopyresized($fullsize, $preview, 0, 0, 0, 0, imagesx($fullsize), imagesy($fullsize), imagesx($preview), imagesy($preview));
    imagepng($fullsize, $path_skin_abs . '2/' . $username . '.png');
    imagedestroy($fullsize);
    imagedestroy($preview);
    imagedestroy($skin);
    if ($way_cloak)
        imagedestroy($cloak);
    uptime(30);
    exit();

    function imageflip(&$result, &$img, $rx = 0, $ry = 0, $x = 0, $y = 0, $size_x = null, $size_y = null) {
        if ($size_x < 1)
            $size_x = imagesx($img);
        if ($size_y < 1)
            $size_y = imagesy($img);
        imagecopyresampled($result, $img, $rx, $ry, ($x + $size_x - 1), $y, $size_x, $size_y, 0 - $size_x, $size_y);
    }
    function uptime($s1){
    global $_SESSION;
    $_SESSION['buytime']=time()+$s1;
    }