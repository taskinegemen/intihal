<?php
class SearchForm extends CFormModel
{
    public $inputForm;
 
    public function rules()
    {
        return array(
            array('inputForm', 'required'),

        );
    }
 

}
?>