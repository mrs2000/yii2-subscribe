<?
namespace mrssoft\subscribe\controllers;

use yii\console\Controller;

class SendController extends Controller
{
    public function actionIndex($limit = null)
    {
        if ($limit === null) {
            $limit = $this->module->params['sendLimit'];
        }


    }
}
