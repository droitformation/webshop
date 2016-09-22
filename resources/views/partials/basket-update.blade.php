@if(!empty(session('cartUpdated')))
    <script>
        $(function(){
            swal({
                title: "Panier",
                text: 'Panier mis à jour',
                timer: 2000,
                type: "success",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('couponApplyed')))
    <script>
        $(function(){
            swal({
                title: "Panier",
                text: 'Coupon appliqué',
                timer: 2000,
                type: "success",
                showConfirmButton: false
            });
        });
    </script>
@endif


@if(!empty(session('wrongCoupon')))
    <script>
        $(function(){
            swal({
                title: "Oho",
                text: 'Ce coupon n\'est pas valide',
                timer: 2500,
                type: "warning",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('OrderConfirmation')))
    <script>
        $(function(){
            swal({
                title: "Merci!",
                text: 'Votre commande a bien été envoyé. Celle-ci vous parviendra dans les plus brefs délais',
                type: "success",
                confirmButtonColor: "#5cb85c",
                showConfirmButton: true
            });
        });
    </script>
@endif