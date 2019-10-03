<?php
/**
 *
 * This helper is used for 
 * adding the table prefix 
 * after table anme
 *
 * <p>
 * 	@author : Dibya Mitra
 * 	@param : Tablename
 *	@return : Table name concatinated with the table prefix
 * </p>
 *
 */

function tablename($tablename)
{
    $CI = & get_instance();
    $CI->load->database();
    return $CI->db->dbprefix($tablename);
}