<?php

class Bonuz extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $top_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $from;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $comment;



    /**
     *
     * @var string
     * @Column(type="int", nullable=true)
     */
    public $quantity;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bonuz");
        $this->setSource("bonuz");
        $this->hasMany('id', 'BonuzDetails', 'bonuz_id', ['alias' => 'BonuzDetails']);
        $this->hasMany('id', 'BonuzHashtags', 'bonuz_id', ['alias' => 'BonuzHashtags']);
        $this->belongsTo('from', '\Users', 'id', ['alias' => 'Users']);

        $this->hasMany('id', 'Bonuz', 'top_id', ['alias' => 'BonuzComments']); // get all comments belongs to bonuz
        $this->belongsTo('top_id', '\Bonuz', 'id', ['alias' => 'Bonuz']); // make foreign key top id (bonuz id)

    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'bonuz';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Bonuz[]|Bonuz|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Bonuz|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }





}
