<?php
namespace App\Builders;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Base\Repository;

use App\Repositories\Criterias\Common\OrderResolvedByUrlCriteria;
use App\Repositories\Criterias\Common\SearchResolvedByUrlCriteria;

class PaginationBuilder
{
    private $perPage;
    private $resource;
    private $criterias;
    private $collection;
    private $repository;

    public function __construct()
    {
        $this->perPage = config('pagination.per_page_default');
        $this->resource = null;
        $this->criterias = collect($this->getDefaultCriterias());
        $this->repository = null;
    }

    /**
     * Configura a classe para paginar um repositório
     *
     * Pode receber uma instância de repositório ou sua respectiva classe.
     *
     * @param App\Base\Repository $repository
     * @return App\Builders\PaginationBuilder
     */
    public function repository($repository)
    {
        if (!($repository instanceof Repository)) {
            $repository = new $repository();
        }

        $this->collection = null;
        $this->repository = $repository;
        $this->originalRepository = clone $repository;

        return $this;
    }

    /**
     * Configura a classe para paginar uma Collection
     *
     * @param Illuminate\Support\Collection $collection
     * @return App\Builders\PaginationBuilder
     */
    public function collection(Collection $collection)
    {
        $this->repository = null;
        $this->collection = $collection;
        $this->originalRepository = null;

        return $this;
    }

    /**
     * Define um Resource para os ítens paginados
     *
     * Este método permite definir um único Resource para formatar
     * todos os elementos paginados.
     *
     * @param Illuminate\Http\Resources\Json\Resource $resource
     * @return App\Builders\PaginationBuilder
     */
    public function resource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Adiciona critérios para filtro da paginação
     *
     * Este método permite definir critérios de filtro para a paginação
     * Os critérios podem ou não estar dentro de uma coleção.
     *
     * @param Illuminate\Support\Collection $criterias
     * @return App\Builders\PaginationBuilder
     */
    public function criterias($criterias)
    {
        if ($criterias instanceof Collection) {
            $this->criterias = $this->criterias->merge($criterias);
        } else {
            $this->criterias->push($criterias);
        }

        return $this;
    }

    /**
     * Remove critérios de filtros
     *
     * @return App\Builders\PaginationBuilder
     */
    public function cleanCriterias()
    {
        $this->criterias = collect();
        return $this;
    }

    /**
     * Define a quantidade de ítens por página
     *
     * @param int $perPage
     * @return App\Builders\PaginationBuilder
     */
    public function perPage(int $perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * Constrói e retorna a paginação
     *
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function build()
    {
        if ($this->repository != null) {
            $paginated = $this->buildForRepository();
        } else {
            $paginated = $this->buildForCollection();
        }

        if ($this->resource) {
            return $this->resource::collection($paginated);
        }

        return Resource::collection($paginated);
    }

    /**
     * Define critérios padrões para todas as paginações.
     *
     * Os critérios podem ser anulados utilizando o método 'cleanCriterias'.
     *
     * @return array
     */
    private function getDefaultCriterias()
    {
        $default_criterias[] = new OrderResolvedByUrlCriteria();
        $default_criterias[] = new SearchResolvedByUrlCriteria();

        return $default_criterias;
    }

    private function buildForRepository()
    {
        $this->repository->pushCriteria($this->criterias);

        return $this->repository->paginate($this->perPage);
    }

    private function buildForCollection()
    {
        if (! $this->collection) {
            $this->collection = collect();
        }

        if (!$this->collection instanceof Collection) {
            $this->collection = collect($this->collection);
        }

        return $this->collection->paginate($this->perPage);
    }
}
