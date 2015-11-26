<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 14-12-3
 * Time: 下午1:46
 */

namespace api\libs;

use yii\rest\Serializer;

class Serializerapi extends Serializer
{
	
    protected function serializeDataProvider($dataProvider)
    {
		$models = $this->serializeModels($dataProvider->getModels());
        if (($pagination = $dataProvider->getPagination()) !== false) {
            $this->addPaginationHeaders($pagination);
        }

        if ($this->request->getIsHead()) {
            return null;
        } elseif ($this->collectionEnvelope === null) {
            if ($pagination !== false) {
                if(sizeof($models) == 0 || @$_GET['page']>$pagination->getPageCount()){
                    return ['status' => 0, 'data' => null, 'info' => null, 'message' => '暂无记录'];
                }else {
                    return ['status' => 0, 'data' => $models, 'info' => [$this->addPagination($pagination)], 'message' => '获取成功'];
                }
            } else {
                return $models;
            }
        } else {
            $result = [
                $this->collectionEnvelope => $models,
            ];
            if ($pagination !== false) {
                return array_merge($result, $this->serializePagination($pagination));
            } else {
                return $result;
            }
        }
    }

    protected function addPagination($pagination)
    {
        return   [
            'pagetotal' => $pagination->getPageCount(),
            'page' => $pagination->getPage()+1,
        ];
    }

} 
