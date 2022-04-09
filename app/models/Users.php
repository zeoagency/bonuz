<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=5, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=22, nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", length=44, nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", length=22, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=22, nullable=true)
     */
    public $surname;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $status;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $is_admin;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $monthly_limit;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $discord_id;
    /**
     *
     * @var string
     */
    public $birthday;
    /**
     *
     * @var string
     */
    public $last_birthday_bonus;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model' => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bonuz");
        $this->setSource("users");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
