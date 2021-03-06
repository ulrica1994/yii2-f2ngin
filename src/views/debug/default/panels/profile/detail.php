<?php
/* @var $panel yii\debug\panels\ProfilingPanel */
/* @var $searchModel yii\debug\models\search\Profile */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $time integer */
/* @var $memory integer */

use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Performance Profiling';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>Total processing time: <b><?= $time ?></b>; Peak memory: <b><?= $memory ?></b>.</p>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'profile-panel-detailed-grid',
    'options' => ['class' => 'detail-grid-view table-responsive'],
    'filterModel' => $searchModel,
    'filterUrl' => $panel->getUrl(),
    'striped' => true,
    'condensed' => true,
    'responsive' => true,  
    'pjax'=>true,
    'export' => false,
    'toolbar'=>['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid'])                    
            ],
    'panel' => [
        'type' => 'primary', 
        'heading' => 'Times Listing',
        'after'=>false,
    ],      
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'seq',
            'label' => 'Time',
            'value' => function ($data) {
                $timeInSeconds = $data['timestamp'] / 1000;
                $millisecondsDiff = (int) (($timeInSeconds - (int) $timeInSeconds) * 1000);

                return date('H:i:s.', $timeInSeconds) . sprintf('%03d', $millisecondsDiff);
            },
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        [
            'attribute' => 'duration',
            'value' => function ($data) {
                return sprintf('%.1f ms', $data['duration']);
            },
            'options' => [
                'width' => '10%',
            ],
            'headerOptions' => [
                'class' => 'sort-numerical'
            ]
        ],
        'category',
        [
            'attribute' => 'info',
            'value' => function ($data) {
                return str_repeat('<span class="indent">→</span>', $data['level']) . Html::encode($data['info']);
            },
            'format' => 'html',
            'options' => [
                'width' => '60%',
            ],
        ],
    ],
]);
