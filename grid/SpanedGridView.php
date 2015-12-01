<?php

namespace app\grid;

use yii\grid\GridView;
use yii\helpers\Html;

class SpanedGridView extends GridView
{
    public $dataColumnClass;

    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            if (!isset($column->headerOptions['header-hide']) || !$column->headerOptions['header-hide']) {
                $cells[] = $column->renderHeaderCell();
            }
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition === self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition === self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }
}