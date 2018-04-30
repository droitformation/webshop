<!-- Bloc content-->
<table border="0" width="{{ isset($width) ? $width : 560 }}" align="center" cellpadding="0" cellspacing="0" class="tableReset contentForm">
    <tr>
        <td valign="top" width="{{ isset($width) ? $width : 560 }}" class="resetMarge">
            {{ $slot }}
        </td>
    </tr>
</table>
<!-- Bloc content-->