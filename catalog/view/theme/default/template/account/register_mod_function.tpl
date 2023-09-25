<?php 

    function mod($data){
        // Error Html
        $error_code    =  '<div class="text-danger">[ERROR]</div>';

        // Input Html
        $input_code    =   '<div class="form-group [PARENT_CLASS]">';
        $input_code    .=  '  <input id ="input_[NAME]" name="[NAME]" type="[TYPE]" class="form-control [EXTRA_CLASS]" value="[VALUE]" placeholder="[PLACEHOLDER]" />';
        $input_code    .=  '  [ERROR_CODE]';
        $input_code    .=  '</div>';
        
        // Select Html
        $select_code    =   '<div class="form-group">';
        $select_code    .=  '  <select id ="input_[NAME]" name="[NAME]" class="form-control [EXTRA_CLASS]">[OPTION_CODE]</select>';
        $select_code    .=  '  [ERROR_CODE]';
        $select_code    .=  '</div>';

        // Option Html
        $option_code    =   '<option value="[VALUE]" [SELECTED] >[LABEL]</option>';

        $name = $data['name'];

        $label = $data['label'];

        $value = $data['value'];

        $error = $data['error'];

        $error_html = $error?str_replace('[ERROR]', $error, $error_code):'';
        
        $case = $data['case'];

        // Case
            $type = 'text';

            $parent_class = '';

            $extra_class = '';

            $list = array();
            
            if(isset($case['type']) && $case['type']) $type = $case['type'];

            if(isset($case['parent_class']) && $case['parent_class']) $parent_class = $case['parent_class'];

            if(isset($case['extra_class']) && $case['extra_class']) $extra_class = $case['extra_class'];

            if(isset($case['list']) && $case['list']) $list = $case['list'];
        // End Case

        if( $type != 'select' ){
            $find = array(
                '[NAME]',
                '[TYPE]',
                '[PARENT_CLASS]',
                '[EXTRA_CLASS]',
                '[VALUE]',
                '[PLACEHOLDER]',
                '[ERROR_CODE]',
            );

            $replace = array(
                $name,
                $type,
                $parent_class,
                $extra_class,
                $value,
                $label,
                $error_html
            );

            return str_replace($find, $replace, $input_code); 
        }
        elseif($type == 'select'){
            
           // List Option
            $options_html = "";

            $find_option = array('[VALUE]','[SELECTED]','[LABEL]');

            $replace_option = array('', '', $label); // Line 1

            $options_html .= str_replace($find_option, $replace_option, $option_code);


            foreach($list as $each){

                $selected = $each['value']==$value?'selected':'';

                $replace_option = array($each['value'], $selected, $each['label']);

                $options_html .= str_replace($find_option, $replace_option, $option_code);

            }
           // End List Option

           // Craft Select
            $find = array(
                '[NAME]',
                '[PARENT_CLASS]',
                '[EXTRA_CLASS]',
                '[OPTION_CODE]',
                '[ERROR_CODE]',
            );
            $replace = array(
                $name,
                $parent_class,
                $extra_class,
                $options_html,
                $error_html,
            );
            
            return str_replace($find, $replace, $select_code); 
           // End Craft
        }

    } // End Function

?>