<?php

namespace wadeshuler\subscriber\models;

use yii\base\Model;

class ImportForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $csvFile;

    public function rules()
    {
        return [
            [['csvFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv,xls,xlsx', 'checkExtensionByMimeType'=>false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csvFile' => 'CSV File',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            return true;
        }

        return false;
    }

}
