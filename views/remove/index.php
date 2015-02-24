<?
/**
 * Unsubscribe view
 * @var string $message
 */
use yii\helpers\Html;
echo Html::tag('h1', \Yii::t('mrssoft/subscribe', 'Unsubscribe'));
echo Html::tag('p', $message);