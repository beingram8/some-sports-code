<?php

namespace common\models;
use yii\data\ActiveDataProvider;

use Yii;

/**
 * This is the model class for table "season_team".
 *
 * @property int $id
 * @property int $season
 * @property string $name
 * @property string $logo
 * @property int $is_active
 * @property float|null $price_for_super_fab_package
 * @property int $is_main_team
 * @property int $is_national_team
 * @property int|null $api_team_id
 * @property string|null $api_response
 * @property int|null $created_at
 *
 * @property NewsAssignedTeam[] $newsAssignedTeams
 * @property Season $season0
 * @property SeasonTeamPlayer[] $seasonTeamPlayers
 * @property UserMatchVote[] $userMatchVotes
 * @property UserSuperFanPackageSelectedTeam[] $userSuperFanPackageSelectedTeams
 */
class SeasonTeam extends \yii\db\ActiveRecord
{

    public $page = 1;
    public $per_page = 20;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'season_team';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['season', 'name', 'logo'], 'required'],
            [['season', 'is_active', 'is_main_team', 'is_national_team', 'api_team_id', 'created_at'], 'integer'],
            [['price_for_super_fab_package'], 'number'],
            [['api_response'], 'string'],
            [['name'], 'string', 'max' => 70],
            [['logo'], 'string', 'max' => 255],
            [['page','per_page'] ,'integer'],
            [['season'], 'exist', 'skipOnError' => true, 'targetClass' => Season::className(), 'targetAttribute' => ['season' => 'season']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'season' => 'Season',
            'name' => 'Name',
            'logo' => 'Logo',
            'is_active' => 'Is Active',
            'price_for_super_fab_package' => 'Price For Super Fab Package',
            'is_main_team' => 'Is Main Team',
            'is_national_team' => 'Is National Team',
            'api_team_id' => 'Api Team ID',
            'api_response' => 'Api Response',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[NewsAssignedTeams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsAssignedTeams()
    {
        return $this->hasMany(NewsAssignedTeam::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[Season0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeason0()
    {
        return $this->hasOne(Season::className(), ['season' => 'season']);
    }

    /**
     * Gets query for [[SeasonTeamPlayers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeasonTeamPlayers()
    {
        return $this->hasMany(SeasonTeamPlayer::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[UserMatchVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserMatchVotes()
    {
        return $this->hasMany(UserMatchVote::className(), ['team_id' => 'id']);
    }

    /**
     * Gets query for [[UserSuperFanPackageSelectedTeams]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserSuperFanPackageSelectedTeams()
    {
        return $this->hasMany(UserSuperFanPackageSelectedTeam::className(), ['team_id' => 'id']);
    }

    public function getList()
    {
        $query = SeasonTeam::find()->where(['is_active' => 1])->asArray();
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
        ]);

        $models = $provider->getModels();
        // print_r($models); die;

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

        foreach ($models as $key => $value) {
            $data[$key]['id'] = $value['id'];
            $data[$key]['name'] = $value['name'];
            $data[$key]['logo'] = $value['logo'];
        }
        return array('rows' => $data, 'pagination' => $pagination);
    }

    public function getLeague() {
        return $this->hasOne(SeasonLeague::className(), ['id' => 'league_id']);
    }
}