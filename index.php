<? require 'initializeContent.php'; ?>
<html>
    <head>
        <link type="text/css" rel="stylesheet" href="<?=$cssLink?>?version=1"/>

        <title> Демо-сайт </title>
    </head>
    <body>
        <div class="header">
            <h3 style="color: darkslateblue"> <?=$greeting?> </h3>
            <p style="color: darkslateblue; text-align: left"> <?=$instruction ?></p>
        </div>
        <? include '/views/tabs/tabs.php'?>
        <div class ="divSet">
                <?
                if (isset($show)) {
                    require($show);
                }
                echo $menu;
                ?>
            </div>
    </body>
</html>