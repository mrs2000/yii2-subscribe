<?
namespace mrssoft\subscribe;

use mrssoft\subscribe\models\Progress;
use mrssoft\subscribe\models\Recipient;
use mrssoft\subscribe\models\Subscribe;
use yii\base\Component;
use yii\base\Event;
use yii\helpers\ArrayHelper;

class SubscribeManager extends Component
{
    const EVENT_LOAD_RECIPIENTS = 'loadRecipients';

    /**
     * Create new subscribe
     * @param $title
     * @param $text
     * @param null $key
     */
    public function create($title, $text, $key = null)
    {
        if ($key === null) $key = $title;
        $key = md5($key);

        if (Subscribe::find()->where(['key' => $key])->one()) return;

        $subscribe = new Subscribe();
        $subscribe->key = $key;
        $subscribe->text = $text;
        $subscribe->title = $title;
        if ($subscribe->save()) {
            foreach ($this->getRecipients() as $email) {
                $progress = new Progress();
                $progress->email = $email;
                $progress->subscribe_id = $subscribe->id;
                $progress->save();
            }
        }
    }

    /**
     * Add new recipient
     * @param $email
     */
    public function addRecipient($email)
    {
        $recipient = new Recipient();
        $recipient->email = $email;
        $recipient->save();
    }

    /**
     * @return array
     */
    private function getRecipients()
    {
        $event = new Event();
        $event->data['recipients'] = [];
        $this->trigger(self::EVENT_LOAD_RECIPIENTS, $event);

        if (empty($event->data['recipients'])) {
            $recipients = Recipient::find()->select('email')->where('public=1')->all();
            return ArrayHelper::getColumn($recipients, 'email');
        } else {
            return $event->data['recipients'];
        }
    }
}
