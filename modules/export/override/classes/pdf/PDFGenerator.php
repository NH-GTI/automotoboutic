<?php

class PDFGenerator extends PDFGeneratorCore
{

    /**
     * Write a PDF page
     */
    public function writePage()
    {
        $this->SetHeaderMargin(5);
        $this->SetFooterMargin(21);
        $this->setMargins(10, 40, 10);
        $this->AddPage();

        /*
        * Add number of page
        */
        if(Tools::isSubmit('submitPrint') || Tools::isSubmit('submitStatus'))
            $this->writeHTML('<div style="text-align: center;">Ordre : '.$this->getAliasNumPage().'/'.$this->getAliasNbPages()."<br></div>");

        $this->writeHTML($this->content, true, false, true, false, '');
    }

}
