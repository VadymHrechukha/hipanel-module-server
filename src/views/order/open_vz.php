<?php

$this->title = Yii::t('hipanel/server/order', 'Open VZ');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel/server/order', 'Buy server'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_priceBox', compact('packages', 'tariffTypes', 'testVDSPurchased'));

?>
