<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AdPublic;
use app\models\AdOther;

$prevAdId = 0;
?>

<h3 class="page-header">
    Реклама
    <div class="btn-group pull-right">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?= Yii::$app->formatter->asDate($thisMonth, 'LLLL yyyy'); ?>&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
            <?php foreach ($statMonthes as $sMonth): ?>
                <?php if ($sMonth['period'] != $thisMonth): ?>
                    <li><a href="<?= Url::to(['/ad/index', 'd' => $sMonth['period']]); ?>"><?= Yii::$app->formatter->asDate($sMonth['period'], 'LLLL yyyy'); ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <a name="sync_settings_back" href="<?= Url::to(['/ad/index', 'all' => '1']); ?>" type="button" class="btn btn-primary pull-right" style="margin-right: 20px;">Показать все</a>
    <a href="<?= Url::to(['/ad-category/index']); ?>" type="button" class="btn btn-primary pull-right" style="margin-right: 20px;">Категории</a>
</h3>
<br />

<p><b>ВСЕГО НА РЕКЛАМУ: <?= number_format($adTotal, 2, '.', ' '); ?> руб.</b></p>

<div class="panel-group" id="accordion">
    <?php if ($adCategories): ?>
        <?php foreach ($adCategories as $cat): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse-<?= $cat->id ?>">
                            <?= $cat->name ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse-<?= $cat->id ?>" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <?php if ($cat->is_vk): ?>
                            <p>
                                <a name="sync_settings_back" href="<?= Url::to(['/ad/create', 'cat_id' => $cat->id]); ?>" type="button" class="btn btn-primary pull-left">Добавить</a>
                            </p>
                            <br><br>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>Паблики</th>
                                    <th style="text-align:center">Автор</th>
                                    <th style="text-align:center">Цена, руб.</th>
                                    <th style="text-align:center">Дата оплаты</th>
                                    <th style="text-align:center">Следующая дата оплаты</th>
                                    <th style="text-align:center">Комментарии</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php foreach ($ads as $k => $ad): ?>
                                        <tr>
                                            <td><a href="<?= $ad['ad_group']; ?>" target="_blank"><?= $ad['ad_group']; ?></a></td>
                                            <?php if ($prevAdId != $ad['ad_id']): ?>
                                                <?php $rows = AdPublic::getCountById($ad['ad_id']); ?>
                                                <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><a href="<?= $ad['creator']; ?>" target="_blank"><?= $ad['creator']; ?></a></td>
                                                <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><?= number_format($ad['price'], 2, '.', ' '); ?></td>
                                                <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><?= !empty($ad['paid_date']) ? date('d-m-Y', strtotime($ad['paid_date'])) : "" ?></td>
                                                <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><?= !empty($ad['next_pay_date']) ? date('d-m-Y', strtotime($ad['next_pay_date'])) : "" ?></td>
                                                <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><?= $ad['amount']; ?><?php if ($ad['ad_type'] == 'P'): ?> постов<?php endif; ?></td>
                                                <td rowspan="<?= $rows; ?>" align="center" style="vertical-align:middle"><a href="<?= Url::to(['/ad/update', 'cat_id' => $cat->id, 'id' => $ad['ad_id']]); ?>"><i class="fa fa-pencil-square-o"></i></a></td>
                                                <?php $prevAdId = $ad['ad_id']; ?>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <?php $others = !empty($all) && $all == 1 ? AdOther::getAds($cat->id) : AdOther::getAds($cat->id, $thisMonth); ?>
                            <p><a name="sync_settings_back" href="<?= Url::to(['/ad/create', 'cat_id' => $cat->id]); ?>" type="button" class="btn btn-primary pull-left">Добавить</a></p>
                            <br><br>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th>Название</th>
                                    <th style="text-align:center">Цена, руб.</th>
                                    <th style="text-align:center">Дата оплаты</th>
                                    <th style="text-align:center">Следующая дата оплаты</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <?php if ($others): ?>
                                        <?php foreach ($others as $ad_other): ?>
                                            <tr>
                                                <td><?= $ad_other->name ?></td>
                                                <td align="center"><?= $ad_other->price ?></td>
                                                <td align="center"><?= date('d-m-Y', strtotime($ad_other->paid_date)) ?></td>
                                                <td align="center"><?= date('d-m-Y', strtotime($ad_other->next_pay_date)) ?></td>
                                                <td align="center">
                                                    <a href="<?= Url::to(['/ad/update', 'cat_id' => $cat->id, 'id' => $ad_other->id]); ?>" title="Редактировать">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                    <a href="<?= Url::to(['/ad/delete-other', 'id' => $ad_other->id]) ?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post">
                                                        <span class="glyphicon glyphicon-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">Нет данных</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
</div>
