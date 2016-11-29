<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class EmailComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $style = [
            /* Layout ------------------------------ */

            'body' => 'margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;',
            'email-wrapper' => 'width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;',

            /* Masthead ----------------------- */

            'email-masthead' => 'padding: 25px 0; text-align: center;',
            'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',

            'email-body' => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
            'email-body_inner' => 'width: 100%; max-width: 570px; margin: 0 auto; padding: 0;text-align: center;',
            'email-body_inner_full' => 'width: 100%; max-width: 600px; margin: 0 auto; padding: 0;text-align: center;',
            'email-body_cell' => 'padding: 35px; text-align: left;',
            'email-body_cell_content' => 'padding: 5px 35px 35px 35px;text-align: center;',
            'email-body_cell_header' => 'padding: 35px 35px 10px 35px;',

            'email-footer' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
            'email-footer_cell' => 'color: #AEAEAE; padding: 35px; text-align: center;',

            /* Body ------------------------------ */

            'body_action' => 'width: 100%; margin: 25px auto; padding: 0; text-align: center;',
            'body_sub' => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2;',

            /* Type ------------------------------ */

            'anchor' => 'color: #3869D4;',
            'header-1' => 'margin-top: 0; color: #b01d22; font-size: 19px; font-weight: bold; text-align: center;',
            'header-2' => 'margin-top: 10px; margin-bottom:0px; color: #2F3133; font-size: 17px; font-weight: bold; text-align: center;',
            'paragraph' => 'margin-top: 0; color: #74787E; font-size: 16px; line-height: 1.5em;',
            'paragraph-sub' => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
            'body_content' => 'color: #54565a; font-size: 14px; line-height: 22px;',
            'paragraph-center' => 'text-align: center;',
            'mb-15' => 'margin-bottom:15px;',

            /* Buttons ------------------------------ */

            'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',

            'button--green' => 'background-color: #22BC66;',
            'button--red' => 'background-color: #dc4d2f;',
            'button--blue' => 'background-color: #3869D4;',
        ];

        $fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;';

        $view->with('style',$style);
        $view->with('fontFamily',$fontFamily);
    }
}