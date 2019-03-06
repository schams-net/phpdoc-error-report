<?php
namespace SchamsNet\DocErrorReport\View;

class Render
{
    /**
     *
     */
    public $view;

    /**
     *
     */
    public $paths;

    /**
     *
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    public function init()
    {
        $this->view = new \TYPO3Fluid\Fluid\View\TemplateView();
        $this->paths = $this->view->getTemplatePaths();
        $this->paths->setTemplateRootPaths([__DIR__ . '/../../Resources/Private/Templates/Web/']);
        $this->paths->setLayoutRootPaths([__DIR__ . '/../../Resources/Private/Layouts/Web/']);
        $this->paths->setPartialRootPaths([__DIR__ . '/../../Resources/Private/Partials/Web/']);
    }

    public function setTemplatePathAndFilename(string $pathAndFilename)
    {
        $this->paths->setTemplatePathAndFilename($pathAndFilename);
    }

    public function render()
    {
        return $this->view->render();
    }

    public function assign(string $key, $value)
    {
        return $this->view->assign($key, $value);
    }

    public function assignMultiple(array $array)
    {
        return $this->view->assignMultiple($array);
    }
}
