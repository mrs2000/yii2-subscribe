<?
namespace mrssoft\subscribe\controllers;

use mrssoft\subscribe\models\Recipient;
use mrssoft\subscribe\SubscribeManager;
use Yii;
use yii\web\Controller;

class RemoveController extends Controller
{
    /**
     * Unsubscribe e-mail
     * @param $key
     * @return string
     */
    public function actionIndex($key)
    {
        $email = SubscribeManager::decodeEmail($key);

        /** @var Recipient $recipient */
        $recipient = Recipient::find()->where('public=1 AND email=:email', [':email' => $email])->one();
        if ($recipient) {
            $recipient->public = 0;
            $recipient->save();
            $message = Yii::t('mrssoft/subscribe', 'Address {0} successfully unsubscribed', $email);
        } else {
            $message = Yii::t('mrssoft/subscribe', 'The wrong link');
        }

        return $this->render('index', ['message' => $message]);
    }
}