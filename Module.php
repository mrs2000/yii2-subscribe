<?
namespace mrssoft\subscribe;

use Yii;

class Module extends \yii\base\Module
{
    public $params = [
        'sendLimit' => 10
    ];

    public function init()
    {
        parent::init();

        Yii::$app->i18n->translations['mrssoft/subscribe'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'messages',
            'fileMap' => [
                'mrssoft/subscribe' => 'subscribe.php',
            ],
        ];
    }
}