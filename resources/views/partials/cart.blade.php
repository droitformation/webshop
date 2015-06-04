@if(!Cart::content()->isEmpty())
    <div class="row">
        <div class="col-md-7"></div>
        <div class="col-md-5">
            <?php
            //echo '<pre>';
            //print_r(Cart::content());
            //echo '</pre>';
            ?>  
             <table class="table">
                 <tr>
                     <th width="60%">Nom</th>
                     <th width="5%">Quantité</th>
                     <th width="30%">Prix</th>
                     <th width="5%"></th>
                 </tr>
                @foreach(Cart::content() as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td class="text-right">{{ $item->price }} CHF</td>
                    <td class="text-right">
                        {!! Form::open(array('url' => 'removeProduct')) !!}
                        {!! Form::hidden('_token', csrf_token()) !!}
                        <button class="btn btn-xs btn-danger" type="submit">x</button>
                        {!! Form::hidden('rowid', $item->rowid) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
                 <tr class="active">
                     <td><strong>Total</strong></td>
                     <td></td>
                     <td></td>
                     <td class="text-right">{{ Cart::total() }} CHF</td>

                 </tr>
            </table>

        </div>
    </div>
@endif