@extends('layouts.master')
@section('content')

    <div class="container-fluid main-container">
        <div class="col-md-3 sidebar">
            <div class="row">
                <!-- uncomment code for absolute positioning tweek see top comment in css -->
                <div class="absolute-wrapper"> </div>
                <!-- Menu -->
                <div class="side-menu">

                        <!-- Main Menu -->
                        <div class="side-menu-container">
                            <div class="list-group">
                                <a href="#" class="list-group-item active">
                                    <h5 class="list-group-item-heading">Profil</h5>
                                    <p class="list-group-item-text">Vos données</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <h5 class="list-group-item-heading">Shop</h5>
                                    <p class="list-group-item-text">Commandes en cours/archives</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <h5 class="list-group-item-heading">Inscriptions</h5>
                                    <p class="list-group-item-text">Colloques et événements</p>
                                </a>
                            </div>
                        </div><!-- /.navbar-collapse -->

                </div>

            </div>
        </div>
        <div class="col-md-9 content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Profil
                </div>
                <div class="panel-body">
                    <div class="row">
                        $fillable = [
                        'user_id', 'civilite_id' ,'first_name','last_name', 'email', 'company', 'profession_id', 'telephone','mobile',
                        'fax', 'adresse', 'cp', 'complement','npa', 'ville', 'canton_id','pays_id', 'type', 'livraison'
                        ];
                        $fillable = ['first_name','last_name', 'email', 'password'];
                        <div class="col-md-8 col-md-offset-2">
                            <form class="form-horizontal" role="form">
                                <fieldset>
                                    <!-- Form Name -->
                                    <legend>Vos données</legend>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="textinput">Prénom</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="first_name" placeholder="Prénom" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="textinput">Line 2</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="Address Line 2" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="textinput">City</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="City" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="textinput">State</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="State" class="form-control">
                                        </div>
                                        <label class="col-sm-2 control-label" for="textinput">Postcode</label>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Post Code" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="textinput">Country</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="Country" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="pull-right"><button type="submit" class="btn btn-primary">Save</button></div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->

                </div>
            </div>
        </div>
    </div>

@endsection
