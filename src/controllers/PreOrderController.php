<?php

namespace hipanel\modules\server\controllers;

use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\base\CrudController;
use hipanel\modules\server\models\Change;
use Yii;
use yii\base\Event;
use yii\filters\AccessControl;

class PreOrderController extends CrudController
{
    public static function modelClassName()
    {
        return Change::class;
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'roles'   => ['resell'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'findOptions' => ['state' => 'new', 'class' => 'serverBuy'],
                'data' => function ($action) {
                    return [
                        'states' => Change::getStates(),
                    ];
                },
            ],
            'bulk-approve' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'approve',
                'success' => Yii::t('hipanel/server', 'VDS were approved successfully'),
                'error' => Yii::t('hipanel/server', 'Error occurred during VDS approving'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $comment = Yii::$app->request->post('comment');
                    foreach ($action->collection->models as $model) {
                        $model->setAttribute('comment', $comment);
                    }
                },
            ],
            'bulk-approve-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'approve',
                'view' => '_bulkApprove',
                'findOptions' => [
                    'state' => Change::STATE_NEW
                ]
            ],
            'bulk-reject' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'reject',
                'success' => Yii::t('hipanel/server', 'VDS were rejected successfully'),
                'error' => Yii::t('hipanel/server', 'Error occurred during VDS rejecting'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $comment = Yii::$app->request->post('comment');
                    foreach ($action->collection->models as $model) {
                        $model->setAttribute('comment', $comment);
                    }
                },
            ],
            'bulk-reject-modal' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'reject',
                'view' => '_bulkReject',
                'findOptions' => [
                    'state' => Change::STATE_NEW
                ]
            ],
        ];
    }
}