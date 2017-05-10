<?php
/**
 * Server module for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\server\widgets;

use hipanel\widgets\ModalButton;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;


class SimpleOperation extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $scenario;

    /**
     * @var array. Store default options for [[ModalButton]]
     */
    public $modalOptions = [
        'button' => [
            'class' => 'btn btn-default btn-block',
        ],
    ];

    /**
     * @var array|Modal stores options for [[ModalButton]]
     * After Modal creating, stores the object.
     */
    protected $modal = [];

    /**
     * @var string
     */
    public $buttonLabel;
    public $buttonClass;
    public $body;
    public $modalHeaderLabel;
    public $modalFooterLabel;
    public $modalFooterLoading;
    public $modalFooterClass;

    /**
     * @var array
     */
    public $form;
    public $modalHeaderOptions;

    /**
     * @var boolean for ignoring device states
     */
    public $skipCheckOperable = false;

    public function init()
    {
        parent::init();

        if ($this->model === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        if ($this->scenario === null) {
            throw new InvalidConfigException('Please specify the "scenario" property.');
        }
    }

    protected function buildModalOptions()
    {
         $config = ArrayHelper::merge([
            'class' => ModalButton::class,
            'model' => $this->model,
            'scenario' => $this->scenario,
            'button' => [
                'label' => $this->buttonLabel,
                'class' => $this->buttonClass,
                'disabled' => !$this->model->isOperable() && !$this->skipCheckOperable,
            ],
            'body' => $this->body,
            'form' => $this->form ? : [],
            'modal' => [
                'header' => Html::tag('h4', $this->modalHeaderLabel),
                'headerOptions' =>  $this->modalHeaderOptions,
                'footer' => [
                    'label' => $this->modalFooterLabel,
                    'data-loading-text' => $this->modalFooterLoading,
                    'class' => $this->modalFooterClass,
                ],
            ],
        ], $this->modalOptions);

        if ($this->buttonClass !== null) {
            $config['button']['class'] = $this->buttonClass;
        }

        return $config;
    }

    public function run()
    {
        echo Yii::createObject($this->buildModalOptions())->run();
    }
}
