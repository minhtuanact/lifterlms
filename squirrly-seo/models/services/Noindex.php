<?php

class SQ_Models_Services_Noindex extends SQ_Models_Abstract_Seo {

    public function __construct() {
        parent::__construct();

        if (isset($this->_post->sq->doseo) && $this->_post->sq->doseo) {
            add_filter('sq_noindex', array($this, 'generateNoindex'));
            add_filter('sq_noindex', array($this, 'packNoindex'), 99);
        } else {
            add_filter('sq_noindex', array($this, 'returnFalse'));
        }
    }

    public function generateNoindex($robots = array()) {
        if (get_option( 'blog_public' ) == 0) {
            return $robots;
        }

        if ((int)$this->_post->sq->noindex == 1) {
            $robots[0] = 'noindex';
        }else{
            $robots[0] = 'index';
        }

        if ((int)$this->_post->sq->nofollow == 1) {
            $robots[1] = 'nofollow';
        } elseif (!empty($robots)) {
            $robots[1] = 'follow';
        }

        if($this->_post->term_id && SQ_Classes_Helpers_Tools::getOption('sq_term_noindex_empty')){
            if(function_exists('have_posts') && !have_posts()){
                $robots[0] = 'noindex';
                $robots[1] = 'nofollow';
            }
        }


        return $robots;
    }

    public function packNoindex($robots = array()) {
        $return='';
        if (!empty($robots)) {
            $return .=  sprintf("<meta name=\"robots\" content=\"%s\">", join(',', $robots))."\n";

            $robots[] ='max-snippet:-1';
            $robots[] = 'max-image-preview:large';
            $robots[] =  'max-video-preview:-1';
            $return .=  sprintf("<meta name=\"googlebot\" content=\"%s\">", join(',', $robots))."\n";
            $return .=  sprintf("<meta name=\"bingbot\" content=\"%s\">", join(',', $robots))."\n";
        }

        return $return;
    }

}