<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.03.2017
 * Time: 10:09
 */
class GoogleDocumentViewer extends ADocumentViewer
{
    const A_V = 'v';
    const A_GT = 'gt';
    const A_BI = 'bi';
    /**
     *  включение/выключение (значения соответственно true/false) интерфейса встраиваемого в сторонние html-страницы (по умолчанию false);
     * @var bool
     */
    public $embedded = false;
    /**
     * тип возвращаемого документа:
    “v” — будет отрыто приложение просмотра документа (это значение по умолчанию);
    “gt” — будет возвращен xml документ с распознанным текстом (пример);
    “bi” — будет возвращено изображение страницы документа в формате PNG8 (параметр pagenumber обязателен);
     * @var string
     */
    public $a = self::A_V;

    protected $_urlViewer = "https://docs.google.com/gview?url={url}";

    protected function _getIframeUrl()
    {
        $url = str_replace('{url}',$this->url, $this->_urlViewer);
        if($this->embedded)
            $url.='&embedded=true';
        $url.='&a='.$this->a;
        return $url;
    }
}