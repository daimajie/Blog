<?php
use yii\helpers\Html;
use app\assets\LayuiAsset;
use app\assets\home\AppAsset;

AppAsset::register($this);
LayuiAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?= Html::csrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


<?= $content?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>