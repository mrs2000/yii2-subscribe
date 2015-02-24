<?
namespace mrssoft\subscribe\controllers;

use mrssoft\subscribe\models\Progress;
use mrssoft\subscribe\models\Subscribe;
use mrssoft\subscribe\SubscribeManager;
use yii\console\Controller;
use Yii;

class SendController extends Controller
{
    public function actionIndex($limit = null)
    {
        if ($limit === null) {
            $limit = $this->module->params['sendLimit'];
        }

        /** @var Progress[] $progress */
        $progress = Progress::find()->with(['subscribe'])
                                    ->where(['state' => 'wait'])
                                    ->limit($limit)
                                    ->all();
        if (empty($progress)) return;

        /** @var Subscribe[] $subscribes */
        $subscribes = [];

        foreach ($progress as $item)
        {
            if (!in_array($item->subscribe, $subscribes)) {
                $subscribes[] = $item->subscribe;
            }

            $ret = Yii::$app->mailer->compose('@vendor/mrssoft/yii2-subscribe/mail/subscribe.php', [
                                        'subscribe' => $item->subscribe,
                                        'key' => SubscribeManager::encodeEmail($item->email),
                                        'name' => $item->name])
                                     ->setSubject($item->subscribe->title)
                                     ->setTo($item->email)
                                     ->setFrom(Yii::$app->params['mail']['login'])
                                     ->send();

            if (!$ret) {
                Yii::error('Error send subscribe email.');
            }

            $item->state = $ret ? 'send' : 'error';
            $item->save(false);
        }

        foreach ($subscribes as $subscribe)
        {
            if (Progress::find()->where('state="wait" AND subscribe_id='.$subscribe->id)->count() == 0) {
                $subscribe->end = date('c');
                $subscribe->save(false);
                //Progress::deleteAll(['subscribe_id' => $subscribe->id]);
            }
        }
    }
}
