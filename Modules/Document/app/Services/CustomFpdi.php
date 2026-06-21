<?php

namespace Modules\Document\Services;

use setasign\Fpdi\Fpdi;

class CustomFpdi extends Fpdi
{
    protected $extgstates = [];
    protected $n_ocg_print;
    protected $n_ocg_view;

    public function SetAlpha($alpha, $bm = 'Normal')
    {
        $gs = $this->addExtGState(['ca' => $alpha, 'CA' => $alpha, 'BM' => '/' . $bm]);
        $this->setExtGState($gs);
    }

    protected function addExtGState($params)
    {
        $n = count($this->extgstates) + 1;
        $this->extgstates[$n] = ['params' => $params];
        return $n;
    }

    protected function setExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    public function SetProtection($permissions = [], $user_pass = '', $owner_pass = null)
    {
        // Skip protection for now to avoid complexity
        // Protection requires encryption implementation which is complex
        // For MVP, watermark text is sufficient deterrent
    }

    protected function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }

    protected function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++) {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $params = $this->extgstates[$i]['params'];
            foreach ($params as $k => $v) {
                $this->_put('/' . $k . ' ' . $v);
            }
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    protected function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_put('/ExtGState <<');
        foreach ($this->extgstates as $k => $extgstate) {
            $this->_put('/GS' . $k . ' ' . $extgstate['n'] . ' 0 R');
        }
        $this->_put('>>');
    }
}
