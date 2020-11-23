<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fonts.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="logo col-1"><a href="/"><img src="img/logo.png"></a></div>
                <div class="top-right-block d-flex align-items-center justify-content-end">
                    <?if (Yii::app()->user->isGuest):?>
                    <div class="auth">
                        <ul>
                            <li><a href="/?r=site/login">Войти</a></li>
                            <li><a href="/?r=user/register">Регистрация</a></li>
                        </ul>
                    </div>
                    <?else:?>
                    <div id="user-header" class="user-top d-flex align-items-center justify-content-end">
                        <div class="user-name">
                            <?=CHtml::encode(Yii::app()->user->getState("username"))?>
                        </div>
                        <div class="avatar">
                            <div class="avatar_wrapper">
                                <?if (Yii::app()->user->getState("avatar")):?>
                                <img src="/upload/avatars/<?=CHtml::encode(Yii::app()->user->getState("avatar"))?>">
                                <?else:?>
                                <img src="img/avatar.png">
                                <?endif;?>
                            </div>
                        </div>
                        <div class="user-menu border">
                            <ul>
                                <li><a href="/?r=user/profile&id=<?=Yii::app()->user->id?>">Моя страница</a></li>
                                <li><a href="/?r=user">Список пользователей</a></li>
                                <li><a href="/?r=site/logout">Выйти</a></li>
                            </ul>
                        </div>
                    </div>
                    <?endif;?>
                </div>
            </div>
        </div>
    </header>

    <div class="container content main" id="page">
        <?php echo $content; ?>

        <div class="clear"></div>
    </div>
    <footer>
        <div class="container">
            <div class="copyright">elbook 2018</div>
        </div>
    </footer>
    <?Yii::app()->getClientScript()->registerCoreScript('jquery');?>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/common.js"></script>
</body>

</html>