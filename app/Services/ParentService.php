<?php
namespace App\Services;

class ParentService
{
    protected $dataSources = [
        'DataProviderX',
        'DataProviderY'
    ];

    public function getAllParents(array $filters = [])
    {
        $parents = [];
        $dataSources = $this->getValidDataSources($filters);
        foreach ($dataSources as $dataSource){
            $this->processFilters($dataSource, $filters);
            $parents = array_merge($parents, $dataSource->getAll());
        }
        return $parents;
    }

    protected function getValidDataSources(array $filters = []):array
    {
        $dataSources = [];

        if(array_key_exists('provider', $filters) && in_array($filters['provider'], $this->dataSources)){
            $dataSources[] = app('App\Datasources\\'.$filters['provider']);
        }else{
            foreach ($this->dataSources as $dataSource){
                $dataSources[] = app('App\Datasources\\'.$dataSource);
            }
        }

        return $dataSources;
    }

    protected function processFilters($dataSource, $filters = [])
    {
        if(!empty($filters)){
            if(array_key_exists('statusCode', $filters)){
                $dataSource->filterByStatus($filters['statusCode']);
            }
            if(array_key_exists('balanceMin', $filters)){
                $dataSource->filterByBalanceMin($filters['balanceMin']);
            }
            if(array_key_exists('balanceMax', $filters)){
                $dataSource->filterByBalanceMax($filters['balanceMax']);
            }
            if(array_key_exists('currency', $filters)){
                $dataSource->filterByCurrency($filters['currency']);
            }
        }
    }
}
