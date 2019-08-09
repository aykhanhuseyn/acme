<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Security;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $uid
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $status
 * @property int $contact_email
 * @property int $contact_phone
 * @property string $auth_key
 * @property string $created
 * @property string $updated
 *
 * @property Auth[] $auths
 * @property Message[] $fromMessages
 * @property Message[] $toMessages
 * @property PhoneNumber[] $phoneNumbers
 * @property Trip[] $trips
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    const STATUS_INSERTED=0;
    const STATUS_ACTIVE=1;
    const STATUS_BLOCKED=2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'username', 'email', 'password', 'auth_key'], 'required'],
            [['status', 'contact_email', 'contact_phone'], 'integer'],
            [['created', 'updated'], 'safe'],
            [['uid', 'password', 'auth_key'], 'string', 'max' => 60],
            [['username'], 'string', 'max' => 45],
            [['email'], 'string', 'max' => 255],
            [['uid'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
            [['email'],'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'status' => Yii::t('app', 'Status'),
            'contact_email' => Yii::t('app', 'Contact Email'),
            'contact_phone' => Yii::t('app', 'Contact Phone'),
            'auth_key' => Yii::t('app', 'Auth. Key'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    public function beforeValidate()
    {
        if($this->isNewRecord){
            $this->setUid();
            $this->setAuthKey();
        }
        return parent::beforeValidate();
    }

    private function setUid(){
        try {
            $this->uid = Yii::$app->getSecurity()->generatePasswordHash(date('YmdHis').rand(1, 999999));
        } catch (Exception $e) {
        }
    }
    private function setAuthKey(){
        try {
            $this->auth_key = Yii::$app->getSecurity()->generatePasswordHash(date('YmdHis').$this->username);
        } catch (Exception $e) {
        }
    }

    public function activate() {
        $this->status = self::STATUS_ACTIVE;
        $this->setUid();
        return $this->save();
    }

    public function beforeSave($import)
    {
        if($this->isNewRecord){
            try {
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            } catch (Exception $e) {
            }
        }
        $this->updated = new Expression('NOW()');
        return parent::beforeSave($import);
    }

    public static function findByEmail($email)
    {
        return self::findOne(['email' => $email]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromMessages()
    {
        return $this->hasMany(Message::className(), ['from_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToMessages()
    {
        return $this->hasMany(Message::className(), ['to_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneNumbers()
    {
        return $this->hasMany(PhoneNumber::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrips()
    {
        return $this->hasMany(Trip::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return User::findOne(['id'=>$id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
}
