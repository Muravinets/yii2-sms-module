<?php
/**
 * Created by PhpStorm.
 * User: sh4d0walker
 * Date: 9/17/15
 * Time: 8:45 PM
 */

namespace ihacklog\sms\models;

use yii\db\ActiveQuery;
use ihacklog\sms\models\Sms;
use ihacklog\sms\components\traits\ModuleTrait;

class SmsQuery extends ActiveQuery
{
    /**
     * Select expired.
     *
     * @param ActiveQuery $query
     */
    public function expired()
    {
        $this->andWhere(['verify_result' => Sms::STATUS_BANNED]);
        return $this;
    }

    public function pending()
    {
        $this->andWhere(['verify_result' => Sms::VERIFY_RESULT_INIT, ['created_at','>', time() - $this->getModule()->verifyTimeout]]);
        return $this;
    }
}