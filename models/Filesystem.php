<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filesystem".
 *
 * @property int $id
 * @property string $name
 * @property int $size
 * @property string $type
 * @property string $ctime
 * @property string $path
 * @property bool $is_dir
 */
class Filesystem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'filesystem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['size'], 'default', 'value' => null],
            [['size'], 'integer'],
            [['ctime'], 'safe'],
            [['is_dir'], 'boolean'],
            [['name', 'type', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'size' => 'Size',
            'type' => 'Type',
            'ctime' => 'Ctime',
            'path' => 'Path',
            'is_dir' => 'Is Dir',
        ];
    }
}
