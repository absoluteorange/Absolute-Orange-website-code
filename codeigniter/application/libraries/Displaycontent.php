<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DisplayContent {

    public function __construct() {

        $this->_ci =& get_instance();
        $this->_ci->load->helper('url');
        $this->_ci->load->library(array('templateparser'));
        $this->version='1.2';
        $this->scriptData=array(
            'script'=>'app/main',
            'version'=>$this->version,
            'CDNPath'=>$this->_ci->config->item('CDNPath')
        );
        $this->contactData=array(
            'email'=>'info@absoluteorange.com',
            'tel'=>'+44 (0)75322 75 361'
        );
        $this->headerData=array(
            'heading'=>'absolute orange'
        );
        $this->footerData['copy']='&copy; 2006 - 2014 Absolute Orange Ltd, all rights reserved';
    }

    /**
     *Display specified content
     * @param $strContent
     * @param $isQunit
     */
    public function display($container, $title, $showNav){
        $this->headerData['displayMenu'] = false;
        $nav = '';
        if ($showNav == true) {
            $navData=$this->getNav();
            $nav=$this->_ci->templateparser->parseTemplate('layout/nav.html',$navData,true);
            $this->headerData['displayMenu'] = true;
        }
        $this->scriptData['title'] = $title;
        $headScript=$this->_ci->templateparser->parseTemplate('layout/headScript.html',$this->scriptData,true);
        $header=$this->_ci->templateparser->parseTemplate('layout/logo.html',$this->headerData,true);
        $contact=$this->_ci->templateparser->parseTemplate('layout/contact.html', $this->contactData,true);
        $footerScript=$this->_ci->templateparser->parseTemplate('layout/footerScript.html',$this->scriptData,true);
        $footer=$this->_ci->templateparser->parseTemplate('layout/footer.html',$this->footerData,true);
        $output=$this->_ci->templateparser->parseTemplate('layout/layout.html',array(
                'headScript'=>$headScript,
                'header'=>$header,
                'nav'=>$nav,
                'contact'=>$contact,
                'container'=>$container,
                'footerScript'=>$footerScript,
                'footer'=>$footer
            )
        );
        echo preg_replace('/[\t\s\n]*(<.*>)[\t\s\n]*/', '$1', $output);
    }

    public function displayLab ($container, $title) {
        $header=$this->_ci->templateparser->parseTemplate('layout/logo.html',$this->headerData,true);
        $footer=$this->_ci->templateparser->parseTemplate('layout/footer.html',$this->footerData,true);
        $output=$this->_ci->templateparser->parseTemplate('layout/labLayout.html',array(
                'title'=>$title,
                'header'=>$header,
                'container'=>$container,
                'footer'=>$footer
            )
        );
        echo preg_replace('/[\t\s\n]*(<.*>)[\t\s\n]*/', '$1', $output);
    }

    /**
     * Displays main navigation
     */
    private function getNav() {
        $section = $this->_ci->uri->segment(2);
        $section = str_replace('#menu', '', $section);
        $data['items'] = array(
            '0' => array(
                'url' => site_url('AmyVarga/'),
                'class' => '',
                'title' => 'labs'
            ),
            '1' => array(
                'url' => 'http://www.github.com/absoluteorange',
                'class' => '',
                'title' => 'github'
            ),
            '2' => array(
                'url' => site_url('/cv/Amy_Varga.pdf'),
                'class' => '',
                'title' => 'cv'
            ),
            '3' => array(
                'url' => site_url('AmyVarga/work'),
                'class' => '',
                'title' => 'work'
            )
        );
        ksort($data['items']);
        for ($i=0; $i<count($data['items']); $i++):
            if ($data['items'][$i]['url'] == site_url("/AmyVarga/".$section)):
                $data['items'][$i]['class'] = 'selected';
            endif;
        endfor;
        return $data;
    }
}
?>
