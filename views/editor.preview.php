
<!DOCTYPE html>
<html>
<head>
    <title><?= $note->title ?></title>
    <?= self::meta(); ?>
    <?= self::css('public/css/preview', 'public/css/theme-doc'); ?>
</head>
<body>

    <div class="doc">
        <?= $note->content ?>
    </div>

</body>
</html>