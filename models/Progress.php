<?
namespace mrssoft\subscribe\models;

use yii\db\ActiveRecord;

/**
 * @property string $email
 * @property string $state
 * @property int $subscribe_id
 */
class Progress extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%subscribe_progress}}';
    }
}