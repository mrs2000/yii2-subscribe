<?
namespace mrssoft\subscribe\models;

use yii\db\ActiveRecord;

/**
 * @property string $email
 * @property string $state
 * @property string $name
 * @property int $subscribe_id
 * @property Subscribe $subscribe
 */
class Progress extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%subscribe_progress}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscribe()
    {
        return self::hasOne(Subscribe::className(), ['id' => 'subscribe_id']);
    }
}