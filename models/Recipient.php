<?

namespace mrssoft\subscribe\models;

use yii\db\ActiveRecord;

/**
 * @property string $email
 * @property int $public
 * @property int $id
 */
class Recipient extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%subscribe_recipient}}';
    }
}