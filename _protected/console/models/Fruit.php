<?php
namespace app\console\models;

use yii\base\Model;
use Yii;

/**
 * Fruit Model.
 */
class Fruit extends Model
{
    public $title;
    public $size;
    public $unit_price;
    public $description;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'size', 'unit_price', 'description'], 'required'],
            [['title', 'size', 'unit_price', 'description'], 'safe'],
            [['title', 'size', 'description'], 'string'],
            [['unit_price'], 'number'],
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title'=> Yii::t('app', 'Title'),
            'size' => Yii::t('app', 'Size'),
            'unit_price' => Yii::t('app', 'Unit Price'),
            'description' => Yii::t('app', 'Description'),            
        ];
    }
}

