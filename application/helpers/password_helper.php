<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  edit_column callback function in Codeigniter (Ignited Datatables)
*
* Grabs a value from the edit_column field for the specified field so you can
* return the desired value.
*
* @access   public
* @return   mixed
*/

if ( ! function_exists('encrypt_password'))
{
  function encrypt_password($password)
  {
    $CI =& get_instance();
    $CI->load->library('encrypt');

    $key = 'ranggaperwiratama';
    $return = $CI->encrypt->encode($password, $key);
    return $return;
  }
}

if ( ! function_exists('decrypt_password'))
{
  function decrypt_password($value)
  {
    $CI =& get_instance();
    $CI->load->library('encrypt');

    $key = 'ranggaperwiratama';
    $decrypted_password = $CI->encrypt->decode($value, $key);
    return $decrypted_password;
  }
}

/* End of file passwod.php */
/* Location: ./application/helpers/password.php */
