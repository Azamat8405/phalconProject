<?php
declare(strict_types=1);

use Phalcon\Mvc\Model;
use App\Models\User as User;
use OpenApi\Annotations as OA;
use Phalcon\Mvc\View;

use Swagger;
use Swagger\Analysis;
use Swagger\StaticAnalyser;
use Swagger\Util;
use Swagger\Annotations as SWG;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="This is my website cool API",
 *         termsOfService="",
 *     )
 * )
 */
class IndexController extends ControllerBase
{

    public function swaggerJsonAction()
    {
        $this->view->disable();

        $options = $this->di->get('swagger');
        $directory = $options['path'];

        $analyser = array_key_exists('analyser', $options) ? $options['analyser'] : new StaticAnalyser();
        $analysis = array_key_exists('analysis', $options) ? $options['analysis'] : new Analysis();
        $processors = array_key_exists('processors', $options) ? $options['processors'] : Analysis::processors();
        $exclude = array_key_exists('exclude', $options) ? $options['exclude'] : null;

        // Crawl directory and parse all files
        $finder = Util::finder($directory, $exclude);
        foreach ($finder as $file) {
            $analysis->addAnalysis($analyser->fromFile($file->getPathname()));
        }

        // Post processing
        $analysis->process($processors);
        // Validation (Generate notices & warnings)
        $analysis->validate();

        echo $analysis->swagger->__toString();
    }

    public function docsAction(){

        $this->view->url = $this->di->get('swagger')['jsonUri'];
    }
}

