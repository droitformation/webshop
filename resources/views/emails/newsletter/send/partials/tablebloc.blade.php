<!-- Bloc content-->
<table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="tableReset contentForm">
    <tr bgcolor="ffffff"><td height="5"></td></tr><!-- space -->
    <tr>
        @if($direction == 'left')
            <td valign="top" align="center" width="160" class="resetMarge">
                {{ $picto }}
            </td>
            <td width="25" class="resetMarge"></td><!-- space -->
        @endif

        <td valign="top" width="375" class="resetMarge">{{ $content }}</td>

        @if($direction == 'right')
            <td width="25" class="resetMarge"></td><!-- space -->
            <td valign="top" align="center" width="160" class="resetMarge">
                {{ $picto }}
            </td>
        @endif
    </tr>
    <tr bgcolor="ffffff"><td height="5"></td></tr><!-- space -->
</table>
<!-- Bloc content-->