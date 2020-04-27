<tr valign="top">
    <td valign="top">
        <h4 style="font-size: 17px;"><?php echo $colloque->titre; ?></h4>
        <h3><?php echo $colloque->soustitre; ?></h3>
    </td>
</tr>
<tr><td height="1" style="padding: 0; margin: 0;height: 1px; line-height: 5px;">&nbsp;</td></tr>
<tr valign="top">
    <td valign="top">
        <p class="organisateur"><strong>Organisé par:</strong> <?php echo $colloque->organisateur; ?></p>
    </td>
</tr>
<tr><td height="1" style="padding: 0; margin: 0;height: 1px; line-height: 5px;">&nbsp;</td></tr>
<tr>
    <td valign="top">
        <h3 class="titre-info"><strong>Date:</strong> <?php echo $colloque->event_date; ?></h3>
        <h3 class="titre-info"><strong>Lieu:</strong> <?php echo $colloque->location->name.', '.strip_tags($colloque->location->adresse); ?></h3>
    </td>
</tr>
