<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="@Designpond | Cindy Leschaud">
    <title>Droit Formation | Administration</title>
    <meta name="description" content="Administration">
    <meta name="_token" content="<?php echo csrf_token(); ?>">

    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('backend/css/styles.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('common/css/validation.css');?>">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script src="//use.fontawesome.com/037c712a00.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo secure_asset('frontend/pubdroit/css/sweetalert.css');?>">
    <script src="<?php echo secure_asset('common/js/messages_fr.js');?>"></script>
    <script src="<?php echo secure_asset('common/js/validation.js');?>"></script>

</head>
<body class="">

    <?php $book = $media->file_name; ?>
    <?php $path = $media->getUrl(); ?>

        <div id="adobe-dc-view" style="width: 100%; height: 100%;"></div>
        <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
        <script type="text/javascript">
            document.addEventListener("adobe_dc_view_sdk.ready", function(){
                var adobeDCView = new AdobeDC.View({clientId: "f51f8e3bd64c4de386b4ac5e5122a348", divId: "adobe-dc-view",locale: "fr-FR"});
                adobeDCView.previewFile({
                    content:{location: {url: "<?php echo $path; ?>"}},
                    metaData:{fileName: "<?php echo $book; ?>"}
                }, { dockPageControls: true, showLeftHandPanel:false, showDownloadPDF: false, defaultViewMode: 'FIT_WIDTH', showAnnotationTools:false});
            });
        </script>
</body>
</html>

