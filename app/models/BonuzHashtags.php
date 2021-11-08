<?php

class BonuzHashtags extends \Phalcon\Mvc\Model
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
    public $hashtag_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("bonuz");
        $this->setSource("bonuz_hashtags");
        $this->belongsTo('bonuz_id', '\Bonuz', 'id', ['alias' => 'Bonuz']);
        $this->belongsTo('hashtag_id', '\Hashtags', 'id', ['alias' => 'Hashtags']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'bonuz_hashtags';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BonuzHashtags[]|BonuzHashtags|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BonuzHashtags|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
