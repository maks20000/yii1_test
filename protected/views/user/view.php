<?php
/* @var $this UserController */
/* @var $model User */
?>
<div class="row justify-content-between user-info-block">
    <div class="col-lg-7 order-lg-1 order-2">
        <div class="user-info">
            <div class="user-title">
                <div class="user-title-name"><?=CHtml::encode($model->username)?></div>
                <?if (Yii::app()->user->id==$model->id):?>
                <div class="edit"></div>
                <ul class="border" id="user_edit">
                    <li><a id="edit_profile" href="#">Редактировать</a></li>
                </ul>
                <?endif;?>
            </div>
            <div class="user-about">
                <div class="about"><span>Немного о себе:</span></div>
                <div class="about-text">
                    <?=$model->about?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-sm-12 text-center text-lg-right order-lg-2 order-1">
        <div class="user-avatar">
            <?if ($model->avatar):?>
            <img src="/upload/avatars/<?=CHtml::encode($model->avatar["path"])?>">
            <?else:?>
            <img src="img/avatar-big.jpg">
            <?endif;?>
            <?if (Yii::app()->user->id==$model->id):?>
            <div class="load-photo">
                <?php echo CHtml::beginForm('','post',array('enctype'=>'multipart/form-data')); ?>
                <?php echo CHtml::activeLabel($avatar,'path'); ?>
                <?php echo CHtml::activeFileField($avatar,'path',array()); ?>
                <?php echo CHtml::endForm(); ?>
            </div>
            <?endif;?>
        </div>
    </div>
</div>
<?if($comments):?>
<div class="comments">
    <div class="bold title">Записи на вашей стене</div>
    <?foreach($comments as $com):?>
    <div class="comment-item">
        <div class="comment-user">
            <div class="avatar">
                <?if ($com->add_user->avatar["path"]):?>
                <img src="/upload/avatars/<?=CHtml::encode($com->add_user->avatar["path"])?>">
                <?else:?>
                <img src="img/deadpool.png">
                <?endif;?>
            </div>
            <div class="user-name">
                <?=CHtml::encode($com->add_user["username"])?>
                <span class="date">
                    <?=CHtml::encode($com["date"])?>
                </span>
            </div>
        </div>
        <div class="text"><?= CHtml::encode($com["message"])?>
        </div>
    </div>
    <?endforeach;?>
</div>
<?endif;?>
<?if (Yii::app()->user):?>
<div class="add-comment">
    <div class="bold title">Добавить запись</div>
    <?php echo CHtml::beginForm(); ?>
    <?php echo CHtml::activeTextArea($comment,'message', array('placeholder' => 'Текст...')); ?>
    <?php echo CHtml::submitButton('Отправить'); ?>
    <?php echo CHtml::endForm(); ?>
</div>
<?endif;?>