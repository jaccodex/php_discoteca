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
            $offset=0,
            $maxPageShown=8)

    {

        $this->_pageName=$pageName;
        $this->_totalRecords=$totalRecords;
        $this->_recordsPerpage=$recordsPerpage;
        $this->_maxPageShown=$maxPageShown;

        //establece el numero de paginas necesarias
        $this->_pageNumber=ceil($this->_totalRecords/$this->_recordsPerpage);

        //numero de offsets->conjunto de paginas
        $this->_offsetNumber=ceil($this->_pageNumber/$this->_maxPageShown);
        
        $this->_offsetNumber--;

        $this->readGetVars();
        $this->setOffset();
        $this->setIniPage();
        $this->setEndPage();
        $this->setActPage();

        $this->setBack();
        $this->setForward();
        

    }
	
    protected function readGetVars()
    {
        $this->getVars=array();
		
        if(count($_GET)>0)
        {
            foreach($_GET as $getIndex=>$getValue)
            {
                $this->getVars[$getIndex]=$getValue;
            }
        }
    }
	
    public function addGetVars()
    {
        $addText='';
        
        if(count($this->getVars)>0)
        {
            foreach($this->getVars as $getIndex=>$getValue)
            {
                $addText.= '&amp;' . $getIndex . '=' . $getValue;
            }
        }
		
        return $addText;
    }
    
    protected function setActPage()
    {
        if(isset($this->getVars['actPage']))
        {
            $this->_actPage=$this->getVars['actPage'];
        }
        else
        {
            $this->_actPage=0;
        }

    }
    
    protected function setOffset()
    {
        if(isset($this->getVars['offset']))
        {
            $this->_offset=$this->getVars['offset'];
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
    {//link de offset atras
        if($this->_offset>0)
        {
            $newOffset=($this->_offset)-1;
            $newPage=$newOffset*$this->_maxPageShown;
			
            $link=$this->_pageName .'?offset=' . $newOffset . '&amp;' . 'actPage=' . $newPage;
			
			unset($this->getVars['offset']);
			unset($this->getVars['actPage']);
			
			$link.=$this->addGetVars();
			
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
            
			$link=$this->_pageName .'?offset=' . $newOffset . '&amp;' . 'actPage=' . $newPage;
			
			unset($this->getVars['offset']);
			unset($this->getVars['actPage']);
			
			$link.=$this->addGetVars();
            
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
        $text.='no.getvars: ' . count($this->getVars) . '<br/>';
        return $text;
    }
    
    public function showNavigator()
    {
        $html_navigator='<div class=\'navigator\'>';
        
        $html_navigator.='<ul>';
        
        $html_navigator.=$this->_back;

        for($i=$this->_iniPage;$i<$this->_endPage;$i++)
        {
            $link=$this->_pageName .'?offset='. $this->_offset .
                    '&amp;' . 'actPage=' . $i;
					
            unset($this->getVars['offset']);
            unset($this->getVars['actPage']);

            $link.=$this->addGetVars();

            if($this->_actPage==$i)
            {
            $html_navigator.='<li span class=\'activo\'>'. ($i+1) . '</li>';
            }
            else
            {
            $html_navigator.='<li>'.'<a href=\'' . $link . '\'>' . ($i+1).'</a></li>';
            }
        }

        $html_navigator.=$this->_forward;

        $html_navigator.='</ul>';

        $html_navigator.='</div><!--fin de navigator-->';

        $html_navigator.='<p class=\'leyendaNavigator\'>';
        $html_navigator.='Regs: ' . $this->_totalRecords . ' Pags: ' .$this->_pageNumber;
        $html_navigator.='</p><!--fin de leyendaNavigator-->';

        return $html_navigator;
     }

}
?>
