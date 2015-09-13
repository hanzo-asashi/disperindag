<?php

/**
 * This is the model class for table "{{berita}}".
 *
 * The followings are the available columns in table '{{berita}}':
 * @property string $berita_id
 * @property string $isi_berita
 * @property string $url
 * @property string $judul
 * @property string $image_path
 * @property string $tgl_berita
 * @property string $tgl_update
 * @property string $tags_id
 * @property string $kategori_id
 * @property integer $is_publish
 * @property integer $is_draft
 * @property integer $is_archive
 *
 * The followings are the available model relations:
 * @property Kategori $kategori
 * @property StatusPublish $isPublish
 * @property Tags $tags
 */
class Berita extends CActiveRecord
{
    const STATUS_PUBLISH = 1;
    const STATUS_DRAFT = 2;
    const STATUS_ARCHIVE = 3;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{berita}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('kategori_id', 'required'),
            array('is_publish, is_draft, is_archive', 'numerical', 'integerOnly' => true),
            array('url', 'length', 'max' => 20),
            array('judul', 'length', 'max' => 50),
            array('imageid,tags_id, kategori_id', 'length', 'max' => 11),
            array('isi_berita, tgl_berita, tgl_update', 'safe'),
            array(
                'is_publish',
                'in',
                'range' => array(self::STATUS_PUBLISH, self::STATUS_ARCHIVE, self::STATUS_DRAFT),
            ),
            array('superuser', 'in', 'range' => array(0, 1)),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('berita_id, isi_berita, url, judul, imageid, tgl_berita, tgl_update, tags_id, kategori_id, is_publish, is_draft, is_archive', 'safe', 'on' => 'search'),
        );
    }

    public static function itemAlias($type, $code = null)
    {
        $_items = array(
            'BeritaStatus'  => array(
                self::STATUS_PUBLISH => AdminModule::t('Publish'),
                self::STATUS_ARCHIVE   => AdminModule::t('Archive'),
                self::STATUS_DRAFT   => AdminModule::t('Draft'),
            ),
            'AdminStatus' => array(
                '0' => AdminModule::t('No'),
                '1' => AdminModule::t('Yes'),
            ),
        );
        if (isset($code)) {
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        } else {
            return isset($_items[$type]) ? $_items[$type] : false;
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'kategori' => array(self::BELONGS_TO, 'Kategori', 'kategori_id'),
            'isPublish' => array(self::BELONGS_TO, 'StatusPublish', 'is_publish'),
            'tags' => array(self::BELONGS_TO, 'Tags', 'tags_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'berita_id' => AdminModule::t("Id"),
            'isi_berita' => AdminModule::t("Isi Berita"),
            'url' => AdminModule::t("url"),
            'judul' => AdminModule::t('Judul'),
            'imageid' => AdminModule::t('Lokasi Image'),
            'tgl_berita' => AdminModule::t('Tgl Berita'),
            'tgl_update' => AdminModule::t('Tgl Update'),
            'tags_id' => AdminModule::t('Tags'),
            'kategori_id' => AdminModule::t('Kategori'),
            'is_publish' => AdminModule::t('Status'),
            'is_draft' => AdminModule::t('Is Draft'),
            'is_archive' => AdminModule::t('Is Archive'),
        );
    }

    public function scopes()
    {
        return array(
            'publish'    => array(
                'condition' => 'is_publish =' . self::STATUS_PUBLISH,
            ),
            'archive' => array(
                'condition' => 'is_publish =' . self::STATUS_ARCHIVE,
            ),
            'draft'    => array(
                'condition' => 'is_publish=' . self::STATUS_DRAFT,
            ),
            'notsafe'   => array(
                'select' => 'berita_id, isi_berita, judul, url, imageid, tgl_berita, tgl_update, tags_id, is_publish,is_draft,is_archive',
            ),
        );
    }

    public function defaultScope()
    {
        return array(
            'alias'  => 'berita',
            'select' => 'berita.berita_id, berita.isi_berita, berita.judul, berita.url, berita.imageid, berita.tgl_berita, berita.tgl_update,berita.tags_id, berita.is_publish',
            'with' => array('isPublish','kategori','tags'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('berita_id', $this->berita_id, true);
        $criteria->compare('isi_berita', $this->isi_berita, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('judul', $this->judul, true);
        $criteria->compare('imageid', $this->imageid, true);
        $criteria->compare('tgl_berita', $this->tgl_berita, true);
        $criteria->compare('tgl_update', $this->tgl_update, true);
        $criteria->compare('tags_id', $this->tags_id, true);
        $criteria->compare('kategori_id', $this->kategori_id, true);
        $criteria->compare('is_publish', $this->is_publish);
        $criteria->compare('is_draft', $this->is_draft);
        $criteria->compare('is_archive', $this->is_archive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->getModule('admin')->user_page_size,
            ),
        ));
    }

    public function getCreatetime()
    {
        return strtotime($this->tgl_berita);
    }

    public function setCreatetime($value)
    {
        $this->tgl_berita = date('Y-m-d H:i:s', $value);
    }

    public function getUpdatetime()
    {
        return strtotime($this->tgl_update);
    }

    public function setUpdatetime($value)
    {
        $this->tgl_update = date('Y-m-d H:i:s', $value);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Berita the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
