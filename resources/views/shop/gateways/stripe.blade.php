


<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('pk_test_GgkNxzPfjrQrNxgrXy2wPgR7');

    function stripeResponseHandler(status, response) {
        var $form = $('#payment-form');

        if (response.error) {
            // Show the errors on the form
            $form.find('.payment-errors').text(response.error.message);
            $form.find('button').prop('disabled', false);
        } else {
            // response contains id and card, which contains additional card details
            var token = response.id;
            // Insert the token into the form so it gets submitted to the server
            $form.append($('<input type="hidden" name="stripeToken" />').val(token));
            // and submit
            $form.get(0).submit();
        }
    };

    jQuery(function($) {
        $('#payment-form').submit(function(event) {
            var $form = $(this);

            // Disable the submit button to prevent repeated clicks
            $form.find('button').prop('disabled', true);

            Stripe.card.createToken($form, stripeResponseHandler);

            // Prevent the form from submitting with the default action
            return false;
        });
    });


</script>

<form action="{{ url('checkout/send') }}" method="POST" id="payment-form">
    <span class="payment-errors text-danger"></span>
    <div class="row">
        <div class="col-xs-12">
            <p class="text-center"><img src="{{ asset('images/creditcards.png') }}" alt="Stripe"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="cardNumber">Numéro de carte</label>
                <div class="input-group">
                    <input  data-stripe="number" type="tel" class="form-control" name="cardNumber" placeholder="Numéro valide" autocomplete="cc-number" required autofocus/>
                    <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-3 col-md-3">
            <div class="form-group">
                <label for="cardExpiry">Mois</label>
                <input type="tel" class="form-control" style="width:70px;" data-stripe="exp-month" name="exp-month" placeholder="MM" autocomplete="exp-month" required/>
            </div>
        </div>
        <div class="col-xs-3 col-md-3">
            <div class="form-group">
                <label for="cardExpiry">Année</label>
                <input type="tel" class="form-control" style="width:70px;" data-stripe="exp-year" name="exp-year" placeholder="YY" autocomplete="exp-year" required/>
            </div>
        </div>
        <div class="col-xs-5 col-md-5 pull-right">
            <div class="form-group">
                <label for="cardCVC">CV CODE</label>
                <input data-stripe="cvc" type="tel" class="form-control" name="cardCVC" placeholder="CVC" autocomplete="exp-year" required/>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-success btn-sm btn-block" type="submit">Payer</button>
        </div>
    </div>

</form>

