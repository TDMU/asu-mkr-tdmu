<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.03.2017
 * Time: 10:03
 */
abstract class ADocumentViewer extends CWidget
{
    /**
     * @var static ширина iframe
     */
    public $width = '100%';
    /**
     * @var string высота iframe
     */
    public $height = '100%';
    /**
     * @var string url документа
     */
    public $url;
    /**
     * @var string патерн строки viewer
     */
    protected $_urlViewer;
    /**
     * @return string геренрация url для iframe
     */
    protected abstract function _getIframeUrl();

    /**
     * проверка не пустой ли url
     */
    protected  function  _checkUrl(){
        if(empty($this->url))
            throw new Exception('Ошибка не задан url');
        if(empty($this->_urlViewer))
            throw new Exception('Ошибка не задан url viewer');
    }

    /**
     * герерация iframe
     * @return string
     */
    protected function _run(){
        $options = [
            'src'=>$this->_getIframeUrl(),
            'width'=>$this->width,
            'height'=>$this->height
        ];
        return CHtml::tag('iframe',$options);
    }

    /**
     * Initializes the widget.
     * This method registers all needed client scripts and renders
     * the tree view content.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Ends running the widget.
     */
    public function run()
    {
        echo $this->_run();
    }
}