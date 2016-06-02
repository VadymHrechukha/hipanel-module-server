<?php

/*
 * Finance module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-finance
 * @package   hipanel-module-finance
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\models;

use Yii;

class Change extends \hipanel\modules\finance\models\Change
{
    public static function find()
    {
        $query = parent::find();
        $query->andWhere(['class' => 'serverBuy']);
        return $query;
    }
}
