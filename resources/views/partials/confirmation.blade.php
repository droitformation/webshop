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


@if(!empty(session('alreadySubscribed')))
    <script>
        $(function(){
            swal({
                title: "Oho",
                text: 'Vous êtes déjà inscrit à la newsletter',
                timer: 2500,
                type: "warning",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('confirmationSent')))
    <script>
        $(function(){
            swal({
                title: "Merci pour votre inscription!",
                text: 'Veuillez confirmer votre adresse email en cliquant le lien qui vous a été envoyé par email',
                timer: 3500,
                type: "success",
                showConfirmButton: false
            });
        });
    </script>
@endif


@if(!empty(session('OrderAbo')))
    <script>
        $(function(){
            swal({
                title: "Oho",
                text: 'Vous êtes déjà abonné à cet ouvrage',
                timer: 2500,
                type: "warning",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('aboAlreadyInCart')))
    <script>
        $(function(){
            swal({
                title: "Oho",
                text: 'Cet abonnement est déjà dans le panier',
                timer: 2500,
                type: "warning",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('AdresseMissing')))
    <script>
        $(function(){
            swal({
                title: "Oho",
                text: 'Vous n\'avez pas indiqué d\'adresse, veuillez ajouter une adresse dans votre profil et recommencer.',
                timer: 2500,
                type: "warning",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('ContactConfirmation')))
    <script>
        $(function(){
            swal({
                title: "Merci pour votre message!",
                text: 'Nous vous contacterons dès que possible.',
                timer: 2500,
                type: "success",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('status')))
    <script>
        $(function(){
            swal({
                title: "Ok",
                text: "<?php echo session('status'); ?>",
                timer: 2500,
                type: "success",
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(!empty(session('updateAdresse')))
    <script>
        $(function(){
            swal({
                title: "Ok",
                text: "Adresse mise à jour",
                timer: 2500,
                type: "success",
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

@if(!empty(session('InscriptionConfirmation')))
    <script>
        $(function(){
            swal({
                title: "Merci!",
                text: 'Nous avons bien pris en compte votre inscription, vous recevrez prochainement une confirmation par email.',
                type: "success",
                confirmButtonColor: "#5cb85c",
                showConfirmButton: true
            });
        });
    </script>
@endif


