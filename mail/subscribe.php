<?
/**
 * @var \mrssoft\subscribe\models\Subscribe $subscribe
 * @var string $key
 * @var string $name
 */
use yii\helpers\Html;
use yii\helpers\Url;

if (!empty($name)) {
    echo Html::tag('p', \Yii::t('mrssoft/subscribe', 'Hello {0}, ', $name));
}

echo Html::tag('h1', $subscribe->title);
echo $subscribe->text;
echo Html::tag('hr');
echo Html::tag('p', \Yii::t('mrssoft/subscribe', 'Unsubscribe').': '.Url::to(['remove/index', 'key' => $key]));