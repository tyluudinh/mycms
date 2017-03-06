<?php
/**
 * CreatedBy: thangcest2@gmail.com
 * Date: 12/12/15
 * Time: 3:38 PM
 */

namespace common\core\console;

class Application extends \yii\console\Application
{
    /**
     * Can be change to another route
     * @var string
     */
    public $defaultRoute = 'help';

    public function __construct($config)
    {
        parent::__construct($config);
    }


}