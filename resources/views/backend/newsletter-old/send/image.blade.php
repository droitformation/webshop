<!-- Bloc -->
<table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset">
    <tr bgcolor="ffffff">
        <td colspan="3" height="35"></td>
    </tr><!-- space -->
    <tr align="center" class="resetMarge">
        <td class="resetMarge">

            <!-- Bloc content-->
            <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="tableReset contentForm">
                <tr>
                    <td align="center" valign="top" width="560" class="resetMarge">
                        <div class="centerText">
                            <?php $lien = (isset($bloc->lien) && !empty($bloc->lien) ? $bloc->lien : url('/') ); ?>

                            <!-- For old system check if multiple images -->
                            @if($bloc->image_list)
                                @foreach($bloc->image_list as $image)
                                    <?php $width = 540/count($bloc->image_list); ?>
                                    <a style="border: none;padding: 0;margin: 0;" target="_blank" href="<?php echo $lien; ?>">
                                        <img style="max-width: {{ $width }}px;float: left; margin: 0 1px;" alt="{{ $bloc->titre }}" src="{{ asset('files/uploads/'.$image) }}" />
                                    </a>
                                @endforeach
                            @else
                                <a style="border: none;padding: 0;margin: 0;" target="_blank" href="<?php echo $lien; ?>">
                                    <img style="max-width: 557px;" alt="{{ $bloc->titre }}" src="{{ asset('files/uploads/'.$bloc->image) }}" />
                                </a>
                            @endif

                        </div>
                    </td>
                </tr>
                <tr bgcolor="ffffff">
                    <td align="center" valign="top" width="560" class="resetMarge">
                        @if( $bloc->titre )
                            <h2 class="centerText">{{ $bloc->titre }}</h2>
                        @endif
                    </td>
                </tr><!-- space -->
            </table>
            <!-- Bloc content-->
        </td>
    </tr>
    <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr><!-- space -->
</table>
<!-- End bloc -->

