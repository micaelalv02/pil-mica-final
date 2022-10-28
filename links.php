<?php
require_once "Config/Autoload.php";
Config\Autoload::run();
$f = new Clases\PublicFunction();

$arrContextOptions = [
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
];

$contentRedes = json_decode(file_get_contents(__DIR__ . '/json/redes.json', false, stream_context_create($arrContextOptions)), true);

?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title><?= TITULO ?></title>
    <link rel="icon" href="<?= FAVICON ?>" type="image/x-icon" />
</head>

<body>
    <img id="userPhoto" src="<?= LOGO ?>" alt="<?= TITULO ?>">
    <div id="links">
        <?php
        if (!empty($contentRedes)) {
            foreach ($contentRedes as $key => $value) { ?>
                <a class="link" href="<?= $value ?>" target="_blank"><?= $key ?></a>
        <?php }
        } ?>
    </div>
</body>

</html>


<style>
    @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

    :root {
        --bgColor: #e1e1e1;
        --accentColor: #333;
        --font: 'Karla', sans-serif;
    }

    body {
        background-color: var(--bgColor);
    }

    #userPhoto {
        width: 200px;
        height: 150px;
        object-fit: contain;
        display: block;
        margin: 35px auto 20px;

    }

    #userName {
        color: #bbb;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.25;
        display: block;
        font-family: var(--font);
        width: 100%;
        text-align: center;
        text-decoration: none;
    }

    #links {
        max-width: 675px;
        width: auto;
        display: block;
        margin: 27px auto;
    }

    .link {
        display: block;
        background-color: var(--accentColor);
        color: var(--bgColor);
        font-family: var(--font);
        text-align: center;
        margin-bottom: 20px;
        padding: 17px;
        text-decoration: none;
        font-size: 1rem;
        transition: all .25s cubic-bezier(.08, .59, .29, .99);
        border: solid var(--accentColor) 2px;
    }

    .link:hover {
        background-color: var(--bgColor);
        color: var(--accentColor);
    }
</style>