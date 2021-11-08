<?php

class Options extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    public $monthly_limit;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    public $welcome_bonus;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field monthly_limit
     *
     * @param integer $monthly_limit
     * @return $this
     */
    public function setMonthlyLimit($monthly_limit)
    {
        $this->monthly_limit = $monthly_limit;

        return $this;
    }

    /**
     * Method to set the value of field welcome_bonus
     *
     * @param integer $welcome_bonus
     * @return $this
     */
    public function setWelcomeBonus($welcome_bonus)
    {
        $this->welcome_bonus = $welcome_bonus;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field monthly_limit
     *
     * @return integer
     */
    public function getMonthlyLimit()
    {
        return $this->monthly_limit;
    }

    /**
     * Returns the value of field welcome_bonus
     *
     * @return integer
     */
    public function getWelcomeBonus()
    {
        return $this->welcome_bonus;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bonuz");
        $this->setSource("options");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Options[]|Options|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Options|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'options';
    }

}
