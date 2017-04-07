<?php
setlocale(LC_ALL, 'tr_TR.UTF-8');
include 'vendor/autoload.php';
use Spatie\Fractalistic\Fractal;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
class CategoryTransformer extends TransformerAbstract
{

    

    /**
     * A Fractal transformer.
     * @param Category $category
     * @return array
     */
    public function transform($data)
    {
        return [
        	'x' => $data['a'],
        ];

    }

}



$fractal = new Fractal(new Manager());

$data = ['a' => 'b'];
echo json_encode($fractal->item($data)->transformWith(new CategoryTransformer())->toArray());





