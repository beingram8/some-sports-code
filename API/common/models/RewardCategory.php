<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "reward_category".
 *
 * @property int $id
 * @property string $name
 *
 * @property RewardProduct[] $rewardProducts
 */
class RewardCategory extends \yii\db\ActiveRecord
{
    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'order_no'], 'required'],
            [['name'], 'string', 'max' => 20],
            ['order_no', 'unique'],
            [['page', 'per_page'], 'integer'],
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
        ];
    }

    /**
     * Gets query for [[RewardProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRewardProducts()
    {
        return $this->hasMany(RewardProduct::className(), ['reward_category_id' => 'id'])
            ->orderBy(['reward_product.order_no' => SORT_ASC]);
    }

    public function getProductList()
    {
        $query = RewardCategory::find()->joinWith(['rewardProducts'])->orderBy('reward_category.order_no ASC')->asArray();

        $page = $this->page > 0 ? ($this->page - 1) : 0;
        $pageSize = (int) $this->per_page;

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => true,
                'page' => $page,
                'pageParam' => 'page',
                'defaultPageSize' => $pageSize,
            ],
            'totalCount' => RewardCategory::find()->count(),
        ]);

        $models = $provider->getModels();

        $pagination = array_intersect_key(
            (array) $provider->pagination,
            array_flip(
                $paginationParams = [
                    'pageParam',
                    'pageSizeParam',
                    'params',
                    'totalCount',
                    'defaultPageSize',
                    'pageSizeLimit',
                ]
            )
        );

        $totalPage = $pagination['totalCount'] / $pageSize;
        $pagination['totalPage'] = $totalPage;
        $pagination['currentPage'] = $this->page;
        $pagination['isMore'] = $totalPage <= $this->page ? false : true;
        $data = [];

        $productAvailable = false;
        foreach ($models as $key => $value) {
            $data[$key]['name'] = $value['name'];
            $product_array = [];
            if (!empty($value['rewardProducts'])) {
                foreach ($value['rewardProducts'] as $product) {
                    $count = \common\models\RewardCode::find()->where(['reward_id' => $product['id']])
                        ->andWhere(['is', 'user_id', new \yii\db\Expression('null')])->count();
                    if ($count > 0) {
                        $productAvailable = true;
                        array_push($product_array, $product);
                    }
                }
            }
            $data[$key]['product_list'] = $product_array;
        }
        return array('rows' => $data, 'pagination' => $pagination, 'product_available' => $productAvailable);
    }

}
