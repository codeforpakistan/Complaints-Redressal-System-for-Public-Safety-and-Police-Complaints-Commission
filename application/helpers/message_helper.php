<?php


if ( ! function_exists('messages'))
{
  function messages()
  {
    $this->session->set_flashdata('feedback',$messages);
    $this->session->set_flashdata('feedbase_class',$className);
  }
}

?>