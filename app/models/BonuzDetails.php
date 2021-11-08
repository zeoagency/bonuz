<?php

class BonuzDetails extends \Phalcon\Mvc\Model
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
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $bonuz_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $to;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $quantity;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $comment;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bonuz");
        $this->setSource("bonuz_details");
        $this->belongsTo('bonuz_id', '\Bonuz', 'id', ['alias' => 'Bonuz']);
        $this->belongsTo('to', '\Users', 'id', ['alias' => 'Users']);

    }


    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BonuzDetails[]|BonuzDetails|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BonuzDetails|\Phalcon\Mvc\Model\ResultInterface
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
        return 'bonuz_details';
    }

}
