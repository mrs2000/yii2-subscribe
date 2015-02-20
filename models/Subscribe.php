<?

namespace mrssoft\subscribe\models;

use yii\db\ActiveRecord;

/**
 * @property string $title
 * @property string $text
 * @property string $key
 * @property int $id
 */
class Subscribe extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%subscribe}}';
    }
}