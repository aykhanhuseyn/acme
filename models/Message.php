<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property string $id
 * @property string $from_user_id
 * @property string $to_user_id
 * @property string $trip_id
 * @property string $text
 * @property string $created
 *
 * @property UserLog $fromUser
 * @property UserLog $toUser
 * @property Trip $trip
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_user_id', 'to_user_id', 'trip_id', 'text'], 'required'],
            [['from_user_id', 'to_user_id', 'trip_id'], 'integer'],
            [['text'], 'string'],
            [['created'], 'safe'],
            [['from_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLog::className(), 'targetAttribute' => ['from_user_id' => 'id']],
            [['to_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLog::className(), 'targetAttribute' => ['to_user_id' => 'id']],
            [['trip_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trip::className(), 'targetAttribute' => ['trip_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from_user_id' => Yii::t('app', 'From UserLog ID'),
            'to_user_id' => Yii::t('app', 'To UserLog ID'),
            'trip_id' => Yii::t('app', 'Trip ID'),
            'text' => Yii::t('app', 'Text'),
            'created' => Yii::t('app', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromUser()
    {
        return $this->hasOne(UserLog::className(), ['id' => 'from_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToUser()
    {
        return $this->hasOne(UserLog::className(), ['id' => 'to_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrip()
    {
        return $this->hasOne(Trip::className(), ['id' => 'trip_id']);
    }
}
