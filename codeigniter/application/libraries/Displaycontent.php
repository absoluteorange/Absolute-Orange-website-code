<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DisplayContent {

    public function __construct() {

        $this->_ci =& get_instance();
        $this->_ci->load->helper('url');
        $this->_ci->load->library(array('templateparser'));
        $this->_ci->load->model(array('profiles'));
        $this->version='1.2';
        $this->scriptData=array(
            'script'=>'app/main',
            'version'=>$this->version,
            'CDNPath'=>$this->_ci->config->item('CDNPath')
        );
        $this->contactData=array(
            'email'=>'info@absoluteorange.com',
            'tel'=>'+44 (0)75322 75 361',
            'twitter' => 'absolute_orange'
        );
        $this->headerData=array(
            'heading'=>'absolute orange',
            'homeUrl' => site_url(''),
            'displayTweets' => true
        );
        $this->footerData['copy']='&copy; 2006 - '.date('Y').' Absolute Orange Ltd, all rights reserved';
	    $this->arrDevelopers  = $this->_ci->profiles->getEmployeeNames();
        $this->navMain=array(
            'home' => array(
                'url' => site_url(''),
                'class' => '',
                'title' => 'home'
            ),
            'github' => array(
                'url' => 'http://www.github.com/absoluteorange',
                'class' => '',
                'title' => 'absolute orange github'
            ),
            'amyvarga' => array(
                'url' => site_url('/AmyVarga/work'),
                'class' => '',
                'title' => "amy varga",
                'subnav' => array(
                    '0' => array(
                        'url' => 'http://www.github.com/amyvarga',
                        'class' => 'subnav',
                        'title' => "amy's github"
                        ),
                    '1' => array(
                        'url' => site_url('/cv/Amy_Varga.pdf'),
                        'class' => 'subnav',
                        'title' => "amy's cv"
                        ),
                    '2' => array(
                        'url' => site_url('AmyVarga/labs'),
                        'class' => 'subnav',
                        'title' => "amy's labs"
                        )
                )
            ),
            'jonreading' => array(
                'url' => site_url('/JonReading/work'),
                'class' => '',
                'title' => 'jon reading',
                'subnav' => array(
                    '0' => array(
                        'url' => site_url('/cv/jon_reading.pdf'),
                        'class' => 'subnav',
                        'title' => "jon's cv"
                        )
                )
            ),
            'contact' => array(
                    'url' => site_url($this->_ci->uri->uri_string())."#contact",
                    'class' => '',
                    'title' => 'contact absolute orange'
                ),
            );
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
            $navData['items']=$this->getNav();
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
                'footer'=>$footer,
                'tweets'=>  $this->_ci->templateparser->parseTemplate('layout/tweets.html')
            )
        );
        echo preg_replace('/[\t\s\n]*(<.*>)[\t\s\n]*/', '$1', $output);
    }

    public function displayLab ($container, $title, $labScripts) {
        $this->headerData['displayTweets'] = false;
        $this->scriptData['labScripts'] = $labScripts;
        $header=$this->_ci->templateparser->parseTemplate('layout/logo.html', $this->headerData, true);
        $footer=$this->_ci->templateparser->parseTemplate('layout/footer.html', $this->footerData, true);
        $footerScript=$this->_ci->templateparser->parseTemplate('layout/footerScript.html', $this->scriptData, true);
        $output=$this->_ci->templateparser->parseTemplate('layout/labLayout.html',array(
                'title'=>$title,
                'header'=>$header,
                'container'=>$container,
                'footer'=>$footer,
                'footerScript'=>$footerScript,
            )
        );
        echo preg_replace('/[\t\s\n]*(<.*>)[\t\s\n]*/', '$1', $output);
    }

    /**
     * Displays main navigation
     */
    private function getNav() {
        $URL= site_url($this->_ci->uri->uri_string());
        $page = strtolower($this->_ci->uri->segment(1));
        if ($page == "") {
           $data = $this->navMain;
           foreach ($this->arrDevelopers as $developer) {
                $subnav = array();
                $developer = str_replace(' ', '', strtolower($developer['employee_name']));
                unset($data[$developer]['subnav']);
            }
        } else {
            $data['home'] = $this->navMain['home']; 
            foreach ($this->arrDevelopers as $developer) {
                $subnav = array();
                $developer = str_replace(' ', '', strtolower($developer['employee_name']));
                $data[$developer] = $this->navMain[$developer];
                if ($page == $developer) {
                    $subnav = $this->navMain[$developer]['subnav'];
                    $index = array_search($developer, array_keys($this->navMain));
                    array_splice($data, $index+1, 0, $subnav);
                }
                unset($data[$developer]['subnav']);
            }
            $data['contact'] = $this->navMain['contact'];
        }
        $data = $this->reIndexArray($data);
        foreach ($data as $key => $value) {
            if ($this->_ci->uri->total_segments() == 3) {
                $thirdSeg = $this->_ci->uri->slash_segment(3, 'leading');
                $URL = str_replace($thirdSeg, '', $URL);
            }
            if ($value['url'] == $URL){
                $data[$key]['class'] = 'selected';
            }
        }
        return $data;
    }

    /**
    * Creates indexed arrays for Moustache
    */
    private function reIndexArray($array) {
        $index = 0;
        foreach ($array as $value) {
            $data[$index] = $value;
            $index = $index+1;
        }
        return $data;
    }

}
?>
