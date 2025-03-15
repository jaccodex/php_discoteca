<?php
class navigator{

    private $_pageName; // normalmente $_SEVER['PHP_SELF']
    private $_totalRecords;
    private $_recordsPerpage;
    private $_offset;// dado en paginas, no en registros
    private $_maxPageShown;
    private $_param;

    private $_pageNumber;

    private $_iniPage;
    private $_endPage;
    private $_actPage;
    private $_back;
    private $_forward;


    public function __construct(
            $pageName,
            $totalRecords, 
            $recordsPerpage,
            $param=null,
            $offset=0,
            $maxPageShown=6)

    {

        $this->_pageName=$pageName;
        $this->_totalRecords=$totalRecords;
        $this->_recordsPerpage=$recordsPerpage;
        $this->_maxPageShown=$maxPageShown;
        $this->_param=$param;

        //establece el numero de paginas necesarias
        $this->_pageNumber=ceil($this->_totalRecords/$this->_recordsPerpage);
        //numero de offsets
        $this->_offsetNumber=ceil($this->_pageNumber/$this->_maxPageShown);
        $this->_offsetNumber--;

        $this->setOffset();
        $this->setIniPage();
        $this->setEndPage();
        $this->setActPage();

        $this->setBack();
        $this->setForward();
    }

    protected function setActPage()
    {
        if(isset($_GET['actPage']))
        {
            $this->_actPage=$_GET['actPage'];
        }
        else
        {
            $this->_actPage=0;
        }

    }
    protected function setOffset()
    {
        if(isset($_GET['offset']))
        {
            $this->_offset=$_GET['offset'];
        }
        else
        {
            $this->_offset=0;
        }
    }
    protected function setIniPage()
    {
        $this->_iniPage=$this->_offset*$this->_maxPageShown;
    }
    protected function setEndpage()
    {
        $this->_endPage=$this->_iniPage+$this->_maxPageShown;

        if($this->_endPage>$this->_pageNumber)
        {
            $this->_endPage=$this->_pageNumber;
        }
    }

    protected function addParam($link)
    {
        if(!is_null($this->_param))
        {
            $link.='&amp;' . $this->_param;
        }

        return $link;
    }
    protected function setBack()
    {
        if($this->_offset>0)
        {
            $newOffset=($this->_offset)-1;
            $newPage=$newOffset*$this->_maxPageShown;
            $link=$this->addParam($this->_pageName .'?offset=' . $newOffset . '&amp;' . 'actPage=' . $newPage);
            $this->_back='<li><a href=\'' . $link . '\'>&lt;&lt;</a></li>';
        }
        else
        {
            $this->_back='';
        }
    }
     protected function setForward()
    {
        if($this->_offset<$this->_offsetNumber)
        {
            $newOffset=($this->_offset)+1;
            $newPage=$newOffset*$this->_maxPageShown;
            $link=$this->addParam($this->_pageName .'?offset=' . $newOffset . '&amp;' . 'actPage=' . $newPage);
            $this->_forward='<li><a href=\'' . $link . '\'>&gt;&gt;</a></li>';
        }
        else
        {
            $this->_forward='';
        }
    }
    public function showInfo()
    {
        $text='';
        $text.='no.registros: ' . $this->_totalRecords . '<br/>';
        $text.='no.paginas: ' . $this->_pageNumber . '<br/>';
        $text.='no.offsets: ' . $this->_offsetNumber . '<br/>';
        $text.='no.ini: ' . $this->_iniPage . '<br/>';
        $text.='no.fin: ' . $this->_endPage . '<br/>';
        return $text;
    }
    public function showNavigator()
    {
        $html_navigator='<div class=\'navigator\'><ul>';

        $html_navigator.=$this->_back;

        for($i=$this->_iniPage;$i<$this->_endPage;$i++)
        {
            $link=$this->addParam($this->_pageName .'?offset='. $this->_offset .
                    '&amp;' . 'actPage=' . $i);

            if($this->_actPage==$i)
            {
            $html_navigator.='<li><span>'. ($i+1) . '</span></li>';

            }
            else
            {
            $html_navigator.='<li>'.'<a href=\'' . $link . '\'>' . ($i+1).'</a></li>';
            }
        }

        $html_navigator.=$this->_forward;

        $html_navigator.='</ul></div>';

        return $html_navigator;
     }


}
?>
