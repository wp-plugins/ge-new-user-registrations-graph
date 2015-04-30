<?php
/**
 * This file could be used to catch submitted form data. When using a non-configuration
 * view to save form data, remember to use some kind of identifying field in your form.
 */
    $number = ( isset( $_POST['number'] ) ) ? stripslashes( $_POST['number'] ) : '';
    self::update_dashboard_widget_options(
            self::wid,                                  //The  widget id
            array(                                      //Associative array of options & default values
                'daystodisplay' => $number
            )
    );

?>
<p>Show me the last...</p>
<select name="number">
<option value=7 selected>Week</option>
<option value=14>2 Weeks</option>
<option value=30>30 Days</option>
<option value=90>90 Days</option>
<option value=365>Year</option>
</select>
<br><br>
