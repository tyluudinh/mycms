<?php

namespace common\modules\adminUser\models;

use common\core\oop\ObjectInterface;
use common\core\web\mvc\BaseModel;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "adminuser.user".
 *
/**
 * @property integer $uid
 * @property integer $role_id
 * @property string $email
 * @property string $username
 * @property string $desc
 * @property string $avatar
 * @property string $position
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $dob
 * @property string $fullname
 * @property Role $role
 */
class User extends BaseModel implements IdentityInterface, ObjectInterface
{
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';
    const SCENARIO_CREATE = 'create';

    public function scenarios()
    {
        $scenarios                                 = parent::scenarios();
        $scenarios[self::SCENARIO_CHANGE_PASSWORD] = ['password', 'password_hash'];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adminuser.user';
    }

    /**
     * @inheritdoc
     */
    public $password_hash_repeat;

    public function rules()
    {
        return [
            [
                [
                    'email',
                    'username',
                    'avatar',
                    'auth_key',
                    'password_hash',
                    'password_reset_token',
                    'position',
                    'dob',
                    'desc',
                    'fullname'
                ],
                'string'
            ],
            [['email'], 'email'],
            [['status', 'created_by', 'updated_by', 'role_id'], 'integer'],
            [['username', 'email'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'role_id', 'email', 'username'], 'required'],
            [['password_hash', 'password_hash_repeat'], 'required', 'on' => [self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_CREATE], 'message' => Yii::t('app', 'Password/Repeat password is required.')],
            [
                'password_hash',
                'string',
                'on'          => [self::SCENARIO_CHANGE_PASSWORD, self::SCENARIO_CREATE],
                'min'         => 6,
                'skipOnEmpty' => true,
                'tooShort'     => Yii::t('app', 'Password must have minimum 6 characters'),
            ],
            [['password_hash_repeat'], 'compare', 'compareAttribute' => 'password_hash', 'message' => Yii::t('app', 'Password & password hash are not matched')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = [
            'role_id'              => Yii::t('app', 'Role Name'),
            'email'                => Yii::t('app', 'Email'),
            'username'             => Yii::t('app', 'Username'),
            'avatar'               => Yii::t('app', 'Avatar'),
            'auth_key'             => Yii::t('app', 'Auth Key'),
            'password_hash'        => Yii::t('app', 'Password'),
            'password_hash_repeat' => Yii::t('app', 'Password Repeat'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'position'             => Yii::t('app', 'Position'),
            'dob'                  => Yii::t('app', 'Date Of Birth'),
            'desc'                 => Yii::t('app', 'Description'),
            'fullname'             => Yii::t('app', 'Fullname'),
        ];
        
        return array_merge($labels, parent::attributeLabels());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
         return static::findOne(['uid' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['uid' => 'role_id']);
    }

}
