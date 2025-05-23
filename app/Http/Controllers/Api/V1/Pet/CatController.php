<?php

namespace App\Http\Controllers\Api\V1\Pet;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Services\TheCatApi\CatService;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\QueryParam;
use App\Http\Requests\SamplePaginatorRequest;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Http\Resources\Api\V1\Pet\CatBreedsResource;
use App\Http\Resources\Api\V1\Pet\CatBreedsCollection;

#[Group("Mascotas", "API RESTful para obtener el listado de razas de gatos")]
#[Authenticated()]
class CatController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private CatService $catService
    ) {}

    #[Endpoint("Obtiene el listado de las razas disponible en 'The CAT API'")]
    #[QueryParam("page", "int", "Current page number.", example: 0)]
    #[QueryParam("perPage", "int", "Registers by page.", example: 10)]
    public function breeds(SamplePaginatorRequest $request)
    {
        try {
            return new CatBreedsCollection(collect(
                $this->catService->breeds($request->getPage(), $request->getPerPage())
            ));
        } catch (\Throwable $th) {
            return $this->responseErrorByException($th);
        }
    }
}
