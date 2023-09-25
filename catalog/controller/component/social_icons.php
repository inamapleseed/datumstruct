<?php
    class ControllerComponentSocialIcons extends controller{
        public function index(){
            $socials = array();

            $congif_socials = $this->config->get('config_social');
            if($congif_socials && is_array($congif_socials)){
                foreach($congif_socials as $each){
                    if(
                        !$each['status'] || // Disabled
                        !is_file(DIR_IMAGE . $each['icon']) || // Icon Deleted / Moved
                        !$each['link']){ // No Social Media url provided
                            continue; 
                        }

                    $socials[] = array(
                        'title' => $each['title'],
                        'icon'  => 'image/' . $each['icon'],
                        'link'  => $each['link'],
                    );
                }
            }

            return $socials;
        }
    }
