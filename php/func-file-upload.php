<?php

# File upload function
function upload_file($files, $allowed_exs, $path) {
    # get data and store in var
    $file_name = $files['name'];
    $tmp_name = $files['tmp_name'];
    $error = $files['error'];

    if($error === 0) {

        # get file extension store and store it in var
        $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);

        /**
         * Convert file extension into lowercase and store it in var
         */

        $file_ex_lc = strtolower($file_ex);

        /**
         * check if file extensions exists in $allowed_exs array
         */

        if(in_array($file_ex_lc, $allowed_exs)) {
            /**
             * rename files with random strings
             */

            $new_file_name = uniqid("",true).'.'.$file_ex_lc;
            # assigning upload path
            $file_upload_path = '../uploads/'.$path.'/'.$new_file_name;

            /**
             * moving upload file to root directory upload/$path folder
             */
            move_uploaded_file($tmp_name, $file_upload_path);

            /**
             * creating success message associate array with named keys status and data
             */
            
            $sm['status'] = 'success';
            $sm['data'] = $new_file_name;

            /**
             * Return em array
            */

            return $sm;

            

        }else {
            /**
            * Creating error message associative array with named keys status and data
            */

            $em['status'] = 'error';
            $em['data'] = "You can't upload files of this type";

            /**
             * Return em array
            */

            return $em;
        }

    }else {
        /**
         * Creating error message associative array with named keys status and data
         */

        $em['status'] = 'error';
        $em['data'] = 'Error occurred while uploading';

        /**
         * Return em array
         */

        return $em;
    }


}