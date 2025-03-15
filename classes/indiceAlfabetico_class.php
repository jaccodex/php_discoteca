<?php
class indiceAlfabetico{

    private $_pageName; // normalmente $_SEVER['PHP_SELF']
    private $_iniciales;
    private $_offset;// dado en paginas, no en registros
    private $_maxPageShown;

    private $_pageNumber;
    private $_offsetNumber;
    
    private $_iniPage;
    private $_endPage;
    private $_actPage;
    private $_back;
    private $_forward;

    public function __construct(
            $pageName,
            $iniciales,
            $offset=0,
            $maxPageShown=4)

    {

        $this->_pageName=$pageName;
        $this->_iniciales=$iniciales;
        $this->_maxPageShown=$maxPageShown;

        //establece el numero de paginas necesarias
        $this->_pageNumber=count($this->_iniciales);;
        //numero de offsets->conjunto de paginas
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
        if(isset($_GET['actPageI']))
        {
            $this->_actPage=$_GET['actPageI'];
        }
        else
        {
            $this->_actPage=0;
        }

    }
    protected function setOffset()
    {
        if(isset($_GET['offsetI']))
        {
            $this->_offset=$_GET['offsetI'];
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

    protected function setBack()
    {//link de offset atras
        if($this->_offset>0)
        {
            $newOffset=($this->_offset)-1;
            $newPage=$newOffset*$this->_maxPageShown;
            $link=$this->_pageName .'?offsetI=' . $newOffset . '&amp;' . 'actPageI=' . $newPage;
            $this->_back='<li><a href=\'' . $link . '\'>&lt;&lt;</a></li>';
        }
        else
        {
            $this->_back='';
        }
    }
    protected function setForward()
    {//link de offset adelante
        if($this->_offset<$this->_offsetNumber)
        {
            $newOffset=($this->_offset)+1;
            $newPage=$newOffset*$this->_maxPageShown;
            $link=$this->_pageName .'?offsetI=' . $newOffset . '&amp;' . 'actPageI=' . $newPage;
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
            $link=$this->_pageName .'?offsetI='. $this->_offset .
                    '&amp;' . 'actPageI=' . $i;

            $value = $this->_iniciales[$i];
            
            if($this->_actPage==$i)
            {
            $html_navigator.='<li class=\'activo\'>'. $value . '</li>';

            }
            else
            {
            $html_navigator.='<li>'.'<a href=\'' . $link . '\'>' . $value.'</a></li>';
            }
        }

        $html_navigator.=$this->_forward;

        $html_navigator.='</ul>';
        
        $html_navigator.='</div>';

        return $html_navigator;
     }


}
?>
