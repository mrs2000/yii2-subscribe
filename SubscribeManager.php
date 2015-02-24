<?
namespace mrssoft\subscribe;

use mrssoft\subscribe\models\Progress;
use mrssoft\subscribe\models\Recipient;
use mrssoft\subscribe\models\Subscribe;
use yii\base\Component;
use yii\base\ErrorException;
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
     * @param array $recipients
     * @return bool
     * @throws ErrorException
     */
    public function create($title, $text, $recipients = [], $key = null)
    {
        if ($key === null) $key = $title;
        $key = md5($key);

        if (Subscribe::find()->where(['key' => $key])->one()) return false;

        $subscribe = new Subscribe();
        $subscribe->key = $key;
        $subscribe->text = $text;
        $subscribe->title = $title;
        if ($subscribe->save()) {

            if (empty($recipients)) {
                $recipients = $this->getRecipients();
            }

            $recipients = array_unique($recipients);

            foreach ($recipients as $name => $email) {
                $progress = new Progress();
                $progress->email = $email;
                $progress->name = is_numeric($name) ? null : $name;
                $progress->subscribe_id = $subscribe->id;
                $progress->save();
            }
            return true;
        } else {
            throw new ErrorException('Error create subscribe.', 500);
        }
    }

    /**
     * Add new recipient
     * @param $email
     * @param null $name
     * @return bool
     */
    public function addRecipient($email, $name = null)
    {
        $recipient = new Recipient();
        $recipient->email = $email;
        $recipient->name = $name;
        return $recipient->save();
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

    /**
     * @param $email
     * @return string
     */
    public static function encodeEmail($email)
    {
        $result = '';
        for ($i = 0; $i < strlen($email); $i++) {
            $result .= dechex(ord(substr($email, $i, 1)));
        }
        return rawurlencode($result);
    }

    /**
     * @param $key
     * @return string
     */
    public static function decodeEmail($key)
    {
        $key = rawurldecode($key);
        $result = '';
        for ($i = 0; $i < strlen($key); $i += 2) {
            $result .= chr(hexdec(substr($key, $i, 2)));
        }
        return $result;
    }
}
