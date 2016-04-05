<?php
class SearchForm extends CFormModel
{
    public $inputForm;
    public $search_type;

 
    public function rules()
    {
        return array(
            array('inputForm,search_type', 'required'),

        );
    }
 

}
?>