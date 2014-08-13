<?php
/**
 * Controller Base.
 */
namespace core;

class Controller implements IController
{
    public $outputType = Output::TYPE_HTML;

    public function handle()
    {
        $aData['data'] = $this->run();

        if ($this->get('gm_type') === Output::TYPE_PJAX) {
            $this->outputType = Output::TYPE_PJAX;
        }

        if ($this->outputType === Output::TYPE_HTML) {
            $aData[Output::TYPE_HTML] = [
                    'header' => $this->getHeader(),
                    'body' => $this->getBody(),
                    'footer' => $this->getFooter(),
            ];
        }

        if ($this->outputType === Output::TYPE_PJAX) {
            $aData[Output::TYPE_PJAX] = $this->getBody();
        }

        if (isset($_GET['debug'])) {
            see($aData);
            die;
        }

        return $aData;
    }

    protected function get($sName)
    {
        return (isset($_GET[$sName])) ? trim($_GET[$sName]) : '';
    }

    protected function post($sName)
    {
        return (isset($_POST[$sName])) ? trim($_POST[$sName]) : '';
    }

    public function getOutputType()
    {
        return $this->outputType;
    }

    public function before()
    {
        return true;
    }

    public function after()
    {
        return true;
    }

    protected function getHeader()
    {
        return 'Header';
    }

    protected function getFooter()
    {
        return 'Footer';
    }
}

# end of this file
