<?php
namespace App\Services;

/**
 * Class ParentService
 * @package App\Services
 */
class ParentService
{
    /**
     * add all datasources that have the data, please make the name as created inside Datasources folder
     * @var array
     */
    protected $dataSources = [
        'DataProviderX',
        'DataProviderY'
    ];
    /**
     * Allowed filters to filter the request data
     * @var array
     */
    protected $allowedFilters = [
        'provider',
        'statusCode',
        'balanceMin',
        'balanceMax',
        'currency'
    ];

    /**
     * main function that returns all parents from different sources
     * @param array $filters
     * @return array
     */
    public function getAllParents(array $filters = [])
    {
        $parents = [];
        //make sure that no other GET attribute is here
        $filters = $this->validateFilters($filters);

        //get list of valid data sources based on the request
        $dataSources = $this->getValidDataSources($filters);
        foreach ($dataSources as $dataSource){
            //apply filters on each data source
            $this->processFilters($dataSource, $filters);
            //combine all datasources data
            $parents = array_merge($parents, $dataSource->getAll());
        }
        return $parents;
    }

    /**
     * make sure that no filter other than allowed filters here
     * @param array $filters
     * @return array
     */
    protected function validateFilters(array $filters = [])
    {
        $allowedFilters = $this->allowedFilters;
        $filtered = array_filter(
            $filters,
            function ($key) use ($allowedFilters) {
                return in_array($key, $allowedFilters);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $filtered;
    }

    /**
     * based on filters get list of data source(s) instances to be used to get parents data
     * @param array $filters
     * @return array
     */
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

    /**
     * Process filters on specific data source
     * @param $dataSource
     * @param array $filters
     */
    protected function processFilters($dataSource, $filters = [])
    {
        if(!empty($filters)){
            if(array_key_exists('statusCode', $filters) && !empty($filters['statusCode'])){
                $dataSource->filterByStatus($filters['statusCode']);
            }
            if(array_key_exists('balanceMin', $filters) && $filters['balanceMin'] >= 0){
                $dataSource->filterByBalanceMin($filters['balanceMin']);
            }
            if(array_key_exists('balanceMax', $filters) && $filters['balanceMax'] >= 0){
                $dataSource->filterByBalanceMax($filters['balanceMax']);
            }
            if(array_key_exists('currency', $filters) && !empty($filters['currency'])){
                $dataSource->filterByCurrency($filters['currency']);
            }
        }
    }
}
