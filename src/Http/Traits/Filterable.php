<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Facades\Input;

trait Filterable
{
    /**
     * @var QueryBuilder
     */
    protected $query;

    public function scopePagination($query, $request, $order = null, $column = null){
        if ($request->has('paginate')) {
            $paginate = $request->get('paginate') == '*' ? $query->getModel()->all()->count() : $request->get('paginate');
        } else {
            $paginate = '10';
        }
        if($request->has('page')){
            $page = $request->get('page');
        }else{
            $page = null;
        }
        if($request->has('order') && $request->has('column')){
            if($request->get('order') == 'ASC'){
                return $query->orderBy($request->get('column'))->paginate($paginate, ['*'], 'page', $page);
            }else{
                return $query->orderByDesc($request->get('column'))->paginate($paginate, ['*'], 'page', $page);
            }
        }
        return $query->paginate($paginate, ['*'], 'page', $page);
    }

    public function scopeFilter($query, array $filterData = [])
    {
        foreach ($filterData as $key => $value) {
            if( $key=='page') continue;

            if (!$this->isFilterable($key))
                throw new Exception("[$key] is not allowed for filtering");

            if (is_null($value) || $value === '') continue;

//            $scopeName = ucfirst(camel_case($key));
            $scopeName = ucfirst($key);
            if (method_exists($this, 'scope' . $scopeName)) {
                $query->$scopeName($value);
            } else if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }
    }

    protected function isFilterable($key)
    {
        $filterable = $this->filterable ?: [];
        return in_array($key, $filterable);
    }

    /**
     * Paginate the given query with url query params appended.
     *
     * @param  int $perPage
     * @param  array $columns
     * @param  string $pageName
     * @param  int|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function scopePaginateFilter($query, $perPage = 16, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $paginator = $query->paginate($perPage, $columns, $pageName, $page);
        $paginator->appends(Input::get());
        return $paginator;
    }


    /**
     * Paginate the given query with url query params appended.
     *
     * @param  int $perPage
     * @param  array $columns
     * @param  string $pageName
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function scopeSimplePaginateFilter($query, $perPage = 16, $columns = ['*'], $pageName = 'page')
    {
        $paginator = $query->simplePaginate($perPage, $columns, $pageName);
        $paginator->appends(Input::get());
        return $paginator;
    }
}
