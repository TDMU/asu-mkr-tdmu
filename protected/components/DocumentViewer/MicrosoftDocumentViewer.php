<?php

/**
 * Created by PhpStorm.
 * User: Neff
 * Date: 13.03.2017
 * Time: 10:09
 */
class MicrosoftDocumentViewer extends ADocumentViewer
{
    protected $_urlViewer = "https://view.officeapps.live.com/op/embed.aspx?src={url}";
    protected function _getIframeUrl()
    {
        // TODO: Implement _getIframeUrl() method.
        return str_replace('{url}',$this->url, $this->_urlViewer);
    }
}